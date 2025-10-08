<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DespachoCombustible>
 */
class DespachoCombustibleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_solicitud' => null,
            'id_proveedor' => \App\Models\Proveedor::factory(),
            'fecha_despacho' => $this->faker->dateTimeBetween('-10 days', 'now'),
            'litros_despachados' => $this->faker->randomFloat(3, 10, 1000),
            'precio_por_litro' => $this->faker->randomFloat(2, 1, 5),
            'costo_total' => 0,
            'numero_vale' => 'VALE-' . $this->faker->unique()->numerify('#####'),
            'numero_factura' => null,
            'id_usuario_despachador' => null,
            'ubicacion_despacho' => $this->faker->city(),
            'observaciones' => $this->faker->sentence(),
            'validado' => false,
            'fecha_validacion' => null,
            'id_usuario_validador' => null,
        ];
    }
}
