# Gestor de Combustible — Guía de uso por roles

Bienvenido. Este documento explica cómo usar el sistema paso a paso según el rol del usuario.

Tecnologías usadas: Laravel 12, Livewire 3, Spatie Permission, DomPDF, Laravel-Excel, Sail (Docker).

---

## Contenido

- Requisitos y arranque rápido
- Acceso y rutas principales
- Roles y permisos (resumen)
- Guía por roles: Admin General, Secretaria, Supervisor, Conductor
- Reportes (PDF/Excel)
- Dashboard y KPIs
- Troubleshooting rápido
- Comandos útiles

---

## Requisitos y arranque rápido

Requisitos mínimos:

- PHP >= 8.2
- Composer
- Docker (opcional, se recomienda Sail)
- Node.js + npm/yarn (opcional para assets)

Arrancar con Sail:

```bash
./vendor/bin/sail up -d
```

Instalar dependencias y migrar:

```bash
composer install
cp .env.example .env
./vendor/bin/sail php artisan key:generate
./vendor/bin/sail php artisan migrate --seed
```

Crear un usuario admin (ejemplo):

```bash
./vendor/bin/sail php artisan tinker --execute "\
\$u = App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('secret')]);\
\$u->assignRole('Admin_Secretaria');\
"
```

---

## Acceso y rutas principales

- URL base (local): `http://127.0.0.1`
- Dashboard: `http://127.0.0.1/dashboard`
- Reportes: `http://127.0.0.1/reportes`
- Solicitudes: `http://127.0.0.1/solicitudes`
- Unidades de Transporte: `http://127.0.0.1/unidades-transporte`
- Consumos: `http://127.0.0.1/consumos`
- Despachos: `http://127.0.0.1/despachos`

Rutas (nombres) clave: `dashboard`, `reportes.index`, `unidades-transporte.index`, `solicitudes.index`, `consumos.index`, `despachos.index`, `users.index`.

---

## Roles y permisos (resumen)

- `Admin_Secretaria`: permisos amplios para gestionar usuarios, reportes y validaciones.
- `Supervisor`: acceso a KPIs y supervisión operativa.
- `Conductor`: registrar consumos y tickets.

Gestionado con Spatie Permission; revisa `config/permission.php` y seeders si faltan permisos.

---

## Guía por roles

### Admin General

Responsabilidades:
- Gestión de usuarios y permisos
- Visualización del Dashboard Ejecutivo
- Generación de reportes PDF/Excel

Pasos habituales:
1. Inicia sesión y abre `http://127.0.0.1/dashboard`.
2. Desde "Accesos Rápidos" accede a Usuarios, Vehículos, Solicitudes o Reportes.
3. Para crear/editar usuarios: `Users` → crear/editar → asignar roles.
4. Para reportes: `Reportes` → seleccionar tipo, aplicar filtros → Generar/Exportar.

Permisos recomendados: `reportes.ver`, `users.*`, `unidades.*`, `despachos.*`.

### Secretaria

Responsabilidades:
- Validación y aprobación de solicitudes
- Generación de reportes operativos

Pasos habituales:
1. Abrir `http://127.0.0.1/solicitudes`.
2. Filtrar por estado `Pendiente` y revisar solicitudes.
3. Aprobar/rechazar solicitudes según corresponda.
4. Generar reportes desde `Reportes` cuando necesites información consolidada.

Permisos recomendados: `solicitudes.ver`, `solicitudes.aprobar`, `reportes.ver`.

### Supervisor

Responsabilidades:
- Monitorizar rendimiento de la flota
- Atender alertas de bajo rendimiento

Pasos habituales:
1. Abrir Dashboard y filtrar por unidad organizacional si aplica.
2. Revisar comparativas por unidad y vehículos con bajo rendimiento.
3. Exportar datos para informes si es necesario.

Permisos recomendados: `kpis.ver`, `consumos.ver`, `despachos.ver`.

### Conductor

Responsabilidades:
- Registrar consumos y tickets
- Reportar incidencias

Pasos habituales:
1. Ir a `http://127.0.0.1/consumos` → `Crear`.
2. Completar kilometraje inicial/fin, litros cargados, tipo de carga y número de ticket.
3. Guardar; la secretaria/supervisor validará el registro.

Permisos recomendados: `consumos.create`, `consumos.ver`.

---

## Reportes (PDF / Excel)

1. Visitar `http://127.0.0.1/reportes`.
2. Elegir tipo de reporte (Combustible o Presupuesto).
3. Aplicar filtros (fechas, unidad, proveedor) y generar.

Notas: PDFs usan DomPDF y Excel usa Laravel-Excel. Si falla, revisar `storage/logs/laravel.log`.

---

## Dashboard y KPIs

- `app/Livewire/Kpis/DashboardEjecutivo.php` muestra rendimiento por vehículo, eficiencia por unidad, top proveedores, consumo mensual y alertas.
- Usuarios con rol `Admin_Secretaria` verán datos filtrados por su unidad automáticamente.

---

## Troubleshooting rápido

1. Columna SQL no encontrada: comprobar esquema
```
./vendor/bin/sail php artisan tinker --execute "DB::select('DESCRIBE unidad_transportes')"
```
2. Ruta no definida: listar rutas
```
./vendor/bin/sail php artisan route:list
```
3. Problemas con permisos (Spatie):
```
./vendor/bin/sail php artisan permission:cache-reset
```

---

## Comandos útiles

```bash
./vendor/bin/sail up -d
./vendor/bin/sail php artisan migrate --seed
./vendor/bin/sail php artisan route:list
./vendor/bin/sail php artisan test
```

---

## Contacto y próximos pasos

Para cambios estructurales (migraciones, CI, despliegue) abre un issue o contacta al equipo de desarrollo.

Puedo: añadir capturas, generar PDF del manual o crear una versión HTML navegable.

---

Archivo generado automáticamente: guía básica de uso por roles.
