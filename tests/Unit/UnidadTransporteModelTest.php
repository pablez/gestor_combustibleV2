<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UnidadTransporte;

class UnidadTransporteModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_unidad_transporte_casts_and_image_fields()
    {
        $vehiculo = UnidadTransporte::factory()->create([
            'foto_principal' => 'vehiculos/ABC123/principal.jpg',
            'galeria_fotos' => ['vehiculos/ABC123/1.jpg', 'vehiculos/ABC123/2.jpg'],
            'metadatos_imagenes' => ['foto_principal' => ['usuario_id' => 1]],
            'activo' => 1,
            'seguro_vigente_hasta' => now()->toDateString(),
        ]);

        $this->assertIsArray($vehiculo->galeria_fotos, 'galeria_fotos debe castear a array');
        $this->assertIsArray($vehiculo->metadatos_imagenes, 'metadatos_imagenes debe castear a array');
        $this->assertIsBool($vehiculo->activo, 'activo debe castear a boolean');
        $this->assertNotNull($vehiculo->foto_principal);
        $this->assertGreaterThanOrEqual(2, $vehiculo->total_fotos);
    }
}
