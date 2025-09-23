<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class UserShow extends Component
{
    public User $user;

    public function mount($id)
    {
        $this->user = User::with([
            'unidad', 
            'roles', 
            'supervisor', 
            'supervisados'
        ])->findOrFail($id);
    }

    public function toggleActive()
    {
        // Basic authorization - only admins can toggle user status
        if (!auth()->user()->hasRole('Admin_General')) {
            session()->flash('error', 'No tienes permisos para cambiar el estado del usuario.');
            return;
        }

        $this->user->update(['activo' => !$this->user->activo]);
        
        $status = $this->user->activo ? 'activado' : 'desactivado';
        session()->flash('message', "Usuario {$status} correctamente.");
        
        // Refresh the user model
        $this->user = $this->user->fresh();
    }

    public function confirmDelete()
    {
        // Use a browser event so the Alpine.js modal can listen to it
        $this->dispatchBrowserEvent('confirm-delete', ['id' => $this->user->id]);
    }

    public function deleteUser()
    {
        // Basic authorization - only admins can delete users
        if (!auth()->user()->hasRole('Admin_General')) {
            session()->flash('error', 'No tienes permisos para eliminar usuarios.');
            return;
        }

        // Prevent self-deletion
        if ($this->user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            return;
        }

        $userName = $this->user->full_name;
        $this->user->delete();
        
        session()->flash('message', "Usuario '{$userName}' eliminado correctamente.");
        
        // Redirect to users index
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.user-show');
    }
}
