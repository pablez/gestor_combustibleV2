<?php

namespace App\Livewire\TipoVehiculo;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\TipoVehiculo;

class Create extends Component
{
    #[Validate('required|string|max:100|unique:tipo_vehiculos,nombre')]
    public string $nombre = '';

    #[Validate('required|string|in:Liviano,Pesado,Motocicleta,Especializado')]
    public string $categoria = '';

    #[Validate('nullable|string|max:500')]
    public string $descripcion = '';

    #[Validate('nullable|numeric|min:0|max:999.99')]
    public ?float $consumo_promedio_ciudad = null;

    #[Validate('nullable|numeric|min:0|max:999.99')]
    public ?float $consumo_promedio_carretera = null;

    #[Validate('nullable|integer|min:0|max:999999')]
    public ?int $capacidad_carga_kg = null;

    #[Validate('nullable|integer|min:1|max:100')]
    public ?int $numero_pasajeros = null;

    public bool $activo = true;

    public function save()
    {
        $this->validate();

        TipoVehiculo::create([
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'descripcion' => $this->descripcion ?: null,
            'consumo_promedio_ciudad' => $this->consumo_promedio_ciudad,
            'consumo_promedio_carretera' => $this->consumo_promedio_carretera,
            'capacidad_carga_kg' => $this->capacidad_carga_kg,
            'numero_pasajeros' => $this->numero_pasajeros,
            'activo' => $this->activo,
        ]);

        $this->dispatch('tipoVehiculoSaved');
        $this->dispatch('closeModal');
        $this->reset();

        session()->flash('message', 'Tipo de vehículo creado exitosamente.');
    }

    public function render()
    {
        return view('livewire.tipo-vehiculo.create');
    }
}
