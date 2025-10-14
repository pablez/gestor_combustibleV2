<?php

namespace App\Livewire\SolicitudAprobacionUsuario;

use App\Models\SolicitudAprobacionUsuario;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Filtros
    public $filtroEstado = '';
    public $filtroTipo = '';
    public $filtroSupervisor = '';
    public $filtroBusqueda = '';
    public $ordenPor = 'created_at';
    public $ordenDireccion = 'desc';

    // Modal de aprobación/rechazo
    public $mostrarModalAprobacion = false;
    public $solicitudSeleccionada = null;
    public $accionSeleccionada = '';
    public $observacionesAprobacion = '';

    // Opciones para filtros
    public $estadosDisponibles = [
        'pendiente' => 'Pendiente',
        'aprobado' => 'Aprobado', 
        'rechazado' => 'Rechazado'
    ];

    public $tiposDisponibles = [
        'nuevo_usuario' => 'Nuevo Usuario',
        'cambio_rol' => 'Cambio de Rol',
        'activacion' => 'Activación',
        'suspension' => 'Suspensión'
    ];

    protected $listeners = ['actualizarLista' => '$refresh'];

    public function mount()
    {
        $this->authorize('solicitudes_aprobacion.ver');
    }

    public function render()
    {
        $solicitudes = SolicitudAprobacionUsuario::query()
            ->with(['usuario', 'creador', 'supervisorAsignado', 'aprobador'])
            ->when($this->filtroEstado, function ($query) {
                $query->where('estado_solicitud', $this->filtroEstado);
            })
            ->when($this->filtroTipo, function ($query) {
                $query->where('tipo_solicitud', $this->filtroTipo);  
            })
            ->when($this->filtroSupervisor, function ($query) {
                $query->where('id_supervisor_asignado', $this->filtroSupervisor);
            })
            ->when($this->filtroBusqueda, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('usuario', function ($subQ) {
                        $subQ->where('name', 'like', "%{$this->filtroBusqueda}%")
                            ->orWhere('email', 'like', "%{$this->filtroBusqueda}%");
                    })
                    ->orWhere('justificacion', 'like', "%{$this->filtroBusqueda}%")
                    ->orWhere('rol_solicitado', 'like', "%{$this->filtroBusqueda}%");
                });
            })
            ->orderBy($this->ordenPor, $this->ordenDireccion)
            ->paginate(10)
            ->withQueryString();

        $supervisores = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin_General', 'Admin_Secretaria']);
        })->get(['id', 'name']);

        $contadores = [
            'total' => SolicitudAprobacionUsuario::count(),
            'pendientes' => SolicitudAprobacionUsuario::pendientes()->count(),
            'aprobadas' => SolicitudAprobacionUsuario::aprobadas()->count(),
            'rechazadas' => SolicitudAprobacionUsuario::rechazadas()->count()
        ];

        return view('livewire.solicitud-aprobacion-usuario.index', [
            'solicitudes' => $solicitudes,
            'supervisores' => $supervisores,
            'contadores' => $contadores
        ]);
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroEstado', 'filtroTipo', 'filtroSupervisor', 'filtroBusqueda']);
        $this->resetPage();
    }

    public function ordenar($campo)
    {
        if ($this->ordenPor === $campo) {
            $this->ordenDireccion = $this->ordenDireccion === 'asc' ? 'desc' : 'asc';
        } else {
            $this->ordenPor = $campo;
            $this->ordenDireccion = 'asc';
        }
        $this->resetPage();
    }

    public function abrirModalAprobacion($solicitudId, $accion)
    {
        $this->authorize('solicitudes_aprobacion.' . ($accion === 'aprobar' ? 'aprobar' : 'rechazar'));
        
        $this->solicitudSeleccionada = SolicitudAprobacionUsuario::findOrFail($solicitudId);
        $this->accionSeleccionada = $accion;
        $this->observacionesAprobacion = '';
        $this->mostrarModalAprobacion = true;
    }

    public function procesarAprobacion()
    {
        $this->validate([
            'observacionesAprobacion' => 'nullable|string|max:1000'
        ]);

        try {
            if ($this->accionSeleccionada === 'aprobar') {
                $this->solicitudSeleccionada->aprobar(auth()->id(), $this->observacionesAprobacion);
                session()->flash('message', 'Solicitud aprobada exitosamente.');
            } else {
                $this->validate([
                    'observacionesAprobacion' => 'required|string|max:1000'
                ], [
                    'observacionesAprobacion.required' => 'Las observaciones son obligatorias para rechazar una solicitud.'
                ]);
                
                $this->solicitudSeleccionada->rechazar(auth()->id(), $this->observacionesAprobacion);
                session()->flash('message', 'Solicitud rechazada.');
            }

            $this->cerrarModalAprobacion();
            $this->dispatch('actualizarLista');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function cerrarModalAprobacion()
    {
        $this->mostrarModalAprobacion = false;
        $this->solicitudSeleccionada = null;
        $this->accionSeleccionada = '';
        $this->observacionesAprobacion = '';
    }

    public function updatingFiltroBusqueda()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroTipo()
    {
        $this->resetPage();
    }

    public function updatingFiltroSupervisor()
    {
        $this->resetPage();
    }
}
