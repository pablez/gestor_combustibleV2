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
        Schema::table('codigo_registros', function (Blueprint $table) {
            // Campos para personalización del código
            $table->foreignId('id_unidad_organizacional_asignada')->nullable()->constrained('unidades_organizacionales', 'id_unidad_organizacional');
            $table->foreignId('id_supervisor_asignado')->nullable()->constrained('users');
            $table->string('rol_asignado', 50)->nullable();
            $table->text('observaciones')->nullable();
            
            // Índices para mejor rendimiento
            $table->index(['id_unidad_organizacional_asignada']);
            $table->index(['id_supervisor_asignado']);
            $table->index(['rol_asignado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('codigo_registros', function (Blueprint $table) {
            $table->dropForeign(['id_unidad_organizacional_asignada']);
            $table->dropForeign(['id_supervisor_asignado']);
            $table->dropColumn([
                'id_unidad_organizacional_asignada',
                'id_supervisor_asignado',
                'rol_asignado',
                'observaciones'
            ]);
        });
    }
};
