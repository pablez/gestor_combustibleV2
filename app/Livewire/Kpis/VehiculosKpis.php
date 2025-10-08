<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\TipoVehiculo;
use App\Models\UnidadTransporte;
use App\Models\TipoCombustible;
use Illuminate\Support\Facades\DB;

class VehiculosKpis extends Component
{
    public $totalTiposVehiculo = 0;
    public $tiposActivosVehiculo = 0;
    public $totalUnidadesTransporte = 0;
    public $unidadesActivasTransporte = 0;
    public $totalTiposCombustible = 0;
    public $tiposActivosCombustible = 0;
    public $vehiculosPorCategoria = [];
    public $vehiculosPorEstado = [];
    public $consumoPromedio = [];
    public $topUnidadesTransporte = [];

    public function mount()
    {
        $this->loadKpis();
    }

    private function loadKpis()
    {
        // KPI 1: Tipos de vehículos
        $this->totalTiposVehiculo = TipoVehiculo::count();
        $this->tiposActivosVehiculo = TipoVehiculo::where('activo', true)->count();

        // KPI 2: Unidades de transporte
        $this->totalUnidadesTransporte = UnidadTransporte::count();
        $this->unidadesActivasTransporte = UnidadTransporte::where('activo', true)->count();

        // KPI 3: Tipos de combustible
        $this->totalTiposCombustible = TipoCombustible::count();
        $this->tiposActivosCombustible = TipoCombustible::where('activo', true)->count();

        // KPI 4: Vehículos por categoría
        $this->vehiculosPorCategoria = TipoVehiculo::select('categoria', DB::raw('COUNT(*) as count'))
            ->where('activo', true)
            ->groupBy('categoria')
            ->get()
            ->pluck('count', 'categoria')
            ->toArray();

        // KPI 5: Estado de unidades de transporte
        $this->vehiculosPorEstado = UnidadTransporte::select('estado_operativo', DB::raw('COUNT(*) as count'))
            ->groupBy('estado_operativo')
            ->get()
            ->pluck('count', 'estado_operativo')
            ->toArray();

        // KPI 6: Consumo promedio por categoría
        $this->consumoPromedio = TipoVehiculo::select('categoria')
            ->selectRaw('AVG(consumo_promedio_ciudad) as consumo_ciudad')
            ->selectRaw('AVG(consumo_promedio_carretera) as consumo_carretera')
            ->where('activo', true)
            ->whereNotNull('consumo_promedio_ciudad')
            ->groupBy('categoria')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->categoria => [
                        'ciudad' => round($item->consumo_ciudad, 1),
                        'carretera' => round($item->consumo_carretera, 1)
                    ]
                ];
            })
            ->toArray();

        // KPI 7: Top 5 unidades de transporte más recientes
        $this->topUnidadesTransporte = UnidadTransporte::with(['tipoVehiculo', 'unidadOrganizacional'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($unidad) {
                return [
                    'id' => $unidad->id,
                    'placa' => $unidad->placa,
                    'modelo' => $unidad->modelo,
                    'ano' => $unidad->anio_fabricacion,
                    'tipo' => $unidad->tipoVehiculo?->nombre ?? 'Sin tipo',
                    'unidad' => $unidad->unidadOrganizacional?->codigo_unidad ?? 'Sin unidad',
                    'estado' => $unidad->estado_operativo,
                    'created_at' => $unidad->created_at
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.kpis.vehiculos-kpis');
    }
}
