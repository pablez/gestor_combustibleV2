<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersKpis extends Component
{
    public $totalUsers = 0;
    public $activeUsers = 0;
    public $inactiveUsers = 0;
    public $usersByRole = [];
    public $recentUsers = [];
    public $supervisedUsers = 0;

    public function mount()
    {
        $this->load();
    }

    public function load()
    {
        $currentUser = auth()->user();

        $baseQuery = User::with('roles')->where('id', '!=', $currentUser->id);
        $this->applyRoleBasedRestrictions($baseQuery, $currentUser);

        $this->totalUsers = $baseQuery->count();
        $this->activeUsers = (clone $baseQuery)->where('activo', true)->count();
        $this->inactiveUsers = (clone $baseQuery)->where('activo', false)->count();

        $this->usersByRole = $this->getUsersByRole($baseQuery);
        $this->recentUsers = $this->getRecentUsers($baseQuery);
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
        $userIds = (clone $baseQuery)->pluck('id')->toArray();

        if (empty($userIds)) {
            return [];
        }

        $rows = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->whereIn('model_has_roles.model_id', $userIds)
                ->where('model_has_roles.model_type', User::class)
                ->select('roles.name as role_name', DB::raw('COUNT(*) as count'))
                ->groupBy('roles.name')
                ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[str_replace('_', ' ', $row->role_name)] = (int) $row->count;
        }

        return $result;
    }

    private function getRecentUsers($baseQuery)
    {
        $query = clone $baseQuery;
        return $query->where('created_at', '>=', now()->subDays(7))
                     ->orderBy('created_at', 'desc')
                     ->limit(5)
                     ->get(['id', 'name', 'apellido_paterno', 'email', 'created_at'])
                     ->map(function ($u) {
                         return [
                             'id' => $u->id,
                             'name' => $u->name,
                             'apellido_paterno' => $u->apellido_paterno,
                             'email' => $u->email,
                             'created_at' => $u->created_at->toDateTimeString(),
                         ];
                     })->toArray();
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
        return view('livewire.kpis.users-kpis');
    }
}
