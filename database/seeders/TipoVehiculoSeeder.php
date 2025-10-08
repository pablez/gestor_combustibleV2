<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoVehiculo;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Sedán',
                'categoria' => 'Liviano',
                'descripcion' => 'Vehículo liviano de 4 puertas',
                'consumo_promedio_ciudad' => 8.5,
                'consumo_promedio_carretera' => 6.8,
                'capacidad_carga_kg' => 500,
                'numero_pasajeros' => 5,
            ],
            [
                'nombre' => 'Camioneta',
                'categoria' => 'Liviano',
                'descripcion' => 'Camioneta pickup',
                'consumo_promedio_ciudad' => 12.0,
                'consumo_promedio_carretera' => 9.5,
                'capacidad_carga_kg' => 1000,
                'numero_pasajeros' => 5,
            ],
            [
                'nombre' => 'Camión',
                'categoria' => 'Pesado',
                'descripcion' => 'Vehículo de carga pesada',
                'consumo_promedio_ciudad' => 25.0,
                'consumo_promedio_carretera' => 20.0,
                'capacidad_carga_kg' => 5000,
                'numero_pasajeros' => 3,
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoVehiculo::updateOrCreate(
                ['nombre' => $tipo['nombre']], 
                $tipo
            );
        }
    }
}
