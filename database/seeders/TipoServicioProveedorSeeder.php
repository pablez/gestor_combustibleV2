<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoServicioProveedor;

class TipoServicioProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'codigo' => 'COMB',
                'nombre' => 'Combustible',
                'descripcion' => 'Proveedores de combustible (gasolina, diésel, gas)',
                'requiere_autorizacion_especial' => true,
                'dias_credito_maximo' => 30,
                'activo' => true,
            ],
            [
                'codigo' => 'MANT',
                'nombre' => 'Mantenimiento',
                'descripcion' => 'Servicios de mantenimiento y reparación de vehículos',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 15,
                'activo' => true,
            ],
            [
                'codigo' => 'LUBE',
                'nombre' => 'Lubricantes',
                'descripcion' => 'Proveedores de aceites, lubricantes y fluidos',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 20,
                'activo' => true,
            ],
            [
                'codigo' => 'NEUMAT',
                'nombre' => 'Neumáticos',
                'descripcion' => 'Proveedores de neumáticos y servicios relacionados',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 25,
                'activo' => true,
            ],
            [
                'codigo' => 'REPUES',
                'nombre' => 'Repuestos',
                'descripcion' => 'Proveedores de repuestos y piezas para vehículos',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 15,
                'activo' => true,
            ],
            [
                'codigo' => 'SEGURO',
                'nombre' => 'Seguros',
                'descripcion' => 'Compañías de seguros vehiculares',
                'requiere_autorizacion_especial' => true,
                'dias_credito_maximo' => 60,
                'activo' => true,
            ],
            [
                'codigo' => 'GRUA',
                'nombre' => 'Grúa y Remolque',
                'descripcion' => 'Servicios de grúa, remolque y auxilio mecánico',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 0,
                'activo' => true,
            ],
            [
                'codigo' => 'LAVADO',
                'nombre' => 'Lavado',
                'descripcion' => 'Servicios de lavado y detallado de vehículos',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 7,
                'activo' => true,
            ],
            [
                'codigo' => 'PERM',
                'nombre' => 'Permisos y Licencias',
                'descripcion' => 'Gestión de permisos de circulación, revisiones técnicas',
                'requiere_autorizacion_especial' => true,
                'dias_credito_maximo' => 0,
                'activo' => true,
            ],
            [
                'codigo' => 'PARKING',
                'nombre' => 'Estacionamiento',
                'descripcion' => 'Servicios de estacionamiento y custodia vehicular',
                'requiere_autorizacion_especial' => false,
                'dias_credito_maximo' => 10,
                'activo' => true,
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoServicioProveedor::updateOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );
        }
    }
}
