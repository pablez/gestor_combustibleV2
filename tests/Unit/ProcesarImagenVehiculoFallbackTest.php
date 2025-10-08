<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcesarImagenVehiculo;
use App\Models\UnidadTransporte;

class ProcesarImagenVehiculoFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_falls_back_to_copy_when_image_processing_throws()
    {
        Storage::fake('public');

        // No forzamos la existencia de la fachada; más abajo definimos un fallback si no existe.

        // Crear vehículo y archivo original
        $placa = 'FALL-1';
        UnidadTransporte::factory()->create(['placa' => $placa]);
        $ruta = 'vehiculos/' . preg_replace('/[^A-Za-z0-9]/', '', $placa) . '/principales/original.jpg';
        Storage::disk('public')->put($ruta, 'contenido-origen');

    // Ensure a user with id=1 exists so any fallback inserts satisfy FK constraints
    $user = \App\Models\User::factory()->create(['id' => 1, 'email' => 'fallback1@example.com']);
    $this->actingAs($user);

        // Mockear la fachada Image para que lance excepción al intentar make.
        // Si la fachada real está disponible y soporta shouldReceive (Mockery), la usamos.
        if (class_exists('\\Intervention\\Image\\Facades\\Image') && method_exists('\\Intervention\\Image\\Facades\\Image', 'shouldReceive')) {
            \Intervention\Image\Facades\Image::shouldReceive('make')->andThrow(new \Exception('processing failed'));
        } else {
            // En entornos sin Intervention/Facade, definimos una clase mínima que simula
            // la API estática y lanza la excepción al invocar make().
            if (! class_exists('\\Intervention\\Image\\Facades\\Image')) {
                eval('namespace Intervention\\Image\\Facades; class Image { public static function make($arg) { throw new \\Exception("processing failed"); } }');
            }
        }

        $job = new ProcesarImagenVehiculo($ruta, 'foto_principal', $placa);
        $job->handle();

        $thumb = str_replace('.', '_thumb.', $ruta);

        // Aunque Image::make falló, el código debe haber copiado el archivo original como thumbnail
        $this->assertTrue(Storage::disk('public')->exists($thumb), 'Expected thumbnail to exist (copied fallback)');

        // Y la auditoría debe haberse registrado
        $this->assertDatabaseHas('registro_auditorias', [
            'tabla_afectada' => 'unidad_transportes',
            'accion_realizada' => 'OPTIMIZAR_IMAGEN'
        ]);
    }

    public function test_job_handles_missing_file_and_records_audit()
    {
        Storage::fake('public');

        $placa = 'MISSING-1';
        UnidadTransporte::factory()->create(['placa' => $placa]);

        $ruta = 'vehiculos/' . preg_replace('/[^A-Za-z0-9]/', '', $placa) . '/principales/nonexistent.jpg';
        // Ensure a user exists so fallback audit insert (id_usuario = 1) satisfies FK
        \App\Models\User::factory()->create(['id' => 1, 'email' => 'fallback@example.com']);

        $job = new ProcesarImagenVehiculo($ruta, 'foto_principal', $placa);
        $job->handle();

        $thumb = str_replace('.', '_thumb.', $ruta);

        // Thumbnail should not exist because source file was missing
        $this->assertFalse(Storage::disk('public')->exists($thumb), 'Thumbnail should not exist when source file missing');

        // But audit should still be recorded as job catches errors and continues
        $this->assertDatabaseHas('registro_auditorias', [
            'tabla_afectada' => 'unidad_transportes',
            'accion_realizada' => 'OPTIMIZAR_IMAGEN'
        ]);
    }
}
