<?php

namespace App\Livewire\DespachoCombustible;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DespachoCombustible;
use App\Models\Proveedor;
use App\Models\SolicitudCombustible;
use Livewire\Attributes\Title;

#[Title('Despachos de Combustible')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $proveedorFilter = '';
    public $validadoFilter = '';
    public $fechaDesde = '';
    public $fechaHasta = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'proveedorFilter' => ['except' => ''],
        'validadoFilter' => ['except' => ''],
        'fechaDesde' => ['except' => ''],
        'fechaHasta' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProveedorFilter()
    {
        $this->resetPage();
    }

    public function updatingValidadoFilter()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'proveedorFilter', 'validadoFilter', 'fechaDesde', 'fechaHasta']);
        $this->resetPage();
    }

    public function validar($despachoId)
    {
        try {
            $despacho = DespachoCombustible::findOrFail($despachoId);
            $despacho->update([
                'validado' => true,
                'fecha_validacion' => now(),
                'id_usuario_validador' => auth()->id(),
            ]);

            session()->flash('message', 'Despacho validado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al validar el despacho: ' . $e->getMessage());
        }
    }

    public function invalidar($despachoId)
    {    
        try {
            $despacho = DespachoCombustible::findOrFail($despachoId);
            $despacho->update([
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ]);

            session()->flash('message', 'Despacho invalidado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al invalidar el despacho: ' . $e->getMessage());
        }
    }

    public function delete($despachoId)
    {
        try {
            $despacho = DespachoCombustible::findOrFail($despachoId);
            $despacho->delete();
            session()->flash('message', 'Despacho eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = DespachoCombustible::with(['solicitud.unidadTransporte', 'proveedor', 'despachador', 'validador'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('numero_vale', 'like', '%' . $this->search . '%')
                          ->orWhere('numero_factura', 'like', '%' . $this->search . '%')
                          ->orWhere('ubicacion_despacho', 'like', '%' . $this->search . '%')
                          ->orWhereHas('proveedor', function ($q) {
                              $q->where('nombre_proveedor', 'like', '%' . $this->search . '%')
                                ->orWhere('nombre_comercial', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('solicitud.unidadTransporte', function ($q) {
                              $q->where('placa', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->proveedorFilter, function ($q) {
                $q->where('id_proveedor', $this->proveedorFilter);
            })
            ->when($this->validadoFilter !== '', function ($q) {
                $q->where('validado', $this->validadoFilter);
            })
            ->when($this->fechaDesde, function ($q) {
                $q->whereDate('fecha_despacho', '>=', $this->fechaDesde);
            })
            ->when($this->fechaHasta, function ($q) {
                $q->whereDate('fecha_despacho', '<=', $this->fechaHasta);
            })
            ->orderBy('fecha_despacho', 'desc');

        $despachos = $query->paginate($this->perPage);
        
        $proveedores = Proveedor::where('activo', true)
            ->orderBy('nombre_comercial')
            ->orderBy('nombre_proveedor')
            ->get();

        return view('livewire.despacho-combustible.index', [
            'despachos' => $despachos,
            'proveedores' => $proveedores,
        ]);
    }
}
