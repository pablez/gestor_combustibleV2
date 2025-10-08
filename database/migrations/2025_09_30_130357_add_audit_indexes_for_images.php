<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registro_auditorias', function (Blueprint $table) {
            // Índices para optimizar consultas de auditoría de imágenes
            $table->index(['tabla_afectada', 'accion_realizada'], 'idx_auditoria_tabla_accion');
            $table->index(['modulo_sistema'], 'idx_auditoria_modulo');
            $table->index(['nivel_criticidad'], 'idx_auditoria_criticidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registro_auditorias', function (Blueprint $table) {
            $table->dropIndex('idx_auditoria_tabla_accion');
            $table->dropIndex('idx_auditoria_modulo');
            $table->dropIndex('idx_auditoria_criticidad');
        });
    }
};
