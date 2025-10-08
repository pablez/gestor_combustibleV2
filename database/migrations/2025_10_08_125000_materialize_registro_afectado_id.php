<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si la columna generada no se pudo crear en la migración anterior,
        // materializamos una columna BIGINT con el id extraído del JSON.
        if (! Schema::hasColumn('registro_auditorias', 'registro_afectado_id')) {
            try {
                Schema::table('registro_auditorias', function (Blueprint $table) {
                    $table->unsignedBigInteger('registro_afectado_id')->nullable()->after('registro_afectado');
                });

                // Poblar la columna con el id extraído del JSON (si es posible)
                try {
                    DB::statement("UPDATE registro_auditorias SET registro_afectado_id = (JSON_UNQUOTE(JSON_EXTRACT(registro_afectado, '$.id')) + 0) WHERE registro_afectado IS NOT NULL");
                } catch (\Exception $e) {
                    // fall back: intentar con un select/update más seguro por lotes podría implementarse
                    \Log::warning('Materialize registro_afectado_id: update statement failed: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                \Log::warning('No se pudo crear columna materializada registro_afectado_id: ' . $e->getMessage());
            }
        }

        // Crear índice compuesto si no existe
        try {
            DB::statement("CREATE INDEX idx_auditoria_registro_fecha_material ON registro_auditorias(tabla_afectada, registro_afectado_id, fecha_hora)");
        } catch (\Exception $e) {
            \Log::warning('No se pudo crear índice idx_auditoria_registro_fecha_material: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('DROP INDEX idx_auditoria_registro_fecha_material ON registro_auditorias');
        } catch (\Exception $e) {
            // noop
        }

        if (Schema::hasColumn('registro_auditorias', 'registro_afectado_id')) {
            try {
                Schema::table('registro_auditorias', function (Blueprint $table) {
                    $table->dropColumn('registro_afectado_id');
                });
            } catch (\Exception $e) {
                \Log::warning('No se pudo eliminar columna materializada registro_afectado_id: ' . $e->getMessage());
            }
        }
    }
};
