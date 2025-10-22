<?php

namespace App\Services;

use App\Models\SolicitudCombustible;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificacionSolicitudService
{
    /**
     * Enviar notificación cuando se crea una nueva solicitud
     */
    public function notificarNuevaSolicitud(SolicitudCombustible $solicitud)
    {
        try {
            // Obtener supervisores que deben ser notificados
            $supervisores = $this->obtenerSupervisores($solicitud);
            
            foreach ($supervisores as $supervisor) {
                $this->enviarNotificacionEmail($supervisor, $solicitud, 'nueva');
                $this->crearNotificacionInterna($supervisor, $solicitud, 'nueva');
            }

            Log::info("Notificación de nueva solicitud enviada", [
                'solicitud_id' => $solicitud->id,
                'numero_solicitud' => $solicitud->numero_solicitud,
                'supervisores_notificados' => $supervisores->count()
            ]);

        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de nueva solicitud", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar notificación cuando se aprueba una solicitud
     */
    public function notificarSolicitudAprobada(SolicitudCombustible $solicitud)
    {
        try {
            // Notificar al solicitante
            $this->enviarNotificacionEmail($solicitud->solicitante, $solicitud, 'aprobada');
            $this->crearNotificacionInterna($solicitud->solicitante, $solicitud, 'aprobada');

            // Notificar a operadores de despacho
            $operadores = $this->obtenerOperadoresDespacho();
            foreach ($operadores as $operador) {
                $this->enviarNotificacionEmail($operador, $solicitud, 'lista_despacho');
                $this->crearNotificacionInterna($operador, $solicitud, 'lista_despacho');
            }

            Log::info("Notificación de solicitud aprobada enviada", [
                'solicitud_id' => $solicitud->id,
                'numero_solicitud' => $solicitud->numero_solicitud
            ]);

        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de solicitud aprobada", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar notificación cuando se rechaza una solicitud
     */
    public function notificarSolicitudRechazada(SolicitudCombustible $solicitud)
    {
        try {
            // Notificar al solicitante
            $this->enviarNotificacionEmail($solicitud->solicitante, $solicitud, 'rechazada');
            $this->crearNotificacionInterna($solicitud->solicitante, $solicitud, 'rechazada');

            Log::info("Notificación de solicitud rechazada enviada", [
                'solicitud_id' => $solicitud->id,
                'numero_solicitud' => $solicitud->numero_solicitud
            ]);

        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de solicitud rechazada", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar notificación cuando se despacha combustible
     */
    public function notificarDespachado(SolicitudCombustible $solicitud)
    {
        try {
            // Notificar al solicitante
            $this->enviarNotificacionEmail($solicitud->solicitante, $solicitud, 'despachada');
            $this->crearNotificacionInterna($solicitud->solicitante, $solicitud, 'despachada');

            Log::info("Notificación de despacho enviada", [
                'solicitud_id' => $solicitud->id,
                'numero_solicitud' => $solicitud->numero_solicitud
            ]);

        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de despacho", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener supervisores que deben ser notificados
     */
    private function obtenerSupervisores(SolicitudCombustible $solicitud)
    {
        // Obtener supervisores de la unidad organizacional del solicitante
        $unidadOrganizacional = $solicitud->solicitante->id_unidad_organizacional;
        
        return User::whereHas('roles', function($query) {
                $query->whereIn('name', ['Admin_General', 'Admin_Secretaria', 'Supervisor']);
            })
            ->where('activo', true)
            ->where(function($query) use ($unidadOrganizacional) {
                $query->where('id_unidad_organizacional', $unidadOrganizacional)
                      ->orWhereHas('roles', function($subQuery) {
                          $subQuery->whereIn('name', ['Admin_General', 'Admin_Secretaria']);
                      });
            })
            ->get();
    }

    /**
     * Obtener operadores de despacho
     */
    private function obtenerOperadoresDespacho()
    {
        return User::whereHas('roles', function($query) {
                $query->whereIn('name', ['Admin_General', 'Admin_Secretaria', 'Operador_Despacho']);
            })
            ->where('activo', true)
            ->get();
    }

    /**
     * Enviar notificación por email
     */
    private function enviarNotificacionEmail(User $usuario, SolicitudCombustible $solicitud, string $tipo)
    {
        if (!$usuario->email) {
            return;
        }

        $datos = [
            'usuario' => $usuario,
            'solicitud' => $solicitud,
            'tipo' => $tipo,
            'url_solicitud' => route('solicitudes.show', $solicitud->id)
        ];

        // Aquí iría la lógica de envío de email
        // Mail::to($usuario->email)->send(new SolicitudNotification($datos));
    }

    /**
     * Crear notificación interna en el sistema
     */
    private function crearNotificacionInterna(User $usuario, SolicitudCombustible $solicitud, string $tipo)
    {
        $mensajes = [
            'nueva' => "Nueva solicitud de combustible #{$solicitud->numero_solicitud} requiere tu revisión",
            'aprobada' => "Tu solicitud de combustible #{$solicitud->numero_solicitud} ha sido aprobada",
            'rechazada' => "Tu solicitud de combustible #{$solicitud->numero_solicitud} ha sido rechazada",
            'lista_despacho' => "Solicitud #{$solicitud->numero_solicitud} lista para despacho",
            'despachada' => "Tu solicitud de combustible #{$solicitud->numero_solicitud} ha sido despachada"
        ];

        // Aquí se podría crear un registro en una tabla de notificaciones
        // o usar el sistema de notificaciones de Laravel
        /*
        $usuario->notifications()->create([
            'type' => 'solicitud_combustible',
            'data' => [
                'solicitud_id' => $solicitud->id,
                'numero_solicitud' => $solicitud->numero_solicitud,
                'tipo' => $tipo,
                'mensaje' => $mensajes[$tipo] ?? 'Notificación de solicitud',
                'url' => route('solicitudes.show', $solicitud->id)
            ]
        ]);
        */
    }

    /**
     * Enviar notificación de solicitud urgente
     */
    public function notificarSolicitudUrgente(SolicitudCombustible $solicitud)
    {
        try {
            // Para solicitudes urgentes, notificar inmediatamente a todos los administradores
            $administradores = User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['Admin_General', 'Admin_Secretaria']);
                })
                ->where('activo', true)
                ->get();

            foreach ($administradores as $admin) {
                $this->enviarNotificacionEmail($admin, $solicitud, 'urgente');
                $this->crearNotificacionInterna($admin, $solicitud, 'urgente');
            }

            Log::info("Notificación de solicitud urgente enviada", [
                'solicitud_id' => $solicitud->id,
                'numero_solicitud' => $solicitud->numero_solicitud,
                'administradores_notificados' => $administradores->count()
            ]);

        } catch (\Exception $e) {
            Log::error("Error al enviar notificación de solicitud urgente", [
                'solicitud_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}