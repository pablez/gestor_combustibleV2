<?php

namespace App\Livewire\Kpis;

use App\Models\CodigoRegistro;
use App\Models\UnidadOrganizacional;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class CodigoRegistroGenerator extends Component
{
    public $diasVigencia = 7;
    public $codigoGenerado = null;
    public $codigosVigentes = [];
    public $mostrarFormulario = false;
    public $mensaje = '';
    public $tipoMensaje = 'success'; // success, error, info

    // Campos de personalización
    public $id_unidad_organizacional = null;
    public $id_supervisor = null;
    public $rol = null;
    public $observaciones = '';

    // Listas para selects
    public $unidades = [];
    public $supervisores = [];
    public $roles = ['Conductor', 'Supervisor', 'Admin_Secretaria', 'Admin_General'];

    protected $rules = [
        'diasVigencia' => 'required|integer|min:1|max:365',
        'id_unidad_organizacional' => 'nullable|exists:unidades_organizacionales,id_unidad_organizacional',
        'id_supervisor' => 'nullable|exists:users,id',
        'rol' => 'nullable|in:Conductor,Supervisor,Admin_Secretaria,Admin_General',
        'observaciones' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'diasVigencia.required' => 'Los días de vigencia son obligatorios.',
        'diasVigencia.integer' => 'Los días de vigencia deben ser un número entero.',
        'diasVigencia.min' => 'Los días de vigencia deben ser al menos 1.',
        'diasVigencia.max' => 'Los días de vigencia no pueden exceder 365.',
        'id_unidad_organizacional.exists' => 'La unidad organizacional seleccionada no es válida.',
        'id_supervisor.exists' => 'El supervisor seleccionado no es válido.',
        'rol.in' => 'El rol seleccionado no es válido.',
        'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres.',
    ];

    public function mount()
    {
        $this->cargarCodigosVigentes();
        $this->cargarDatosFormulario();
    }

    public function cargarDatosFormulario()
    {
        // Cargar unidades organizacionales activas
        $this->unidades = UnidadOrganizacional::where('activa', true)
            ->orderBy('nombre_unidad')
            ->get();

        // Cargar supervisores (usuarios con roles de supervisión)
        $this->supervisores = User::where('activo', true)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['Admin_General', 'Admin_Secretaria', 'Supervisor']);
            })
            ->orderBy('name')
            ->get();
    }

    #[On('refreshCodigos')]
    public function cargarCodigosVigentes()
    {
        // Solo mostrar códigos del usuario actual o todos si es Admin
        if (auth()->user()->hasRole('Admin_General')) {
            $this->codigosVigentes = CodigoRegistro::vigentes()
                ->with(['generador', 'unidadAsignada', 'supervisorAsignado'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $this->codigosVigentes = CodigoRegistro::vigentes()
                ->where('id_usuario_generador', auth()->id())
                ->with(['generador', 'unidadAsignada', 'supervisorAsignado'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    public function generarCodigo()
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            $this->mostrarMensaje('No tienes permisos para generar códigos de registro.', 'error');
            return;
        }

        $this->validate();

        try {
            // Preparar datos de personalización
            $datosPersonalizacion = [
                'id_unidad_organizacional' => $this->id_unidad_organizacional,
                'id_supervisor' => $this->id_supervisor,
                'rol' => $this->rol,
                'observaciones' => $this->observaciones,
            ];

            $codigo = CodigoRegistro::crear(auth()->id(), $this->diasVigencia, $datosPersonalizacion);
            
            $this->codigoGenerado = $codigo->codigo;
            $this->mostrarMensaje("Código personalizado generado exitosamente: {$codigo->codigo}", 'success');
            
            // Recargar códigos vigentes
            $this->cargarCodigosVigentes();
            
            // Limpiar formulario
            $this->limpiarFormulario();
            $this->mostrarFormulario = false;

        } catch (\Exception $e) {
            $this->mostrarMensaje('Error al generar el código: ' . $e->getMessage(), 'error');
        }
    }

    public function limpiarFormulario()
    {
        $this->reset([
            'diasVigencia',
            'id_unidad_organizacional', 
            'id_supervisor',
            'rol',
            'observaciones'
        ]);
        $this->diasVigencia = 7;
    }

    public function copiarCodigo($codigo)
    {
        $this->dispatch('copiarTexto', $codigo);
        $this->mostrarMensaje('Código copiado al portapapeles', 'info');
    }

    public function toggleFormulario()
    {
        $this->mostrarFormulario = !$this->mostrarFormulario;
        $this->reset('mensaje', 'codigoGenerado');
        
        if ($this->mostrarFormulario) {
            $this->limpiarFormulario();
        }
    }

    public function marcarComoUsado($codigoId)
    {
        try {
            $codigo = CodigoRegistro::findOrFail($codigoId);
            
            // Verificar permisos (solo el generador o admin puede marcar como usado)
            if (!auth()->user()->hasRole('Admin_General') && $codigo->id_usuario_generador !== auth()->id()) {
                $this->mostrarMensaje('No tienes permisos para modificar este código.', 'error');
                return;
            }

            $codigo->usar(auth()->id());
            $this->mostrarMensaje('Código marcado como usado.', 'success');
            $this->cargarCodigosVigentes();

        } catch (\Exception $e) {
            $this->mostrarMensaje('Error: ' . $e->getMessage(), 'error');
        }
    }

    private function mostrarMensaje($mensaje, $tipo = 'info')
    {
        $this->mensaje = $mensaje;
        $this->tipoMensaje = $tipo;
        
        // Auto-limpiar mensaje después de 5 segundos
        $this->dispatch('limpiarMensaje');
    }

    public function limpiarMensaje()
    {
        $this->reset('mensaje');
    }

    public function render()
    {
        return view('livewire.kpis.codigo-registro-generator');
    }
}