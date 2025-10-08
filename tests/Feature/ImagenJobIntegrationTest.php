<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImagenJobIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guardar_imagen_executes_job_sync_and_creates_audit_and_thumbnail()
    {
        Storage::fake('public');

        // Force queue to run jobs synchronously for this test
        config(['queue.default' => 'sync']);

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $placa = 'INT-123';
        $vehiculo = \App\Models\UnidadTransporte::factory()->create(['placa' => $placa]);

        $file = UploadedFile::fake()->image('vehiculo.jpg', 1200, 800)->size(600);

        $service = app(\App\Services\ImagenVehiculoService::class);

        $resultado = $service->guardarImagen($file, 'foto_principal', $placa);

        $this->assertArrayHasKey('ruta', $resultado);

        // Since queue is sync, the job should have run and created thumbnail and audit
        $ruta = $resultado['ruta'];
        $thumb = str_replace('.', '_thumb.', $ruta);

        $this->assertTrue(Storage::disk('public')->exists($ruta), 'Original must exist');
        $this->assertTrue(Storage::disk('public')->exists($thumb), 'Thumbnail must exist after job runs');

        $this->assertDatabaseHas('registro_auditorias', [
            'tabla_afectada' => 'unidad_transportes',
            'accion_realizada' => 'OPTIMIZAR_IMAGEN'
        ]);
    }
}
