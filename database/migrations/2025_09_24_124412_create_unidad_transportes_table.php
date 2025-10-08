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
        Schema::create('unidad_transportes', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 15)->unique();
            $table->string('numero_chasis', 30)->nullable()->unique();
            $table->string('numero_motor', 30)->nullable();

            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->year('anio_fabricacion')->nullable();
            $table->string('color', 30)->default('');

            $table->foreignId('id_tipo_vehiculo')->constrained('tipo_vehiculos');
            $table->foreignId('id_tipo_combustible')->constrained('tipo_combustibles');
            $table->decimal('capacidad_tanque', 6, 2);

            $table->unsignedInteger('kilometraje_actual')->default(0);
            $table->unsignedInteger('kilometraje_ultimo_mantenimiento')->default(0);
            $table->unsignedInteger('proximo_mantenimiento_km')->nullable();

            $table->foreignId('id_unidad_organizacional')->references('id_unidad_organizacional')->on('unidades_organizacionales');
            $table->foreignId('id_conductor_asignado')->nullable()->constrained('users');
            $table->enum('estado_operativo', ['Operativo','Mantenimiento','Taller','Baja','Reserva'])->default('Operativo');

            $table->date('seguro_vigente_hasta')->nullable();
            $table->date('revision_tecnica_hasta')->nullable();
            $table->date('fecha_ultimo_servicio')->nullable();

            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index(['placa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_transportes');
    }
};
