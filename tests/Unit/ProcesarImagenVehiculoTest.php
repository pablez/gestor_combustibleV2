<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcesarImagenVehiculo;
use App\Models\UnidadTransporte;
use App\Models\RegistroAuditoria;

class ProcesarImagenVehiculoTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_creates_thumbnail_and_audit()
    {
        Storage::fake('public');

        // Crear usuario y vehículo
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $placa = 'ABC-123';
        $placa_clean = preg_replace('/[^A-Za-z0-9]/', '', $placa);

        $vehiculo = UnidadTransporte::factory()->create(['placa' => $placa]);

        $ruta = "vehiculos/{$placa_clean}/principales/original.jpg";
        Storage::disk('public')->put($ruta, 'dummy-image-content');

        $job = new ProcesarImagenVehiculo($ruta, 'foto_principal', $placa);
        $job->handle();

        $thumb = str_replace('.', '_thumb.', $ruta);

        $this->assertTrue(Storage::disk('public')->exists($thumb), 'Se esperaba que existiera el thumbnail');

        $this->assertDatabaseHas('registro_auditorias', [
            'tabla_afectada' => 'unidad_transportes',
            'accion_realizada' => 'OPTIMIZAR_IMAGEN'
        ]);

        $registro = RegistroAuditoria::where('tabla_afectada', 'unidad_transportes')
            ->where('accion_realizada', 'OPTIMIZAR_IMAGEN')
            ->first();

        $this->assertNotNull($registro);
        $this->assertEquals($vehiculo->id, $registro->registro_afectado['id']);
    }

    public function test_job_no_vehicle_no_audit_created()
    {
        Storage::fake('public');

        $placa = 'NO-EXISTE';
        $placa_clean = preg_replace('/[^A-Za-z0-9]/', '', $placa);

        $ruta = "vehiculos/{$placa_clean}/principales/original.jpg";
        Storage::disk('public')->put($ruta, 'dummy-image-content');

        $job = new ProcesarImagenVehiculo($ruta, 'foto_principal', $placa);
        $job->handle();

        $this->assertDatabaseMissing('registro_auditorias', [
            'tabla_afectada' => 'unidad_transportes',
            'accion_realizada' => 'OPTIMIZAR_IMAGEN'
        ]);
    }
}
