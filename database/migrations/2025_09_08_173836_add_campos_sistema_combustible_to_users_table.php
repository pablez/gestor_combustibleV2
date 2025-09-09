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
            // Campos de identificación personal
            $table->string('username', 50)->unique()->after('id');
            $table->string('nombre', 100)->after('username');
            $table->string('apellido_paterno', 50)->after('nombre');
            $table->string('apellido_materno', 50)->nullable()->after('apellido_paterno');
            $table->string('ci', 15)->unique()->after('apellido_materno');
            $table->string('telefono', 15)->nullable()->after('ci');
            
            // Campos organizacionales
            $table->enum('rol', [
                'Admin_General',
                'Admin_Secretaria', 
                'Supervisor',
                'Conductor'
            ])->after('telefono');
            
            // Relaciones jerárquicas (se crearán las FK después de crear las tablas relacionadas)
            $table->unsignedBigInteger('id_supervisor')->nullable()->after('rol');
            $table->unsignedBigInteger('id_unidad_organizacional')->after('id_supervisor');
            
            // Campos de estado y seguridad
            $table->boolean('activo')->default(true)->after('id_unidad_organizacional');
            $table->timestamp('fecha_ultimo_acceso')->nullable()->after('activo');
            $table->tinyInteger('intentos_fallidos')->unsigned()->default(0)->after('fecha_ultimo_acceso');
            $table->timestamp('bloqueado_hasta')->nullable()->after('intentos_fallidos');
            
            // Modificar campo name existente para ser nullable (lo usaremos como nombre completo calculado)
            $table->string('name')->nullable()->change();
            
            // Índices para optimizar consultas
            $table->index(['rol', 'activo']);
            $table->index(['id_unidad_organizacional', 'activo']);
            $table->index('id_supervisor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar índices primero
            $table->dropIndex(['rol', 'activo']);
            $table->dropIndex(['id_unidad_organizacional', 'activo']);
            $table->dropIndex(['id_supervisor']);
            
            // Eliminar campos en orden inverso
            $table->dropColumn([
                'bloqueado_hasta',
                'intentos_fallidos', 
                'fecha_ultimo_acceso',
                'activo',
                'id_unidad_organizacional',
                'id_supervisor',
                'rol',
                'telefono',
                'ci',
                'apellido_materno',
                'apellido_paterno',
                'nombre',
                'username'
            ]);
            
            // Restaurar campo name como NOT NULL
            $table->string('name')->nullable(false)->change();
        });
    }
};
