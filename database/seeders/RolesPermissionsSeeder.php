<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Constants\Permissions as P;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles = [
            'Admin_General',
            'Admin_Secretaria',
            'Supervisor',
            'Conductor',
        ];

        // Minimal set of permissions representative for the app.
        $permissions = [
            P::USUARIOS_VER,
            P::USUARIOS_CREAR,
            P::USUARIOS_EDITAR,
            P::USUARIOS_ELIMINAR,
            P::USUARIOS_GESTIONAR,

            P::UNIDADES_VER,
            P::UNIDADES_CREAR,
            P::UNIDADES_EDITAR,
            P::UNIDADES_ELIMINAR,

            P::SOLICITUDES_VER,
            P::SOLICITUDES_CREAR,
            P::SOLICITUDES_EDITAR,
            P::SOLICITUDES_APROBAR,

            P::DESPACHOS_VER,
            P::DESPACHOS_CREAR,
            P::DESPACHOS_EDITAR,
            P::DESPACHOS_ELIMINAR,
            P::DESPACHOS_VALIDAR,

            P::CONSUMOS_VER,
            P::CONSUMOS_CREAR,
            P::CONSUMOS_EDITAR,
            P::CONSUMOS_ELIMINAR,
            P::CONSUMOS_VALIDAR,

            P::PROVEEDORES_VER,
            P::PROVEEDORES_CREAR,
            P::PROVEEDORES_EDITAR,
            P::PROVEEDORES_ELIMINAR,

            P::TIPOS_SERVICIO_PROVEEDOR_VER,
            P::TIPOS_SERVICIO_PROVEEDOR_CREAR,
            P::TIPOS_SERVICIO_PROVEEDOR_EDITAR,
            P::TIPOS_SERVICIO_PROVEEDOR_ELIMINAR,

            P::PRESUPUESTOS_VER,
            P::PRESUPUESTOS_CREAR,
            P::PRESUPUESTOS_EDITAR,
            P::PRESUPUESTOS_ELIMINAR,

            // Solicitudes de Aprobación de Usuario
            P::SOLICITUDES_APROBACION_VER,
            P::SOLICITUDES_APROBACION_CREAR,
            P::SOLICITUDES_APROBACION_APROBAR,
            P::SOLICITUDES_APROBACION_RECHAZAR,

            // Códigos de Registro
            P::CODIGOS_REGISTRO_VER,
            P::CODIGOS_REGISTRO_CREAR,
            P::CODIGOS_REGISTRO_ELIMINAR,

            // Reportes
            P::REPORTES_VER,
            P::REPORTES_COMBUSTIBLE,
            P::REPORTES_PRESUPUESTO,
            P::REPORTES_GENERAR,
        ];

        // Create permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create roles and assign permissions
        // Admin_General: all permissions
        $admin = Role::firstOrCreate(['name' => 'Admin_General']);
    $admin->syncPermissions($permissions);

        // Admin_Secretaria: user and unidades management, solicitudes (no aprobaciones), proveedores, despachos, presupuestos, solicitudes_aprobacion, codigos_registro, reportes
        $secretaria = Role::firstOrCreate(['name' => 'Admin_Secretaria']);
        $secretariaPerms = array_filter($permissions, function ($p) {
            return str_starts_with($p, 'usuarios.') || 
                   str_starts_with($p, 'unidades.') || 
                   str_starts_with($p, 'solicitudes.') ||
                   str_starts_with($p, 'proveedores.') ||
                   str_starts_with($p, 'tipos-servicio-proveedor.') ||
                   str_starts_with($p, 'despachos.') ||
                   str_starts_with($p, 'consumos.') ||
                   str_starts_with($p, 'presupuestos.') ||
                   str_starts_with($p, 'solicitudes_aprobacion.') ||
                   str_starts_with($p, 'codigos_registro.') ||
                   str_starts_with($p, 'reportes.');
        });
        $secretaria->syncPermissions($secretariaPerms);

        // Supervisor: ver/editar solicitudes y aprobar, ver solicitudes_aprobacion, reportes
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $supervisorPerms = array_filter($permissions, fn($p) => str_starts_with($p, 'solicitudes.') || 
                                                                $p === P::UNIDADES_VER ||
                                                                str_starts_with($p, 'solicitudes_aprobacion.') ||
                                                                str_starts_with($p, 'reportes.'));
        $supervisor->syncPermissions($supervisorPerms);

        // Conductor: permisos mínimos: ver solicitudes propias y despachos
        $conductor = Role::firstOrCreate(['name' => 'Conductor']);
    $conductorPerms = array_filter($permissions, fn($p) => in_array($p, [P::SOLICITUDES_CREAR, P::SOLICITUDES_VER, P::DESPACHOS_VER, P::DESPACHOS_CREAR, P::CONSUMOS_VER, P::CONSUMOS_CREAR]));
        $conductor->syncPermissions($conductorPerms);
    }
}
