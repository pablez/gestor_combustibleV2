<?php

namespace App\Livewire\Presupuesto;

use App\Models\Presupuesto;
use App\Models\UnidadOrganizacional;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;
    
    #[Url]
    public $search = '';
    
    #[Url]
    public $unidadFilter = '';
    
    #[Url]
    public $categoriaFilter = '';
    
    #[Url]
    public $fuenteFilter = '';
    
    #[Url]
    public $anioFilter = '';
    
    #[Url]
    public $trimestreFilter = '';
    
    #[Url]
    public $estadoFilter = 'todos'; // todos, activos, inactivos, alerta
    
    #[Url]
    public $sortBy = 'created_at';
    
    #[Url]
    public $sortDirection = 'desc';
    
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'unidadFilter' => ['except' => ''],
        'categoriaFilter' => ['except' => ''],
        'fuenteFilter' => ['except' => ''],
        'anioFilter' => ['except' => ''],
        'trimestreFilter' => ['except' => ''],
        'estadoFilter' => ['except' => 'todos'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnidadFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoriaFilter()
    {
        $this->resetPage();
    }

    public function updatingFuenteFilter()
    {
        $this->resetPage();
    }

    public function updatingAnioFilter()
    {
        $this->resetPage();
    }

    public function updatingTrimestreFilter()
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

    public function clearFilters()
    {
        $this->reset(['search', 'unidadFilter', 'categoriaFilter', 'fuenteFilter', 'anioFilter', 'trimestreFilter', 'estadoFilter']);
        $this->resetPage();
    }

    public function toggleActivo($presupuestoId)
    {
        $presupuesto = Presupuesto::findOrFail($presupuestoId);
        $presupuesto->update(['activo' => !$presupuesto->activo]);
        
        session()->flash('message', 'Estado del presupuesto actualizado exitosamente.');
    }

    public function getPresupuestosProperty()
    {
        $query = Presupuesto::with(['unidadOrganizacional', 'categoriaProgramatica', 'fuenteOrganismoFinanciero']);

        // Filtro de búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('num_documento', 'like', '%' . $this->search . '%')
                  ->orWhere('numero_comprobante', 'like', '%' . $this->search . '%')
                  ->orWhere('observaciones', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unidadOrganizacional', function ($q) {
                      $q->where('nombre_unidad', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtros específicos
        if ($this->unidadFilter) {
            $query->where('id_unidad_organizacional', $this->unidadFilter);
        }

        if ($this->categoriaFilter) {
            $query->where('id_cat_programatica', $this->categoriaFilter);
        }

        if ($this->fuenteFilter) {
            $query->where('id_fuente_org_fin', $this->fuenteFilter);
        }

        if ($this->anioFilter) {
            $query->where('anio_fiscal', $this->anioFilter);
        }

        if ($this->trimestreFilter) {
            $query->where('trimestre', $this->trimestreFilter);
        }

        // Filtro de estado
        switch ($this->estadoFilter) {
            case 'activos':
                $query->where('activo', true);
                break;
            case 'inactivos':
                $query->where('activo', false);
                break;
            case 'alerta':
                $query->where('activo', true)
                      ->whereRaw('(total_gastado / presupuesto_inicial * 100) >= alerta_porcentaje');
                break;
        }

        // Ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        $unidades = UnidadOrganizacional::where('activa', true)->orderBy('nombre_unidad')->get();
        $categorias = CategoriaProgramatica::orderBy('descripcion')->get();
        $fuentes = FuenteOrganismoFinanciero::orderBy('descripcion')->get();
        
        // Obtener años únicos
        $anios = Presupuesto::distinct()->orderBy('anio_fiscal', 'desc')->pluck('anio_fiscal');
        
        return view('livewire.presupuesto.index', [
            'presupuestos' => $this->presupuestos,
            'unidades' => $unidades,
            'categorias' => $categorias,
            'fuentes' => $fuentes,
            'anios' => $anios,
        ]);
    }
}
