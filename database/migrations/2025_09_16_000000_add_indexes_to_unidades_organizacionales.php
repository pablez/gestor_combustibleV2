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
        if (! Schema::hasTable('unidades_organizacionales')) {
            return;
        }

        Schema::table('unidades_organizacionales', function (Blueprint $table) {
            // Add indexes to improve lookup performance if the columns exist.
            if (Schema::hasColumn('unidades_organizacionales', 'id_unidad_padre')) {
                // Creating an index that might already exist will fail at DB level,
                // but in typical migration flows it won't. We keep simple creation
                // and let the migration fail visibly if there is a conflict.
                $table->index('id_unidad_padre', 'unidades_id_unidad_padre_index');
            }

            if (Schema::hasColumn('unidades_organizacionales', 'tipo_unidad')) {
                $table->index('tipo_unidad', 'unidades_tipo_unidad_index');
            }

            if (Schema::hasColumn('unidades_organizacionales', 'activa')) {
                $table->index('activa', 'unidades_activa_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('unidades_organizacionales')) {
            return;
        }

        // Attempt to drop indexes; wrap each in try/catch to avoid migration
        // failure if an index is absent.
        try {
            Schema::table('unidades_organizacionales', function (Blueprint $table) {
                $table->dropIndex('unidades_id_unidad_padre_index');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            Schema::table('unidades_organizacionales', function (Blueprint $table) {
                $table->dropIndex('unidades_tipo_unidad_index');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            Schema::table('unidades_organizacionales', function (Blueprint $table) {
                $table->dropIndex('unidades_activa_index');
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
