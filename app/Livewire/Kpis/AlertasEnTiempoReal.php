<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\SolicitudCombustible;
use App\Models\DespachoCombustible;
use App\Models\UnidadTransporte;
use App\Models\ConsumoCombustible;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AlertasEnTiempoReal extends Component
{
    public $alertasActivas = [];
    public $mostrarAlertas = true;

    public function mount()
    {
        $this->cargarAlertas();
    }

    #[On('refreshAlertas')]
    public function cargarAlertas()
    {
        $this->alertasActivas = [];
        $user = auth()->user();

        // Alertas críticas (rojas)
        $this->verificarSolicitudesUrgentes($user);
        $this->verificarVehiculosEnPanne($user);
        $this->verificarConsumoExcesivo($user);

        // Alertas importantes (amarillas)
        $this->verificarMantenimientoPendiente($user);
        $this->verificarDespachosSinValidar($user);
        $this->verificarRendimientoBajo($user);

        // Alertas informativas (azules)
        $this->verificarMetasPresupuestarias($user);
        $this->verificarActividadUsuarios($user);
    }

    private function verificarSolicitudesUrgentes($user)
    {
        $query = SolicitudCombustible::where('urgente', true)
                                   ->where('estado_solicitud', 'Pendiente')
                                   ->where('created_at', '>=', Carbon::now()->subHours(2));

        if ($user->hasRole('Admin_Secretaria')) {
            $query->whereHas('unidadTransporte', function($q) use ($user) {
                $q->where('id_unidad_organizacional', $user->id_unidad_organizacional);
            });
        }

        $count = $query->count();
        if ($count > 0) {
            $this->alertasActivas[] = [
                'id' => 'solicitudes_urgentes',
                'tipo' => 'critica',
                'titulo' => 'Solicitudes Urgentes',
                'mensaje' => "Hay {$count} solicitud(es) urgente(s) sin atender",
                'detalle' => 'Requieren atención inmediata',
                'accion' => 'Revisar ahora',
                'icono' => 'exclamation',
                'tiempo' => 'Últimas 2 horas',
                'prioridad' => 1
            ];
        }
    }

    private function verificarVehiculosEnPanne($user)
    {
        $query = UnidadTransporte::where('estado_operativo', 'Taller')
                               ->orWhere('estado_operativo', 'Baja');

        if ($user->hasRole('Admin_Secretaria')) {
            $query->where('id_unidad_organizacional', $user->id_unidad_organizacional);
        }

        $count = $query->count();
        if ($count > 0) {
            $this->alertasActivas[] = [
                'id' => 'vehiculos_panne',
                'tipo' => 'critica',
                'titulo' => 'Vehículos Fuera de Servicio',
                'mensaje' => "{$count} vehículo(s) en taller o dados de baja",
                'detalle' => 'Impacto en la capacidad operativa',
                'accion' => 'Ver detalles',
                'icono' => 'truck',
                'tiempo' => 'Estado actual',
                'prioridad' => 2
            ];
        }
    }

    private function verificarConsumoExcesivo($user)
    {
        // Consumo de hoy vs promedio de los últimos 7 días
        $consumoHoy = DespachoCombustible::whereDate('fecha_despacho', Carbon::today())
                                        ->sum('litros_despachados');

        $promedioSemanal = DB::table('despacho_combustibles')
                                           ->selectRaw('AVG(daily_total) as promedio')
                                           ->fromSub(function($query) {
                                               $query->selectRaw('DATE(fecha_despacho) as fecha, SUM(litros_despachados) as daily_total')
                                                     ->from('despacho_combustibles')
                                                     ->whereBetween('fecha_despacho', [Carbon::now()->subDays(7), Carbon::now()->subDay()])
                                                     ->groupBy('fecha');
                                           }, 'daily_totals')
                                           ->value('promedio') ?? 0;

        if ($consumoHoy > ($promedioSemanal * 1.5) && $consumoHoy > 100) {
            $this->alertasActivas[] = [
                'id' => 'consumo_excesivo',
                'tipo' => 'critica',
                'titulo' => 'Consumo Excesivo Detectado',
                'mensaje' => "Consumo hoy: {$consumoHoy}L vs promedio: " . number_format($promedioSemanal, 0) . 'L',
                'detalle' => 'Consumo ' . number_format((($consumoHoy / $promedioSemanal) - 1) * 100, 0) . '% superior al promedio',
                'accion' => 'Investigar',
                'icono' => 'fire',
                'tiempo' => 'Hoy',
                'prioridad' => 3
            ];
        }
    }

    private function verificarMantenimientoPendiente($user)
    {
        $query = UnidadTransporte::where('estado_operativo', 'Mantenimiento');

        if ($user->hasRole('Admin_Secretaria')) {
            $query->where('id_unidad_organizacional', $user->id_unidad_organizacional);
        }

        $count = $query->count();
        if ($count > 0) {
            $this->alertasActivas[] = [
                'id' => 'mantenimiento_pendiente',
                'tipo' => 'importante',
                'titulo' => 'Mantenimiento Pendiente',
                'mensaje' => "{$count} vehículo(s) en mantenimiento",
                'detalle' => 'Verificar estado y programación',
                'accion' => 'Gestionar',
                'icono' => 'wrench',
                'tiempo' => 'Estado actual',
                'prioridad' => 4
            ];
        }
    }

    private function verificarDespachosSinValidar($user)
    {
        $query = DespachoCombustible::where('validado', false)
                                  ->where('fecha_despacho', '>=', Carbon::now()->subDays(3));

        if ($user->hasRole('Admin_Secretaria')) {
            $query->whereHas('solicitud.unidadTransporte', function($q) use ($user) {
                $q->where('id_unidad_organizacional', $user->id_unidad_organizacional);
            });
        }

        $count = $query->count();
        if ($count > 0) {
            $this->alertasActivas[] = [
                'id' => 'despachos_sin_validar',
                'tipo' => 'importante',
                'titulo' => 'Despachos Sin Validar',
                'mensaje' => "{$count} despacho(s) pendiente(s) de validación",
                'detalle' => 'Últimos 3 días',
                'accion' => 'Validar',
                'icono' => 'clipboard-check',
                'tiempo' => 'Últimos 3 días',
                'prioridad' => 5
            ];
        }
    }

    private function verificarRendimientoBajo($user)
    {
        // Vehículos con rendimiento menor a 6 km/L en los últimos 15 días
        $vehiculosBajoRendimiento = ConsumoCombustible::select('id_unidad_transporte')
            ->selectRaw('SUM(kilometraje_fin - kilometraje_inicial) as km_total')
            ->selectRaw('SUM(litros_cargados) as litros_total')
            ->selectRaw('(SUM(kilometraje_fin - kilometraje_inicial) / SUM(litros_cargados)) as rendimiento')
            ->where('fecha_registro', '>=', Carbon::now()->subDays(15))
            ->groupBy('id_unidad_transporte')
            ->having('litros_total', '>', 20) // Al menos 20 litros consumidos
            ->having('rendimiento', '<', 6)
            ->count();

        if ($vehiculosBajoRendimiento > 0) {
            $this->alertasActivas[] = [
                'id' => 'rendimiento_bajo',
                'tipo' => 'importante',
                'titulo' => 'Rendimiento Bajo',
                'mensaje' => "{$vehiculosBajoRendimiento} vehículo(s) con rendimiento <6 km/L",
                'detalle' => 'Últimos 15 días de actividad',
                'accion' => 'Analizar',
                'icono' => 'chart-line',
                'tiempo' => 'Últimos 15 días',
                'prioridad' => 6
            ];
        }
    }

    private function verificarMetasPresupuestarias($user)
    {
        // Esta es una función placeholder - necesitaría acceso a datos de presupuesto
        // Para efectos de demostración, simularemos una alerta
        $mesActual = Carbon::now()->month;
        $porcentajeAño = ($mesActual / 12) * 100;
        
        // Simulamos que si estamos en más del 75% del año y hemos gastado más del 80%, hay alerta
        if ($porcentajeAño > 75) {
            $this->alertasActivas[] = [
                'id' => 'meta_presupuestaria',
                'tipo' => 'informativa',
                'titulo' => 'Seguimiento Presupuestario',
                'mensaje' => 'Revisar ejecución presupuestaria del período',
                'detalle' => "Mes {$mesActual}/12 del ejercicio fiscal",
                'accion' => 'Ver reportes',
                'icono' => 'currency-dollar',
                'tiempo' => 'Mensual',
                'prioridad' => 7
            ];
        }
    }

    private function verificarActividadUsuarios($user)
    {
        $usuariosInactivos = \App\Models\User::where('activo', true)
                                           ->where('fecha_ultimo_acceso', '<', Carbon::now()->subDays(7))
                                           ->count();

        if ($usuariosInactivos > 0) {
            $this->alertasActivas[] = [
                'id' => 'usuarios_inactivos',
                'tipo' => 'informativa',
                'titulo' => 'Actividad de Usuarios',
                'mensaje' => "{$usuariosInactivos} usuario(s) sin acceso en 7 días",
                'detalle' => 'Verificar estado de cuentas',
                'accion' => 'Revisar',
                'icono' => 'user-circle',
                'tiempo' => 'Últimos 7 días',
                'prioridad' => 8
            ];
        }
    }

    public function cerrarAlerta($alertaId)
    {
        $this->alertasActivas = array_filter($this->alertasActivas, function($alerta) use ($alertaId) {
            return $alerta['id'] !== $alertaId;
        });
    }

    public function toggleAlertas()
    {
        $this->mostrarAlertas = !$this->mostrarAlertas;
    }

    public function render()
    {
        // Ordenar alertas por prioridad
        usort($this->alertasActivas, function($a, $b) {
            return $a['prioridad'] <=> $b['prioridad'];
        });

        return view('livewire.kpis.alertas-en-tiempo-real');
    }
}