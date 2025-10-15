<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SolicitudAprobacionPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para solicitudes de aprobación
        $permissions = [
            'solicitudes_aprobacion.ver',
            'solicitudes_aprobacion.crear',
            'solicitudes_aprobacion.aprobar',
            'solicitudes_aprobacion.rechazar',
            'solicitudes_aprobacion.procesar',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        $adminGeneral = Role::where('name', 'Admin_General')->first();
        $adminSecretaria = Role::where('name', 'Admin_Secretaria')->first();

        if ($adminGeneral) {
            $adminGeneral->givePermissionTo($permissions);
        }

        if ($adminSecretaria) {
            // Admin Secretaría puede ver, crear y procesar, pero no aprobar directamente
            $adminSecretaria->givePermissionTo([
                'solicitudes_aprobacion.ver',
                'solicitudes_aprobacion.crear',
            ]);
        }

        $this->command->info('Permisos de solicitudes de aprobación creados y asignados correctamente.');
    }
}
