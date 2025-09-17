<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnidadOrganizacional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@local.test';
        $password = env('ADMIN_USER_PASSWORD', 'password');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'username' => 'admin',
                'name' => 'Administrador Sistema',
                'nombre' => 'Administrador',
                'apellido_paterno' => 'Sistema',
                'ci' => env('ADMIN_USER_CI', '00000000'),
                'telefono' => env('ADMIN_USER_TELEFONO', null),
                'email' => $email,
                'password' => Hash::make($password),
                'activo' => true,
            ]
        );

        // Ensure 'DESPACHO DE LA GOBERNACIÓN' unidad exists and assign to admin
        $unidad = UnidadOrganizacional::firstOrCreate(
            ['nombre_unidad' => 'DESPACHO DE LA GOBERNACIÓN'],
            [
                'codigo_unidad' => 'DG',
                'tipo_unidad' => 'Superior',
                'id_unidad_padre' => null,
                'nivel_jerarquico' => 1,
                'responsable_unidad' => null,
                'telefono' => null,
                'direccion' => null,
                'presupuesto_asignado' => 0,
                'descripcion' => null,
                'activa' => true,
            ]
        );

        // assign unidad to user if column exists and is different or null
        if (array_key_exists('id_unidad_organizacional', $user->getAttributes())) {
            if (empty($user->id_unidad_organizacional) || $user->id_unidad_organizacional !== $unidad->id_unidad_organizacional) {
                $user->id_unidad_organizacional = $unidad->id_unidad_organizacional;
                $user->save();
            }
        }

        // Assign role using Spatie if available and not already assigned
        if (method_exists($user, 'assignRole') && ! method_exists($user, 'hasRole') || (method_exists($user, 'hasRole') && ! $user->hasRole('Admin_General'))) {
            $user->assignRole('Admin_General');
        }

        if ($user->wasRecentlyCreated) {
            $this->command->info("Admin user created: {$email} (password: {$password})");
        } else {
            $this->command->info("Admin user exists and was ensured: {$email}");
        }
    }
}
