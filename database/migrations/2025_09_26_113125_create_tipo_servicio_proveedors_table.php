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
        Schema::create('tipo_servicio_proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            
            $table->boolean('requiere_autorizacion_especial')->default(false);
            $table->unsignedTinyInteger('dias_credito_maximo')->default(0);
            
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['codigo']);
            $table->index(['nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_servicio_proveedors');
    }
};
