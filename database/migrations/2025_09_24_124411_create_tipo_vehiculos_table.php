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
        Schema::create('tipo_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->enum('categoria', ['Liviano','Pesado','Motocicleta','Especializado']);
            $table->text('descripcion')->nullable();
            
            $table->decimal('consumo_promedio_ciudad', 4, 2)->nullable();
            $table->decimal('consumo_promedio_carretera', 4, 2)->nullable();
            $table->unsignedInteger('capacidad_carga_kg')->nullable();
            $table->unsignedTinyInteger('numero_pasajeros')->nullable();
            
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_vehiculos');
    }
};
