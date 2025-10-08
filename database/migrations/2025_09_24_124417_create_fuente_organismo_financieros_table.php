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
        Schema::create('fuente_organismo_financieros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('descripcion', 200);
            $table->enum('tipo_fuente', ['Nacional','Departamental','Municipal','Internacional','Otros']);
            $table->string('organismo_financiador', 100)->nullable();
            $table->boolean('requiere_contrapartida')->default(false);
            $table->decimal('porcentaje_contrapartida', 5, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->date('fecha_vigencia_inicio')->nullable();
            $table->date('fecha_vigencia_fin')->nullable();
            $table->timestamps();

            $table->index(['codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuente_organismo_financieros');
    }
};
