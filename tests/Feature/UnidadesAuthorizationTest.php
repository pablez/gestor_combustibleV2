<?php

use App\Models\UnidadOrganizacional;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Livewire\Volt\Volt;

test('usuario sin permiso no puede abrir ni guardar edición de unidad', function () {
    // Ensure permissions/roles are seeded so policy checks using Spatie don't throw
    $this->seed(RolesPermissionsSeeder::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $unidad = UnidadOrganizacional::factory()->create();

    // Intentar abrir modal de edición: no debería abrir (show seguirá false)
    $component = Volt::test('unidades.edit')
        ->call('open', $unidad->id_unidad_organizacional)
        ->assertSet('show', false);

    // Intentar crear una unidad sin permisos
    $create = Volt::test('unidades.create')
        ->set('codigo_unidad', 'U-X')
        ->set('nombre_unidad', 'No Permitido')
        ->set('tipo_unidad', 'Operativa')
        ->call('save');

    // La acción no debe crear la unidad (ya comprobado más abajo)

    // Asegurarnos que no se creó
    $this->assertDatabaseMissing('unidades_organizacionales', ['nombre_unidad' => 'No Permitido']);
});
