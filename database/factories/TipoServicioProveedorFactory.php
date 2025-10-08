<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoServicioProveedor>
 */
class TipoServicioProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->bothify('TSP-##'),
            'nombre' => $this->faker->unique()->word(),
            'descripcion' => $this->faker->sentence(),
            'requiere_autorizacion_especial' => false,
            'dias_credito_maximo' => 0,
            'activo' => true,
        ];
    }
}
