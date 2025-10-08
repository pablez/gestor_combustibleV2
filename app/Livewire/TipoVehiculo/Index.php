<?php

namespace App\Livewire\TipoVehiculo;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\TipoVehiculo;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoria = '';
    public bool $mostrarInactivos = false;
    public int $perPage = 10;
    public string $sortBy = 'nombre';
    public string $sortDirection = 'asc';
    public ?int $editingTipo = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoria' => ['except' => ''],
        'mostrarInactivos' => ['except' => false]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoria()
    {
        $this->resetPage();
    }

    public function updatingMostrarInactivos()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
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
        $this->resetPage();
    }

    public function delete($id)
    {
        $tipoVehiculo = TipoVehiculo::find($id);
        if ($tipoVehiculo) {
            $tipoVehiculo->delete();
            $this->dispatch('tipoVehiculoDeleted');
        }
    }

    public function toggleActivo($id)
    {
        $tipoVehiculo = TipoVehiculo::find($id);
        if ($tipoVehiculo) {
            $tipoVehiculo->update(['activo' => !$tipoVehiculo->activo]);
            $this->dispatch('tipoVehiculoUpdated');
        }
    }

    public function openCreateModal()
    {
        \Log::info('openCreateModal called');
        $this->dispatch('openModal', component: 'tipo-vehiculo.create');
    }

    public function openEditModal($tipoId)
    {
        \Log::info('openEditModal called with ID: ' . $tipoId);
        $this->editingTipo = $tipoId;
        $this->dispatch('openModal', component: 'tipo-vehiculo.edit', arguments: ['tipoVehiculo' => $tipoId]);
    }

    public function closeModal()
    {
        \Log::info('closeModal called');
        $this->editingTipo = null;
        $this->dispatch('closeModal');
    }

    #[On('tipoVehiculoSaved')]
    #[On('tipoVehiculoUpdated')]
    #[On('tipoVehiculoDeleted')]
    public function refreshComponent()
    {
        // Component will automatically refresh
    }

    public function render()
    {
        $query = TipoVehiculo::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoria) {
            $query->where('categoria', $this->categoria);
        }

        if (!$this->mostrarInactivos) {
            $query->where('activo', true);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        $tiposVehiculo = $query->paginate($this->perPage);
        $categorias = TipoVehiculo::distinct()->pluck('categoria')->filter();

        // Calcular estadísticas globales (sin filtros aplicados)
        $estadisticas = [
            'total' => TipoVehiculo::count(),
            'activos' => TipoVehiculo::where('activo', true)->count(),
            'categorias' => TipoVehiculo::distinct()->count('categoria'),
            'enUso' => TipoVehiculo::has('unidadesTransporte')->count(),
        ];

        return view('livewire.tipo-vehiculo.index', [
            'tiposVehiculo' => $tiposVehiculo,
            'categorias' => $categorias,
            'estadisticas' => $estadisticas,
        ]);
    }
}
