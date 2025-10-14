<?php

namespace App\Livewire\SolicitudAprobacionUsuario;

use App\Models\SolicitudAprobacionUsuario;
use Livewire\Component;

class Show extends Component
{
    public SolicitudAprobacionUsuario $solicitud;

    public $tiposDisponibles = [
        'nuevo_usuario' => 'Nuevo Usuario',
        'cambio_rol' => 'Cambio de Rol',
        'activacion' => 'Activación',
        'suspension' => 'Suspensión'
    ];

    public function mount(SolicitudAprobacionUsuario $solicitud)
    {
        $this->authorize('solicitudes_aprobacion.ver');
        $this->solicitud = $solicitud->load(['usuario', 'creador', 'supervisorAsignado', 'aprobador']);
    }

    public function render()
    {
        return view('livewire.solicitud-aprobacion-usuario.show');
    }

    public function volver()
    {
        return redirect()->route('solicitudes-aprobacion.index');
    }
}
