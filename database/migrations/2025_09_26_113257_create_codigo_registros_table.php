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
        Schema::create('codigo_registros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 15)->unique();
            $table->foreignId('id_usuario_generador')->constrained('users');
            $table->date('vigente_hasta');
            $table->boolean('usado')->default(false);
            $table->foreignId('id_usuario_usado')->nullable()->constrained('users');
            $table->timestamp('fecha_uso')->nullable();
            
            $table->timestamps();
            
            $table->index(['codigo']);
            $table->index(['vigente_hasta']);
            $table->index(['usado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_registros');
    }
};
