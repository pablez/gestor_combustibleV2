<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FuenteOrganismoFinanciero;

class FuenteOrganismoFinancieroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuenteOrganismoFinanciero::insert([
            ['codigo' => 'FN-001', 'descripcion' => 'Fondo Nacional', 'tipo_fuente' => 'Nacional', 'organismo_financiador' => 'Ministerio de Transporte', 'porcentaje_contrapartida' => 0],
            ['codigo' => 'FD-001', 'descripcion' => 'Fondo Departamental', 'tipo_fuente' => 'Departamental', 'organismo_financiador' => 'Gobernación', 'porcentaje_contrapartida' => 10.00],
        ]);
    }
}
