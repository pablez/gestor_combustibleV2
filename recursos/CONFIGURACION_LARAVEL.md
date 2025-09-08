# Configuración Laravel para Sistema de Combustible - Gobernación de Cochabamba

## 1. Estructura de Proyecto Laravel

### 1.1 Instalación en Proyecto Laravel Existente

```bash
# Instalar Laravel Sail en proyecto existente
composer require laravel/sail --dev

# Configurar Sail - IMPORTANTE: Seleccionar servicios según análisis siguiente
php artisan sail:install

# Al ejecutar sail:install, seleccionar los siguientes servicios:
# [✓] mysql (Base de datos principal)
# [✓] redis (Cache, sesiones y colas)
# [✓] mailpit (Testing de emails localmente)
# [✓] selenium (Testing E2E - opcional pero recomendado)

# Iniciar contenedores Docker
./vendor/bin/sail up -d

# Verificar que los servicios estén funcionando
./vendor/bin/sail ps

# Instalar Breeze con Livewire
./vendor/bin/sail composer require laravel/breeze --dev
./vendor/bin/sail artisan breeze:install livewire

# Instalar Spatie Permissions
./vendor/bin/sail composer require spatie/laravel-permission

# Instalar dependencias adicionales para el sistema
./vendor/bin/sail composer require intervention/image
./vendor/bin/sail composer require barryvdh/laravel-dompdf
./vendor/bin/sail composer require maatwebsite/excel
./vendor/bin/sail composer require guzzlehttp/guzzle

# Instalar dependencias de frontend
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 1.1.1 Análisis de Servicios Sail Requeridos

Basado en las funcionalidades del sistema de combustible, necesitamos los siguientes servicios:

#### **MySQL (OBLIGATORIO)**
```
Razón: Base de datos principal del sistema
- Almacena todas las tablas: usuarios, solicitudes, despachos, etc.
- Triggers automáticos para auditoría
- Procedimientos almacenados para operaciones críticas
- Vistas para reportes optimizados
```

#### **Redis (OBLIGATORIO)**
```
Razón: Cache, sesiones y sistema de colas
- Cache: Datos de configuración, catálogos, reportes frecuentes
- Sesiones: Manejo de sesiones de usuario seguras
- Colas: Procesamiento asíncrono de:
  * Envío de notificaciones WhatsApp
  * Generación de reportes pesados
  * Actualizaciones de presupuesto en lote
  * Envío de emails
```

#### **Mailpit (RECOMENDADO para desarrollo)**
```
Razón: Testing de notificaciones por email
- Pruebas de credenciales de usuario
- Notificaciones de alertas presupuestarias
- Reportes automáticos por email
- Verificación de emails de registro
```

#### **Selenium (OPCIONAL)**
```
Razón: Testing E2E del flujo completo
- Testing del flujo de registro con WhatsApp
- Pruebas de aprobación de solicitudes
- Testing de generación de reportes
- Validación de permisos por rol
```

#### **Servicios NO necesarios para este proyecto:**
```
- PostgreSQL: Ya usamos MySQL
- MariaDB: Ya usamos MySQL
- Minio: No necesitamos S3 compatible storage
- Soketi: No usamos WebSockets en tiempo real
- Typesense/MeiliSearch: No necesitamos búsqueda full-text
```

### 1.1.2 Configuración docker-compose.yml Resultante

Después de ejecutar `sail:install` con los servicios seleccionados, verificar que el archivo `docker-compose.yml` contenga:

```yaml
version: '3'
services:
    laravel.test:
        build:
            context: ./vendor/laravel/sail/runtimes/8.3
            dockerfile: Dockerfile
            args:
                WWWGROUP: '${WWWGROUP}'
        image: sail-8.3/app
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        ports:
            - '${APP_PORT:-80}:80'
            - '${VITE_PORT:-5173}:${VITE_PORT:-5173}'
        environment:
            WWWUSER: '${WWWUSER}'
            LARAVEL_SAIL: 1
            XDEBUG_MODE: '${SAIL_XDEBUG_MODE:-off}'
            XDEBUG_CONFIG: '${SAIL_XDEBUG_CONFIG:-client_host=host.docker.internal}'
            IGNITION_LOCAL_SITES_PATH: '${PWD}'
        volumes:
            - '.:/var/www/html'
        networks:
            - sail
        depends_on:
            - mysql
            - redis

    mysql:
        image: 'mysql/mysql-server:8.0'
        ports:
            - '${FORWARD_DB_PORT:-3306}:3306'
        environment:
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ROOT_HOST: '%'
            MYSQL_DATABASE: '${DB_DATABASE}'
            MYSQL_USER: '${DB_USERNAME}'
            MYSQL_PASSWORD: '${DB_PASSWORD}'
            MYSQL_ALLOW_EMPTY_PASSWORD: 1
        volumes:
            - 'sail-mysql:/var/lib/mysql'
            - './vendor/laravel/sail/database/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh'
        networks:
            - sail
        healthcheck:
            test:
                - CMD
                - mysqladmin
                - ping
                - '-p${DB_PASSWORD}'
            retries: 3
            timeout: 5s

    redis:
        image: 'redis:alpine'
        ports:
            - '${FORWARD_REDIS_PORT:-6379}:6379'
        volumes:
            - 'sail-redis:/data'
        networks:
            - sail
        healthcheck:
            test:
                - CMD
                - redis-cli
                - ping
            retries: 3
            timeout: 5s

    mailpit:
        image: 'axllent/mailpit:latest'
        ports:
            - '${FORWARD_MAILPIT_PORT:-1025}:1025'
            - '${FORWARD_MAILPIT_DASHBOARD_PORT:-8025}:8025'
        networks:
            - sail

networks:
    sail:
        driver: bridge

volumes:
    sail-mysql:
        driver: local
    sail-redis:
        driver: local
```

### 1.1.3 Configuración Específica para el Sistema de Combustible

Crear alias para facilitar el uso de Sail:

```bash
# En Windows (PowerShell)
Set-Alias sail "./vendor/bin/sail"

# O crear un archivo batch sail.bat en la raíz del proyecto:
@echo off
./vendor/bin/sail %*

# En Linux/Mac (agregar al .bashrc o .zshrc)
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Comandos específicos para nuestro sistema:

```bash
# Configurar base de datos
sail artisan migrate:fresh --seed

# Optimizar para producción
sail artisan config:cache
sail artisan route:cache
sail artisan view:cache

# Comandos específicos del sistema de combustible
sail artisan combustible:generar-reportes-diarios
sail artisan combustible:verificar-presupuestos
sail artisan combustible:limpiar-codigos-expirados

# Monitorear logs en tiempo real
sail logs --follow

# Acceder a MySQL directamente
sail mysql

# Acceder al contenedor de la aplicación
sail shell
```

### 1.2 Configuración del .env para Sail

```env
APP_NAME="Sistema de Gestión de Combustible - Gobernación de Cochabamba"
APP_ENV=local
APP_KEY=base64:GENERAR_CON_php_artisan_key_generate
APP_DEBUG=true
APP_TIMEZONE=America/La_Paz
APP_URL=http://localhost

# Configuración específica de Sail
APP_PORT=80
VITE_PORT=5173

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Base de datos MySQL con Sail
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sistema_combustible_gobernacion
DB_USERNAME=sail
DB_PASSWORD=password

# Redis con Sail
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Broadcast
BROADCAST_CONNECTION=log

# Filesystem
FILESYSTEM_DISK=local

# Email configuración para desarrollo con Mailpit
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="sistema@gobernacion.gob.bo"
MAIL_FROM_NAME="${APP_NAME}"

# Para producción usar configuración real de email:
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=sistema@gobernacion.gob.bo
# MAIL_PASSWORD=tu_password_aqui
# MAIL_ENCRYPTION=tls

# Configuración de WhatsApp API
WHATSAPP_API_URL=https://graph.facebook.com/v17.0
WHATSAPP_ACCESS_TOKEN=tu_token_aqui
WHATSAPP_PHONE_NUMBER_ID=tu_phone_id_aqui
WHATSAPP_BUSINESS_ACCOUNT_ID=tu_business_id_aqui
WHATSAPP_WEBHOOK_VERIFY_TOKEN=tu_webhook_token_aqui

# Configuración específica de la Gobernación de Cochabamba
GOBERNACION_NIT=1234567890123
GOBERNACION_NOMBRE="Gobierno Autónomo Departamental de Cochabamba"
GOBERNACION_DIRECCION="Plaza 14 de Septiembre, Cochabamba, Bolivia"
GOBERNACION_TELEFONO="+591-4-4258888"
GOBERNACION_EMAIL="info@gobernacion.gob.bo"

# Configuración de reportes y documentos
REPORTES_LOGO_PATH=storage/assets/logo_gobernacion.png
REPORTES_ENCABEZADO="Gobierno Autónomo Departamental de Cochabamba"
REPORTES_PIE_PAGINA="Sistema de Gestión de Combustible"
REPORTES_DIRECTORIO=storage/reportes

# Configuración específica del sistema de combustible
COMBUSTIBLE_CODIGO_VIGENCIA_HORAS=24
COMBUSTIBLE_TOKEN_APROBACION_HORAS=24
COMBUSTIBLE_ALERTA_PRESUPUESTO_70=70
COMBUSTIBLE_ALERTA_PRESUPUESTO_85=85
COMBUSTIBLE_ALERTA_PRESUPUESTO_95=95

# Configuración de colas para WhatsApp
QUEUE_WHATSAPP=whatsapp
QUEUE_REPORTES=reportes
QUEUE_EMAILS=emails

# Configuración de cache
CACHE_PREFIX=gobernacion_combustible

# Configuración de sesiones
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

# Configuración para desarrollo con Sail
WWWGROUP=1000
WWWUSER=1000
SAIL_XDEBUG_MODE=develop,debug
SAIL_XDEBUG_CONFIG="client_host=host.docker.internal"

# Puertos específicos para Sail (opcional, usa defaults si no se especifica)
FORWARD_DB_PORT=3306
FORWARD_REDIS_PORT=6379
FORWARD_MAILPIT_PORT=1025
FORWARD_MAILPIT_DASHBOARD_PORT=8025
```

### 1.2.1 Comandos de Configuración Inicial

```bash
# Generar clave de aplicación
sail artisan key:generate

# Crear enlace simbólico para storage
sail artisan storage:link

# Configurar cache de configuración
sail artisan config:cache

# Instalar Spatie Permissions
sail artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Ejecutar migraciones
sail artisan migrate

# Ejecutar seeders para datos iniciales
sail artisan db:seed --class=RolesPermissionsSeeder
sail artisan db:seed --class=UnidadesOrganizacionalesSeeder
sail artisan db:seed --class=TiposCombustibleSeeder
sail artisan db:seed --class=TiposVehiculoSeeder

# Configurar colas de trabajo
sail artisan queue:table
sail artisan migrate

# Crear usuario administrador inicial
sail artisan combustible:crear-admin-inicial
```

### 1.2.2 Configuración de Servicios (config/services.php)

Agregar al archivo `config/services.php`:

```php
<?php

return [
    // ... configuraciones existentes ...

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v17.0'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
        'country_code' => '591', // Bolivia
    ],

    'gobernacion' => [
        'nit' => env('GOBERNACION_NIT'),
        'nombre' => env('GOBERNACION_NOMBRE'),
        'direccion' => env('GOBERNACION_DIRECCION'),
        'telefono' => env('GOBERNACION_TELEFONO'),
        'email' => env('GOBERNACION_EMAIL'),
    ],

    'combustible' => [
        'codigo_vigencia_horas' => env('COMBUSTIBLE_CODIGO_VIGENCIA_HORAS', 24),
        'token_aprobacion_horas' => env('COMBUSTIBLE_TOKEN_APROBACION_HORAS', 24),
        'alertas_presupuesto' => [
            'preventiva' => env('COMBUSTIBLE_ALERTA_PRESUPUESTO_70', 70),
            'critica' => env('COMBUSTIBLE_ALERTA_PRESUPUESTO_85', 85),
            'maxima' => env('COMBUSTIBLE_ALERTA_PRESUPUESTO_95', 95),
        ],
    ],

    'reportes' => [
        'logo_path' => env('REPORTES_LOGO_PATH'),
        'encabezado' => env('REPORTES_ENCABEZADO'),
        'pie_pagina' => env('REPORTES_PIE_PAGINA'),
        'directorio' => env('REPORTES_DIRECTORIO', 'storage/reportes'),
    ],
];
```

### 1.2.3 Configuración de Colas (config/queue.php)

Modificar el archivo `config/queue.php` para agregar colas específicas:

```php
'connections' => [
    // ... conexiones existentes ...

    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],

    // Cola específica para WhatsApp
    'whatsapp' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'whatsapp',
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],

    // Cola específica para reportes
    'reportes' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'reportes',
        'retry_after' => 300, // 5 minutos para reportes pesados
        'block_for' => null,
        'after_commit' => false,
    ],

    // Cola específica para emails
    'emails' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'emails',
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],
],
```

### 1.2.4 Scripts de Desarrollo con Sail

Crear archivo `scripts/dev-setup.sh` para automatizar el setup:

```bash
#!/bin/bash

echo "🚀 Configurando Sistema de Combustible - Gobernación de Cochabamba"

# Verificar que Sail esté instalado
if [ ! -f "vendor/bin/sail" ]; then
    echo "❌ Laravel Sail no encontrado. Ejecutar: composer require laravel/sail --dev"
    exit 1
fi

# Levantar servicios
echo "📦 Levantando servicios Docker..."
./vendor/bin/sail up -d

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
./vendor/bin/sail exec mysql mysqladmin ping --silent

# Instalar dependencias
echo "📥 Instalando dependencias de Composer..."
./vendor/bin/sail composer install

# Configurar aplicación
echo "🔧 Configurando aplicación..."
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan storage:link
./vendor/bin/sail artisan config:cache

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
./vendor/bin/sail artisan migrate:fresh --seed

# Instalar NPM
echo "📦 Instalando dependencias de NPM..."
./vendor/bin/sail npm install
./vendor/bin/sail npm run build

# Configurar permisos
echo "🔐 Configurando roles y permisos..."
./vendor/bin/sail artisan db:seed --class=RolesPermissionsSeeder

echo "✅ Configuración completada!"
echo ""
echo "🌐 Aplicación disponible en: http://localhost"
echo "📧 Mailpit (emails) en: http://localhost:8025"
echo "🗄️ MySQL puerto: 3306"
echo "🔴 Redis puerto: 6379"
echo ""
echo "📝 Comandos útiles:"
echo "  ./vendor/bin/sail artisan queue:work"
echo "  ./vendor/bin/sail logs --follow"
echo "  ./vendor/bin/sail mysql"
```

Hacer el script ejecutable:
```bash
chmod +x scripts/dev-setup.sh
```

### 1.2.5 Workers de Cola para Producción

Para producción, configurar workers específicos en `supervisor.conf`:

```ini
[program:gobernacion-combustible-default]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work redis --queue=default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/queue-default.log
stopwaitsecs=3600

[program:gobernacion-combustible-whatsapp]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work redis --queue=whatsapp --sleep=3 --tries=5 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=3
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/queue-whatsapp.log
stopwaitsecs=3600

[program:gobernacion-combustible-reportes]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work redis --queue=reportes --sleep=3 --tries=2 --max-time=7200
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/queue-reportes.log
stopwaitsecs=7200
```

Durante desarrollo con Sail, usar:

```bash
# Worker principal (en terminal separado)
sail artisan queue:work redis --queue=default,whatsapp,emails --sleep=3 --tries=3

# Worker para reportes pesados (en terminal separado)
sail artisan queue:work redis --queue=reportes --sleep=3 --tries=2 --timeout=300

# Monitorear colas
sail artisan queue:monitor redis:default,redis:whatsapp,redis:reportes,redis:emails

# Ver trabajos fallidos
sail artisan queue:failed

# Reintentar trabajos fallidos
sail artisan queue:retry all
```

## 2. Migraciones de Base de Datos

### 2.1 Orden de Ejecución de Migraciones

```bash
# Migración 1: Crear tablas de configuración base
./vendor/bin/sail artisan make:migration create_tipos_combustible_table
./vendor/bin/sail artisan make:migration create_tipos_vehiculo_table
./vendor/bin/sail artisan make:migration create_tipos_servicio_proveedor_table
./vendor/bin/sail artisan make:migration create_unidades_organizacionales_table
./vendor/bin/sail artisan make:migration create_categorias_programaticas_table
./vendor/bin/sail artisan make:migration create_fuentes_organismo_financiero_table

# Migración 2: Tablas de usuarios y autenticación
./vendor/bin/sail artisan make:migration create_usuarios_table
./vendor/bin/sail artisan make:migration create_codigos_registro_table
./vendor/bin/sail artisan make:migration create_solicitudes_aprobacion_usuario_table

# Migración 3: Tablas de proveedores y flota
./vendor/bin/sail artisan make:migration create_proveedores_table
./vendor/bin/sail artisan make:migration create_unidades_transporte_table

# Migración 4: Tablas de presupuesto y combustible
./vendor/bin/sail artisan make:migration create_presupuestos_table
./vendor/bin/sail artisan make:migration create_solicitudes_combustible_table
./vendor/bin/sail artisan make:migration create_despachos_combustible_table
./vendor/bin/sail artisan make:migration create_consumos_combustible_table

# Migración 5: Auditoría
./vendor/bin/sail artisan make:migration create_registro_auditoria_table

# Migración 6: Spatie Permissions
./vendor/bin/sail artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 2.2 Migración Principal: create_usuarios_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('nombre', 100);
            $table->string('email', 100)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->enum('rol', [
                'Admin_General',
                'Admin_Secretaria', 
                'Supervisor',
                'Conductor',
                'Operator'
            ]);
            $table->unsignedBigInteger('id_supervisor')->nullable();
            $table->unsignedBigInteger('id_unidad_organizacional');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            
            $table->foreign('id_supervisor')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('set null');
                  
            $table->foreign('id_unidad_organizacional')
                  ->references('id_unidad_organizacional')
                  ->on('unidades_organizacionales')
                  ->onDelete('restrict');
                  
            $table->index(['rol', 'activo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
```

### 2.3 Migración: create_solicitudes_combustible_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solicitudes_combustible', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->string('numero_solicitud', 20)->unique();
            $table->unsignedBigInteger('id_usuario_solicitante');
            $table->unsignedBigInteger('id_unidad_transporte');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->decimal('cantidad_litros_solicitados', 10, 2);
            $table->text('motivo');
            $table->string('destino', 255)->nullable();
            $table->date('fecha_viaje_programado')->nullable();
            $table->enum('estado_solicitud', [
                'Pendiente',
                'Aprobada',
                'Rechazada',
                'Despachada',
                'Cancelada'
            ])->default('Pendiente');
            $table->unsignedBigInteger('id_usuario_aprobador')->nullable();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->unsignedBigInteger('id_cat_programatica');
            $table->unsignedBigInteger('id_fuente_org_fin');
            $table->decimal('saldo_actual_combustible', 10, 2)->nullable();
            $table->integer('km_actual');
            $table->integer('km_recorr');
            $table->text('observaciones_solicitud')->nullable();
            $table->text('observaciones_aprobacion')->nullable();
            $table->enum('prioridad', ['Baja', 'Normal', 'Alta', 'Urgente'])
                  ->default('Normal');
            $table->timestamps();
            
            $table->foreign('id_usuario_solicitante')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('restrict');
                  
            $table->foreign('id_unidad_transporte')
                  ->references('id_unidad_transporte')
                  ->on('unidades_transporte')
                  ->onDelete('restrict');
                  
            $table->foreign('id_usuario_aprobador')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('set null');
                  
            $table->foreign('id_cat_programatica')
                  ->references('id_cat_programatica')
                  ->on('categorias_programaticas')
                  ->onDelete('restrict');
                  
            $table->foreign('id_fuente_org_fin')
                  ->references('id_fuente_org_fin')
                  ->on('fuentes_organismo_financiero')
                  ->onDelete('restrict');
                  
            $table->index(['estado_solicitud', 'fecha_solicitud']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes_combustible');
    }
};
```

## 3. Modelos Eloquent

### 3.1 Modelo Usuario

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'username',
        'password',
        'nombre',
        'email',
        'telefono',
        'rol',
        'id_supervisor',
        'id_unidad_organizacional',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_creacion' => 'datetime',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function supervisor()
    {
        return $this->belongsTo(Usuario::class, 'id_supervisor', 'id_usuario');
    }

    public function subordinados()
    {
        return $this->hasMany(Usuario::class, 'id_supervisor', 'id_usuario');
    }

    public function unidadOrganizacional()
    {
        return $this->belongsTo(UnidadOrganizacional::class, 'id_unidad_organizacional');
    }

    public function solicitudesCombustible()
    {
        return $this->hasMany(SolicitudCombustible::class, 'id_usuario_solicitante');
    }

    public function solicitudesAprobadas()
    {
        return $this->hasMany(SolicitudCombustible::class, 'id_usuario_aprobador');
    }

    public function despachos()
    {
        return $this->hasMany(DespachoCombustible::class, 'id_usuario_despachador');
    }

    public function unidadesTransporteAsignadas()
    {
        return $this->hasMany(UnidadTransporte::class, 'id_conductor_asignado');
    }

    public function registrosAuditoria()
    {
        return $this->hasMany(RegistroAuditoria::class, 'id_usuario');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorRol($query, $rol)
    {
        return $query->where('rol', $rol);
    }

    public function scopePorUnidadOrganizacional($query, $unidadId)
    {
        return $query->where('id_unidad_organizacional', $unidadId);
    }

    // Métodos auxiliares
    public function esAdmin()
    {
        return in_array($this->rol, ['Admin_General', 'Admin_Secretaria']);
    }

    public function esSupervisor()
    {
        return $this->rol === 'Supervisor';
    }

    public function esConductor()
    {
        return $this->rol === 'Conductor';
    }

    public function esOperator()
    {
        return $this->rol === 'Operator';
    }

    public function puedeAprobarSolicitudes()
    {
        return in_array($this->rol, ['Admin_General', 'Admin_Secretaria', 'Supervisor']);
    }
}
```

### 3.2 Modelo SolicitudCombustible

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCombustible extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_combustible';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'numero_solicitud',
        'id_usuario_solicitante',
        'id_unidad_transporte',
        'cantidad_litros_solicitados',
        'motivo',
        'destino',
        'fecha_viaje_programado',
        'estado_solicitud',
        'id_cat_programatica',
        'id_fuente_org_fin',
        'saldo_actual_combustible',
        'km_actual',
        'km_recorr',
        'observaciones_solicitud',
        'prioridad'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_viaje_programado' => 'date',
        'fecha_aprobacion' => 'datetime',
        'cantidad_litros_solicitados' => 'decimal:2',
        'saldo_actual_combustible' => 'decimal:2',
        'km_actual' => 'integer',
        'km_recorr' => 'integer'
    ];

    // Relaciones
    public function usuarioSolicitante()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_solicitante', 'id_usuario');
    }

    public function usuarioAprobador()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_aprobador', 'id_usuario');
    }

    public function unidadTransporte()
    {
        return $this->belongsTo(UnidadTransporte::class, 'id_unidad_transporte');
    }

    public function categoriaProgramatica()
    {
        return $this->belongsTo(CategoriaProgramatica::class, 'id_cat_programatica');
    }

    public function fuenteOrganismoFinanciero()
    {
        return $this->belongsTo(FuenteOrganismoFinanciero::class, 'id_fuente_org_fin');
    }

    public function despacho()
    {
        return $this->hasOne(DespachoCombustible::class, 'id_solicitud');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado_solicitud', 'Pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado_solicitud', 'Aprobada');
    }

    public function scopeDespachadas($query)
    {
        return $query->where('estado_solicitud', 'Despachada');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_solicitud', [$fechaInicio, $fechaFin]);
    }

    public function scopePorUnidadOrganizacional($query, $unidadId)
    {
        return $query->whereHas('usuarioSolicitante', function ($q) use ($unidadId) {
            $q->where('id_unidad_organizacional', $unidadId);
        });
    }

    // Métodos auxiliares
    public function puedeSerAprobada()
    {
        return $this->estado_solicitud === 'Pendiente';
    }

    public function puedeSerDespachada()
    {
        return $this->estado_solicitud === 'Aprobada';
    }

    public function aprobar($usuarioAprobador, $observaciones = null)
    {
        $this->update([
            'estado_solicitud' => 'Aprobada',
            'id_usuario_aprobador' => $usuarioAprobador->id_usuario,
            'fecha_aprobacion' => now(),
            'observaciones_aprobacion' => $observaciones
        ]);
    }

    public function rechazar($usuarioAprobador, $observaciones)
    {
        $this->update([
            'estado_solicitud' => 'Rechazada',
            'id_usuario_aprobador' => $usuarioAprobador->id_usuario,
            'fecha_aprobacion' => now(),
            'observaciones_aprobacion' => $observaciones
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitud) {
            $solicitud->numero_solicitud = self::generarNumeroSolicitud();
        });
    }

    public static function generarNumeroSolicitud()
    {
        $prefijo = 'SC' . date('Y');
        $ultimo = self::where('numero_solicitud', 'like', $prefijo . '%')
                      ->orderBy('numero_solicitud', 'desc')
                      ->first();

        if ($ultimo) {
            $numero = (int) substr($ultimo->numero_solicitud, -6) + 1;
        } else {
            $numero = 1;
        }

        return $prefijo . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }
}
```

## 4. Configuración de Roles y Permisos (Spatie)

### 4.1 Seeder de Roles y Permisos

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos
        $permissions = [
            // Gestión de usuarios
            'gestionar_usuarios',
            'generar_codigos_registro',
            'aprobar_usuarios',
            
            // Gestión de combustible
            'crear_solicitud_combustible',
            'aprobar_solicitud_combustible',
            'despachar_combustible',
            'registrar_consumo',
            
            // Gestión de flota
            'gestionar_vehiculos',
            'asignar_conductores',
            'ver_rendimientos',
            
            // Reportes
            'ver_reportes_propios',
            'ver_reportes_unidad',
            'ver_reportes_globales',
            'exportar_reportes',
            
            // Configuración
            'gestionar_proveedores',
            'gestionar_presupuestos',
            'configurar_sistema',
            
            // Auditoría
            'ver_auditoria_propia',
            'ver_auditoria_unidad',
            'ver_auditoria_global'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $this->crearRolAdminGeneral();
        $this->crearRolAdminSecretaria();
        $this->crearRolSupervisor();
        $this->crearRolConductor();
        $this->crearRolOperator();
    }

    private function crearRolAdminGeneral()
    {
        $role = Role::firstOrCreate(['name' => 'Admin_General']);
        
        // Admin General tiene todos los permisos
        $role->givePermissionTo(Permission::all());
    }

    private function crearRolAdminSecretaria()
    {
        $role = Role::firstOrCreate(['name' => 'Admin_Secretaria']);
        
        $permissions = [
            'gestionar_usuarios',
            'generar_codigos_registro',
            'aprobar_usuarios',
            'aprobar_solicitud_combustible',
            'gestionar_vehiculos',
            'asignar_conductores',
            'ver_rendimientos',
            'ver_reportes_unidad',
            'exportar_reportes',
            'gestionar_proveedores',
            'ver_auditoria_unidad'
        ];
        
        $role->givePermissionTo($permissions);
    }

    private function crearRolSupervisor()
    {
        $role = Role::firstOrCreate(['name' => 'Supervisor']);
        
        $permissions = [
            'generar_codigos_registro',
            'aprobar_usuarios',
            'aprobar_solicitud_combustible',
            'ver_rendimientos',
            'ver_reportes_unidad',
            'exportar_reportes',
            'ver_auditoria_unidad'
        ];
        
        $role->givePermissionTo($permissions);
    }

    private function crearRolConductor()
    {
        $role = Role::firstOrCreate(['name' => 'Conductor']);
        
        $permissions = [
            'crear_solicitud_combustible',
            'registrar_consumo',
            'ver_reportes_propios',
            'ver_auditoria_propia'
        ];
        
        $role->givePermissionTo($permissions);
    }

    private function crearRolOperator()
    {
        $role = Role::firstOrCreate(['name' => 'Operator']);
        
        $permissions = [
            'despachar_combustible',
            'registrar_consumo',
            'gestionar_proveedores',
            'ver_reportes_propios',
            'ver_auditoria_propia'
        ];
        
        $role->givePermissionTo($permissions);
    }
}
```

## 5. Configuración de WhatsApp Service

### 5.1 Servicio WhatsApp

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class WhatsAppService
{
    protected $apiUrl;
    protected $accessToken;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    public function enviarNotificacionSolicitudUsuario($solicitud)
    {
        try {
            $codigo = $solicitud->codigoRegistro;
            $creador = $codigo->usuarioCreador;
            
            $mensaje = $this->construirMensajeSolicitud($solicitud);
            $urlAprobacion = $this->generarUrlAprobacion($solicitud);
            
            // Enviar mensaje informativo
            $this->enviarMensaje($creador->telefono, $mensaje);
            
            // Enviar enlace de aprobación
            $mensajeEnlace = "🔗 *ENLACE DE APROBACIÓN*\n\n" .
                           "Para procesar esta solicitud:\n" .
                           $urlAprobacion . "\n\n" .
                           "⏰ Válido por 24 horas";
            
            $this->enviarMensaje($creador->telefono, $mensajeEnlace);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error enviando notificación WhatsApp: ' . $e->getMessage());
            return false;
        }
    }

    public function enviarCredencialesUsuario($usuario, $password)
    {
        $mensaje = "🎉 *USUARIO APROBADO*\n\n" .
                   "Sus credenciales de acceso:\n\n" .
                   "🆔 Usuario: {$usuario->username}\n" .
                   "🔐 Contraseña: {$password}\n\n" .
                   "🌐 Acceder al sistema:\n" .
                   config('app.url') . "\n\n" .
                   "⚠️ Cambie su contraseña al primer acceso";

        return $this->enviarMensaje($usuario->telefono, $mensaje);
    }

    private function enviarMensaje($telefono, $mensaje)
    {
        $telefono = $this->formatearTelefono($telefono);
        
        $response = Http::withToken($this->accessToken)
            ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $telefono,
                'type' => 'text',
                'text' => [
                    'body' => $mensaje
                ]
            ]);

        if (!$response->successful()) {
            Log::error('Error API WhatsApp: ' . $response->body());
            throw new \Exception('Error enviando mensaje WhatsApp');
        }

        return $response->json();
    }

    private function construirMensajeSolicitud($solicitud)
    {
        return "🔔 *NUEVA SOLICITUD DE USUARIO*\n\n" .
               "📋 *Datos del Solicitante:*\n" .
               "👤 Nombre: {$solicitud->nombre_solicitado}\n" .
               "🆔 Usuario: {$solicitud->username_solicitado}\n" .
               "📧 Email: {$solicitud->email_solicitado}\n" .
               "📱 Teléfono: {$solicitud->telefono_solicitado}\n" .
               "🏢 Unidad: {$solicitud->unidadOrganizacional->nombre_unidad}\n" .
               "👔 Rol: {$solicitud->rol_solicitado}\n\n" .
               "🔑 Código: {$solicitud->codigoRegistro->codigo}\n" .
               "📅 Fecha: " . $solicitud->created_at->format('d/m/Y H:i');
    }

    private function generarUrlAprobacion($solicitud)
    {
        $token = $this->generarTokenSeguro($solicitud->id);
        return route('aprobar-usuario', [
            'token' => $token,
            'solicitud' => $solicitud->id
        ]);
    }

    private function generarTokenSeguro($solicitudId)
    {
        $payload = [
            'solicitud_id' => $solicitudId,
            'timestamp' => time(),
            'expires_at' => time() + (24 * 60 * 60)
        ];
        
        return Crypt::encrypt($payload);
    }

    private function formatearTelefono($telefono)
    {
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        
        if (strlen($telefono) === 8) {
            return '591' . $telefono;
        }
        
        return $telefono;
    }
}
```

Esta configuración proporciona una base sólida para implementar el sistema de gestión de combustible con todas las características requeridas para la Gobernación de Cochabamba.
