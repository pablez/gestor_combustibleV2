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
        Schema::create('unidades_organizacionales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_unidad_organizacional', true);
            $table->string('codigo_unidad', 20)->unique();
            $table->string('nombre_unidad', 100)->unique();
            $table->enum('tipo_unidad', ['Superior', 'Ejecutiva', 'Operativa'])->default('Operativa');
            $table->unsignedBigInteger('id_unidad_padre')->nullable();
            $table->tinyInteger('nivel_jerarquico')->unsigned()->default(1);
            $table->string('responsable_unidad', 100)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->decimal('presupuesto_asignado', 14, 2)->default(0);
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();

            // FK a la misma tabla
            $table->foreign('id_unidad_padre')
                ->references('id_unidad_organizacional')
                ->on('unidades_organizacionales')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_organizacionales');
    }
};
