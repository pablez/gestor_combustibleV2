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
        Schema::table('unidad_transportes', function (Blueprint $table) {
            // Imagen principal del vehículo
            $table->string('foto_principal', 255)->nullable()->after('observaciones');
            
            // Galería de imágenes adicionales (JSON array de rutas)
            $table->json('galeria_fotos')->nullable()->after('foto_principal');
            
            // Foto del tarjetón de propiedad
            $table->string('foto_tarjeton_propiedad', 255)->nullable()->after('galeria_fotos');
            
            // Foto de la cédula de identidad vehicular
            $table->string('foto_cedula_identidad', 255)->nullable()->after('foto_tarjeton_propiedad');
            
            // Foto del seguro vigente
            $table->string('foto_seguro', 255)->nullable()->after('foto_cedula_identidad');
            
            // Foto de la revisión técnica
            $table->string('foto_revision_tecnica', 255)->nullable()->after('foto_seguro');
            
            // Metadatos de las imágenes
            $table->json('metadatos_imagenes')->nullable()->after('foto_revision_tecnica')
                  ->comment('Información adicional como fechas, tamaños, usuarios que subieron, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidad_transportes', function (Blueprint $table) {
            $table->dropColumn([
                'foto_principal',
                'galeria_fotos',
                'foto_tarjeton_propiedad',
                'foto_cedula_identidad',
                'foto_seguro',
                'foto_revision_tecnica',
                'metadatos_imagenes'
            ]);
        });
    }
};
