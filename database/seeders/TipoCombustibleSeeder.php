<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoCombustible;

class TipoCombustibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Gasolina Regular',
                'codigo_comercial' => 'REG',
                'descripcion' => 'Gasolina de 87 octanos',
                'octanaje' => 87,
                'precio_referencial' => 3.74,
                'unidad_medida' => 'Litros',
            ],
            [
                'nombre' => 'Gasolina Premium',
                'codigo_comercial' => 'PREM',
                'descripcion' => 'Gasolina de 95 octanos',
                'octanaje' => 95,
                'precio_referencial' => 4.51,
                'unidad_medida' => 'Litros',
            ],
            [
                'nombre' => 'Diésel',
                'codigo_comercial' => 'DIES',
                'descripcion' => 'Combustible diésel',
                'octanaje' => null,
                'precio_referencial' => 3.72,
                'unidad_medida' => 'Litros',
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoCombustible::updateOrCreate(
                ['nombre' => $tipo['nombre']],
                $tipo
            );
        }
    }
}
