## Resumen operativo: tareas, backup y checklist

Documento corto para llevar el control de tareas, backups y verificación del proyecto.

## Estado rápido (hecho / pendiente)
- [x] Instalar Laravel Breeze (Livewire + Volt funcional) y Alpine
- [x] Generar `APP_KEY` y configurar `.env`
- [x] Migraciones básicas aplicadas (`artisan migrate`)
- [x] Añadida migración para `unidades_organizacionales`
- [x] Actualizado `User` model con campos del proyecto y `HasRoles` (Spatie)
- [x] Seeder `RolesPermissionsSeeder` creado y traducido a español
- [x] Seeder actualizado para crear unidad por defecto (`SDPDE`) y admin
- [x] Enum `rol` normalizado a 4 roles (Admin_General, Admin_Secretaria, Supervisor, Conductor)
- [ ] Añadir seeders para catálogos (tipos de combustible, tipos de vehículo, proveedores)
- [ ] Completar plantilla de registro (inputs HTML para todos los campos nuevos)
- [ ] Decidir estrategia final sobre columna `rol` vs Spatie (migrar a Spatie recomendado)
- [ ] Añadir pruebas unitarias e integración para seeders y registro
- [ ] Configurar CI (migraciones, seeders, tests)

## Objetivos inmediatos (próximas tareas)
1. Verificar en BD que roles, permisos, unidad `SDPDE` y usuario admin existen. (Tinker/DB)
2. Crear seeders para catálogos mínimos (tipos combustible, tipos vehículo, proveedores).
3. Completar formulario de registro Livewire (mostrar select de unidades, validar ci, apellidos, etc.).
4. Añadir tests: Seeder roles/permissions, test de registro (happy path + casos de fallo).
5. Decidir y aplicar estrategia para `rol` (mantener enum + Spatie sincronizado o migrar a Spatie como fuente única).

## Backup y recuperación (usando Spatie Laravel Backup)
Recomendado: usar `spatie/laravel-backup` para backups consistentes de BD y archivos.

1) Instalar (desde el contenedor Sail):

```bash
./vendor/bin/sail composer require spatie/laravel-backup
./vendor/bin/sail artisan vendor:publish --provider="Spatie\\Backup\\BackupServiceProvider" --tag="config"
```

2) Configurar `config/backup.php` y `.env` (ejemplo):

```env
# Opciones de disco (usar s3 o local)
BACKUP_DISK=local
BACKUP_NAME=gestor_combustible_backup
```

3) Hacer un backup manual (rápido):

```bash
./vendor/bin/sail artisan backup:run --only-db
```

```markdown
# README operativo — Tareas, backup y roadmap estratégico

Documento consolidado que reúne lo esencial del proyecto y enlaza los análisis técnicos (diagrama, MySQL, configuración) para tomar decisiones operativas rápidas.

## Qué contiene este README
- Estado actual y objetivos inmediatos
- Verificaciones rápidas en BD (comandos útiles)
- Plan de seeders y formulario de registro
- Estrategia para `rol` vs Spatie (pasos concretos)
- Backup y recuperación (spatie/laravel-backup)
- Referencias a documentos de análisis (diagrama, MySQL, flujos)

---

## Estado rápido (hecho / pendiente)
- [x] Instalar Laravel Breeze (Livewire + Volt funcional) y Alpine
- [x] Generar `APP_KEY` y configurar `.env`
- [x] Migraciones básicas aplicadas (`artisan migrate`)
- [x] Añadida migración para `unidades_organizacionales`
- [x] Actualizado `app/Models/User.php` (campos del proyecto + `HasRoles` de Spatie)
- [x] Seeder `RolesPermissionsSeeder` creado y traducido a español
- [x] Seeder actualizado para crear unidad por defecto (`SDPDE`) y admin
- [x] Enum `rol` normalizado a 4 roles (Admin_General, Admin_Secretaria, Supervisor, Conductor)
- [ ] Añadir seeders para catálogos (tipos de combustible, tipos de vehículo, proveedores)
- [ ] Completar plantilla de registro Livewire (inputs HTML para todos los campos nuevos)
- [ ] Migración final / decisión sobre columna `rol` vs Spatie (migrar a Spatie recomendado)
- [ ] Añadir pruebas unitarias e integración para seeders y registro
- [ ] Configurar CI (migraciones, seeders, tests)

---

## Checklist de requisitos (extracto de la petición)
1. Consolidar README uniendo documentación técnica relevante — Done (este archivo).
2. Mantener una lista priorizada de tareas y próximos pasos — Done.
3. Incluir comandos rápidos y verificación en BD — Done (sección "Verificaciones rápidas").
4. Incluir estrategia de migración `rol` → Spatie y backups — Done.

Si falta algo en el alcance, indícalo y lo añado.

---

## Objetivos inmediatos y prioridades
1) Alta — Verificar que el seeder finalizó correctamente y que existen: roles, permisos, unidad `SDPDE` y usuario admin.
2) Alta — Implementar seeders de catálogos mínimos: `TiposCombustible`, `TiposVehiculo`, `Proveedores`.
3) Media — Completar formulario de registro Livewire (inputs + select de unidades desde BD).
4) Media — Crear tests básicos: seeder roles, registro (happy path + validaciones).
5) Baja — Planificar migración completa para que Spatie sea la única fuente de roles.

---

## Verificaciones rápidas en BD (comandos sugeridos)
Ejecutar desde el contenedor Sail (o local si tienes DB accesible). No ejecutes sin revisar el entorno.

Comprobar roles y permisos (Spatie):
```bash
./vendor/bin/sail artisan tinker --execute="\Spatie\Permission\Models\Role::all()->pluck('name')"
./vendor/bin/sail artisan tinker --execute="\Spatie\Permission\Models\Permission::all()->pluck('name')"
```

Comprobar unidad `SDPDE`:
```bash
./vendor/bin/sail artisan tinker --execute="\DB::table('unidades_organizacionales')->where('codigo_unidad','SDPDE')->first()"
```

Comprobar usuario admin y roles asignados (email por defecto del seeder):
```bash
./vendor/bin/sail artisan tinker --execute="\App\Models\User::where('email','admin@example.com')->with('roles')->first()"
```

Si prefieres, ejecuto estas comprobaciones y pego la salida.

---

## Seeders a crear (plan y ejemplo rápido)
Prioridad: `TiposCombustible` → `TiposVehiculo` → `Proveedores`.

Comando para crear seeder:
```bash
./vendor/bin/sail artisan make:seeder TiposCombustibleSeeder
```

Estructura mínima recomendada (ejemplo en el seeder):
- insertar filas con `nombre`, `codigo`, `activo` y `created_at`.

Ejecutar:
```bash
./vendor/bin/sail artisan db:seed --class=TiposCombustibleSeeder
```

---

## Formulario de registro Livewire — checklist de campos
- nombre, apellido_paterno, apellido_materno
- username
- ci (Cédula)
- telefono
- email
- id_unidad_organizacional (select cargado desde DB)
- rol (valor por defecto: `Conductor`) — validar `in:` con los 4 roles

Notas: Ya actualizamos las reglas y el estado en `resources/views/livewire/pages/auth/register.blade.php`, pero falta asegurar que los inputs y el select estén renderizados y que la lista de unidades se cargue desde DB.

---

## Estrategia para `rol` vs Spatie (plan concreto)
Recomendación: migración en 3 pasos para minimizar riesgo.

1) Corto plazo (sin interrupciones):
  - Mantener columna `rol` como `rol_legacy` o nullable.
  - Al crear/actualizar usuario, asegurarse de llamar a `$user->assignRole($rol)` para sincronizar Spatie.

2) Mediano plazo (migración controlada):
  - Crear un comando Artisan `roles:migrate-legacy` que:
    - Lea `users.rol` y asigne `Spatie` roles equivalentes si no existen.
    - Registre acciones en log y reporte de cambios.
  - Ejecutar en staging, revisar, luego en producción.

3) Largo plazo: eliminar `rol` legacy o renombrarla a `rol_legacy` y marcar nullable; depender únicamente de Spatie.

---

## Backup y recuperación (resumen operativo)
Usar `spatie/laravel-backup` y política mínima recomendada:

- Discos: `local` en staging; `s3` (o compatible) para producción.
- Incluir: base de datos, `storage/app/public`, `public/build` si hay activos que cambian en runtime.
- Retención: mantener 14 días completos, con rotación semanal en almacenamiento económico.
- Frecuencia: backups diarios de BD, snapshots semanales completos.

Instalación rápida (Sail):
```bash
./vendor/bin/sail composer require spatie/laravel-backup
./vendor/bin/sail artisan vendor:publish --provider="Spatie\\Backup\\BackupServiceProvider" --tag="config"
```

Ejecutar un backup de BD manual:
```bash
./vendor/bin/sail artisan backup:run --only-db
```

Programar en scheduler (ejemplo cron entry para ejecutar el scheduler cada 5 minutos):
```cron
*/5 * * * * cd /path/to/project && ./vendor/bin/sail artisan schedule:run >> /dev/null 2>&1
```

Notas importantes:
- Probar restauraciones en un entorno staging antes de confiar en la política.
- Asegurar que `DB_PASSWORD` y credenciales estén protegidas; no subir dumps a repositorios públicos.

---

## Operaciones rápidas (comandos más usados)
```bash
# Migraciones
./vendor/bin/sail artisan migrate --force

# Ejecutar seeders específicos
./vendor/bin/sail artisan db:seed --class=RolesPermissionsSeeder --ansi
./vendor/bin/sail artisan db:seed --class=TiposCombustibleSeeder

# Ejecutar todas las seeders
./vendor/bin/sail artisan db:seed

# Ejecutar tests
./vendor/bin/sail artisan test

# NPM / Vite
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

---

## Referencias y análisis (documentos relevantes en `recursos/`)
- `recursos/ANALISIS_DIAGRAMA_CORREGIDO.md` — correcciones del diagrama PlantUML y modelo de datos.
- `recursos/ANALISIS_MYSQL_OPTIMIZADO.md` — recomendaciones de MySQL: tipos, índices, particionado y triggers.
- `recursos/CONFIGURACION_LARAVEL.md` — guía de setup con Sail, services y comandos útiles.
- `recursos/DIAGRAMAS_FLUJO.md` y `recursos/FLUJOS_DETALLADOS.md` — flujos UX y procesos (registro, solicitudes, consumo, presupuestos).

---

## Registro de cambios (qué hicimos aquí)
- Se añadieron migraciones para `unidades_organizacionales` y para normalizar `rol`.
- Se actualizó `RolesPermissionsSeeder` para crear la unidad `SDPDE` y admin.
- Se añadió `HasRoles` al modelo `User`.
- Se actualizaron validaciones del registro Livewire para usar los 4 roles.

---

## Estado final y próximos pasos (acciones que puedo ejecutar ahora)
- Puedo ejecutar las comprobaciones DB y pegar los resultados aquí.
- Puedo crear e implementar los seeders de catálogos mínimos.
- Puedo completar la vista Livewire de registro (HTML + select de unidades).
- Puedo implementar el comando `roles:migrate-legacy` para sincronizar `rol` a Spatie.

Indica cuál de estas acciones quieres que ejecute ahora y la ejecutamos.

```
