<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoVehiculo>
 */
class TipoVehiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word() . ' ' . $this->faker->randomElement(['A','B','C']),
            'categoria' => $this->faker->randomElement(['Liviano','Pesado','Motocicleta','Especializado']),
            'descripcion' => $this->faker->sentence(),
            'consumo_promedio_ciudad' => $this->faker->randomFloat(2, 5, 20),
            'consumo_promedio_carretera' => $this->faker->randomFloat(2, 5, 20),
            'capacidad_carga_kg' => $this->faker->numberBetween(100, 5000),
            'numero_pasajeros' => $this->faker->numberBetween(1, 60),
            'activo' => true,
        ];
    }
}
