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
        Schema::table('registro_auditorias', function (Blueprint $table) {
            // Agregar índice simple sobre (id_usuario, fecha_hora)
            $table->index(['id_usuario', 'fecha_hora'], 'idx_auditoria_usuario_fecha');
        });

        // Para indexar el id dentro del JSON 'registro_afectado' creamos
        // una columna generada virtual que extrae $.id y luego creamos
        // un índice compuesto (tabla_afectada, registro_afectado_id, fecha_hora).
        // Usamos sentencias raw para compatibilidad MySQL.
        if (!Schema::hasColumn('registro_auditorias', 'registro_afectado_id')) {
            try {
                // Añadimos columna generada que extrae el id del JSON y la convierte a número
                // Esta operación es específica de MySQL (JSON_UNQUOTE/JSON_EXTRACT y columnas generated).
                if (DB::getDriverName() === 'mysql') {
                    DB::statement("ALTER TABLE `registro_auditorias` ADD COLUMN `registro_afectado_id` BIGINT UNSIGNED GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(`registro_afectado`, '$.id')) + 0) VIRTUAL");
                } else {
                    \Log::info('Skipping creation of generated column registro_afectado_id for driver: ' . DB::getDriverName());
                }
            } catch (\Exception $e) {
                // Si la sentencia falla por versión de MySQL u otras razones, lo registramos
                \Log::warning('No se pudo crear columna generada registro_afectado_id: ' . $e->getMessage());
            }
        }

        // Crear índice compuesto usando la columna generada (si existe)
        try {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("CREATE INDEX idx_auditoria_registro_fecha ON registro_auditorias(tabla_afectada, registro_afectado_id, fecha_hora)");
            } else {
                \Log::info('Skipping creation of idx_auditoria_registro_fecha for driver: ' . DB::getDriverName());
            }
        } catch (\Exception $e) {
            \Log::warning('No se pudo crear índice idx_auditoria_registro_fecha: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registro_auditorias', function (Blueprint $table) {
            // Solo dropear los índices que creó esta migración
            // dropIndex por nombre puede fallar si no existe, así que usamos try/catch
            try {
                $table->dropIndex('idx_auditoria_registro_fecha');
            } catch (\Exception $e) {
                // noop
            }

            try {
                $table->dropIndex('idx_auditoria_usuario_fecha');
            } catch (\Exception $e) {
                // noop
            }
        });

        // Intentar eliminar la columna generada si existe
        if (Schema::hasColumn('registro_auditorias', 'registro_afectado_id')) {
            try {
                if (DB::getDriverName() === 'mysql') {
                    DB::statement("ALTER TABLE `registro_auditorias` DROP COLUMN `registro_afectado_id`");
                } else {
                    // Si no es MySQL, la columna fue probablemente creada como columna normal
                    // y será eliminada por Blueprint dropColumn en el otro migration si procede.
                    \Log::info('Skipping drop of generated column registro_afectado_id for driver: ' . DB::getDriverName());
                }
            } catch (\Exception $e) {
                \Log::warning('No se pudo eliminar columna generada registro_afectado_id: ' . $e->getMessage());
            }
        }
    }
};
