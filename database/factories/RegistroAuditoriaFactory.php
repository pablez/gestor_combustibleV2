<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroAuditoria>
 */
class RegistroAuditoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_usuario' => \App\Models\User::first()?->id ?? \App\Models\User::factory()->create()->id,
            'fecha_hora' => now(),
            'accion_realizada' => $this->faker->randomElement(['SUBIR_IMAGEN','ELIMINAR_IMAGEN','OPTIMIZAR_IMAGEN']),
            'tabla_afectada' => 'unidad_transportes',
            'registro_afectado' => ['id' => 1, 'placa' => 'TEST-1'],
            'valores_anteriores' => null,
            'valores_nuevos' => ['foo' => 'bar'],
            'ip_origen' => '127.0.0.1',
            'user_agent' => 'phpunit',
            'sesion_id' => 'test',
            'modulo_sistema' => 'TEST',
            'nivel_criticidad' => 'MEDIO'
        ];
    }
}
