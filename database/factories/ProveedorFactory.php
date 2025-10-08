<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_proveedor' => $this->faker->company(),
            'nombre_comercial' => $this->faker->companySuffix(),
            'nit' => $this->faker->unique()->numerify('#########'),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->numerify('7########'),
            'email' => $this->faker->safeEmail(),
            'id_tipo_servicio_proveedor' => \App\Models\TipoServicioProveedor::factory(),
            'contacto_principal' => $this->faker->name(),
            'calificacion' => $this->faker->randomElement(['A','B','C']),
            'observaciones' => $this->faker->sentence(),
            'activo' => true,
        ];
    }
}
