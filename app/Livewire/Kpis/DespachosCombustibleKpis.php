<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\DespachoCombustible;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DespachosCombustibleKpis extends Component
{
    public $totalDespachos;
    public $despachosValidados;
    public $despachosPendientes;
    public $porcentajeValidados;
    public $totalLitrosHoy;
    public $totalCostoHoy;
    public $totalLitrosMes;
    public $totalCostoMes;
    public $promedioLitrosPorDespacho;
    public $promedioCostoPorLitro;
    public $despachosRecientes;
    public $proveedoresMasUsados;
    public $estadisticasSemanal;
    public $despachosPorDia;
    public $totalDespachosHoy;
    public $despachosUltimos7Dias;

    public function mount()
    {
        $this->loadKpis();
    }

    private function loadKpis()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $inicioSemana = Carbon::now()->startOfWeek();

        // Totales generales
        $this->totalDespachos = DespachoCombustible::count();
        $this->despachosValidados = DespachoCombustible::where('validado', true)->count();
        $this->despachosPendientes = DespachoCombustible::where('validado', false)->count();

        // Porcentaje de validados
        $this->porcentajeValidados = $this->totalDespachos > 0 
            ? round(($this->despachosValidados / $this->totalDespachos) * 100, 1) 
            : 0;

        // Despachos de hoy
        $this->totalDespachosHoy = DespachoCombustible::whereDate('fecha_despacho', $hoy)->count();
        
        // Totales del día actual
        $despachosHoy = DespachoCombustible::whereDate('fecha_despacho', $hoy);
        $this->totalLitrosHoy = $despachosHoy->sum('litros_despachados') ?? 0;
        $this->totalCostoHoy = $despachosHoy->sum('costo_total') ?? 0;

        // Totales del mes actual
        $despachosMes = DespachoCombustible::whereBetween('fecha_despacho', [$inicioMes, Carbon::now()]);
        $this->totalLitrosMes = $despachosMes->sum('litros_despachados') ?? 0;
        $this->totalCostoMes = $despachosMes->sum('costo_total') ?? 0;

        // Promedios
        $this->promedioLitrosPorDespacho = $this->totalDespachos > 0 
            ? round(DespachoCombustible::avg('litros_despachados'), 1) 
            : 0;
        
        $this->promedioCostoPorLitro = $this->totalLitrosHoy > 0 
            ? round(DespachoCombustible::avg('precio_por_litro'), 0) 
            : 0;

        // Despachos de los últimos 7 días
        $this->despachosUltimos7Dias = DespachoCombustible::where('fecha_despacho', '>=', Carbon::now()->subDays(7))->count();

        // Despachos recientes (últimos 5)
        $this->despachosRecientes = DespachoCombustible::with(['solicitud.unidadTransporte', 'proveedor'])
            ->orderBy('fecha_despacho', 'desc')
            ->limit(5)
            ->get();

        // Proveedores más utilizados (últimos 30 días)
        $this->proveedoresMasUsados = DespachoCombustible::with('proveedor')
            ->where('fecha_despacho', '>=', Carbon::now()->subDays(30))
            ->select('id_proveedor', DB::raw('count(*) as total_despachos'), DB::raw('sum(litros_despachados) as total_litros'))
            ->groupBy('id_proveedor')
            ->orderBy('total_despachos', 'desc')
            ->limit(5)
            ->get();

        // Estadísticas por día de la semana (últimos 30 días)
        $this->despachosPorDia = DespachoCombustible::where('fecha_despacho', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DAYOFWEEK(fecha_despacho) as dia_semana'),
                DB::raw('count(*) as total_despachos'),
                DB::raw('sum(litros_despachados) as total_litros'),
                DB::raw('sum(costo_total) as total_costo')
            )
            ->groupBy('dia_semana')
            ->orderBy('dia_semana')
            ->get()
            ->mapWithKeys(function ($item) {
                $dias = ['', 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                return [$dias[$item->dia_semana] => [
                    'despachos' => $item->total_despachos,
                    'litros' => round($item->total_litros, 1),
                    'costo' => $item->total_costo
                ]];
            });

        // Tendencia semanal (últimas 4 semanas)
        $this->estadisticasSemanal = [];
        for ($i = 3; $i >= 0; $i--) {
            $inicioSemanaX = Carbon::now()->subWeeks($i)->startOfWeek();
            $finSemanaX = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $despachosSemanales = DespachoCombustible::whereBetween('fecha_despacho', [$inicioSemanaX, $finSemanaX]);
            
            $this->estadisticasSemanal[] = [
                'semana' => $inicioSemanaX->format('d/m') . ' - ' . $finSemanaX->format('d/m'),
                'despachos' => $despachosSemanales->count(),
                'litros' => round($despachosSemanales->sum('litros_despachados'), 1),
                'costo' => $despachosSemanales->sum('costo_total'),
                'validados' => $despachosSemanales->where('validado', true)->count()
            ];
        }
    }

    public function render()
    {
        return view('livewire.kpis.despachos-combustible-kpis');
    }
}