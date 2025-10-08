<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Presupuesto>
 */
class PresupuestoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_cat_programatica' => \App\Models\CategoriaProgramatica::factory(),
            'id_fuente_org_fin' => \App\Models\FuenteOrganismoFinanciero::factory(),
            'id_unidad_organizacional' => \App\Models\UnidadOrganizacional::factory(),
            'anio_fiscal' => date('Y'),
            'trimestre' => $this->faker->numberBetween(1,4),
            'presupuesto_inicial' => $this->faker->randomFloat(2, 10000, 1000000),
            'presupuesto_actual' => $this->faker->randomFloat(2, 10000, 1000000),
            'total_gastado' => 0,
            'total_comprometido' => 0,
            'num_documento' => 'DOC-' . $this->faker->unique()->numerify('#####'),
            'numero_comprobante' => null,
            'fecha_aprobacion' => null,
            'porcentaje_preventivo' => 10.0,
            'alerta_porcentaje' => 80.0,
            'activo' => true,
            'observaciones' => null,
        ];
    }
}
