<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnidadTransporte>
 */
class UnidadTransporteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'placa' => strtoupper($this->faker->bothify('???-####')),
            'numero_chasis' => $this->faker->unique()->bothify('CHS######'),
            'numero_motor' => $this->faker->bothify('MTR-####'),
            'marca' => $this->faker->randomElement(['Toyota','Ford','Chevrolet','Nissan','Hyundai']),
            'modelo' => $this->faker->word(),
            'anio_fabricacion' => $this->faker->year(),
            'color' => $this->faker->safeColorName(),
            'id_tipo_vehiculo' => 1,
            'id_tipo_combustible' => 1,
            'capacidad_tanque' => $this->faker->randomFloat(2, 30, 200),
            'kilometraje_actual' => $this->faker->numberBetween(0, 200000),
            'kilometraje_ultimo_mantenimiento' => $this->faker->numberBetween(0, 200000),
            'proximo_mantenimiento_km' => null,
            'id_unidad_organizacional' => 1,
            'id_conductor_asignado' => null,
            'estado_operativo' => 'Operativo',
            'activo' => true,
        ];
    }
}
