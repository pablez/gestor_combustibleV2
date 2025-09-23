<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnidadOrganizacional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea usuarios de prueba con diferentes roles para testing y desarrollo
     */
    public function run(): void
    {
        $defaultPassword = env('TEST_USERS_PASSWORD', 'password');

        // Obtener o crear unidades organizacionales necesarias
        $unidadDespacho = UnidadOrganizacional::firstOrCreate(
            ['nombre_unidad' => 'DESPACHO DE LA GOBERNACIÓN'],
            [
                'codigo_unidad' => 'DG',
                'tipo_unidad' => 'Superior',
                'id_unidad_padre' => null,
                'nivel_jerarquico' => 1,
                'responsable_unidad' => null,
                'activa' => true,
            ]
        );

        $unidadRRHH = UnidadOrganizacional::firstOrCreate(
            ['nombre_unidad' => 'RECURSOS HUMANOS'],
            [
                'codigo_unidad' => 'RRHH',
                'tipo_unidad' => 'Ejecutiva',
                'id_unidad_padre' => $unidadDespacho->id_unidad_organizacional,
                'nivel_jerarquico' => 2,
                'responsable_unidad' => null,
                'activa' => true,
            ]
        );

        $unidadTransporte = UnidadOrganizacional::firstOrCreate(
            ['nombre_unidad' => 'UNIDAD DE TRANSPORTE'],
            [
                'codigo_unidad' => 'TRANS',
                'tipo_unidad' => 'Operativa',
                'id_unidad_padre' => $unidadDespacho->id_unidad_organizacional,
                'nivel_jerarquico' => 2,
                'responsable_unidad' => null,
                'activa' => true,
            ]
        );

        $unidadFinanzas = UnidadOrganizacional::firstOrCreate(
            ['nombre_unidad' => 'UNIDAD DE FINANZAS'],
            [
                'codigo_unidad' => 'FIN',
                'tipo_unidad' => 'Ejecutiva',
                'id_unidad_padre' => $unidadDespacho->id_unidad_organizacional,
                'nivel_jerarquico' => 2,
                'responsable_unidad' => null,
                'activa' => true,
            ]
        );

        // Definir usuarios de prueba
        $testUsers = [
            // Admin Secretaría
            [
                'username' => 'secretaria.admin',
                'name' => 'María Elena',
                'apellido_paterno' => 'Vargas',
                'apellido_materno' => 'Delgado',
                'ci' => '12345678',
                'telefono' => '70123456',
                'email' => 'secretaria@example.com',
                'password' => Hash::make($defaultPassword),
                'id_unidad_organizacional' => $unidadRRHH->id_unidad_organizacional,
                'role' => 'Admin_Secretaria',
                'activo' => true,
            ],
            // Supervisor de Transporte
            [
                'username' => 'supervisor.trans',
                'name' => 'Carlos Roberto',
                'apellido_paterno' => 'Mendoza',
                'apellido_materno' => 'Silva',
                'ci' => '87654321',
                'telefono' => '70654321',
                'email' => 'supervisor.transporte@example.com',
                'password' => Hash::make($defaultPassword),
                'id_unidad_organizacional' => $unidadTransporte->id_unidad_organizacional,
                'role' => 'Supervisor',
                'activo' => true,
            ],
            // Supervisor de Finanzas
            [
                'username' => 'supervisor.fin',
                'name' => 'Ana Beatriz',
                'apellido_paterno' => 'Quispe',
                'apellido_materno' => 'Mamani',
                'ci' => '11223344',
                'telefono' => '70112233',
                'email' => 'supervisor.finanzas@example.com',
                'password' => Hash::make($defaultPassword),
                'id_unidad_organizacional' => $unidadFinanzas->id_unidad_organizacional,
                'role' => 'Supervisor',
                'activo' => true,
            ],
            // Conductor 1
            [
                'username' => 'conductor1',
                'name' => 'Juan Pablo',
                'apellido_paterno' => 'Rojas',
                'apellido_materno' => 'Fernández',
                'ci' => '55667788',
                'telefono' => '70556677',
                'email' => 'conductor1@example.com',
                'password' => Hash::make($defaultPassword),
                'id_unidad_organizacional' => $unidadTransporte->id_unidad_organizacional,
                'role' => 'Conductor',
                'activo' => true,
            ],
            // Conductor 2
            [
                'username' => 'conductor2',
                'name' => 'Miguel Ángel',
                'apellido_paterno' => 'Torrez',
                'apellido_materno' => 'Choque',
                'ci' => '99887766',
                'telefono' => '70998877',
                'email' => 'conductor2@example.com',
                'password' => Hash::make($defaultPassword),
                'id_unidad_organizacional' => $unidadTransporte->id_unidad_organizacional,
                'role' => 'Conductor',
                'activo' => true,
            ],
            // Conductor 3
            [
                'username' => 'conductor3',
                'name' => 'Pedro Luis',
                'apellido_paterno' => 'Condori',
                'apellido_materno' => 'Apaza',
                'ci' => '44556677',
                'telefono' => '70445566',
                'email' => 'conductor3@example.com',
                'password' => Hash::make($defaultPassword),
                'id_unidad_organizacional' => $unidadTransporte->id_unidad_organizacional,
                'role' => 'Conductor',
                'activo' => true,
            ],
        ];

        foreach ($testUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            // Verificar si el usuario ya existe
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                $user = User::create($userData);
                
                // Asignar rol si el usuario tiene el trait HasRoles
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole($role);
                }
                
                $this->command->info("Usuario creado: {$userData['username']} ({$userData['email']}) - Rol: {$role}");
            } else {
                // Actualizar usuario existente si es necesario
                $existingUser->update([
                    'username' => $userData['username'],
                    'name' => $userData['name'],
                    'apellido_paterno' => $userData['apellido_paterno'],
                    'apellido_materno' => $userData['apellido_materno'],
                    'ci' => $userData['ci'],
                    'telefono' => $userData['telefono'],
                    'id_unidad_organizacional' => $userData['id_unidad_organizacional'],
                    'activo' => $userData['activo'],
                ]);
                
                // Asegurar que tenga el rol correcto
                if (method_exists($existingUser, 'assignRole') && method_exists($existingUser, 'hasRole')) {
                    if (!$existingUser->hasRole($role)) {
                        $existingUser->assignRole($role);
                    }
                }
                
                $this->command->info("Usuario actualizado: {$userData['username']} ({$userData['email']}) - Rol: {$role}");
            }
        }

        // Establecer relaciones supervisor/supervisado
        $this->establishSupervisorRelations();
        
        $this->command->info("Usuarios de prueba creados/actualizados exitosamente.");
        $this->command->info("Contraseña por defecto: {$defaultPassword}");
    }

    /**
     * Establece relaciones de supervisor/supervisado entre usuarios
     */
    private function establishSupervisorRelations(): void
    {
        // El supervisor de transporte supervisa a los conductores
        $supervisorTransporte = User::where('username', 'supervisor.trans')->first();
        if ($supervisorTransporte) {
            $conductores = User::whereIn('username', ['conductor1', 'conductor2', 'conductor3'])->get();
            foreach ($conductores as $conductor) {
                $conductor->update(['id_supervisor' => $supervisorTransporte->id]);
            }
        }

        // El admin de secretaría puede supervisar a los supervisores (opcional)
        $secretariaAdmin = User::where('username', 'secretaria.admin')->first();
        $supervisores = User::whereIn('username', ['supervisor.trans', 'supervisor.fin'])->get();
        if ($secretariaAdmin && $supervisores->count() > 0) {
            foreach ($supervisores as $supervisor) {
                if (!$supervisor->id_supervisor) { // Solo si no tiene supervisor asignado
                    $supervisor->update(['id_supervisor' => $secretariaAdmin->id]);
                }
            }
        }

        $this->command->info("Relaciones supervisor/supervisado establecidas.");
    }
}