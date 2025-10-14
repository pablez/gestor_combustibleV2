<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\UnidadTransporte;

class VehiculoImagenesShow extends Component
{
    public UnidadTransporte $vehiculo;

    public function mount($id)
    {
        $this->vehiculo = UnidadTransporte::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.vehiculo-imagenes-show', [
            'vehiculo' => $this->vehiculo
        ]);
    }
}
