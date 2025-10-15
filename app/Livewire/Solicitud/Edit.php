<?php

namespace App\Livewire\Solicitud;

use App\Models\SolicitudCombustible;
use App\Models\UnidadTransporte;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;
use Livewire\Component;

class Edit extends Component
{
    public SolicitudCombustible $solicitud;
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

    public function mount($solicitud)
    {
        $this->solicitud = SolicitudCombustible::with(['solicitante'])->findOrFail($solicitud);

        // Verificar permisos
        $user = auth()->user();
        
        if (!$user->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            // Solo el solicitante original puede editar su solicitud
            if ($this->solicitud->id_usuario_solicitante !== $user->id) {
                abort(403, 'No tienes permisos para editar esta solicitud.');
            }
        }

        // Solo se pueden editar solicitudes pendientes
        if ($this->solicitud->estado_solicitud !== 'Pendiente') {
            abort(403, 'Solo se pueden editar solicitudes pendientes.');
        }

        // Cargar datos del modelo
        $this->id_unidad_transporte = $this->solicitud->id_unidad_transporte;
        $this->cantidad_litros_solicitados = $this->solicitud->cantidad_litros_solicitados;
        $this->motivo = $this->solicitud->motivo;
        $this->urgente = $this->solicitud->urgente;
        $this->justificacion_urgencia = $this->solicitud->justificacion_urgencia;
        $this->id_cat_programatica = $this->solicitud->id_cat_programatica;
        $this->id_fuente_org_fin = $this->solicitud->id_fuente_org_fin;
        $this->saldo_actual_combustible = $this->solicitud->saldo_actual_combustible;
        $this->km_actual = $this->solicitud->km_actual;
        $this->km_proyectado = $this->solicitud->km_proyectado;
        $this->rendimiento_estimado = $this->solicitud->rendimiento_estimado;
    }

    public function actualizar()
    {
        $this->validate();

        try {
            $this->solicitud->update([
                'id_unidad_transporte' => $this->id_unidad_transporte,
                'cantidad_litros_solicitados' => $this->cantidad_litros_solicitados,
                'motivo' => $this->motivo,
                'urgente' => $this->urgente,
                'justificacion_urgencia' => $this->justificacion_urgencia,
                'id_cat_programatica' => $this->id_cat_programatica,
                'id_fuente_org_fin' => $this->id_fuente_org_fin,
                'saldo_actual_combustible' => $this->saldo_actual_combustible,
                'km_actual' => $this->km_actual,
                'km_proyectado' => $this->km_proyectado,
                'rendimiento_estimado' => $this->rendimiento_estimado,
            ]);

            session()->flash('success', 'Solicitud actualizada exitosamente.');
            return redirect()->route('solicitudes.show', $this->solicitud->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $unidadesTransporte = UnidadTransporte::where('estado_operativo', 'Activo')
            ->orderBy('placa')
            ->get();

        $categoriasProgramaticas = CategoriaProgramatica::orderBy('nombre')->get();
        $fuentesOrganismo = FuenteOrganismoFinanciero::orderBy('nombre')->get();

        return view('livewire.solicitud.edit', [
            'unidadesTransporte' => $unidadesTransporte,
            'categoriasProgramaticas' => $categoriasProgramaticas,
            'fuentesOrganismo' => $fuentesOrganismo,
        ]);
    }
}