<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoriaProgramatica>
 */
class CategoriaProgramaticaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->bothify('CAT-###'),
            'descripcion' => $this->faker->sentence(),
            'tipo_categoria' => $this->faker->randomElement(['Programa','Proyecto','Actividad']),
            'id_categoria_padre' => null,
            'nivel' => 1,
            'activo' => true,
        ];
    }
}
