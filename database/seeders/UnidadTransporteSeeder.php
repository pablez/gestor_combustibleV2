<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnidadTransporte;
use App\Models\UnidadOrganizacional;
use App\Models\TipoVehiculo;
use App\Models\TipoCombustible;

class UnidadTransporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs válidos
        $unidades = UnidadOrganizacional::all();
        $tiposVehiculo = TipoVehiculo::all();
        $tiposCombustible = TipoCombustible::all();

        if ($unidades->isEmpty() || $tiposVehiculo->isEmpty() || $tiposCombustible->isEmpty()) {
            $this->command->info('No hay datos base suficientes para crear unidades de transporte');
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            UnidadTransporte::factory()->create([
                'id_unidad_organizacional' => $unidades->random()->id_unidad_organizacional,
                'id_tipo_vehiculo' => $tiposVehiculo->random()->id,
                'id_tipo_combustible' => $tiposCombustible->random()->id,
            ]);
        }
    }
}
