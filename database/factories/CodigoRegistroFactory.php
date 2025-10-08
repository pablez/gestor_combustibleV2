<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CodigoRegistro>
 */
class CodigoRegistroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->bothify('CR-#####'),
            'id_usuario_generador' => \App\Models\User::factory(),
            'vigente_hasta' => $this->faker->dateTimeBetween('now', '+1 year'),
            'usado' => false,
            'id_usuario_usado' => null,
            'fecha_uso' => null,
        ];
    }
}
