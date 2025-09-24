<?php

namespace App\Http\Livewire\Solicitud;

use Livewire\Component;
use App\Models\SolicitudCombustible;
use Illuminate\Support\Str;

class Create extends Component
{
    public $cantidad_litros_solicitados;
    public $motivo;
    public $id_unidad_transporte;
    public $urgente = false;

    protected $rules = [
        'cantidad_litros_solicitados' => 'required|numeric|min:0.1',
        'motivo' => 'nullable|string|max:500',
    ];

    public function submit()
    {
        $this->validate();

        $sol = SolicitudCombustible::create([
            'numero_solicitud' => 'SOL-' . strtoupper(Str::random(6)),
            'id_usuario_solicitante' => auth()->id(),
            'id_unidad_transporte' => $this->id_unidad_transporte,
            'cantidad_litros_solicitados' => $this->cantidad_litros_solicitados,
            'motivo' => $this->motivo,
            'urgente' => $this->urgente,
            'estado_solicitud' => 'Pendiente',
        ]);

        $this->reset(['cantidad_litros_solicitados','motivo','id_unidad_transporte','urgente']);

        $this->emit('solicitudCreated', $sol->id);
        session()->flash('message', 'Solicitud creada correctamente');
    }

    public function render()
    {
        return view('livewire.solicitud.create');
    }
}
