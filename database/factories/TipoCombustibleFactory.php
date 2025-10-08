<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoCombustible>
 */
class TipoCombustibleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word() . ' Combustible',
            'codigo_comercial' => strtoupper($this->faker->bothify('C-##')),
            'descripcion' => $this->faker->sentence(),
            'octanaje' => $this->faker->numberBetween(80, 98),
            'precio_referencial' => $this->faker->randomFloat(2, 1, 6),
            'unidad_medida' => 'Litros',
            'activo' => true,
        ];
    }
}
