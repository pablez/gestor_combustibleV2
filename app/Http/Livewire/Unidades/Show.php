<?php

namespace App\Http\Livewire\Unidades;

use Livewire\Component;
use App\Models\UnidadOrganizacional;

class Show extends Component
{
    public $unidad;

    public function mount($id)
    {
        if (! auth()->check() || ! (auth()->user()->can('usuarios.ver') || auth()->user()->can('usuarios.gestionar'))) {
            abort(403);
        }

        $this->unidad = UnidadOrganizacional::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.unidades.show', ['unidad' => $this->unidad]);
    }
}
