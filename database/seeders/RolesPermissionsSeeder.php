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
            P::DESPACHOS_VALIDAR,
        ];

        // Create permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create roles and assign permissions
        // Admin_General: all permissions
        $admin = Role::firstOrCreate(['name' => 'Admin_General']);
    $admin->syncPermissions($permissions);

        // Admin_Secretaria: user and unidades management, solicitudes (no aprobaciones)
        $secretaria = Role::firstOrCreate(['name' => 'Admin_Secretaria']);
        $secretariaPerms = array_filter($permissions, function ($p) {
            return str_starts_with($p, 'usuarios.') || str_starts_with($p, 'unidades.') || str_starts_with($p, 'solicitudes.');
        });
        $secretaria->syncPermissions($secretariaPerms);

        // Supervisor: ver/editar solicitudes y aprobar
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
    $supervisorPerms = array_filter($permissions, fn($p) => str_starts_with($p, 'solicitudes.') || $p === P::UNIDADES_VER);
        $supervisor->syncPermissions($supervisorPerms);

        // Conductor: permisos mínimos: ver solicitudes propias y despachos
        $conductor = Role::firstOrCreate(['name' => 'Conductor']);
    $conductorPerms = array_filter($permissions, fn($p) => in_array($p, [P::SOLICITUDES_CREAR, P::SOLICITUDES_VER, P::DESPACHOS_VER, P::DESPACHOS_CREAR]));
        $conductor->syncPermissions($conductorPerms);
    }
}
