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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_proveedor', 100);
            $table->string('nombre_comercial', 100)->nullable();
            $table->string('nit', 20)->unique();
            
            $table->string('direccion', 200)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('email', 100)->nullable();
            
            $table->foreignId('id_tipo_servicio_proveedor')->constrained('tipo_servicio_proveedors');
            $table->string('contacto_principal', 100)->nullable();
            
            $table->enum('calificacion', ['A','B','C','D'])->default('C');
            $table->text('observaciones')->nullable();
            
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['nit']);
            $table->index(['nombre_proveedor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
