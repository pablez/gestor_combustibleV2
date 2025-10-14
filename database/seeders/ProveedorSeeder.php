<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;
use App\Models\TipoServicioProveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener tipos de servicio
        $tipoCombustible = TipoServicioProveedor::where('codigo', 'COMB')->first();
        $tipoMantenimiento = TipoServicioProveedor::where('codigo', 'MANT')->first();
        $tipoLubricantes = TipoServicioProveedor::where('codigo', 'LUBE')->first();
        $tipoNeumaticos = TipoServicioProveedor::where('codigo', 'NEUMAT')->first();
        $tipoRepuestos = TipoServicioProveedor::where('codigo', 'REPUES')->first();

        $proveedores = [
            [
                'id_tipo_servicio_proveedor' => $tipoCombustible?->id,
                'nit' => '76.123.456-7',
                'nombre_proveedor' => 'Petróleo Chileno S.A.',
                'nombre_comercial' => 'PetroChile',
                'telefono' => '+56 2 2234 5678',
                'email' => 'ventas@petrochile.cl',
                'direccion' => 'Av. Providencia 1234, Providencia',
                'contacto_principal' => 'Juan Pérez Soto',
                'calificacion' => 'A',
                'observaciones' => 'Proveedor principal de combustible. Descuento por volumen aplicable.',
                'activo' => true,
            ],
            [
                'id_tipo_servicio_proveedor' => $tipoCombustible?->id,
                'nit' => '96.789.123-4',
                'nombre_proveedor' => 'Combustibles del Sur SpA',
                'nombre_comercial' => 'CombuSur',
                'telefono' => '+56 2 2345 6789',
                'email' => 'contacto@combusur.cl',
                'direccion' => 'Calle Los Aromos 567, Las Condes',
                'contacto_principal' => 'María González López',
                'calificacion' => 'B',
                'activo' => true,
            ],
            [
                'id_tipo_servicio_proveedor' => $tipoMantenimiento?->id,
                'nit' => '12.345.678-9',
                'nombre_proveedor' => 'Taller Mecánico Central Ltda.',
                'nombre_comercial' => 'MecaCentral',
                'telefono' => '+56 2 2567 8901',
                'email' => 'servicios@mecacentral.cl',
                'direccion' => 'San Diego 890, Santiago Centro',
                'contacto_principal' => 'Carlos Rodríguez Muñoz',
                'calificacion' => 'A',
                'observaciones' => 'Especialista en vehículos públicos y de carga.',
                'activo' => true,
            ],
            [
                'id_tipo_servicio_proveedor' => $tipoLubricantes?->id,
                'nit' => '87.654.321-0',
                'nombre_proveedor' => 'Lubricantes Premium S.A.',
                'nombre_comercial' => 'LubriPrem',
                'telefono' => '+56 2 2678 9012',
                'email' => 'ventas@lubriprem.cl',
                'direccion' => 'Av. Industrial 345, Quilicura',
                'contacto_principal' => 'Ana Martínez Silva',
                'calificacion' => 'B',
                'activo' => true,
            ],
            [
                'id_tipo_servicio_proveedor' => $tipoNeumaticos?->id,
                'nit' => '65.432.109-8',
                'nombre_proveedor' => 'Neumáticos Rápidos SpA',
                'nombre_comercial' => 'NeumaRapid',
                'telefono' => '+56 2 2789 0123',
                'email' => 'info@neumarapid.cl',
                'direccion' => 'Gran Avenida 1234, San Miguel',
                'contacto_principal' => 'Roberto Fernández Torres',
                'calificacion' => 'A',
                'observaciones' => 'Servicio de cambio a domicilio disponible.',
                'activo' => true,
            ],
            [
                'id_tipo_servicio_proveedor' => $tipoRepuestos?->id,
                'nit' => '54.321.098-7',
                'nombre_proveedor' => 'Repuestos Automotrices Norte Ltda.',
                'nombre_comercial' => 'RepuestoNorte',
                'telefono' => '+56 2 2890 1234',
                'email' => 'pedidos@repuestonorte.cl',
                'direccion' => 'Av. Recoleta 567, Recoleta',
                'contacto_principal' => 'Luis Herrera Campos',
                'calificacion' => 'B',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            // Solo crear si el tipo de servicio existe
            if ($proveedor['id_tipo_servicio_proveedor']) {
                Proveedor::updateOrCreate(
                    ['nit' => $proveedor['nit']],
                    $proveedor
                );
            }
        }
    }
}
