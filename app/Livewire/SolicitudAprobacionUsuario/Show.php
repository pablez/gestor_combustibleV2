<?php

namespace App\Livewire\SolicitudAprobacionUsuario;

use App\Models\SolicitudAprobacionUsuario;
use Livewire\Component;

class Show extends Component
{
    public SolicitudAprobacionUsuario $solicitud;

    // Modal de aprobación/rechazo
    public $mostrarModalAprobacion = false;
    public $accionSeleccionada = '';
    public $observacionesAprobacion = '';

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

    public function aprobar()
    {
        $this->authorize('solicitudes_aprobacion.aprobar');
        $this->accionSeleccionada = 'aprobar';
        $this->observacionesAprobacion = '';
        $this->mostrarModalAprobacion = true;
    }

    public function rechazar()
    {
        $this->authorize('solicitudes_aprobacion.rechazar');
        $this->accionSeleccionada = 'rechazar';
        $this->observacionesAprobacion = '';
        $this->mostrarModalAprobacion = true;
    }

    public function procesarAprobacion()
    {
        $this->validate([
            'observacionesAprobacion' => $this->accionSeleccionada === 'rechazar' ? 'required|string|max:1000' : 'nullable|string|max:1000'
        ], [
            'observacionesAprobacion.required' => 'Las observaciones son obligatorias para rechazar una solicitud.'
        ]);

        try {
            if ($this->accionSeleccionada === 'aprobar') {
                $this->solicitud->aprobar(auth()->id(), $this->observacionesAprobacion);
                session()->flash('message', 'Solicitud aprobada exitosamente.');
            } else {
                $this->solicitud->rechazar(auth()->id(), $this->observacionesAprobacion);
                session()->flash('message', 'Solicitud rechazada.');
            }

            $this->cerrarModalAprobacion();
            
            // Recargar la solicitud para mostrar los cambios
            $this->solicitud = $this->solicitud->fresh()->load(['usuario', 'creador', 'supervisorAsignado', 'aprobador']);
            
            // Emitir evento para actualizar notificaciones
            $this->dispatch('solicitudProcesada')->to('components.notification-bell');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function cerrarModalAprobacion()
    {
        $this->mostrarModalAprobacion = false;
        $this->accionSeleccionada = '';
        $this->observacionesAprobacion = '';
    }
}
