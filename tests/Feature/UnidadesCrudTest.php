<?php

use App\Models\UnidadOrganizacional;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Database\Seeders\AdminUserSeeder;
use Livewire\Volt\Volt;

beforeEach(function () {
    // Run the roles/permissions and admin seeders to ensure permissions exist
    $this->seed(RolesPermissionsSeeder::class);
    $this->seed(AdminUserSeeder::class);
});

test('admin can create, edit and delete unidad organizacional', function () {
    $admin = User::where('email', 'admin@local.test')->first() ?? User::factory()->create(['email' => 'admin@local.test']);

    $this->actingAs($admin);

    // Create using the create component
    $create = Volt::test('unidades.create')
        ->set('codigo_unidad', 'U-001')
        ->set('nombre_unidad', 'Unidad Test')
        // Use a valid tipo_unidad according to component validation
        ->set('tipo_unidad', 'Operativa')
        ->set('nivel_jerarquico', 1)
        ->set('responsable_unidad', 'Responsable Test')
        ->set('telefono', '70000000')
        ->set('direccion', 'Calle Falsa 123')
        ->set('presupuesto_asignado', '1000.00')
        ->call('save');

    $create->assertHasNoErrors();

    $this->assertDatabaseHas('unidades_organizacionales', [
        'codigo_unidad' => 'U-001',
        'nombre_unidad' => 'Unidad Test',
    ]);

    $unidad = UnidadOrganizacional::where('codigo_unidad', 'U-001')->first();
    $this->assertNotNull($unidad);

    // Edit using the edit component: open the modal for the specific id first
    $edit = Volt::test('unidades.edit')
        ->call('open', $unidad->id_unidad_organizacional)
        ->set('nombre_unidad', 'Unidad Modificada')
        ->call('save');

    $edit->assertHasNoErrors();

    $this->assertDatabaseHas('unidades_organizacionales', [
        'id_unidad_organizacional' => $unidad->id_unidad_organizacional,
        'nombre_unidad' => 'Unidad Modificada',
    ]);

    // Delete using the index component (assuming a delete method exists)
    $index = Volt::test('unidades.index')
        ->call('delete', $unidad->id_unidad_organizacional);

    // After deletion, check whether it's soft-deactivated (activa = false) or removed.
    $found = UnidadOrganizacional::where('id_unidad_organizacional', $unidad->id_unidad_organizacional)->first();
    if ($found) {
        $this->assertFalse((bool) $found->activa, 'Expected unidad to be deactivated (activa = false) after delete');
    } else {
        $this->assertTrue(true, 'Unidad record not found (deleted)');
    }
});
