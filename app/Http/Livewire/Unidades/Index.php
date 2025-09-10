<?php

namespace App\Http\Livewire\Unidades;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UnidadOrganizacional;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;

    protected $queryString = ['search'];

    public function mount()
    {
        // authorization: 'usuarios.ver' or 'usuarios.gestionar' could be used, choose permisos generales
        if (! auth()->check()) {
            abort(403);
        }
        // Allow if user has any of these permissions
        if (! (auth()->user()->can('usuarios.ver') || auth()->user()->can('usuarios.gestionar') )) {
            abort(403);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = UnidadOrganizacional::query();

        if ($this->search) {
            $q = '%' . $this->search . '%';
            $query->where('codigo_unidad', 'like', $q)
                ->orWhere('nombre_unidad', 'like', $q)
                ->orWhere('responsable_unidad', 'like', $q);
        }

        $items = $query->orderBy('nivel_jerarquico')->paginate($this->perPage);

        return view('livewire.unidades.index', ['items' => $items]);
    }
}
