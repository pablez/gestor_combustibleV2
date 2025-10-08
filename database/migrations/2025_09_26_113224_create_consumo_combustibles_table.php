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
        Schema::create('consumo_combustibles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_unidad_transporte');
            $table->unsignedBigInteger('id_despacho')->nullable();
            $table->foreignId('id_usuario_conductor')->constrained('users');
            
            $table->timestamp('fecha_registro')->useCurrent();
            $table->unsignedInteger('kilometraje_inicial');
            $table->unsignedInteger('kilometraje_fin');
            $table->decimal('litros_cargados', 8, 3);
            
            $table->enum('tipo_carga', ['despacho_oficial','carga_externa','emergencia'])->default('despacho_oficial');
            $table->string('lugar_carga', 100);
            $table->string('numero_ticket', 30)->nullable();
            
            $table->text('observaciones')->nullable();
            $table->boolean('validado')->default(false);
            $table->timestamp('fecha_validacion')->nullable();
            $table->foreignId('id_usuario_validador')->nullable()->constrained('users');
            
            $table->timestamps();
            
            $table->index(['id_unidad_transporte']);
            $table->index(['id_despacho']);
            $table->index(['fecha_registro']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_combustibles');
    }
};
