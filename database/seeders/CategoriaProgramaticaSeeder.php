<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoriaProgramatica;

class CategoriaProgramaticaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'codigo' => 'PROG-001', 
                'descripcion' => 'Programa de Transporte', 
                'tipo_categoria' => 'Programa', 
                'nivel' => 1,
                'id_categoria_padre' => null,
                'activo' => true,
            ],
            [
                'codigo' => 'PROJ-001', 
                'descripcion' => 'Proyecto Mantenimiento', 
                'tipo_categoria' => 'Proyecto', 
                'nivel' => 1,
                'id_categoria_padre' => null,
                'activo' => true,
            ],
            [
                'codigo' => 'ACT-001', 
                'descripcion' => 'Actividad Compra Combustible', 
                'tipo_categoria' => 'Actividad', 
                'nivel' => 2,
                'id_categoria_padre' => null, // Se puede modificar después
                'activo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            CategoriaProgramatica::updateOrCreate(
                ['codigo' => $categoria['codigo']],
                $categoria
            );
        }
    }
}
