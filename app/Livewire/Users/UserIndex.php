<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UnidadOrganizacional;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public $selectedUnidad = null;
    public $selectedRole = null;
    public int $perPage = 10;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedUnidad' => ['except' => null], 
        'selectedRole' => ['except' => null]
    ];

    protected $listeners = [
        'userSaved' => '$refresh',
        'userDeleted' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedUnidad()
    {
        $this->resetPage();
    }

    public function updatingSelectedRole()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($userId)
    {
        $this->dispatch('confirm-delete', userId: $userId);
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $currentUser = auth()->user();
        
        // Verificar que el usuario puede gestionar este usuario específico
        if (!$this->canManageUser($user, $currentUser)) {
            session()->flash('error', 'No tienes permisos para eliminar este usuario.');
            return;
        }

        // Prevenir auto-eliminación
        if ($user->id === $currentUser->id) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            return;
        }

        $user->delete();
        $this->dispatch('userDeleted');
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    /**
     * Aplica restricciones basadas en el rol del usuario actual
     */
    private function applyRoleBasedRestrictions($query, $currentUser)
    {
        if ($currentUser->hasRole('Admin_General')) {
            // Admin General puede ver todos los usuarios - sin restricciones
            return;
        }

        if ($currentUser->hasRole('Admin_Secretaria')) {
            // Admin Secretaría puede ver todos los usuarios de su unidad organizacional
            if ($currentUser->id_unidad_organizacional) {
                $query->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
            } else {
                // Si no tiene unidad asignada, no puede ver ningún usuario
                $query->where('id', -1); // Forzar resultado vacío
            }
            return;
        }

        if ($currentUser->hasRole('Supervisor')) {
            // Supervisor solo puede ver usuarios bajo su supervisión con rol Conductor
            // y de su misma unidad organizacional
            $query->where('id_supervisor', $currentUser->id)
                  ->whereHas('roles', function ($q) {
                      $q->where('name', 'Conductor');
                  });

            // Además, debe ser de la misma unidad organizacional
            if ($currentUser->id_unidad_organizacional) {
                $query->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
            }
            return;
        }

        // Para Conductor u otros roles sin permisos de gestión de usuarios
        // No pueden ver ningún usuario
        $query->where('id', -1); // Forzar resultado vacío
    }

    /**
     * Obtiene las unidades organizacionales disponibles según el rol del usuario
     */
    private function getAvailableUnidades($currentUser)
    {
        if ($currentUser->hasRole('Admin_General')) {
            // Admin General puede ver todas las unidades
            return UnidadOrganizacional::orderBy('nombre_unidad')->get();
        }

        if ($currentUser->hasRole('Admin_Secretaria')) {
            // Admin Secretaría solo puede filtrar por su propia unidad
            if ($currentUser->id_unidad_organizacional) {
                return UnidadOrganizacional::where('id_unidad_organizacional', $currentUser->id_unidad_organizacional)
                                          ->orderBy('nombre_unidad')
                                          ->get();
            }
        }

        if ($currentUser->hasRole('Supervisor')) {
            // Supervisor solo puede filtrar por su propia unidad
            if ($currentUser->id_unidad_organizacional) {
                return UnidadOrganizacional::where('id_unidad_organizacional', $currentUser->id_unidad_organizacional)
                                          ->orderBy('nombre_unidad')
                                          ->get();
            }
        }

        // Para otros roles, devolver colección vacía
        return collect();
    }

    /**
     * Obtiene los roles disponibles según el rol del usuario actual
     */
    private function getAvailableRoles($currentUser)
    {
        if ($currentUser->hasRole('Admin_General')) {
            // Admin General puede filtrar por todos los roles
            return Role::orderBy('name')->get();
        }

        if ($currentUser->hasRole('Admin_Secretaria')) {
            // Admin Secretaría puede filtrar por todos los roles de su unidad
            return Role::orderBy('name')->get();
        }

        if ($currentUser->hasRole('Supervisor')) {
            // Supervisor solo puede filtrar por rol Conductor
            return Role::where('name', 'Conductor')->orderBy('name')->get();
        }

        // Para otros roles, devolver colección vacía
        return collect();
    }

    public function render()
    {
        $currentUser = auth()->user();
        $query = User::with(['unidad', 'roles'])->where('id', '!=', $currentUser->id);

        // Aplicar restricciones según el rol del usuario actual
        $this->applyRoleBasedRestrictions($query, $currentUser);

        if ($this->search) {
            $like = '%' . $this->search . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('email', 'like', $like)
                  ->orWhere('username', 'like', $like)
                  ->orWhere('apellido_paterno', 'like', $like)
                  ->orWhere('ci', 'like', $like);
            });
        }

        if ($this->selectedUnidad) {
            $query->where('id_unidad_organizacional', $this->selectedUnidad);
        }

        if ($this->selectedRole) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->selectedRole);
            });
        }

        $query->orderBy($this->sortBy, $this->sortDirection);
        $users = $query->paginate($this->perPage);

        // Filtrar unidades y roles disponibles según las restricciones del usuario
        $unidades = $this->getAvailableUnidades($currentUser);
        $roles = $this->getAvailableRoles($currentUser);

        return view('livewire.users.user-index', compact('users', 'unidades', 'roles'));
    }

    /**
     * Verifica si el usuario actual puede gestionar (ver/editar/eliminar) un usuario específico
     */
    private function canManageUser($targetUser, $currentUser)
    {
        // Admin General puede gestionar todos los usuarios
        if ($currentUser->hasRole('Admin_General')) {
            return true;
        }

        // Admin Secretaría puede gestionar usuarios de su misma unidad organizacional
        if ($currentUser->hasRole('Admin_Secretaria')) {
            return $currentUser->id_unidad_organizacional && 
                   $targetUser->id_unidad_organizacional === $currentUser->id_unidad_organizacional;
        }

        // Supervisor solo puede gestionar conductores bajo su supervisión de su misma unidad
        if ($currentUser->hasRole('Supervisor')) {
            return $targetUser->id_supervisor === $currentUser->id &&
                   $targetUser->hasRole('Conductor') &&
                   $currentUser->id_unidad_organizacional &&
                   $targetUser->id_unidad_organizacional === $currentUser->id_unidad_organizacional;
        }

        // Otros roles no pueden gestionar usuarios
        return false;
    }
}
