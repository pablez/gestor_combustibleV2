<?php

namespace App\Livewire\Solicitud;

use App\Models\SolicitudCombustible;
use Livewire\Component;

class Show extends Component
{
    public SolicitudCombustible $solicitud;

    public function mount($solicitud)
    {
        $this->solicitud = SolicitudCombustible::with([
            'solicitante', 
            'unidadTransporte', 
            'aprobador',
            'categoriaProgramatica',
            'fuenteOrganismoFinanciero',
            'despachos'
        ])->findOrFail($solicitud);

        // Verificar permisos
        $user = auth()->user();
        
        if ($user->hasRole('Conductor')) {
            // Los conductores solo pueden ver sus propias solicitudes
            if ($this->solicitud->id_usuario_solicitante !== $user->id) {
                abort(403, 'No tienes permisos para ver esta solicitud.');
            }
        } elseif ($user->hasRole('Supervisor')) {
            // Los supervisores solo pueden ver solicitudes de su unidad
            if ($user->unidad && $this->solicitud->solicitante->id_unidad_organizacional !== $user->unidad->id_unidad_organizacional) {
                abort(403, 'No tienes permisos para ver esta solicitud.');
            }
        }
        // Admin_General y Admin_Secretaria pueden ver todas
    }

    public function aprobar()
    {
        if (!auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            session()->flash('error', 'No tienes permisos para aprobar solicitudes.');
            return;
        }

        if ($this->solicitud->estado_solicitud !== 'Pendiente') {
            session()->flash('error', 'Solo se pueden aprobar solicitudes pendientes.');
            return;
        }

        $this->solicitud->aprobar(auth()->id(), 'Aprobada desde el sistema');
        session()->flash('success', 'Solicitud aprobada exitosamente.');
        
        // Refrescar el modelo
        $this->solicitud->refresh();
    }

    public function rechazar()
    {
        if (!auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            session()->flash('error', 'No tienes permisos para rechazar solicitudes.');
            return;
        }

        if ($this->solicitud->estado_solicitud !== 'Pendiente') {
            session()->flash('error', 'Solo se pueden rechazar solicitudes pendientes.');
            return;
        }

        $this->solicitud->rechazar(auth()->id(), 'Rechazada desde el sistema');
        session()->flash('success', 'Solicitud rechazada.');
        
        // Refrescar el modelo
        $this->solicitud->refresh();
    }

    public function render()
    {
        return view('livewire.solicitud.show');
    }
}