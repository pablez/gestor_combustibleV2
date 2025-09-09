<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions (en español)
        $permissions = [
            'usuarios.gestionar',
            'usuarios.ver',
            'solicitudes.crear',
            'solicitudes.ver',
            'solicitudes.aprobar',
            'despachos.crear',
            'despachos.validar',
            'vehiculos.gestionar',
            'reportes.ver',
            'presupuestos.gestionar',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Define roles and assign permissions
        $roles = [
            'Admin_General' => $permissions,
            'Admin_Secretaria' => [
                'usuarios.ver',
                'solicitudes.ver',
                'solicitudes.aprobar',
                'despachos.crear',
                'reportes.ver',
            ],
            'Supervisor' => [
                'solicitudes.ver',
                'solicitudes.aprobar',
                'despachos.validar',
                'reportes.ver',
            ],
            'Conductor' => [
                'solicitudes.crear',
                'despachos.crear',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

        // ----- Normalizar permisos legacy en inglés hacia la versión en español -----
        $legacyMap = [
            'dispatch.create' => 'despachos.crear',
            'dispatch.validate' => 'despachos.validar',
            'requests.create' => 'solicitudes.crear',
            'requests.view' => 'solicitudes.ver',
            'requests.approve' => 'solicitudes.aprobar',
            'reports.view' => 'reportes.ver',
            'budgets.manage' => 'presupuestos.gestionar',
            'users.manage' => 'usuarios.gestionar',
            'users.view' => 'usuarios.ver',
            'vehicles.manage' => 'vehiculos.gestionar',
        ];

        foreach ($legacyMap as $old => $new) {
            $oldPerm = Permission::where('name', $old)->first();
            if (! $oldPerm) {
                continue;
            }

            // Ensure the canonical permission exists
            $newPerm = Permission::firstOrCreate(['name' => $new]);

            // Assign the canonical permission to any role that had the legacy permission
            $rolesWithOld = Role::whereHas('permissions', function ($q) use ($old) {
                $q->where('name', $old);
            })->get();

            foreach ($rolesWithOld as $r) {
                if (! $r->hasPermissionTo($newPerm->name)) {
                    $r->givePermissionTo($newPerm->name);
                }
            }

            // Remove the legacy permission to avoid duplicates
            $oldPerm->delete();
        }

        // ----- Normalizar role 'Operator' => 'Conductor' (reasignar usuarios y eliminar role legacy) -----
        $operatorRole = Role::where('name', 'Operator')->first();
        if ($operatorRole) {
            $conductorRole = Role::firstOrCreate(['name' => 'Conductor']);

            // Reassign users who have Operator to Conductor
            $usersWithOperator = User::role('Operator')->get();
            foreach ($usersWithOperator as $u) {
                if (! $u->hasRole('Conductor')) {
                    $u->assignRole('Conductor');
                }
                // remove Operator role if present
                if ($u->hasRole('Operator')) {
                    $u->removeRole('Operator');
                }
            }

            // Delete the legacy role
            $operatorRole->delete();
        }

        // Create initial admin user if not exists
        $adminEmail = env('SEED_ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('SEED_ADMIN_PASSWORD', 'password');

        $admin = User::where('email', $adminEmail)->first();

        if (! $admin) {
            // Ensure there is at least one unidad organizacional and get its id
            $unidad = \DB::table('unidades_organizacionales')->where('codigo_unidad', 'SDPDE')->first();
            if (! $unidad) {
                $id = \DB::table('unidades_organizacionales')->insertGetId([
                    'codigo_unidad' => 'SDPDE',
                    'nombre_unidad' => 'SECRETARÍA DEPARTAMENTAL DE PLANIFICACIÓN Y DESARROLLO ESTRATÉGICO',
                    // Valor válido según la migración: 'Superior','Ejecutiva','Operativa'
                    'tipo_unidad' => 'Ejecutiva',
                    'nivel_jerarquico' => 1,
                    'activa' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $id = $unidad->id_unidad_organizacional;
            }

            $admin = User::create([
                'username' => 'admin',
                'name' => 'Administrador',
                'nombre' => 'Admin',
                'apellido_paterno' => 'Sistema',
                'apellido_materno' => null,
                'ci' => '00000000',
                'telefono' => null,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'rol' => 'Admin_General',
                'id_unidad_organizacional' => $id,
                'activo' => true,
            ]);
        }

        // Assign role
        if (! $admin->hasRole('Admin_General')) {
            $admin->assignRole('Admin_General');
        }
    }
}
