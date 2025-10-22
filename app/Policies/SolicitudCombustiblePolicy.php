<?php

namespace App\Policies;

use App\Models\SolicitudCombustible;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitudCombustiblePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver el listado de solicitudes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SolicitudCombustible $solicitudCombustible): bool
    {
        // Los administradores pueden ver todas las solicitudes
        if ($user->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            return true;
        }

        // Los conductores solo pueden ver sus propias solicitudes
        if ($user->hasRole('Conductor')) {
            return $solicitudCombustible->id_usuario_solicitante === $user->id;
        }

        // Los supervisores pueden ver solicitudes de su unidad
        if ($user->hasRole('Supervisor')) {
            // Verificar si el usuario tiene unidad asignada
            if ($user->unidad && $solicitudCombustible->solicitante) {
                return $solicitudCombustible->solicitante->id_unidad_organizacional === $user->unidad->id_unidad_organizacional;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Todos los usuarios pueden crear solicitudes
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SolicitudCombustible $solicitudCombustible): bool
    {
        // Solo los administradores pueden aprobar/rechazar solicitudes
        if ($user->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            return true;
        }

        // Los conductores pueden editar sus propias solicitudes si están pendientes
        if ($user->hasRole('Conductor') && 
            $solicitudCombustible->id_usuario_solicitante === $user->id &&
            $solicitudCombustible->estado_solicitud === 'Pendiente') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SolicitudCombustible $solicitudCombustible): bool
    {
        // Solo los administradores pueden eliminar solicitudes
        if ($user->hasAnyRole(['Admin_General', 'Admin_Secretaria'])) {
            return true;
        }

        // Los conductores pueden eliminar sus propias solicitudes si están pendientes
        if ($user->hasRole('Conductor') && 
            $solicitudCombustible->id_usuario_solicitante === $user->id &&
            $solicitudCombustible->estado_solicitud === 'Pendiente') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, SolicitudCombustible $solicitudCombustible): bool
    {
        // Solo los administradores pueden aprobar solicitudes
        return $user->hasAnyRole(['Admin_General', 'Admin_Secretaria']) && 
               $solicitudCombustible->estado_solicitud === 'Pendiente';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, SolicitudCombustible $solicitudCombustible): bool
    {
        // Solo los administradores pueden rechazar solicitudes
        return $user->hasAnyRole(['Admin_General', 'Admin_Secretaria']) && 
               $solicitudCombustible->estado_solicitud === 'Pendiente';
    }
}