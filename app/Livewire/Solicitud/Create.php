<?php

namespace App\Livewire\Solicitud;

use App\Models\SolicitudCombustible;
use App\Models\UnidadTransporte;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;
use Livewire\Component;

class Create extends Component
{
    // Propiedades del formulario
    public $id_unidad_transporte;
    public $cantidad_litros_solicitados;
    public $motivo;
    public $urgente = false;
    public $justificacion_urgencia;
    public $id_cat_programatica;
    public $id_fuente_org_fin;
    public $saldo_actual_combustible;
    public $km_actual;
    public $km_proyectado;
    public $rendimiento_estimado;

    // Control de modal/formulario
    public $mostrarFormulario = false;

    protected $rules = [
        'id_unidad_transporte' => 'required|exists:unidad_transportes,id',
        'cantidad_litros_solicitados' => 'required|numeric|min:0.01|max:9999.99',
        'motivo' => 'required|string|max:500',
        'urgente' => 'boolean',
        'justificacion_urgencia' => 'nullable|string|max:500',
        'id_cat_programatica' => 'nullable|exists:categoria_programaticas,id',
        'id_fuente_org_fin' => 'nullable|exists:fuente_organismo_financieros,id',
        'saldo_actual_combustible' => 'nullable|numeric|min:0',
        'km_actual' => 'nullable|integer|min:0',
        'km_proyectado' => 'nullable|integer|min:0',
        'rendimiento_estimado' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'id_unidad_transporte.required' => 'La unidad de transporte es obligatoria.',
        'id_unidad_transporte.exists' => 'La unidad de transporte seleccionada no es válida.',
        'cantidad_litros_solicitados.required' => 'La cantidad de litros es obligatoria.',
        'cantidad_litros_solicitados.numeric' => 'La cantidad de litros debe ser un número.',
        'cantidad_litros_solicitados.min' => 'La cantidad mínima es 0.01 litros.',
        'cantidad_litros_solicitados.max' => 'La cantidad máxima es 9999.99 litros.',
        'motivo.required' => 'El motivo es obligatorio.',
        'motivo.max' => 'El motivo no puede exceder 500 caracteres.',
        'justificacion_urgencia.max' => 'La justificación no puede exceder 500 caracteres.',
    ];

    public function toggleFormulario()
    {
        $this->mostrarFormulario = !$this->mostrarFormulario;
        
        if (!$this->mostrarFormulario) {
            $this->limpiarFormulario();
        }
    }

    public function limpiarFormulario()
    {
        $this->reset([
            'id_unidad_transporte',
            'cantidad_litros_solicitados',
            'motivo',
            'urgente',
            'justificacion_urgencia',
            'id_cat_programatica',
            'id_fuente_org_fin',
            'saldo_actual_combustible',
            'km_actual',
            'km_proyectado',
            'rendimiento_estimado'
        ]);
        $this->resetValidation();
    }

    public function crear()
    {
        $this->validate();

        try {
            // Generar número de solicitud único
            $numeroSolicitud = 'SOL-' . rand(10000, 99999);
            while (SolicitudCombustible::where('numero_solicitud', $numeroSolicitud)->exists()) {
                $numeroSolicitud = 'SOL-' . rand(10000, 99999);
            }

            SolicitudCombustible::create([
                'numero_solicitud' => $numeroSolicitud,
                'id_usuario_solicitante' => auth()->id(),
                'id_unidad_transporte' => $this->id_unidad_transporte,
                'fecha_solicitud' => now(),
                'cantidad_litros_solicitados' => $this->cantidad_litros_solicitados,
                'motivo' => $this->motivo,
                'urgente' => $this->urgente,
                'justificacion_urgencia' => $this->justificacion_urgencia,
                'estado_solicitud' => 'Pendiente',
                'id_cat_programatica' => $this->id_cat_programatica,
                'id_fuente_org_fin' => $this->id_fuente_org_fin,
                'saldo_actual_combustible' => $this->saldo_actual_combustible,
                'km_actual' => $this->km_actual,
                'km_proyectado' => $this->km_proyectado,
                'rendimiento_estimado' => $this->rendimiento_estimado,
            ]);

            session()->flash('success', 'Solicitud creada exitosamente con número: ' . $numeroSolicitud);
            
            $this->limpiarFormulario();
            $this->mostrarFormulario = false;
            
            // Refrescar la lista de solicitudes del componente padre
            $this->dispatch('solicitudCreada');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $unidadesTransporte = UnidadTransporte::where('estado_operativo', 'Activo')
            ->orderBy('placa')
            ->get();

        $categoriasProgramaticas = CategoriaProgramatica::orderBy('descripcion')->get();
        $fuentesOrganismo = FuenteOrganismoFinanciero::orderBy('descripcion')->get();

        return view('livewire.solicitud.create', [
            'unidadesTransporte' => $unidadesTransporte,
            'categoriasProgramaticas' => $categoriasProgramaticas,
            'fuentesOrganismo' => $fuentesOrganismo,
        ]);
    }
}
