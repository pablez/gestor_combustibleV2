# Usuarios de Prueba - Sistema de Gestión de Combustible

Este documento contiene las credenciales de los usuarios de prueba creados para el sistema.

## Contraseña por Defecto
**Contraseña:** `password123` (configurable en .env como `TEST_USERS_PASSWORD`)

## Usuarios Disponibles

### 1. Administrador General
- **Username:** `admin`
- **Email:** `admin@example.com`
- **Nombre:** Administrador Sistema
- **Rol:** Admin_General
- **Unidad:** DESPACHO DE LA GOBERNACIÓN
- **Permisos:** Todos los permisos del sistema

### 2. Administradora de Secretaría
- **Username:** `secretaria.admin`
- **Email:** `secretaria@gobernacion.bo`
- **Nombre:** María Elena Vargas Delgado
- **Rol:** Admin_Secretaria
- **Unidad:** RECURSOS HUMANOS
- **Permisos:** Gestión de usuarios, unidades organizacionales y solicitudes
- **Supervisados:** Supervisor de Transporte, Supervisor de Finanzas

### 3. Supervisor de Transporte
- **Username:** `supervisor.trans`
- **Email:** `supervisor.transporte@gobernacion.bo`
- **Nombre:** Carlos Roberto Mendoza Silva
- **Rol:** Supervisor
- **Unidad:** UNIDAD DE TRANSPORTE
- **Supervisor:** secretaria.admin
- **Supervisados:** Todos los conductores (conductor1, conductor2, conductor3)
- **Permisos:** Ver/editar/aprobar solicitudes, ver unidades

### 4. Supervisor de Finanzas
- **Username:** `supervisor.fin`
- **Email:** `supervisor.finanzas@gobernacion.bo`
- **Nombre:** Ana Beatriz Quispe Mamani
- **Rol:** Supervisor
- **Unidad:** UNIDAD DE FINANZAS
- **Supervisor:** secretaria.admin
- **Permisos:** Ver/editar/aprobar solicitudes, ver unidades

### 5-7. Conductores
#### Conductor 1
- **Username:** `conductor1`
- **Email:** `conductor1@gobernacion.bo`
- **Nombre:** Juan Pablo Rojas Fernández
- **CI:** 55667788
- **Teléfono:** 70556677

#### Conductor 2
- **Username:** `conductor2`
- **Email:** `conductor2@gobernacion.bo`
- **Nombre:** Miguel Ángel Torrez Choque
- **CI:** 99887766
- **Teléfono:** 70998877

#### Conductor 3
- **Username:** `conductor3`
- **Email:** `conductor3@gobernacion.bo`
- **Nombre:** Pedro Luis Condori Apaza
- **CI:** 44556677
- **Teléfono:** 70445566

**Todos los conductores:**
- **Rol:** Conductor
- **Unidad:** UNIDAD DE TRANSPORTE
- **Supervisor:** supervisor.trans
- **Permisos:** Crear/ver solicitudes propias, ver/crear despachos

## Estructura Organizacional

```
DESPACHO DE LA GOBERNACIÓN (Superior)
├── RECURSOS HUMANOS (Ejecutiva)
│   └── María Elena Vargas (Admin_Secretaria)
├── UNIDAD DE TRANSPORTE (Operativa)
│   ├── Carlos Roberto Mendoza (Supervisor)
│   ├── Juan Pablo Rojas (Conductor)
│   ├── Miguel Ángel Torrez (Conductor)
│   └── Pedro Luis Condori (Conductor)
└── UNIDAD DE FINANZAS (Ejecutiva)
    └── Ana Beatriz Quispe (Supervisor)
```

## Casos de Prueba Sugeridos

### Pruebas de Autenticación
1. Login con cada tipo de usuario
2. Verificar permisos según el rol
3. Probar acceso a diferentes secciones del sistema

### Pruebas de Jerarquía
1. Supervisores pueden ver/gestionar usuarios supervisados
2. Conductores solo ven sus propias solicitudes
3. Administradores tienen acceso completo

### Pruebas de Unidades Organizacionales
1. Usuarios asignados a unidades correctas
2. Jerarquía organizacional funcional
3. Filtrado por unidad organizacional

## Comando para Re-ejecutar Seeders

Si necesitas recrear los usuarios de prueba:

```bash
# Ejecutar solo el seeder de usuarios de prueba
./vendor/bin/sail artisan db:seed --class=UsersTestSeeder

# O ejecutar todos los seeders
./vendor/bin/sail artisan db:seed
```

## Variables de Entorno

Para personalizar los usuarios de prueba, puedes configurar en tu archivo `.env`:

```
TEST_USERS_PASSWORD=tu_password_personalizado
ADMIN_USER_PASSWORD=password_admin
ADMIN_USER_CI=12345678
ADMIN_USER_TELEFONO=70123456
```