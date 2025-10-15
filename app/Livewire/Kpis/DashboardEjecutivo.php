<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\User;
use App\Models\UnidadTransporte;
use App\Models\DespachoCombustible;
use App\Models\ConsumoCombustible;
use App\Models\SolicitudCombustible;
use App\Models\UnidadOrganizacional;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardEjecutivo extends Component
{
    // Métricas principales
    public $metricasPrincipales = [];
    
    // Eficiencia operativa
    public $eficienciaOperativa = [];
    
    // Análisis financiero
    public $analisisFinanciero = [];
    
    // Alertas y notificaciones
    public $alertas = [];
    
    // Tendencias y pronósticos
    public $tendencias = [];
    
    // Comparativas por unidad
    public $comparativasUnidades = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    private function loadDashboardData()
    {
        $user = auth()->user();
        
        $this->metricasPrincipales = $this->getMetricasPrincipales($user);
        $this->eficienciaOperativa = $this->getEficienciaOperativa($user);
        $this->analisisFinanciero = $this->getAnalisisFinanciero($user);
        $this->alertas = $this->getAlertas($user);
        $this->tendencias = $this->getTendencias($user);
        $this->comparativasUnidades = $this->getComparativasUnidades($user);
    }

    private function getMetricasPrincipales($user)
    {
        $baseQuery = $this->getBaseQuery($user);
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $inicioAnio = Carbon::now()->startOfYear();

        return [
            'flota_total' => [
                'valor' => $baseQuery['vehiculos']->count(),
                'activos' => $baseQuery['vehiculos']->where('estado_operativo', 'Operativo')->count(),
                'porcentaje_operativo' => $this->calculatePercentage(
                    $baseQuery['vehiculos']->where('estado_operativo', 'Operativo')->count(),
                    $baseQuery['vehiculos']->count()
                )
            ],
            'combustible_hoy' => [
                'litros' => $baseQuery['despachos']->whereDate('fecha_despacho', $hoy)->sum('litros_despachados'),
                'costo' => $baseQuery['despachos']->whereDate('fecha_despacho', $hoy)->sum('costo_total'),
                'despachos' => $baseQuery['despachos']->whereDate('fecha_despacho', $hoy)->count()
            ],
            'combustible_mes' => [
                'litros' => $baseQuery['despachos']->whereBetween('fecha_despacho', [$inicioMes, now()])->sum('litros_despachados'),
                'costo' => $baseQuery['despachos']->whereBetween('fecha_despacho', [$inicioMes, now()])->sum('costo_total'),
                'despachos' => $baseQuery['despachos']->whereBetween('fecha_despacho', [$inicioMes, now()])->count()
            ],
            'solicitudes_pendientes' => [
                'total' => $baseQuery['solicitudes']->where('estado_solicitud', 'Pendiente')->count(),
                'urgentes' => $baseQuery['solicitudes']->where('estado_solicitud', 'Pendiente')->where('urgente', true)->count(),
                'en_revision' => $baseQuery['solicitudes']->where('estado_solicitud', 'En_Revision')->count()
            ],
            'usuarios_activos' => [
                'conductores' => $baseQuery['usuarios']->whereHas('roles', function($q) { 
                    $q->where('name', 'Conductor'); 
                })->where('activo', true)->count(),
                'supervisores' => $baseQuery['usuarios']->whereHas('roles', function($q) { 
                    $q->where('name', 'Supervisor'); 
                })->where('activo', true)->count(),
                'total_activos' => $baseQuery['usuarios']->where('activo', true)->count()
            ]
        ];
    }

    private function getEficienciaOperativa($user)
    {
        $baseQuery = $this->getBaseQuery($user);
        $ultimosMes = Carbon::now()->subDays(30);

        // Rendimiento promedio por vehículo
        $rendimientoPorVehiculo = DB::table('consumo_combustibles as cc')
            ->join('unidad_transportes as ut', 'cc.id_unidad_transporte', '=', 'ut.id')
            ->join('unidades_organizacionales as uo', 'ut.id_unidad_organizacional', '=', 'uo.id_unidad_organizacional')
            ->where('cc.fecha_registro', '>=', $ultimosMes)
            ->when($user->hasRole('Admin_Secretaria'), function($query) use ($user) {
                return $query->where('uo.id_unidad_organizacional', $user->id_unidad_organizacional);
            })
            ->select(
                'ut.placa',
                'ut.marca',
                'ut.modelo',
                'uo.nombre_unidad',
                DB::raw('SUM(cc.kilometraje_fin - cc.kilometraje_inicial) as km_recorridos'),
                DB::raw('SUM(cc.litros_cargados) as litros_consumidos'),
                DB::raw('ROUND(SUM(cc.kilometraje_fin - cc.kilometraje_inicial) / SUM(cc.litros_cargados), 2) as rendimiento_km_litro')
            )
            ->groupBy('ut.id', 'ut.placa', 'ut.marca', 'ut.modelo', 'uo.nombre_unidad')
            ->having('litros_consumidos', '>', 0)
            ->orderBy('rendimiento_km_litro', 'desc')
            ->limit(10)
            ->get();

        // Eficiencia por unidad organizacional
        $eficienciaPorUnidad = DB::table('unidades_organizacionales as uo')
            ->leftJoin('unidad_transportes as ut', 'uo.id_unidad_organizacional', '=', 'ut.id_unidad_organizacional')
            ->leftJoin('consumo_combustibles as cc', 'ut.id', '=', 'cc.id_unidad_transporte')
            ->where('cc.fecha_registro', '>=', $ultimosMes)
            ->when($user->hasRole('Admin_Secretaria'), function($query) use ($user) {
                return $query->where('uo.id_unidad_organizacional', $user->id_unidad_organizacional);
            })
            ->select(
                'uo.nombre_unidad',
                DB::raw('COUNT(DISTINCT ut.id) as vehiculos_activos'),
                DB::raw('SUM(cc.litros_cargados) as litros_totales'),
                DB::raw('SUM(cc.kilometraje_fin - cc.kilometraje_inicial) as km_totales'),
                DB::raw('ROUND(SUM(cc.kilometraje_fin - cc.kilometraje_inicial) / SUM(cc.litros_cargados), 2) as eficiencia_promedio')
            )
            ->groupBy('uo.id_unidad_organizacional', 'uo.nombre_unidad')
            ->having('litros_totales', '>', 0)
            ->orderBy('eficiencia_promedio', 'desc')
            ->get();

        return [
            'rendimiento_vehiculos' => $rendimientoPorVehiculo,
            'eficiencia_unidades' => $eficienciaPorUnidad,
            'promedio_general' => $rendimientoPorVehiculo->avg('rendimiento_km_litro') ?? 0,
            'mejor_rendimiento' => $rendimientoPorVehiculo->first(),
            'vehiculos_bajo_rendimiento' => $rendimientoPorVehiculo->where('rendimiento_km_litro', '<', 8)->count()
        ];
    }

    private function getAnalisisFinanciero($user)
    {
        $baseQuery = $this->getBaseQuery($user);
        $hoy = Carbon::today();
        $mesActual = Carbon::now()->startOfMonth();
        $mesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();

        // Costos actuales vs mes anterior
        $costoMesActual = $baseQuery['despachos']->whereBetween('fecha_despacho', [$mesActual, now()])->sum('costo_total');
        $costoMesAnterior = $baseQuery['despachos']->whereBetween('fecha_despacho', [$mesAnterior, $finMesAnterior])->sum('costo_total');
        
        // Análisis por proveedor
        $gastosPorProveedor = DB::table('despacho_combustibles as dc')
            ->join('proveedors as p', 'dc.id_proveedor', '=', 'p.id')
            ->join('solicitud_combustibles as sc', 'dc.id_solicitud', '=', 'sc.id')
            ->join('unidad_transportes as ut', 'sc.id_unidad_transporte', '=', 'ut.id')
            ->join('unidades_organizacionales as uo', 'ut.id_unidad_organizacional', '=', 'uo.id_unidad_organizacional')
            ->whereBetween('dc.fecha_despacho', [$mesActual, now()])
            ->when($user->hasRole('Admin_Secretaria'), function($query) use ($user) {
                return $query->where('uo.id_unidad_organizacional', $user->id_unidad_organizacional);
            })
            ->select(
                'p.nombre_proveedor',
                DB::raw('SUM(dc.costo_total) as costo_total'),
                DB::raw('SUM(dc.litros_despachados) as litros_total'),
                DB::raw('ROUND(AVG(dc.precio_por_litro), 2) as precio_promedio'),
                DB::raw('COUNT(*) as total_despachos')
            )
            ->groupBy('p.id', 'p.nombre_proveedor')
            ->orderBy('costo_total', 'desc')
            ->get();

        return [
            'costos_comparativos' => [
                'mes_actual' => $costoMesActual,
                'mes_anterior' => $costoMesAnterior,
                'variacion_porcentual' => $this->calculateVariation($costoMesActual, $costoMesAnterior),
                'diferencia_absoluta' => $costoMesActual - $costoMesAnterior
            ],
            'gastos_por_proveedor' => $gastosPorProveedor,
            'costo_promedio_litro' => $gastosPorProveedor->avg('precio_promedio') ?? 0,
            'proveedor_mas_caro' => $gastosPorProveedor->sortByDesc('precio_promedio')->first(),
            'proveedor_mas_economico' => $gastosPorProveedor->sortBy('precio_promedio')->first()
        ];
    }

    private function getAlertas($user)
    {
        $alertas = [];
        $baseQuery = $this->getBaseQuery($user);

        // Alertas de mantenimiento
        $vehiculosMantenimiento = $baseQuery['vehiculos']
            ->where('estado_operativo', 'Mantenimiento')
            ->with('unidadOrganizacional')
            ->get();

        if ($vehiculosMantenimiento->count() > 0) {
            $alertas[] = [
                'tipo' => 'warning',
                'categoria' => 'Mantenimiento',
                'mensaje' => "Hay {$vehiculosMantenimiento->count()} vehículo(s) en mantenimiento",
                'detalle' => $vehiculosMantenimiento->pluck('placa')->toArray(),
                'urgencia' => 'media'
            ];
        }

        // Alertas de solicitudes urgentes
        $solicitudesUrgentes = $baseQuery['solicitudes']
            ->where('urgente', true)
            ->where('estado_solicitud', 'Pendiente')
            ->count();

        if ($solicitudesUrgentes > 0) {
            $alertas[] = [
                'tipo' => 'danger',
                'categoria' => 'Solicitudes Urgentes',
                'mensaje' => "Hay {$solicitudesUrgentes} solicitud(es) urgente(s) pendiente(s)",
                'urgencia' => 'alta'
            ];
        }

        // Alertas de combustible sin validar
        $despachosSinValidar = $baseQuery['despachos']
            ->where('validado', false)
            ->where('fecha_despacho', '>=', Carbon::now()->subDays(7))
            ->count();

        if ($despachosSinValidar > 0) {
            $alertas[] = [
                'tipo' => 'info',
                'categoria' => 'Validaciones Pendientes',
                'mensaje' => "Hay {$despachosSinValidar} despacho(s) sin validar de los últimos 7 días",
                'urgencia' => 'baja'
            ];
        }

        // Alertas de rendimiento bajo
        $vehiculosBajoRendimiento = DB::table('consumo_combustibles as cc')
            ->join('unidad_transportes as ut', 'cc.id_unidad_transporte', '=', 'ut.id')
            ->where('cc.fecha_registro', '>=', Carbon::now()->subDays(30))
            ->select(
                'ut.placa',
                DB::raw('ROUND(SUM(cc.kilometraje_fin - cc.kilometraje_inicial) / SUM(cc.litros_cargados), 2) as rendimiento')
            )
            ->groupBy('ut.id', 'ut.placa')
            ->having('rendimiento', '<', 6)
            ->having(DB::raw('SUM(cc.litros_cargados)'), '>', 0)
            ->get();

        if ($vehiculosBajoRendimiento->count() > 0) {
            $alertas[] = [
                'tipo' => 'warning',
                'categoria' => 'Rendimiento Bajo',
                'mensaje' => "Hay {$vehiculosBajoRendimiento->count()} vehículo(s) con rendimiento bajo (<6 km/l)",
                'detalle' => $vehiculosBajoRendimiento->pluck('placa')->toArray(),
                'urgencia' => 'media'
            ];
        }

        return $alertas;
    }

    private function getTendencias($user)
    {
        // Consumo de combustible últimos 6 meses
        $consumoMensual = DB::table('despacho_combustibles as dc')
            ->join('solicitud_combustibles as sc', 'dc.id_solicitud', '=', 'sc.id')
            ->join('unidad_transportes as ut', 'sc.id_unidad_transporte', '=', 'ut.id')
            ->join('unidades_organizacionales as uo', 'ut.id_unidad_organizacional', '=', 'uo.id_unidad_organizacional')
            ->where('dc.fecha_despacho', '>=', Carbon::now()->subMonths(6))
            ->when($user->hasRole('Admin_Secretaria'), function($query) use ($user) {
                return $query->where('uo.id_unidad_organizacional', $user->id_unidad_organizacional);
            })
            ->select(
                DB::raw('YEAR(dc.fecha_despacho) as anio'),
                DB::raw('MONTH(dc.fecha_despacho) as mes'),
                DB::raw('SUM(dc.litros_despachados) as litros'),
                DB::raw('SUM(dc.costo_total) as costo'),
                DB::raw('COUNT(*) as despachos')
            )
            ->groupBy(DB::raw('YEAR(dc.fecha_despacho)'), DB::raw('MONTH(dc.fecha_despacho)'))
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        return [
            'consumo_mensual' => $consumoMensual,
            'tendencia_litros' => $this->calculateTrend($consumoMensual->pluck('litros')->toArray()),
            'tendencia_costos' => $this->calculateTrend($consumoMensual->pluck('costo')->toArray()),
            'proyeccion_mes_siguiente' => $this->projectNextMonth($consumoMensual)
        ];
    }

    private function getComparativasUnidades($user)
    {
        if ($user->hasRole('Admin_Secretaria')) {
            return []; // No mostrar comparativas si es admin de secretaría
        }

        return DB::table('unidades_organizacionales as uo')
            ->leftJoin('unidad_transportes as ut', 'uo.id_unidad_organizacional', '=', 'ut.id_unidad_organizacional')
            ->leftJoin('consumo_combustibles as cc', function($join) {
                $join->on('ut.id', '=', 'cc.id_unidad_transporte')
                     ->where('cc.fecha_registro', '>=', Carbon::now()->subDays(30));
            })
            ->leftJoin('despacho_combustibles as dc', function($join) {
                $join->on('cc.id_despacho', '=', 'dc.id');
            })
            ->select(
                'uo.nombre_unidad',
                DB::raw('COUNT(DISTINCT ut.id) as total_vehiculos'),
                DB::raw('COUNT(DISTINCT CASE WHEN ut.estado_operativo = "Operativo" THEN ut.id END) as vehiculos_operativos'),
                DB::raw('COALESCE(SUM(cc.litros_cargados), 0) as litros_consumidos'),
                DB::raw('COALESCE(SUM(dc.costo_total), 0) as costo_total'),
                DB::raw('COALESCE(SUM(cc.kilometraje_fin - cc.kilometraje_inicial), 0) as km_recorridos'),
                DB::raw('CASE WHEN SUM(cc.litros_cargados) > 0 THEN ROUND(SUM(cc.kilometraje_fin - cc.kilometraje_inicial) / SUM(cc.litros_cargados), 2) ELSE 0 END as eficiencia')
            )
            ->groupBy('uo.id_unidad_organizacional', 'uo.nombre_unidad')
            ->orderBy('eficiencia', 'desc')
            ->get();
    }

    private function getBaseQuery($user)
    {
        $queries = [];
        
        if ($user->hasRole('Admin_General')) {
            $queries['vehiculos'] = UnidadTransporte::query();
            $queries['usuarios'] = User::query();
            $queries['despachos'] = DespachoCombustible::query();
            $queries['solicitudes'] = SolicitudCombustible::query();
        } elseif ($user->hasRole('Admin_Secretaria')) {
            $queries['vehiculos'] = UnidadTransporte::where('id_unidad_organizacional', $user->id_unidad_organizacional);
            $queries['usuarios'] = User::where('id_unidad_organizacional', $user->id_unidad_organizacional);
            $queries['despachos'] = DespachoCombustible::whereHas('solicitud.unidadTransporte', function($q) use ($user) {
                $q->where('id_unidad_organizacional', $user->id_unidad_organizacional);
            });
            $queries['solicitudes'] = SolicitudCombustible::whereHas('unidadTransporte', function($q) use ($user) {
                $q->where('id_unidad_organizacional', $user->id_unidad_organizacional);
            });
        } else {
            // Para supervisores y conductores, datos limitados
            $queries['vehiculos'] = UnidadTransporte::where('id_conductor_asignado', $user->id);
            $queries['usuarios'] = User::where('id', $user->id);
            $queries['despachos'] = DespachoCombustible::whereHas('solicitud', function($q) use ($user) {
                $q->where('id_usuario_solicitante', $user->id);
            });
            $queries['solicitudes'] = SolicitudCombustible::where('id_usuario_solicitante', $user->id);
        }

        return $queries;
    }

    private function calculatePercentage($numerator, $denominator)
    {
        return $denominator > 0 ? round(($numerator / $denominator) * 100, 1) : 0;
    }

    private function calculateVariation($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function calculateTrend($values)
    {
        if (count($values) < 2) return 'estable';
        
        $lastValue = end($values);
        $firstValue = reset($values);
        
        if ($lastValue > $firstValue * 1.1) return 'ascendente';
        if ($lastValue < $firstValue * 0.9) return 'descendente';
        return 'estable';
    }

    private function projectNextMonth($consumoMensual)
    {
        if ($consumoMensual->count() < 2) return null;
        
        $ultimos3Meses = $consumoMensual->take(-3);
        $promedioLitros = $ultimos3Meses->avg('litros');
        $promedioCosto = $ultimos3Meses->avg('costo');
        
        return [
            'litros_estimados' => round($promedioLitros),
            'costo_estimado' => round($promedioCosto, 2)
        ];
    }

    public function render()
    {
        return view('livewire.kpis.dashboard-ejecutivo');
    }
}