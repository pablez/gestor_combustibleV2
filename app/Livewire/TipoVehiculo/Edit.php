<?php

namespace App\Livewire\TipoVehiculo;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\TipoVehiculo;

class Edit extends Component
{
    public TipoVehiculo $tipoVehiculo;

    #[Validate('required|string|max:100')]
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

    public function mount(TipoVehiculo $tipoVehiculo)
    {
        $this->tipoVehiculo = $tipoVehiculo;
        $this->nombre = $tipoVehiculo->nombre;
        $this->categoria = $tipoVehiculo->categoria;
        $this->descripcion = $tipoVehiculo->descripcion ?? '';
        $this->consumo_promedio_ciudad = $tipoVehiculo->consumo_promedio_ciudad;
        $this->consumo_promedio_carretera = $tipoVehiculo->consumo_promedio_carretera;
        $this->capacidad_carga_kg = $tipoVehiculo->capacidad_carga_kg;
        $this->numero_pasajeros = $tipoVehiculo->numero_pasajeros;
        $this->activo = $tipoVehiculo->activo;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:100|unique:tipo_vehiculos,nombre,' . $this->tipoVehiculo->id,
            'categoria' => 'required|string|in:Liviano,Pesado,Motocicleta,Especializado',
            'descripcion' => 'nullable|string|max:500',
            'consumo_promedio_ciudad' => 'nullable|numeric|min:0|max:999.99',
            'consumo_promedio_carretera' => 'nullable|numeric|min:0|max:999.99',
            'capacidad_carga_kg' => 'nullable|integer|min:0|max:999999',
            'numero_pasajeros' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->tipoVehiculo->update([
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'descripcion' => $this->descripcion ?: null,
            'consumo_promedio_ciudad' => $this->consumo_promedio_ciudad,
            'consumo_promedio_carretera' => $this->consumo_promedio_carretera,
            'capacidad_carga_kg' => $this->capacidad_carga_kg,
            'numero_pasajeros' => $this->numero_pasajeros,
            'activo' => $this->activo,
        ]);

        $this->dispatch('tipoVehiculoUpdated');
        $this->dispatch('closeModal');

        session()->flash('message', 'Tipo de vehículo actualizado exitosamente.');
    }

    public function render()
    {
        return view('livewire.tipo-vehiculo.edit');
    }
}
