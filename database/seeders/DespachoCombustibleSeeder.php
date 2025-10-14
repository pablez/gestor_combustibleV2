<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DespachoCombustible;
use App\Models\SolicitudCombustible;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;

class DespachoCombustibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $solicitudes = SolicitudCombustible::all();
        $proveedores = Proveedor::all();
        $usuarios = User::all();

        if ($solicitudes->isEmpty() || $proveedores->isEmpty() || $usuarios->isEmpty()) {
            $this->command->warn('Faltan datos previos. Asegúrate de ejecutar los seeders de Solicitudes, Proveedores y Usuarios primero.');
            return;
        }

        $despachos = [
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(1),
                'litros_despachados' => 50.0,
                'precio_por_litro' => 1250,
                'costo_total' => 62500,
                'numero_vale' => 'V-001234',
                'numero_factura' => 'F-2024-001',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Estación Shell - Av. Providencia 1234',
                'observaciones' => 'Despacho normal, sin observaciones',
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subHours(2),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,  
                'fecha_despacho' => Carbon::now()->subDays(2),
                'litros_despachados' => 75.5,
                'precio_por_litro' => 1280,
                'costo_total' => 96640,
                'numero_vale' => 'V-001235',
                'numero_factura' => 'F-2024-002',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Estación Copec - Mall Plaza Norte',
                'observaciones' => null,
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subDays(1),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(3),
                'litros_despachados' => 40.0,
                'precio_por_litro' => 1230,
                'costo_total' => 49200,
                'numero_vale' => 'V-001236',
                'numero_factura' => null,
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Petrobras - Ruta 5 Sur Km 15',
                'observaciones' => 'Despacho de emergencia por solicitud urgente',
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(5),
                'litros_despachados' => 100.0,
                'precio_por_litro' => 1290,
                'costo_total' => 129000,
                'numero_vale' => 'V-001237',
                'numero_factura' => 'F-2024-003',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Estación Esso - Centro',
                'observaciones' => null,
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subDays(4),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(7),
                'litros_despachados' => 30.5,
                'precio_por_litro' => 1240,
                'costo_total' => 37820,
                'numero_vale' => 'V-001238',
                'numero_factura' => 'F-2024-004',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Shell Select - Las Condes',
                'observaciones' => 'Cliente satisfecho con el servicio',
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subDays(6),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(10),
                'litros_despachados' => 85.0,
                'precio_por_litro' => 1260,
                'costo_total' => 107100,
                'numero_vale' => 'V-001239',
                'numero_factura' => null,
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Copec Full - Maipú',
                'observaciones' => 'Bomba con problema menor, resuelto rápidamente',
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(12),
                'litros_despachados' => 60.0,
                'precio_por_litro' => 1275,
                'costo_total' => 76500,
                'numero_vale' => 'V-001240',
                'numero_factura' => 'F-2024-005',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Terpel - Ñuñoa',
                'observaciones' => null,
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subDays(11),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(15),
                'litros_despachados' => 45.5,
                'precio_por_litro' => 1220,
                'costo_total' => 55510,
                'numero_vale' => 'V-001241',
                'numero_factura' => 'F-2024-006',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Estación independiente - San Miguel',
                'observaciones' => 'Precio competitivo, buen servicio',
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subDays(14),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(18),
                'litros_despachados' => 90.0,
                'precio_por_litro' => 1295,
                'costo_total' => 116550,
                'numero_vale' => 'V-001242',
                'numero_factura' => null,
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Shell V-Power - Vitacura',
                'observaciones' => 'Combustible premium para vehículo ejecutivo',
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ],
            [
                'id_solicitud' => $solicitudes->random()->id,
                'id_proveedor' => $proveedores->random()->id,
                'fecha_despacho' => Carbon::now()->subDays(20),
                'litros_despachados' => 55.0,
                'precio_por_litro' => 1245,
                'costo_total' => 68475,
                'numero_vale' => 'V-001243',
                'numero_factura' => 'F-2024-007',
                'id_usuario_despachador' => $usuarios->random()->id,
                'ubicacion_despacho' => 'Copec - Aeropuerto',
                'observaciones' => 'Despacho para vehículo oficial en comisión',
                'validado' => true,
                'fecha_validacion' => Carbon::now()->subDays(19),
                'id_usuario_validador' => $usuarios->random()->id,
            ],
        ];

        foreach ($despachos as $despacho) {
            DespachoCombustible::create($despacho);
        }

        $this->command->info('Se han creado ' . count($despachos) . ' despachos de combustible');
    }
}
