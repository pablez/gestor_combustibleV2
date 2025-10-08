<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UnidadTransporte;
use App\Models\RegistroAuditoria;
use App\Services\AuditoriaImagenService;
use Illuminate\Support\Facades\Storage;

class AuditoriaImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_auditoria_y_columna_generada()
    {
        Storage::fake('public');

    // Seed completo para asegurarnos de que las relaciones FK existen
    $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Crear usuario y vehículo
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $vehiculo = UnidadTransporte::factory()->create([
            'placa' => 'TEST123'
        ]);

        // Registrar acción mediante el servicio
        $service = new AuditoriaImagenService();
        $service->registrarAccion('SUBIR_IMAGEN', $vehiculo, 'foto_principal', ['ruta' => 'vehiculos/test.jpg']);

        $this->assertDatabaseHas('registro_auditorias', [
            'tabla_afectada' => 'unidad_transportes',
            'accion_realizada' => 'SUBIR_IMAGEN'
        ]);

        $registro = RegistroAuditoria::where('tabla_afectada', 'unidad_transportes')
            ->where('accion_realizada', 'SUBIR_IMAGEN')
            ->first();

        $this->assertNotNull($registro);
        // La columna generada debe existir y coincidir con el id del vehículo
        $this->assertEquals($vehiculo->id, (int) $registro->registro_afectado_id);
    }

    public function test_exportar_auditoria_genera_archivo()
    {
        Storage::fake('public');

    // Seed completo para asegurarnos de que las relaciones FK existen
    $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Crear usuario y vehículo
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $vehiculo = UnidadTransporte::factory()->create(['placa' => 'EXP123']);

        // Registrar un par de eventos
        $service = new AuditoriaImagenService();
        $service->registrarAccion('SUBIR_IMAGEN', $vehiculo, 'foto_principal', ['ruta' => 'vehiculos/exp.jpg']);
        $service->registrarAccion('ELIMINAR_IMAGEN', $vehiculo, 'galeria_fotos', ['ruta_eliminada' => 'vehiculos/exp2.jpg']);

        // Ejecutar export
        $url = $service->exportarAuditoria();

        // Extraer la ruta relativa al disk
        $path = parse_url($url, PHP_URL_PATH);
        $relative = preg_replace('#/storage/#', '', $path);

        Storage::disk('public')->assertExists($relative);

        $contenido = Storage::disk('public')->get($relative);
        $this->assertStringContainsString('SUBIR_IMAGEN', $contenido);
        $this->assertStringContainsString('ELIMINAR_IMAGEN', $contenido);
    }
}
