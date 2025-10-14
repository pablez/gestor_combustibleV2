<?php

namespace App\Livewire\CodigoRegistro;

use App\Models\CodigoRegistro;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Filtros
    public $filtroEstado = '';
    public $filtroGenerador = '';
    public $filtroBusqueda = '';
    public $ordenPor = 'created_at';
    public $ordenDireccion = 'desc';

    // Estados disponibles
    public $estadosDisponibles = [
        'vigente' => 'Vigente',
        'usado' => 'Usado',
        'vencido' => 'Vencido'
    ];

    protected $listeners = ['actualizarLista' => '$refresh'];

    public function mount()
    {
        $this->authorize('codigos_registro.ver');
    }

    public function render()
    {
        $query = CodigoRegistro::query()
            ->with(['generador', 'usuarioUsado'])
            ->when($this->filtroEstado, function ($query) {
                if ($this->filtroEstado === 'vigente') {
                    $query->vigentes();
                } elseif ($this->filtroEstado === 'usado') {
                    $query->usados();
                } elseif ($this->filtroEstado === 'vencido') {
                    $query->vencidos();
                }
            })
            ->when($this->filtroGenerador, function ($query) {
                $query->where('id_usuario_generador', $this->filtroGenerador);
            })
            ->when($this->filtroBusqueda, function ($query) {
                $query->where(function ($q) {
                    $q->where('codigo', 'like', "%{$this->filtroBusqueda}%")
                      ->orWhereHas('generador', function ($subQ) {
                          $subQ->where('name', 'like', "%{$this->filtroBusqueda}%");
                      })
                      ->orWhereHas('usuarioUsado', function ($subQ) {
                          $subQ->where('name', 'like', "%{$this->filtroBusqueda}%");
                      });
                });
            })
            ->orderBy($this->ordenPor, $this->ordenDireccion);

        $codigos = $query->paginate(10)->withQueryString();

        $generadores = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin_General', 'Admin_Secretaria']);
        })->get(['id', 'name']);

        $contadores = [
            'total' => CodigoRegistro::count(),
            'vigentes' => CodigoRegistro::vigentes()->count(),
            'usados' => CodigoRegistro::usados()->count(),
            'vencidos' => CodigoRegistro::vencidos()->count()
        ];

        return view('livewire.codigo-registro.index', [
            'codigos' => $codigos,
            'generadores' => $generadores,
            'contadores' => $contadores
        ]);
    }

    public function limpiarFiltros()
    {
        $this->reset(['filtroEstado', 'filtroGenerador', 'filtroBusqueda']);
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

    public function eliminarCodigo($codigoId)
    {
        $this->authorize('codigos_registro.eliminar');
        
        try {
            $codigo = CodigoRegistro::findOrFail($codigoId);
            
            if ($codigo->usado) {
                session()->flash('error', 'No se puede eliminar un código que ya ha sido usado.');
                return;
            }
            
            $codigo->delete();
            session()->flash('message', 'Código eliminado exitosamente.');
            $this->dispatch('actualizarLista');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el código: ' . $e->getMessage());
        }
    }

    public function updatingFiltroBusqueda()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatingFiltroGenerador()
    {
        $this->resetPage();
    }
}
