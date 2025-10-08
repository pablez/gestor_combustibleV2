<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use App\Jobs\ProcesarImagenVehiculo;
use App\Models\UnidadTransporte;

class VehiculoImagenesUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_subir_imagen_dispatch_job_y_guarda_archivo()
    {
        Storage::fake('public');
        Bus::fake();

        // Seed completo (asegurar roles/permissions si el componente depende de ellos)
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $vehiculo = UnidadTransporte::factory()->create(['placa' => 'LV123']);

        // Crear un archivo de prueba tipo UploadedFile usando Http testing helper
        $file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg', 800, 600)->size(100);

        // Renderizar componente Livewire y ejecutar la acción de subir
        $component = Livewire::test(\App\Livewire\VehiculoImagenes::class, ['vehiculo' => $vehiculo]);

        // Abrir modal y asignar archivo
        $component->call('abrirModal', 'foto_principal');
        $component->set('nuevasImagenes', [0 => $file]);

        $component->call('subirImagen');

        // Afirmar que se dispatchó el job
        Bus::assertDispatched(ProcesarImagenVehiculo::class);

        // Afirmar que el archivo original fue guardado en storage
        $files = Storage::disk('public')->allFiles('vehiculos/LV123');
        $this->assertNotEmpty($files);
    }
}
