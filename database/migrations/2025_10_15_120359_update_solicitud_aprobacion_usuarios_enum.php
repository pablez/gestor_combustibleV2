<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar 'registro_nuevo' al enum tipo_solicitud
        DB::statement("ALTER TABLE solicitud_aprobacion_usuarios MODIFY tipo_solicitud ENUM('nuevo_usuario','cambio_rol','reactivacion','cambio_supervisor','registro_nuevo') NOT NULL DEFAULT 'nuevo_usuario'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al enum original
        DB::statement("ALTER TABLE solicitud_aprobacion_usuarios MODIFY tipo_solicitud ENUM('nuevo_usuario','cambio_rol','reactivacion','cambio_supervisor') NOT NULL DEFAULT 'nuevo_usuario'");
    }
};
