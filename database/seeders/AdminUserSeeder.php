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
        $email = 'admin@example.com';
        $password = env('ADMIN_USER_PASSWORD', 'password');

        // Try to find existing user by email first
        $user = User::where('email', $email)->first();

        // Determine the unidad to use: prefer id 10 (DESPACHO), fall back to nombre
        $unidad = UnidadOrganizacional::where('id_unidad_organizacional', 10)->first();
        if (! $unidad) {
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
        }
        $unidadIdToUse = $unidad->id_unidad_organizacional;

        // Determine username to use; prefer 'admin' but avoid unique collisions
        $desiredUsername = 'admin';

        if (! $user) {
            // If another account already has the desired username, generate a safe fallback
            $conflict = User::where('username', $desiredUsername)->first();
            $usernameToUse = $desiredUsername;
            if ($conflict) {
                // If conflict exists for a different email, create a unique suffix
                $usernameToUse = $desiredUsername . '_' . substr(md5((string) time() . $email), 0, 6);
            }

            $user = User::create([
                'username' => $usernameToUse,
                'name' => 'Administrador Sistema',
                'apellido_paterno' => 'Sistema',
                'id_unidad_organizacional' => $unidadIdToUse,
                'ci' => env('ADMIN_USER_CI', '00000000'),
                'telefono' => env('ADMIN_USER_TELEFONO', null),
                'email' => $email,
                'password' => Hash::make($password),
                'activo' => true,
            ]);
        } else {
            // Update existing user attributes (do not override username unless empty)
            $changed = false;
            $attributes = [
                'name' => 'Administrador Sistema',
                'apellido_paterno' => 'Sistema',
                'ci' => env('ADMIN_USER_CI', '00000000'),
                'telefono' => env('ADMIN_USER_TELEFONO', null),
                'activo' => true,
            ];
            foreach ($attributes as $k => $v) {
                if (! isset($user->{$k}) || $user->{$k} !== $v) {
                    $user->{$k} = $v;
                    $changed = true;
                }
            }

            if (empty($user->username)) {
                // If username missing, try to set the desired username or a fallback
                $conflict = User::where('username', $desiredUsername)->where('id', '!=', $user->id)->first();
                $user->username = $conflict ? $desiredUsername . '_' . substr(md5((string) time() . $email), 0, 6) : $desiredUsername;
                $changed = true;
            }

            // Ensure unidad id is set to the chosen unidad if column exists and differs
            if (array_key_exists('id_unidad_organizacional', $user->getAttributes())) {
                if (empty($user->id_unidad_organizacional) || $user->id_unidad_organizacional !== $unidadIdToUse) {
                    $user->id_unidad_organizacional = $unidadIdToUse;
                    $changed = true;
                }
            }

            if ($changed) {
                $user->save();
            }
        }

        // Ensure 'DESPACHO DE LA GOBERNACIÓN' unidad exists (prefer this superior unit)
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

        // If for some reason this unidad is not marked as Superior, try to find any Superior unit as fallback
        if ($unidad->tipo_unidad !== 'Superior') {
            $fallback = UnidadOrganizacional::where('tipo_unidad', 'Superior')->orderBy('nivel_jerarquico')->first();
            if ($fallback) {
                $unidad = $fallback;
            }
        }

        // assign unidad to user if DB column exists and value differs
        if (array_key_exists('id_unidad_organizacional', $user->getAttributes())) {
            if (empty($user->id_unidad_organizacional) || $user->id_unidad_organizacional !== $unidad->id_unidad_organizacional) {
                $user->id_unidad_organizacional = $unidad->id_unidad_organizacional;
                $user->save();
            }
        }

        // Assign role using Spatie if available and not already assigned
        if (method_exists($user, 'assignRole') && method_exists($user, 'hasRole')) {
            if (! $user->hasRole('Admin_General')) {
                $user->assignRole('Admin_General');
            }
        } elseif (method_exists($user, 'assignRole')) {
            // fallback: assign if assignRole exists
            $user->assignRole('Admin_General');
        }

        if ($user->wasRecentlyCreated) {
            $this->command->info("Admin user created: {$email} (password: {$password})");
        } else {
            $this->command->info("Admin user exists and was ensured: {$email}");
        }
    }
}
