<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SolicitudCombustible>
 */
class SolicitudCombustibleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_solicitud' => 'SOL-' . $this->faker->unique()->numerify('#####'),
            'id_usuario_solicitante' => null, // set in seeder or test
            'id_unidad_transporte' => null,
            'fecha_solicitud' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'cantidad_litros_solicitados' => $this->faker->randomFloat(3, 5, 500),
            'motivo' => $this->faker->sentence(6),
            'urgente' => $this->faker->boolean(10),
            'justificacion_urgencia' => null,
            'estado_solicitud' => 'Pendiente',
            'id_usuario_aprobador' => null,
            'fecha_aprobacion' => null,
            'observaciones_aprobacion' => null,
            'id_cat_programatica' => null,
            'id_fuente_org_fin' => null,
            'saldo_actual_combustible' => null,
            'km_actual' => $this->faker->numberBetween(0, 200000),
            'km_proyectado' => $this->faker->numberBetween(100, 2000),
            'rendimiento_estimado' => $this->faker->randomFloat(2, 5, 20),
        ];
    }
}
