<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcesarImagenVehiculo;
use App\Models\UnidadTransporte;
use App\Models\RegistroAuditoria;

class ProcesarImagenVehiculoIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_is_idempotent_on_thumbnail_creation_and_records_audits_each_run()
    {
        Storage::fake('public');

        // Crear usuario y vehículo y autenticarse
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $placa = 'IDEMP-1';
        UnidadTransporte::factory()->create(['placa' => $placa]);

        $ruta = 'vehiculos/' . preg_replace('/[^A-Za-z0-9]/', '', $placa) . '/principales/original.jpg';
        Storage::disk('public')->put($ruta, 'contenido-origen');

        $job = new ProcesarImagenVehiculo($ruta, 'foto_principal', $placa);

        // Ejecutar job dos veces
        $job->handle();
        $job->handle();

        $thumb = str_replace('.', '_thumb.', $ruta);

        // Thumbnail debe existir y no debe lanzar errores al ejecutar dos veces
        $this->assertTrue(Storage::disk('public')->exists($thumb), 'Se esperaba que existiera el thumbnail tras ejecuciones repetidas');

        // Deben haberse registrado dos auditorías (una por cada ejecución)
        $count = RegistroAuditoria::where('tabla_afectada', 'unidad_transportes')
            ->where('accion_realizada', 'OPTIMIZAR_IMAGEN')
            ->count();

        $this->assertEquals(2, $count, 'Se esperaban 2 registros de auditoría (uno por cada ejecución del job)');
    }
}
