<?php

namespace App\Livewire\Components;

use App\Models\SolicitudCombustible;
use App\Models\SolicitudAprobacionUsuario;
use Livewire\Component;

class NotificationBell extends Component
{
    public $solicitudesCombustiblePendientes = 0;
    public $solicitudesAprobacionPendientes = 0;
    public $totalNotificaciones = 0;
    public $mostrarDropdown = false;
    public $solicitudesCombustible = [];
    public $solicitudesAprobacion = [];

    protected $listeners = [
        'solicitudCreada' => 'actualizarNotificaciones', 
        'solicitudProcesada' => 'actualizarNotificaciones',
        'echo:notificaciones,NotificacionSolicitud' => 'actualizarNotificaciones'
    ];

    public function mount()
    {
        $this->actualizarNotificaciones();
        
        // Programar actualización automática cada 30 segundos
        $this->dispatch('startNotificationPolling');
    }

    public function polling()
    {
        $this->actualizarNotificaciones();
    }

    public function actualizarNotificaciones()
    {
        $user = auth()->user();
        
        // Contar solicitudes de combustible pendientes según el rol del usuario
        if ($user->hasAnyRole(['Admin_General', 'Admin_Secretaria', 'Supervisor'])) {
            $this->solicitudesCombustiblePendientes = SolicitudCombustible::where('estado_solicitud', 'Pendiente')
                ->count();
            
            // Obtener las últimas 5 solicitudes de combustible pendientes
            $this->solicitudesCombustible = SolicitudCombustible::where('estado_solicitud', 'Pendiente')
                ->with(['solicitante', 'unidadTransporte'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $this->solicitudesCombustiblePendientes = 0;
            $this->solicitudesCombustible = collect();
        }

        // Contar solicitudes de aprobación de usuario pendientes (solo admins)
        if ($user->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            $this->solicitudesAprobacionPendientes = SolicitudAprobacionUsuario::where('estado_solicitud', 'pendiente')
                ->count();
            
            // Obtener las últimas 5 solicitudes de aprobación pendientes
            $this->solicitudesAprobacion = SolicitudAprobacionUsuario::where('estado_solicitud', 'pendiente')
                ->with(['usuario', 'creador'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $this->solicitudesAprobacionPendientes = 0;
            $this->solicitudesAprobacion = collect();
        }

        $this->totalNotificaciones = $this->solicitudesCombustiblePendientes + $this->solicitudesAprobacionPendientes;
    }

    public function toggleDropdown()
    {
        $this->mostrarDropdown = !$this->mostrarDropdown;
        if ($this->mostrarDropdown) {
            $this->actualizarNotificaciones();
        }
    }

    public function verSolicitudCombustible($solicitudId)
    {
        $this->mostrarDropdown = false;
        return redirect()->route('solicitudes.show', $solicitudId);
    }

    public function verSolicitudAprobacion($solicitudId)
    {
        $this->mostrarDropdown = false;
        return redirect()->route('solicitudes-aprobacion.show', $solicitudId);
    }

    public function irASolicitudesCombustible()
    {
        $this->mostrarDropdown = false;
        return redirect()->route('solicitudes.index');
    }

    public function irASolicitudesAprobacion()
    {
        $this->mostrarDropdown = false;
        return redirect()->route('solicitudes-aprobacion.index');
    }

    public function render()
    {
        return view('livewire.components.notification-bell');
    }
}