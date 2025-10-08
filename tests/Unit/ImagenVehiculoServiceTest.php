<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\UploadedFile;
use App\Jobs\ProcesarImagenVehiculo;

class ImagenVehiculoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guardar_imagen_stores_file_and_dispatches_job()
    {
        Storage::fake('public');
        Bus::fake();

        // Crear usuario y actuar como él para metadata
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $placa = 'ABC-123';
        $tipo = 'foto_principal';

        $file = UploadedFile::fake()->image('vehiculo.jpg', 1200, 800)->size(500);

        $service = app(\App\Services\ImagenVehiculoService::class);

        $resultado = $service->guardarImagen($file, $tipo, $placa);

        $this->assertArrayHasKey('ruta', $resultado);
        $this->assertArrayHasKey('url', $resultado);
        $this->assertArrayHasKey('metadatos', $resultado);

        // Comprobar que el archivo fue guardado en disco
        $this->assertTrue(Storage::disk('public')->exists($resultado['ruta']));

        // Comprobar que se dispatchó el job ProcesarImagenVehiculo
        Bus::assertDispatched(ProcesarImagenVehiculo::class, function ($job) use ($placa, $tipo) {
            return $job->placa === $placa && $job->tipoImagen === $tipo && is_string($job->archivoPath);
        });

        // Aún no debe existir registro de auditoría porque el job no se ejecutó
        $this->assertDatabaseMissing('registro_auditorias', [
            'accion_realizada' => 'OPTIMIZAR_IMAGEN'
        ]);
    }
}
