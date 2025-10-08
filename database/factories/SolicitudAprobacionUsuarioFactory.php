<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SolicitudAprobacionUsuario>
 */
class SolicitudAprobacionUsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_usuario' => \App\Models\User::factory(),
            'id_creador' => \App\Models\User::factory(),
            'id_supervisor_asignado' => null,
            'tipo_solicitud' => 'nuevo_usuario',
            'estado_solicitud' => 'pendiente',
            'rol_solicitado' => null,
            'justificacion' => $this->faker->sentence(),
            'observaciones_aprobacion' => null,
            'fecha_aprobacion' => null,
            'id_usuario_aprobador' => null,
        ];
    }
}
