<?php

use App\Models\User;
use App\Models\UnidadTransporte;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('permite acceso autenticado a estadisticas de imagenes', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->getJson(route('vehiculos.imagenes.estadisticas'))
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'data']);
});

it('valida subida de imagen y devuelve error para datos invalidos', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $vehiculo = UnidadTransporte::factory()->create();

    // Enviar sin archivo ni tipo
    $response = $this->postJson(route('vehiculos.imagenes.store', ['vehiculo' => $vehiculo->id, 'tipo_imagen' => 'foto_principal']), []);

    $response->assertStatus(422);
});
