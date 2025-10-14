<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UnidadTransporte;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImagenVehiculo>
 */
class ImagenVehiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unidad_transporte_id' => UnidadTransporte::factory(),
            'ruta' => 'vehiculos/' . strtoupper($this->faker->bothify('??###')) . '/' . $this->faker->uuid() . '.jpg',
            'disk' => 'public',
            'tipo' => 'original',
            'metadatos' => ['width' => 1024, 'height' => 768],
            'original_nombre' => $this->faker->word() . '.jpg',
            'mime' => 'image/jpeg',
            'size' => $this->faker->numberBetween(1024, 500000),
            'creado_por' => User::factory(),
        ];
    }
}
