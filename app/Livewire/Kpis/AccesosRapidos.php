<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\SolicitudCombustible;
use App\Models\DespachoCombustible;
use App\Models\UnidadTransporte;
use App\Models\User;
use Carbon\Carbon;

class AccesosRapidos extends Component
{
    public $accesosAdministrador = [];
    public $estadisticasRapidas = [];
    public $notificacionesPendientes = [];

    public function mount()
    {
        $this->loadAccesosRapidos();
    }

    private function loadAccesosRapidos()
    {
        $user = auth()->user();
        
        $this->accesosAdministrador = [
            [
                'titulo' => 'Solicitudes Pendientes',
                'descripcion' => 'Revisar y aprobar solicitudes',
                'icono' => 'clipboard-list',
                'color' => 'orange',
                'ruta' => route('solicitudes.index'),
                'contador' => $this->getSolicitudesPendientes($user),
                'urgente' => true
            ],
            [
                'titulo' => 'Generar Reportes',
                'descripcion' => 'Reportes de combustible y presupuesto',
                'icono' => 'chart-bar',
                'color' => 'blue',
                'ruta' => route('reportes.index'),
                'contador' => null,
                'urgente' => false
            ],
            [
                'titulo' => 'Gestionar Vehículos',
                'descripcion' => 'Administrar flota de vehículos',
                'icono' => 'truck',
                'color' => 'green',
                'ruta' => route('unidades-transporte.index'),
                'contador' => $this->getVehiculosMantenimiento($user),
                'urgente' => false
            ],
            [
                'titulo' => 'Usuarios y Permisos',
                'descripcion' => 'Gestionar usuarios del sistema',
                'icono' => 'users',
                'color' => 'purple',
                'ruta' => route('users.index'),
                'contador' => $this->getUsuariosPendientes($user),
                'urgente' => false
            ],
            [
                'titulo' => 'Validar Despachos',
                'descripcion' => 'Validar consumos y despachos',
                'icono' => 'check-circle',
                'color' => 'indigo',
                'ruta' => route('despachos.index'),
                'contador' => $this->getDespachosPendientes($user),
                'urgente' => false
            ],
            [
                'titulo' => 'Monitoreo en Tiempo Real',
                'descripcion' => 'Dashboard de KPIs en vivo',
                'icono' => 'monitor',
                'color' => 'emerald',
                'ruta' => route('dashboard'),
                'contador' => null,
                'urgente' => false
            ]
        ];

        $this->estadisticasRapidas = [
            'solicitudes_hoy' => SolicitudCombustible::whereDate('fecha_solicitud', Carbon::today())->count(),
            'despachos_hoy' => DespachoCombustible::whereDate('fecha_despacho', Carbon::today())->count(),
            'litros_hoy' => DespachoCombustible::whereDate('fecha_despacho', Carbon::today())->sum('litros_despachados'),
            'vehiculos_operativos' => UnidadTransporte::where('estado_operativo', 'Operativo')->count(),
            'usuarios_activos_hoy' => User::where('fecha_ultimo_acceso', '>=', Carbon::today())->count()
        ];

        $this->notificacionesPendientes = $this->getNotificacionesPendientes($user);
    }

    private function getSolicitudesPendientes($user)
    {
        $query = SolicitudCombustible::where('estado_solicitud', 'Pendiente');
        
        if ($user->hasRole('Admin_Secretaria')) {
            $query->whereHas('unidadTransporte', function($q) use ($user) {
                $q->where('id_unidad_organizacional', $user->id_unidad_organizacional);
            });
        }
        
        return $query->count();
    }

    private function getVehiculosMantenimiento($user)
    {
        $query = UnidadTransporte::where('estado_operativo', 'Mantenimiento');
        
        if ($user->hasRole('Admin_Secretaria')) {
            $query->where('id_unidad_organizacional', $user->id_unidad_organizacional);
        }
        
        return $query->count();
    }

    private function getUsuariosPendientes($user)
    {
        if ($user->hasRole('Admin_General')) {
            return User::where('activo', false)->count();
        }
        
        if ($user->hasRole('Admin_Secretaria')) {
            return User::where('id_unidad_organizacional', $user->id_unidad_organizacional)
                      ->where('activo', false)
                      ->count();
        }
        
        return 0;
    }

    private function getDespachosPendientes($user)
    {
        $query = DespachoCombustible::where('validado', false)
                                  ->where('fecha_despacho', '>=', Carbon::now()->subDays(7));
        
        if ($user->hasRole('Admin_Secretaria')) {
            $query->whereHas('solicitud.unidadTransporte', function($q) use ($user) {
                $q->where('id_unidad_organizacional', $user->id_unidad_organizacional);
            });
        }
        
        return $query->count();
    }

    private function getNotificacionesPendientes($user)
    {
        $notificaciones = [];
        
        // Solicitudes urgentes
        $solicitudesUrgentes = SolicitudCombustible::where('urgente', true)
                                                 ->where('estado_solicitud', 'Pendiente')
                                                 ->count();
        
        if ($solicitudesUrgentes > 0) {
            $notificaciones[] = [
                'tipo' => 'urgente',
                'mensaje' => "{$solicitudesUrgentes} solicitud(es) urgente(s) pendiente(s)",
                'accion' => 'Revisar ahora',
                'ruta' => route('solicitudes.index', ['urgente' => 1])
            ];
        }
        
        // Vehículos que necesitan mantenimiento
        $vehiculosMantenimiento = UnidadTransporte::where('estado_operativo', 'Mantenimiento')->count();
        
        if ($vehiculosMantenimiento > 0) {
            $notificaciones[] = [
                'tipo' => 'mantenimiento',
                'mensaje' => "{$vehiculosMantenimiento} vehículo(s) en mantenimiento",
                'accion' => 'Ver detalles',
                'ruta' => route('vehiculos.index', ['estado' => 'Mantenimiento'])
            ];
        }
        
        // Despachos sin validar (últimos 3 días)
        $despachosSinValidar = DespachoCombustible::where('validado', false)
                                                 ->where('fecha_despacho', '>=', Carbon::now()->subDays(3))
                                                 ->count();
        
        if ($despachosSinValidar > 0) {
            $notificaciones[] = [
                'tipo' => 'validacion',
                'mensaje' => "{$despachosSinValidar} despacho(s) pendiente(s) de validación",
                'accion' => 'Validar',
                'ruta' => route('despachos.index', ['validado' => 0])
            ];
        }
        
        return $notificaciones;
    }

    public function render()
    {
        return view('livewire.kpis.accesos-rapidos');
    }
}