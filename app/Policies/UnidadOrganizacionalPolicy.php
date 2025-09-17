<?php

namespace App\Policies;

use App\Models\UnidadOrganizacional;
use App\Models\User;

class UnidadOrganizacionalPolicy
{
    public function view(User $user, UnidadOrganizacional $unidad)
    {
        if ($user->hasRole('Admin_General') || $user->hasPermissionTo('unidades.ver')) {
            return true;
        }

        // Ejemplo de regla: permitir ver si pertenece a la misma unidad
        if ($user->id_unidad_organizacional && $user->id_unidad_organizacional === $unidad->id_unidad_organizacional) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('unidades.crear');
    }

    public function update(User $user, UnidadOrganizacional $unidad)
    {
        if ($user->hasRole('Admin_General')) {
            return true;
        }

        if (! $user->hasPermissionTo('unidades.editar')) {
            return false;
        }

        // Ejemplo: permitir edición si el usuario pertenece a la misma unidad
        if ($user->id_unidad_organizacional && $user->id_unidad_organizacional === $unidad->id_unidad_organizacional) {
            return true;
        }

        return false;
    }

    public function delete(User $user, UnidadOrganizacional $unidad)
    {
        return $user->hasPermissionTo('unidades.eliminar') || $user->hasRole('Admin_General');
    }
}
