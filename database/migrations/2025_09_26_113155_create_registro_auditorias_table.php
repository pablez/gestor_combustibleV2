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
        Schema::create('registro_auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users');
            $table->timestamp('fecha_hora')->useCurrent();
            
            $table->string('accion_realizada', 50);
            $table->string('tabla_afectada', 50);
            $table->json('registro_afectado');
            
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent', 200)->nullable();
            $table->string('sesion_id', 100)->nullable();
            
            $table->string('modulo_sistema', 50)->nullable();
            $table->enum('nivel_criticidad', ['BAJO','MEDIO','ALTO','CRÍTICO'])->default('MEDIO');
            
            $table->index(['id_usuario']);
            $table->index(['tabla_afectada']);
            $table->index(['fecha_hora']);
            $table->index(['nivel_criticidad']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_auditorias');
    }
};
