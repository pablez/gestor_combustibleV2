<?php

namespace App\Livewire\ConsumoCombustible;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ConsumoCombustible;
use App\Models\UnidadTransporte;
use App\Models\DespachoCombustible;
use Livewire\Attributes\Title;

#[Title('Consumos de Combustible')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $unidadFilter = '';
    public $validadoFilter = '';
    public $fechaDesde = '';
    public $fechaHasta = '';
    public $tipoCargaFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'unidadFilter' => ['except' => ''],
        'validadoFilter' => ['except' => ''],
        'fechaDesde' => ['except' => ''],
        'fechaHasta' => ['except' => ''],
        'tipoCargaFilter' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnidadFilter()
    {
        $this->resetPage();
    }

    public function updatingValidadoFilter()
    {
        $this->resetPage();
    }

    public function updatingTipoCargaFilter()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'unidadFilter', 'validadoFilter', 'fechaDesde', 'fechaHasta', 'tipoCargaFilter']);
        $this->resetPage();
    }

    public function validar($consumoId)
    {
        try {
            $consumo = ConsumoCombustible::findOrFail($consumoId);
            $consumo->update([
                'validado' => true,
                'fecha_validacion' => now(),
                'id_usuario_validador' => auth()->id(),
            ]);

            session()->flash('message', 'Consumo validado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al validar el consumo: ' . $e->getMessage());
        }
    }

    public function invalidar($consumoId)
    {    
        try {
            $consumo = ConsumoCombustible::findOrFail($consumoId);
            $consumo->update([
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ]);

            session()->flash('message', 'Consumo invalidado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al invalidar el consumo: ' . $e->getMessage());
        }
    }

    public function delete($consumoId)
    {
        try {
            $consumo = ConsumoCombustible::findOrFail($consumoId);
            $consumo->delete();
            session()->flash('message', 'Consumo eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = ConsumoCombustible::with(['unidadTransporte', 'despacho.proveedor', 'conductor', 'validador'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('numero_ticket', 'like', '%' . $this->search . '%')
                          ->orWhere('lugar_carga', 'like', '%' . $this->search . '%')
                          ->orWhereHas('unidadTransporte', function ($q) {
                              $q->where('placa', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('conductor', function ($q) {
                              $q->where('name', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->unidadFilter, function ($q) {
                $q->where('id_unidad_transporte', $this->unidadFilter);
            })
            ->when($this->validadoFilter !== '', function ($q) {
                $q->where('validado', $this->validadoFilter);
            })
            ->when($this->tipoCargaFilter, function ($q) {
                $q->where('tipo_carga', $this->tipoCargaFilter);
            })
            ->when($this->fechaDesde, function ($q) {
                $q->whereDate('fecha_registro', '>=', $this->fechaDesde);
            })
            ->when($this->fechaHasta, function ($q) {
                $q->whereDate('fecha_registro', '<=', $this->fechaHasta);
            })
            ->orderBy('fecha_registro', 'desc');

        $consumos = $query->paginate($this->perPage);
        
        $unidades = UnidadTransporte::where('activo', true)
            ->orderBy('placa')
            ->get();

        $tiposCarga = ['Completa', 'Parcial', 'Emergencia'];

        return view('livewire.consumo-combustible.index', [
            'consumos' => $consumos,
            'unidades' => $unidades,
            'tiposCarga' => $tiposCarga,
        ]);
    }
}
