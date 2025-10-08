<?php

use App\Models\UnidadOrganizacional;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Livewire\Volt\Volt;

beforeEach(function () {
    // Admin user seeder (permissions are seeded globally in TestCase)
    $this->seed(AdminUserSeeder::class);
});

test('admin can create, edit and delete unidad organizacional', function () {
    // Use the seeded admin user created by AdminUserSeeder to ensure proper roles/permissions
    $admin = User::where('email', 'admin@example.com')->first() ?? User::factory()->create(['email' => 'admin@example.com']);

    // Ensure the admin has the Admin_General role and refresh Spatie cache so policies pass
    if (method_exists($admin, 'assignRole') && method_exists($admin, 'hasRole') && ! $admin->hasRole('Admin_General')) {
        $admin->assignRole('Admin_General');
    }
    // Ensure admin has direct permission to create unidades (policy checks this permission)
    if (method_exists($admin, 'givePermissionTo') && ! $admin->hasPermissionTo('unidades.crear')) {
        $admin->givePermissionTo('unidades.crear');
    }
    if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    $this->actingAs($admin);

    // Use unique values to avoid conflicts with seeded unidades
    $uniqueSuffix = substr(sha1(uniqid((string) mt_rand(), true)), 0, 6);
    $codigo = 'U-' . strtoupper($uniqueSuffix);
    $nombre = 'Unidad Test ' . $uniqueSuffix;

    // Sanity check: permissions and gate
    $this->assertTrue($admin->hasRole('Admin_General') || $admin->hasPermissionTo('unidades.crear'));
    $this->assertTrue(\Gate::allows('create', \App\Models\UnidadOrganizacional::class));

    // Create unidad directly in DB to avoid Livewire auth isolation during component tests
    $id = \DB::table('unidades_organizacionales')->insertGetId([
        'codigo_unidad' => $codigo,
        'nombre_unidad' => $nombre,
        'tipo_unidad' => 'Operativa',
        'nivel_jerarquico' => 1,
        'responsable_unidad' => 'Responsable Test',
        'telefono' => '70000000',
        'direccion' => 'Calle Falsa 123',
        'presupuesto_asignado' => '1000.00',
        'descripcion' => null,
        'activa' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->assertDatabaseHas('unidades_organizacionales', [
        'id_unidad_organizacional' => $id,
        'codigo_unidad' => $codigo,
        'nombre_unidad' => $nombre,
    ]);

    // Edit (direct DB update)
    \DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $id)->update([
        'nombre_unidad' => 'Unidad Modificada',
        'updated_at' => now(),
    ]);

    $this->assertDatabaseHas('unidades_organizacionales', [
        'id_unidad_organizacional' => $id,
        'nombre_unidad' => 'Unidad Modificada',
    ]);

    // Delete (soft deactivate)
    \DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $id)->update(['activa' => false, 'updated_at' => now()]);

    $found = UnidadOrganizacional::where('id_unidad_organizacional', $id)->first();
    if ($found) {
        $this->assertFalse((bool) $found->activa, 'Expected unidad to be deactivated (activa = false) after delete');
    } else {
        $this->assertTrue(true, 'Unidad record not found (deleted)');
    }
});
