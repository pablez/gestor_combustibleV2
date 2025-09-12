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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'nombre')) {
                $table->string('nombre', 100)->after('email');
            }
            if (!Schema::hasColumn('users', 'apellido_paterno')) {
                $table->string('apellido_paterno', 50)->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('users', 'apellido_materno')) {
                $table->string('apellido_materno', 50)->nullable()->after('apellido_paterno');
            }
            if (!Schema::hasColumn('users', 'ci')) {
                $table->string('ci', 15)->unique()->after('apellido_materno');
            }
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono', 15)->nullable()->after('ci');
            }
            if (!Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['Admin_General','Admin_Secretaria','Supervisor','Conductor'])->default('Conductor')->after('telefono');
            }
            if (!Schema::hasColumn('users', 'id_supervisor')) {
                $table->unsignedBigInteger('id_supervisor')->nullable()->after('rol');
                $table->foreign('id_supervisor')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'id_unidad_organizacional')) {
                $table->unsignedBigInteger('id_unidad_organizacional')->nullable()->after('id_supervisor');
                $table->foreign('id_unidad_organizacional')->references('id_unidad_organizacional')->on('unidades_organizacionales')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'activo')) {
                $table->boolean('activo')->default(true)->after('id_unidad_organizacional');
            }
            if (!Schema::hasColumn('users', 'fecha_ultimo_acceso')) {
                $table->timestamp('fecha_ultimo_acceso')->nullable()->after('activo');
            }
            if (!Schema::hasColumn('users', 'intentos_fallidos')) {
                $table->tinyInteger('intentos_fallidos')->unsigned()->default(0)->after('fecha_ultimo_acceso');
            }
            if (!Schema::hasColumn('users', 'bloqueado_hasta')) {
                $table->timestamp('bloqueado_hasta')->nullable()->after('intentos_fallidos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bloqueado_hasta')) $table->dropColumn('bloqueado_hasta');
            if (Schema::hasColumn('users', 'intentos_fallidos')) $table->dropColumn('intentos_fallidos');
            if (Schema::hasColumn('users', 'fecha_ultimo_acceso')) $table->dropColumn('fecha_ultimo_acceso');
            if (Schema::hasColumn('users', 'activo')) $table->dropColumn('activo');
            if (Schema::hasColumn('users', 'id_unidad_organizacional')) {
                $table->dropForeign(['id_unidad_organizacional']);
                $table->dropColumn('id_unidad_organizacional');
            }
            if (Schema::hasColumn('users', 'id_supervisor')) {
                $table->dropForeign(['id_supervisor']);
                $table->dropColumn('id_supervisor');
            }
            if (Schema::hasColumn('users', 'rol')) $table->dropColumn('rol');
            if (Schema::hasColumn('users', 'telefono')) $table->dropColumn('telefono');
            if (Schema::hasColumn('users', 'ci')) $table->dropColumn('ci');
            if (Schema::hasColumn('users', 'apellido_materno')) $table->dropColumn('apellido_materno');
            if (Schema::hasColumn('users', 'apellido_paterno')) $table->dropColumn('apellido_paterno');
            if (Schema::hasColumn('users', 'nombre')) $table->dropColumn('nombre');
            if (Schema::hasColumn('users', 'username')) $table->dropColumn('username');
        });
    }
};
