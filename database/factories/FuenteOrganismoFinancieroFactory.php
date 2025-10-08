<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FuenteOrganismoFinanciero>
 */
class FuenteOrganismoFinancieroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->bothify('FOF-###'),
            'descripcion' => $this->faker->sentence(),
            'tipo_fuente' => $this->faker->randomElement(['Nacional','Departamental','Municipal','Internacional','Otros']),
            'organismo_financiador' => $this->faker->company(),
            'requiere_contrapartida' => false,
            'porcentaje_contrapartida' => 0,
            'activo' => true,
        ];
    }
}
