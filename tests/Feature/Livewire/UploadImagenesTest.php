<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use App\Jobs\ProcesarImagenVehiculo;

class UploadImagenesTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_component_stores_file_and_dispatches_job()
    {
        Storage::fake('public');
        Bus::fake();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $file = \Illuminate\Http\UploadedFile::fake()->image('upload.jpg', 800, 600)->size(150);

        $component = Livewire::test(\App\Livewire\Vehiculo\UploadImagenes::class, ['placa' => 'UP123'])
            ->set('tipo', 'foto_principal')
            ->set('archivo', $file)
            ->call('subir');

        // Verificar que el job fue dispatchado
        Bus::assertDispatched(ProcesarImagenVehiculo::class);

        // Verificar que se guardó al menos un archivo en la ruta del vehículo
        $files = Storage::disk('public')->allFiles('vehiculos/UP123');
        $this->assertNotEmpty($files, 'Se esperaba al menos un archivo en storage para el vehículo');
    }
}
