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
        Schema::create('categoria_programaticas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('descripcion', 200);
            $table->enum('tipo_categoria', ['Programa','Proyecto','Actividad']);
            $table->unsignedBigInteger('id_categoria_padre')->nullable();
            $table->unsignedTinyInteger('nivel')->default(1);
            $table->boolean('activo')->default(true);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();

            $table->index(['codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_programaticas');
    }
};
