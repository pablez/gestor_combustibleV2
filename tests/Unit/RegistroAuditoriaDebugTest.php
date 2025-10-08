<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\RegistroAuditoria;

class RegistroAuditoriaDebugTest extends TestCase
{
    use RefreshDatabase;

    public function test_inspect_registro_afectado_storage()
    {
        $registro = RegistroAuditoria::factory()->create([
            'registro_afectado' => ['id' => 42, 'placa' => 'ZZZ-42']
        ]);

        // Mostrar cómo se leyó el atributo del modelo
        \dump('modelo->registro_afectado (casted):', $registro->registro_afectado);
        \dump('modelo->registro_afectado_id (accessor):', $registro->registro_afectado_id);

        // Leer directamente desde la DB la columna cruda
        $raw = \DB::table('registro_auditorias')->where('id', $registro->id)->first();
        \dump('raw->registro_afectado (DB):', $raw->registro_afectado);

        // Ejecutar la expresión JSON que usamos
        $driver = \DB::getDriverName();
        \dump('driver:', $driver);
        if ($driver === 'sqlite') {
            $res = \DB::select("select json_extract(registro_afectado, '$.id') as ext from registro_auditorias where id = ?", [$registro->id]);
            \dump('json_extract result:', $res);
        } else {
            $res = \DB::select("select JSON_UNQUOTE(JSON_EXTRACT(registro_afectado, '$.id')) as ext from registro_auditorias where id = ?", [$registro->id]);
            \dump('json_extract mysql result:', $res);
        }

    // Intentar la consulta que usa el scope y volcar el SQL generado
    \DB::enableQueryLog();
    $found = RegistroAuditoria::porRegistroAfectadoId(42)->first();
    $queries = \DB::getQueryLog();
    \dump('found via scope:', $found?->id ?? null);
    \dump('queries:', $queries);

        // Asegurar que el test termine sin assertions (es solo debug)
        $this->assertTrue(true);
    }
}
