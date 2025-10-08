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
        Schema::create('despacho_combustibles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_solicitud');
            $table->foreignId('id_proveedor')->constrained('proveedors');
            
            $table->timestamp('fecha_despacho')->useCurrent();
            $table->decimal('litros_despachados', 8, 3);
            $table->decimal('precio_por_litro', 6, 2);
            $table->decimal('costo_total', 12, 2);
            
            $table->string('numero_vale', 20)->unique();
            $table->string('numero_factura', 30)->nullable();
            $table->foreignId('id_usuario_despachador')->constrained('users');
            
            $table->string('ubicacion_despacho', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('validado')->default(false);
            $table->timestamp('fecha_validacion')->nullable();
            $table->foreignId('id_usuario_validador')->nullable()->constrained('users');
            
            $table->timestamps();
            
            $table->index(['id_solicitud']);
            $table->index(['numero_vale']);
            $table->index(['fecha_despacho']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despacho_combustibles');
    }
};
