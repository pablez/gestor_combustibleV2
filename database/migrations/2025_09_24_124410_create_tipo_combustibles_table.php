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
        Schema::create('tipo_combustibles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('codigo_comercial', 10)->nullable()->unique();
            $table->text('descripcion')->nullable();
            $table->unsignedTinyInteger('octanaje')->nullable();
            
            $table->decimal('precio_referencial', 6, 2)->nullable();
            $table->string('unidad_medida', 20)->default('Litros');
            
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
        Schema::dropIfExists('tipo_combustibles');
    }
};
