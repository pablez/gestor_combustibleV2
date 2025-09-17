<?php

namespace App\Livewire\Unidades;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $tipoFilter = '';
    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'tipoFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $listeners = [
        'unidadDeleted' => '$refresh',
        'unidadSaved' => '$refresh',
        'unidadUpdated' => '$refresh',
    ];

    public function mount()
    {
        // Authorization: only users with 'unidades.ver' or Admin_General can view index
        if (! Auth::check() || (! Auth::user()->hasRole('Admin_General') && ! Auth::user()->hasPermissionTo('unidades.ver'))) {
            abort(403);
        }
    }

    public function delete($id)
    {
        if (! Auth::user()->hasPermissionTo('unidades.eliminar')) {
            session()->flash('error', 'No tiene permiso para eliminar unidades.');
            return;
        }

        $unidad = DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $id)->first();
        if (! $unidad) {
            session()->flash('error', 'Unidad no encontrada.');
            return;
        }

        DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $id)->delete();
        session()->flash('success', 'Unidad eliminada.');
        $this->dispatch('unidadDeleted');
    }

    // Open the Create modal (server-side) and forward event to Create component
    public function openCreate()
    {
        $this->dispatch('openCreate')->to('unidades.create');
    }

    // Open the Edit modal for a given id
    public function openEdit($id)
    {
        $this->dispatch('openEdit', $id)->to('unidades.edit');
    }

    /**
     * Acción al presionar el botón Buscar: resetear paginación
     */
    public function applySearch()
    {
        $this->resetPage();
    }

    // Cuando cambie el tamaño de página, reiniciar la paginación
    public function updatedPerPage($value)
    {
        // asegurar entero y límites aceptados
        $v = (int) $value;
        $allowed = [5,10,25,50,100];
        if (! in_array($v, $allowed, true)) {
            $this->perPage = 10;
        } else {
            $this->perPage = $v;
        }
        $this->resetPage();
    }

    // Cuando cambie el filtro de tipo, reiniciar la paginación
    public function updatedTipoFilter($value)
    {
        $this->resetPage();
    }

    // Opcional: si el usuario escribe la búsqueda directamente, reiniciar la paginación
    public function updatedSearch($value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table('unidades_organizacionales')
            ->when($this->search, function ($q) {
                $q->where('codigo_unidad', 'like', "%{$this->search}%")
                  ->orWhere('nombre_unidad', 'like', "%{$this->search}%");
            })
            ->when($this->tipoFilter, function ($q) {
                $q->where('tipo_unidad', $this->tipoFilter);
            })
            ->orderBy('id_unidad_organizacional', 'desc');

    // Use explicit pageName to avoid conflicts if other paginators appear
    // usar pageName único para evitar conflictos con otros paginadores
    $items = $query->paginate($this->perPage, ['*'], 'unidades_page');

        // Debug: obtener tipos únicos para verificar valores en BD
        $tiposUnicos = DB::table('unidades_organizacionales')->distinct()->pluck('tipo_unidad')->toArray();

        return view('livewire.unidades.index', ['unidades' => $items, 'tiposUnicos' => $tiposUnicos]);
    }
}
