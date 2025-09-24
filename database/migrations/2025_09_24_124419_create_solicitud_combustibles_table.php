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
        Schema::create('solicitud_combustibles', function (Blueprint $table) {
            $table->id();
            $table->string('numero_solicitud', 20)->unique();
            $table->foreignId('id_usuario_solicitante')->constrained('users');
            $table->foreignId('id_unidad_transporte')->nullable()->constrained('unidad_transportes');

            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->decimal('cantidad_litros_solicitados', 8, 3);
            $table->text('motivo')->nullable();
            $table->boolean('urgente')->default(false);
            $table->text('justificacion_urgencia')->nullable();

            $table->enum('estado_solicitud', ['Pendiente','En_Revision','Aprobada','Rechazada','Despachada','Cancelada'])->default('Pendiente');
            $table->foreignId('id_usuario_aprobador')->nullable()->constrained('users');
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->text('observaciones_aprobacion')->nullable();

            $table->foreignId('id_cat_programatica')->nullable()->constrained('categoria_programatica');
            $table->foreignId('id_fuente_org_fin')->nullable()->constrained('fuente_organismo_financieros');
            $table->decimal('saldo_actual_combustible', 12, 2)->nullable();

            $table->unsignedInteger('km_actual')->default(0);
            $table->unsignedInteger('km_proyectado')->default(0);
            $table->decimal('rendimiento_estimado', 6, 2)->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['estado_solicitud']);
            $table->index(['id_usuario_solicitante']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_combustibles');
    }
};
