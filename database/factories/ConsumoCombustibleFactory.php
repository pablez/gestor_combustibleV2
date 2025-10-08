<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsumoCombustible>
 */
class ConsumoCombustibleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_unidad_transporte' => \App\Models\UnidadTransporte::factory(),
            'id_despacho' => null,
            'id_usuario_conductor' => null,
            'fecha_registro' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'kilometraje_inicial' => $this->faker->numberBetween(0, 100000),
            'kilometraje_fin' => $this->faker->numberBetween(100000, 200000),
            'litros_cargados' => $this->faker->randomFloat(3, 5, 300),
            'tipo_carga' => $this->faker->randomElement(['despacho_oficial','carga_externa','emergencia']),
            'lugar_carga' => $this->faker->city(),
            'numero_ticket' => null,
            'observaciones' => $this->faker->sentence(),
            'validado' => false,
            'fecha_validacion' => null,
            'id_usuario_validador' => null,
        ];
    }
}
