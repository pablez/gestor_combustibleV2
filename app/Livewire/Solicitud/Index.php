<?php

namespace App\Livewire\Solicitud;

use App\Models\SolicitudCombustible;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filtroUrgente = null;
    public $filtroEstado = '';
    public $buscar = '';

    protected $queryString = [
        'filtroUrgente' => ['except' => null, 'as' => 'urgente'],
        'filtroEstado' => ['except' => '', 'as' => 'estado'],
        'buscar' => ['except' => '', 'as' => 'q'],
    ];

    public function mount()
    {
        // Obtener filtro urgente desde la URL
        $this->filtroUrgente = request()->get('urgente') ? (bool) request()->get('urgente') : null;
        $this->filtroEstado = request()->get('estado', '');
        $this->buscar = request()->get('q', '');
    }

    public function render()
    {
        $user = auth()->user();
        
        // Base query con permisos por rol
        $query = SolicitudCombustible::with(['solicitante', 'unidadTransporte', 'aprobador']);

        // Filtros según el rol del usuario
        if ($user->hasRole('Conductor')) {
            // Los conductores solo ven sus propias solicitudes
            $query->where('id_usuario_solicitante', $user->id);
        } elseif ($user->hasRole('Supervisor')) {
            // Los supervisores ven solicitudes de su unidad organizacional
            if ($user->unidad) {
                $query->whereHas('solicitante', function ($q) use ($user) {
                    $q->where('id_unidad_organizacional', $user->unidad->id_unidad_organizacional);
                });
            }
        }
        // Admin_General y Admin_Secretaria ven todas las solicitudes

        // Aplicar filtros
        if ($this->filtroUrgente !== null) {
            $query->where('urgente', $this->filtroUrgente);
        }

        if (!empty($this->filtroEstado)) {
            $query->where('estado_solicitud', $this->filtroEstado);
        }

        if (!empty($this->buscar)) {
            $query->where(function ($q) {
                $q->where('numero_solicitud', 'like', '%' . $this->buscar . '%')
                  ->orWhere('motivo', 'like', '%' . $this->buscar . '%')
                  ->orWhereHas('solicitante', function ($subQ) {
                      $subQ->where('name', 'like', '%' . $this->buscar . '%');
                  });
            });
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.solicitud.index', [
            'solicitudes' => $solicitudes
        ]);
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroUrgente', 'filtroEstado', 'buscar']);
        $this->resetPage();
    }
}
