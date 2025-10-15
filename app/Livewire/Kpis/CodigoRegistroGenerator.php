<?php

namespace App\Livewire\Kpis;

use App\Models\CodigoRegistro;
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

    protected $rules = [
        'diasVigencia' => 'required|integer|min:1|max:365',
    ];

    protected $messages = [
        'diasVigencia.required' => 'Los días de vigencia son obligatorios.',
        'diasVigencia.integer' => 'Los días de vigencia deben ser un número entero.',
        'diasVigencia.min' => 'Los días de vigencia deben ser al menos 1.',
        'diasVigencia.max' => 'Los días de vigencia no pueden exceder 365.',
    ];

    public function mount()
    {
        $this->cargarCodigosVigentes();
    }

    #[On('refreshCodigos')]
    public function cargarCodigosVigentes()
    {
        // Solo mostrar códigos del usuario actual o todos si es Admin
        if (auth()->user()->hasRole('Admin_General')) {
            $this->codigosVigentes = CodigoRegistro::vigentes()
                ->with(['generador'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $this->codigosVigentes = CodigoRegistro::vigentes()
                ->where('id_usuario_generador', auth()->id())
                ->with(['generador'])
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
            $codigo = CodigoRegistro::crear(auth()->id(), $this->diasVigencia);
            
            $this->codigoGenerado = $codigo->codigo;
            $this->mostrarMensaje("Código generado exitosamente: {$codigo->codigo}", 'success');
            
            // Recargar códigos vigentes
            $this->cargarCodigosVigentes();
            
            // Limpiar formulario
            $this->diasVigencia = 7;
            $this->mostrarFormulario = false;

        } catch (\Exception $e) {
            $this->mostrarMensaje('Error al generar el código: ' . $e->getMessage(), 'error');
        }
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