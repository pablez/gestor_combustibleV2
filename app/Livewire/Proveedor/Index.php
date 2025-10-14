<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;
use App\Models\TipoServicioProveedor;
use Livewire\Attributes\Title;

#[Title('Proveedores')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'nombre_comercial';
    public $sortDirection = 'asc';
    public $filterTipoServicio = '';
    public $filterActivo = '';

    protected $queryString = ['search', 'filterTipoServicio', 'filterActivo'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTipoServicio()
    {
        $this->resetPage();
    }

    public function updatingFilterActivo()
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

    public function toggleStatus($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->update(['activo' => !$proveedor->activo]);
            session()->flash('message', 'Estado del proveedor actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->delete();
            session()->flash('message', 'Proveedor eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterTipoServicio = '';
        $this->filterActivo = '';
        $this->resetPage();
    }

    public function render()
    {
        $proveedores = Proveedor::query()
            ->with('tipoServicioProveedor')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre_comercial', 'like', '%' . $this->search . '%')
                      ->orWhere('nombre_proveedor', 'like', '%' . $this->search . '%')
                      ->orWhere('nit', 'like', '%' . $this->search . '%')
                      ->orWhere('telefono', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterTipoServicio, function ($query) {
                $query->where('id_tipo_servicio_proveedor', $this->filterTipoServicio);
            })
            ->when($this->filterActivo !== '', function ($query) {
                $query->where('activo', $this->filterActivo);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);

        $tiposServicio = TipoServicioProveedor::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('livewire.proveedor.index', [
            'proveedores' => $proveedores,
            'tiposServicio' => $tiposServicio
        ]);
    }
}
