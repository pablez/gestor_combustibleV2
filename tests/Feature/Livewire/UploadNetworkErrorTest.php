<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

class UploadNetworkErrorTest extends TestCase
{
    use RefreshDatabase;

    public function test_emits_error_when_service_throws_exception()
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Mockear el servicio para lanzar excepción
        $this->mock(\App\Services\ImagenVehiculoService::class, function ($mock) {
            $mock->shouldReceive('guardarImagen')->andThrow(new \Exception('Simulated network error'));
            $mock->shouldReceive('getTiposImagen')->andReturn(['foto_principal']);
        });

        $file = \Illuminate\Http\UploadedFile::fake()->image('err.jpg')->size(100);

        $component = Livewire::test(\App\Livewire\Vehiculo\UploadImagenes::class, ['placa' => 'ERR1'])
            ->set('tipo', 'foto_principal')
            ->set('archivo', $file)
            ->call('subir');

    // Debe haber un error en el bag de Livewire (el flash de sesión puede no persistir en este entorno de test)
    $component->assertHasErrors(['upload']);
    }
}
