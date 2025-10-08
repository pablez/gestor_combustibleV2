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
        Schema::create('solicitud_aprobacion_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users');
            $table->foreignId('id_creador')->constrained('users');
            $table->foreignId('id_supervisor_asignado')->nullable()->constrained('users');
            
            $table->enum('tipo_solicitud', ['nuevo_usuario','cambio_rol','reactivacion','cambio_supervisor'])->default('nuevo_usuario');
            $table->enum('estado_solicitud', ['pendiente','aprobado','rechazado','en_revision'])->default('pendiente');
            $table->string('rol_solicitado', 50)->nullable();
            
            $table->text('justificacion');
            $table->text('observaciones_aprobacion')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->foreignId('id_usuario_aprobador')->nullable()->constrained('users');
            
            $table->timestamps();
            
            $table->index(['id_usuario']);
            $table->index(['estado_solicitud']);
            $table->index(['tipo_solicitud']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_aprobacion_usuarios');
    }
};
