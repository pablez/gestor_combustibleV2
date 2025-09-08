## Gestor Combustible V1

Aplicación Laravel para la gestión de usuarios, solicitudes de combustible, despachos, consumos, viáticos, mantenimientos y auditorías.

## Tecnologías / Stack Instalado
- **PHP**: ^8.2
- **Laravel**: ^12.0 (framework principal)
- **Livewire**: ^3.4 (componentes dinámicos)
- **Livewire Volt**: ^1.7.0
- **Spatie Laravel Permission**: ^6.20 (roles y permisos)
- **Tailwind CSS**: ^3.1.0 (estilos)
- **Vite**: ^6.2.4 (bundling de assets)
- **Node.js / npm**: Para gestión de dependencias JS
- **Docker / Laravel Sail**: Entorno de desarrollo
- **MySQL / MariaDB**: Base de datos (configurable via `.env`)
- **Pest / PHPUnit**: Tests (instalado, pero no implementados aún)

## Dependencias Instaladas
### PHP (composer.json)
- laravel/framework: ^12.0
- livewire/livewire: ^3.4
- livewire/volt: ^1.7.0
- spatie/laravel-permission: ^6.20
- Dev: laravel/breeze, laravel/sail, pestphp/pest, etc.

### JavaScript (package.json)
- @tailwindcss/forms: ^0.5.2
- @tailwindcss/vite: ^4.0.0
- autoprefixer: ^10.4.2
- axios: ^1.8.2
- concurrently: ^9.0.1
- laravel-vite-plugin: ^1.2.0
- postcss: ^8.4.31
- tailwindcss: ^3.1.0
- vite: ^6.2.4

## Estructura del Proyecto
### Migraciones (database/migrations/)
- 0001_01_01_000000_create_users_table.php
- 0001_01_01_000001_create_cache_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2025_06_27_180054_create_permission_tables.php
- 2025_06_27_181445_create_unidad_transportes_table.php
- 2025_06_27_181503_create_proveedors_table.php
- 2025_06_27_181809_create_normativas_table.php
- 2025_06_27_182115_create_solicitud_combustibles_table.php
- 2025_06_27_182134_create_despacho_combustibles_table.php
- 2025_06_27_182148_create_consumo_combustibles_table.php
- 2025_06_27_182202_create_solicitud_viaticos_table.php
- 2025_06_27_182213_create_liquidacion_viaticos_table.php
- 2025_06_27_182226_create_gasto_extra_transportes_table.php
- 2025_06_27_182239_create_mantenimientos_table.php
- 2025_06_27_182252_create_registro_auditorias_table.php
- 2025_07_04_181952_add_supervisor_id_to_users_table.php
- 2025_07_11_155427_add_foto_perfil_to_users_table.php
- 2025_07_16_170146_create_unidad_organizacionals_table.php
- 2025_07_16_171248_add_unidad_organizacional_id_to_users_table.php
- 2025_07_18_160429_add_estado_column_to_unidad_organizacionals_table.php
- 2025_07_21_164527_create_user_approval_requests_table.php
- 2025_07_24_174935_create_codigo_registros_table.php
- 2025_07_31_000000_add_fields_to_codigo_registros_table.php

### Modelos (app/Models/)
- CodigoRegistro.php
- ConsumoCombustible.php
- DespachoCombustible.php
- GastoExtraTransporte.php
- LiquidacionViatico.php
- Mantenimiento.php
- Normativa.php
- Proveedor.php
- RegistroAuditoria.php
- SolicitudCombustible.php
- SolicitudViatico.php
- UnidadOrganizacional.php
- UnidadTransporte.php
- User.php
- UserApprovalRequest.php

### Componentes Livewire (app/Livewire/)
- CodigoRegistroPanel.php
- HistorialCodigosPanel.php
- Directorios: Actions/, Forms/, Reports/, UnidadTransporte/, User/
- User/: ApprovalQueue.php, UserCreate.php, UserEdit.php, UserIndex.php, UserPrint.php, UserShow.php

## Cómo Levantar el Proyecto
1. Copiar `.env.example` a `.env` y ajustar variables (DB, APP_URL, etc.).
2. Levantar contenedores con Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```
3. Instalar dependencias PHP:
   ```bash
   ./vendor/bin/sail composer install
   ```
4. Instalar dependencias JS:
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```
5. Ejecutar migraciones y seeders:
   ```bash
   ./vendor/bin/sail php artisan migrate --seed
   ```
6. Acceder en el navegador a la URL configurada en `.env`.

## Notas
- El proyecto incluye gestión de usuarios con roles (Admin, Supervisor, Conductor/Operador), solicitudes de combustible, despachos, consumos, viáticos, mantenimientos y auditorías.
- Livewire se usa para componentes dinámicos en la UI.
- Spatie Permission maneja autorizaciones.
- Tailwind CSS para estilos.
- Sail proporciona el entorno Docker para desarrollo.

---
README actualizado con el estado del proyecto a la fecha: 2025-09-05.
