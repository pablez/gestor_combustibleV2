<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\RegistroAuditoria;

class RegistroAuditoriaModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_afectado_id_accessor_and_scope()
    {
        // Crear un registro con registro_afectado como array
        $registro = RegistroAuditoria::factory()->create([
            'registro_afectado' => ['id' => 42, 'placa' => 'ZZZ-42']
        ]);

        $this->assertEquals(42, $registro->registro_afectado_id, 'El accessor debe extraer id del JSON');

        $found = RegistroAuditoria::porRegistroAfectadoId(42)->first();
        $this->assertNotNull($found, 'El scope porRegistroAfectadoId debe encontrar el registro por id');
        $this->assertEquals(42, $found->registro_afectado_id);
    }
}
