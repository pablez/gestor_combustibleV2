<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UnidadOrganizacional;
use Illuminate\Support\Facades\DB;

class DashboardUser extends Component
{
    public $totalUsers = 0;
    public $activeUsers = 0;
    public $inactiveUsers = 0;
    public $usersByRole = [];
    public $usersByUnidad = [];
    public $recentUsers = [];
    public $supervisedUsers = 0;

    public function mount()
    {
        $this->loadKpis();
    }

    private function loadKpis()
    {
        $currentUser = auth()->user();

        // Obtener usuarios base aplicando las mismas restricciones que UserIndex
        $baseQuery = User::with(['unidad', 'roles'])->where('id', '!=', $currentUser->id);
        $this->applyRoleBasedRestrictions($baseQuery, $currentUser);

        // KPI 1: Total de usuarios visibles
        $this->totalUsers = $baseQuery->count();

        // KPI 2: Usuarios activos e inactivos
        $activeQuery = clone $baseQuery;
        $this->activeUsers = $activeQuery->where('activo', true)->count();

        $inactiveQuery = clone $baseQuery;
        $this->inactiveUsers = $inactiveQuery->where('activo', false)->count();

        // KPI 3: Usuarios por rol
        $this->usersByRole = $this->getUsersByRole($baseQuery);

        // KPI 4: Usuarios por unidad organizacional
        $this->usersByUnidad = $this->getUsersByUnidad($baseQuery);

        // KPI 5: Usuarios recientes (últimos 7 días)
        $this->recentUsers = $this->getRecentUsers($baseQuery);

        // KPI 6: Usuarios supervisados (solo para supervisores)
        $this->supervisedUsers = $this->getSupervisedUsersCount($currentUser);
    }

    private function applyRoleBasedRestrictions($query, $currentUser)
    {
        if ($currentUser->hasRole('Admin_General')) {
            return;
        }

        if ($currentUser->hasRole('Admin_Secretaria')) {
            if ($currentUser->id_unidad_organizacional) {
                $query->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
            } else {
                $query->where('id', -1);
            }
            return;
        }

        if ($currentUser->hasRole('Supervisor')) {
            $query->where('id_supervisor', $currentUser->id)
                  ->whereHas('roles', function ($q) {
                      $q->where('name', 'Conductor');
                  });

            if ($currentUser->id_unidad_organizacional) {
                $query->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
            }
            return;
        }

        $query->where('id', -1);
    }

    private function getUsersByRole($baseQuery)
    {
        $userIds = clone $baseQuery;
        $userIds = $userIds->pluck('id')->toArray();

        if (empty($userIds)) {
            return [];
        }

        return DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->whereIn('model_has_roles.model_id', $userIds)
                ->where('model_has_roles.model_type', User::class)
                ->select('roles.name as role_name', DB::raw('COUNT(*) as count'))
                ->groupBy('roles.name')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [str_replace('_', ' ', $item->role_name) => $item->count];
                })
                ->toArray();
    }

    private function getUsersByUnidad($baseQuery)
    {
        $userIds = clone $baseQuery;
        $userIds = $userIds->pluck('id')->toArray();

        if (empty($userIds)) {
            return [];
        }

        return DB::table('users')
                ->join('unidades_organizacionales', 'users.id_unidad_organizacional', '=', 'unidades_organizacionales.id_unidad_organizacional')
                ->whereIn('users.id', $userIds)
                ->select('unidades_organizacionales.codigo_unidad', 'unidades_organizacionales.nombre_unidad', DB::raw('COUNT(*) as count'))
                ->groupBy('unidades_organizacionales.id_unidad_organizacional', 'unidades_organizacionales.codigo_unidad', 'unidades_organizacionales.nombre_unidad')
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })
                ->toArray();
    }

    private function getRecentUsers($baseQuery)
    {
        $query = clone $baseQuery;
        return $query->where('created_at', '>=', now()->subDays(7))
                     ->orderBy('created_at', 'desc')
                     ->limit(5)
                     ->get(['id', 'name', 'apellido_paterno', 'email', 'created_at'])
                     ->toArray();
    }

    private function getSupervisedUsersCount($currentUser)
    {
        if ($currentUser->hasRole('Supervisor')) {
            return User::where('id_supervisor', $currentUser->id)->count();
        }

        if ($currentUser->hasRole('Admin_Secretaria') || $currentUser->hasRole('Admin_General')) {
            $query = User::whereNotNull('id_supervisor');

            if ($currentUser->hasRole('Admin_Secretaria') && $currentUser->id_unidad_organizacional) {
                $query->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
            }

            return $query->count();
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.users.dashboard-user');
    }
}
