<?php

namespace App\Livewire\ConsumoCombustible;

use Livewire\Component;
use App\Models\ConsumoCombustible;
use Livewire\Attributes\Title;

#[Title('Detalle del Consumo')]
class Show extends Component
{
    public ConsumoCombustible $consumo;

    public function mount(ConsumoCombustible $consumo)
    {
        $this->consumo = $consumo->load([
            'unidadTransporte.tipoVehiculo',
            'unidadTransporte.tipoCombustible',
            'despacho.proveedor',
            'despacho.solicitud',
            'conductor',
            'validador'
        ]);
    }

    public function validar()
    {
        try {
            $this->consumo->update([
                'validado' => true,
                'fecha_validacion' => now(),
                'id_usuario_validador' => auth()->id(),
            ]);

            $this->consumo->refresh();
            session()->flash('message', 'Consumo validado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al validar el consumo: ' . $e->getMessage());
        }
    }

    public function invalidar()
    {
        try {
            $this->consumo->update([
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ]);

            $this->consumo->refresh();
            session()->flash('message', 'Consumo invalidado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al invalidar el consumo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.consumo-combustible.show');
    }
}
