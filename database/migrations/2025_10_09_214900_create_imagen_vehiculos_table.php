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
        Schema::create('imagen_vehiculos', function (Blueprint $table) {
            $table->id();

            // FK a unidad_transportes
            $table->foreignId('unidad_transporte_id')->constrained('unidad_transportes')->onDelete('cascade');

            // Ruta en el disco (ej: vehiculos/ABC123/original.jpg)
            $table->string('ruta', 255);
            $table->string('disk', 20)->default('public');

            // Tipo de imagen (original, thumbnail, tarjeton, cedula, seguro, revision)
            $table->string('tipo', 50)->nullable();

            // Metadatos JSON (ej: width, height, uploaded_by, uploaded_at)
            $table->json('metadatos')->nullable();

            $table->string('original_nombre', 255)->nullable();
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable();

            // Usuario que creó el registro (opcional)
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Índices útiles
            $table->index(['unidad_transporte_id', 'tipo'], 'idx_imagen_unidad_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen_vehiculos');
    }
};
