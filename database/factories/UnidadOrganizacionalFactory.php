<?php

namespace Database\Factories;

use App\Models\UnidadOrganizacional;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadOrganizacionalFactory extends Factory
{
    protected $model = UnidadOrganizacional::class;

    public function definition()
    {
        return [
            'codigo_unidad' => $this->faker->unique()->bothify('U-###'),
            'nombre_unidad' => $this->faker->unique()->company(),
            // Use allowed values matching validation rules in components
            'tipo_unidad' => $this->faker->randomElement(['Operativa', 'Ejecutiva', 'Superior']),
            'id_unidad_padre' => null,
            'nivel_jerarquico' => $this->faker->numberBetween(1, 5),
            'responsable_unidad' => $this->faker->name(),
            'telefono' => $this->faker->numerify('7########'),
            'direccion' => $this->faker->address(),
            'presupuesto_asignado' => $this->faker->randomFloat(2, 0, 1000000),
            'descripcion' => $this->faker->sentence(),
            'activa' => true,
        ];
    }
}
