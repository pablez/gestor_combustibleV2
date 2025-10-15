<?php

namespace App\Livewire\CategoriaProgramatica;

use App\Models\CategoriaProgramatica;
use App\Constants\Permissions;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $tipoFilter = '';

    #[Url]
    public $estadoFilter = 'todos';

    #[Url]
    public $sortBy = 'descripcion';

    #[Url]
    public $sortDirection = 'asc';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'tipoFilter' => ['except' => ''],
        'estadoFilter' => ['except' => 'todos'],
        'sortBy' => ['except' => 'descripcion'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_VER);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTipoFilter()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
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

    public function toggleEstado($id)
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR);
        
        $categoria = CategoriaProgramatica::findOrFail($id);
        $categoria->update(['activo' => !$categoria->activo]);
        
        session()->flash('success', 'Estado actualizado exitosamente.');
    }

    public function delete($id)
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_ELIMINAR);
        
        try {
            $categoria = CategoriaProgramatica::findOrFail($id);
            
            // Verificar si tiene categorías hijas
            if ($categoria->categoriasHijas()->count() > 0) {
                session()->flash('error', 'No se puede eliminar una categoría que tiene subcategorías.');
                return;
            }
            
            // Verificar si tiene presupuestos asociados
            if ($categoria->presupuestos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar una categoría que tiene presupuestos asociados.');
                return;
            }
            
            $categoria->delete();
            session()->flash('success', 'Categoría eliminada exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categorias = CategoriaProgramatica::query()
            ->with(['categoriaPadre'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('codigo', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->tipoFilter, function ($query) {
                $query->where('tipo_categoria', $this->tipoFilter);
            })
            ->when($this->estadoFilter !== 'todos', function ($query) {
                $estado = $this->estadoFilter === 'activos';
                $query->where('activo', $estado);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $tipos = ['Programa', 'Proyecto', 'Actividad'];

        return view('livewire.categoria-programatica.index', [
            'categorias' => $categorias,
            'tipos' => $tipos,
        ]);
    }
}
