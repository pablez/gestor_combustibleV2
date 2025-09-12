<?php

namespace App\Livewire\Unidades;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    public $unidad;

    public function mount($id)
    {
        if (! Auth::check() || (! Auth::user()->hasRole('Admin_General') && ! Auth::user()->hasPermissionTo('usuarios.ver'))) {
            abort(403);
        }

        $this->unidad = DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $id)->first();
        if (! $this->unidad) {
            abort(404);
        }
    }

    public function openEdit($id)
    {
        $this->dispatch('openEdit', $id);
    }

    public function render()
    {
        return view('livewire.unidades.show', ['unidad' => $this->unidad]);
    }
}

