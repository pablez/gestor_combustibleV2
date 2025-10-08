<?php
require __DIR__ . "/../vendor/autoload.php";
$app = require_once __DIR__ . "/../bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Comprobando columna registro_afectado_id...\n";
    if (Schema::hasColumn('registro_auditorias', 'registro_afectado_id')) {
        echo "La columna existe. Intentando dropearla para recrear con expresión correcta...\n";
        try {
            DB::statement('ALTER TABLE `registro_auditorias` DROP COLUMN `registro_afectado_id`');
            echo "Columna dropeada.\n";
        } catch (\Exception $e) {
            echo "No se pudo dropear la columna: " . $e->getMessage() . "\n";
        }
    } else {
        echo "La columna no existe; procederemos a crearla.\n";
    }

    // Crear columna generada correctamente
    try {
        DB::statement("ALTER TABLE `registro_auditorias` ADD COLUMN `registro_afectado_id` BIGINT UNSIGNED GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(`registro_afectado`, '$.id')) + 0) VIRTUAL");
        echo "Columna generada creada correctamente.\n";
    } catch (\Exception $e) {
        echo "Error creando columna generada: " . $e->getMessage() . "\n";
    }

    // Crear índice compuesto si no existe
    $indexName = 'idx_auditoria_registro_fecha';
    // Comprobar existencia de índice
    $rows = DB::select("SHOW INDEX FROM registro_auditorias WHERE Key_name = ?", [$indexName]);
    if (count($rows) === 0) {
        try {
            DB::statement("CREATE INDEX {$indexName} ON registro_auditorias(tabla_afectada, registro_afectado_id, fecha_hora)");
            echo "Índice {$indexName} creado correctamente.\n";
        } catch (\Exception $e) {
            echo "Error creando índice {$indexName}: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Índice {$indexName} ya existe.\n";
    }

    echo "Hecho.\n";
} catch (\Exception $e) {
    echo "Error general: " . $e->getMessage() . "\n";
}
