<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use App\Jobs\ProcesarImagenVehiculo;

class UploadImagenesIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_modal_integration_with_uploader_component()
    {
        Storage::fake('public');
        Bus::fake();

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $vehiculo = \App\Models\UnidadTransporte::factory()->create(['placa' => 'INT1']);

        // Abrir el componente padre y abrir modal
        $parent = Livewire::test(\App\Livewire\VehiculoImagenes::class, ['vehiculo' => $vehiculo]);
        $parent->call('abrirModal', 'foto_principal');

        // Verificar que el uploader integrado aparece en el DOM del componente padre
        $parent->assertSeeLivewire('vehiculo.upload-imagenes');
    }
}
