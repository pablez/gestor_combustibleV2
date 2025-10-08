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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cat_programatica')->constrained('categoria_programaticas');
            $table->foreignId('id_fuente_org_fin')->constrained('fuente_organismo_financieros');
            $table->unsignedBigInteger('id_unidad_organizacional');
            $table->foreign('id_unidad_organizacional')->references('id_unidad_organizacional')->on('unidades_organizacionales');
            
            $table->year('anio_fiscal');
            $table->unsignedTinyInteger('trimestre')->nullable();
            
            $table->decimal('presupuesto_inicial', 14, 2);
            $table->decimal('presupuesto_actual', 14, 2);
            $table->decimal('total_gastado', 14, 2)->default(0);
            $table->decimal('total_comprometido', 14, 2)->default(0);
            
            $table->string('num_documento', 50);
            $table->string('numero_comprobante', 50)->nullable();
            $table->date('fecha_aprobacion')->nullable();
            
            $table->decimal('porcentaje_preventivo', 5, 2)->default(10.00);
            $table->decimal('alerta_porcentaje', 5, 2)->default(80.00);
            
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
            
            $table->index(['id_cat_programatica']);
            $table->index(['id_fuente_org_fin']);
            $table->index(['anio_fiscal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
