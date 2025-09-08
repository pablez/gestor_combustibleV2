# Sistema de Gestión de Combustible - Gobernación de Cochabamba

## Descripción del Proyecto

Sistema integral para la gestión y control de combustible de la flota vehicular de la Gobernación de Cochabamba, Bolivia. Desarrollado con tecnologías modernas para garantizar eficiencia, seguridad y trazabilidad en todos los procesos.

## Stack Tecnológico

- **Backend**: Laravel 12
- **Autenticación**: Laravel Breeze
- **Frontend**: Livewire
- **Roles y Permisos**: Spatie Laravel Permission
- **Contenedorización**: Laravel Sail
- **Base de Datos**: MySQL 8.0
- **Servidor Web**: Nginx (via Sail)

## Arquitectura de la Base de Datos

### Entidades Principales

#### 1. Gestión de Usuarios y Autenticación
- **Usuario**: Gestión completa de usuarios del sistema
- **SolicitudAprobacionUsuario**: Flujo de aprobación para nuevos usuarios
- **CodigoRegistro**: Códigos únicos para registro controlado
- **RegistroAuditoria**: Trazabilidad de todas las acciones

#### 2. Gestión de Combustible
- **SolicitudCombustible**: Solicitudes de combustible por unidad
- **DespachoCombustible**: Registro de entregas de combustible
- **ConsumoCombustible**: Control de consumo y rendimiento

#### 3. Gestión de Flota
- **UnidadTransporte**: Información de vehículos
- **TipoVehiculo**: Categorización de vehículos
- **TipoCombustible**: Tipos de combustible disponibles

#### 4. Gestión Presupuestaria
- **Presupuesto**: Control presupuestario por unidad organizacional
- **CategoriaProgramatica**: Categorías programáticas del gobierno
- **FuenteOrganismoFinanciero**: Fuentes de financiamiento

#### 5. Gestión Organizacional
- **UnidadOrganizacional**: Estructura organizacional de la gobernación
- **Proveedor**: Proveedores de combustible
- **TipoServicioProveedor**: Tipos de servicios de proveedores

## Flujos Principales del Sistema

### 1. Flujo de Registro de Usuario

#### Paso 1: Generación de Código de Registro
```
Administrador/Supervisor → Genera código único → CodigoRegistro
- Código único de 15 caracteres
- Fecha de vigencia
- Asociado al usuario creador
```

#### Paso 2: Pre-registro del Usuario
```
Usuario Nuevo → Completa formulario con:
- Datos personales (nombre, username)
- Código de registro proporcionado
- Unidad organizacional de destino
- Rol solicitado
```

#### Paso 3: Validación y Registro Temporal
```
Sistema valida:
- Código de registro válido y vigente
- Username único
- Datos completos
→ Crea registro en SolicitudAprobacionUsuario
→ Usuario queda en estado 'pendiente'
```

#### Paso 4: Notificación WhatsApp
```
Sistema automático:
- Identifica al usuario que generó el código
- Envía mensaje WhatsApp con:
  * Notificación de uso del código
  * Datos del solicitante
  * Link directo a página de aprobación
  * Código de solicitud para tracking
```

#### Paso 5: Proceso de Aprobación
```
Usuario Aprobador (vía WhatsApp link):
- Accede a página de aprobación
- Revisa datos del solicitante
- Puede:
  * Aprobar → Usuario activo con rol asignado
  * Rechazar → Solicitud rechazada con observaciones
  * Modificar rol antes de aprobar
```

### 2. Flujo de Solicitud de Combustible

#### Paso 1: Creación de Solicitud
```
Conductor/Usuario autorizado:
- Selecciona unidad de transporte asignada
- Especifica:
  * Cantidad de litros solicitados
  * Kilometraje actual del vehículo
  * Kilómetros a recorrer
  * Motivo del viaje/actividad
  * Categoría programática
  * Fuente de financiamiento
→ Estado: 'Pendiente'
```

#### Paso 2: Validaciones Automáticas
```
Sistema verifica:
- Presupuesto disponible en categoría programática
- Capacidad del tanque del vehículo
- Historial de consumo (rendimiento promedio)
- Saldo actual de combustible en el vehículo
```

#### Paso 3: Aprobación por Supervisor
```
Supervisor revisa:
- Justificación del motivo
- Disponibilidad presupuestaria
- Rendimiento histórico del vehículo
- Necesidad operativa
→ Aprueba o Rechaza con observaciones
```

#### Paso 4: Despacho de Combustible
```
Usuario Despachador:
- Registra despacho real:
  * Proveedor utilizado
  * Litros despachados (puede diferir de lo solicitado)
  * Costo total
  * Número de vale/factura
  * Observaciones del despacho
→ Estado: 'Despachada'
→ Actualiza presupuesto
```

### 3. Flujo de Control de Consumo

#### Registro de Consumo
```
Conductor (al finalizar recorrido):
- Registra:
  * Kilometraje inicial del viaje
  * Kilometraje final del viaje
  * Observaciones sobre el rendimiento
→ Sistema calcula rendimiento automáticamente
→ Actualiza kilometraje actual del vehículo
```

#### Análisis de Rendimiento
```
Sistema genera alertas automáticas:
- Rendimiento anómalo (muy alto/bajo)
- Inconsistencias en kilometrajes
- Consumo excesivo comparado con histórico
→ Notifica a supervisores para revisión
```

### 4. Flujo de Control Presupuestario

#### Seguimiento en Tiempo Real
```
Sistema mantiene actualizado:
- Presupuesto inicial por categoría programática
- Presupuesto actual (descontando comprometido)
- Total gastado acumulado
- Porcentaje de ejecución
```

#### Alertas Presupuestarias
```
Configuración de alertas automáticas:
- 70% de ejecución presupuestaria
- 85% de ejecución presupuestaria
- 95% de ejecución presupuestaria
→ Notifica a administradores y supervisores
```

### 5. Flujo de Auditoría y Trazabilidad

#### Registro Automático
```
Todas las acciones quedan registradas:
- Usuario que realiza la acción
- Fecha y hora exacta
- Tipo de acción realizada
- Tabla y registro afectado
- IP de origen
- Datos antes y después del cambio
```

#### Reportes de Auditoría
```
Generación automática de reportes:
- Consumo por vehículo/conductor
- Ejecución presupuestaria por unidad
- Rendimiento promedio de flota
- Alertas y excepciones detectadas
```

## Roles y Permisos del Sistema

### Admin_General
- Control total del sistema
- Gestión de usuarios y códigos de registro
- Configuración de presupuestos
- Acceso a todos los reportes y auditoría

### Admin_Secretaria
- Gestión de usuarios de su secretaría
- Aprobación de solicitudes de combustible
- Reportes de su unidad organizacional
- Gestión de proveedores

### Supervisor
- Aprobación de solicitudes de su área
- Generación de códigos de registro
- Reportes de vehículos bajo su supervisión
- Gestión de conductores asignados

### Conductor
- Creación de solicitudes de combustible
- Registro de consumo de combustible
- Consulta de historial de sus vehículos
- Actualización de kilometrajes

### Operator
- Despacho de combustible
- Registro de entregas
- Gestión de proveedores
- Reportes operativos

## Configuración del Proyecto

### Requisitos del Sistema
- Docker Desktop
- PHP 8.2+
- Composer
- Node.js 18+
- Git

### Instalación

1. **Clonar el repositorio**
```bash
git clone [url-del-repositorio]
cd sistema-combustible-gobernacion
```

2. **Configurar Laravel Sail**
```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. **Configurar base de datos en .env**
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sistema_combustible
DB_USERNAME=sail
DB_PASSWORD=password
```

4. **Levantar el entorno con Sail**
```bash
./vendor/bin/sail up -d
```

5. **Ejecutar migraciones y seeders**
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

6. **Instalar dependencias de frontend**
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### Configuración de WhatsApp API

Para el envío de notificaciones WhatsApp, configurar en `.env`:
```env
WHATSAPP_API_URL=https://api.whatsapp.com/send
WHATSAPP_API_TOKEN=tu-token-aqui
WHATSAPP_PHONE_COUNTRY_CODE=591
```

## Estructura de Directorios

```
proyecto/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   ├── Combustible/
│   │   ├── Admin/
│   │   └── Reportes/
│   ├── Livewire/
│   │   ├── Auth/
│   │   ├── Solicitudes/
│   │   ├── Despachos/
│   │   └── Reportes/
│   ├── Models/
│   ├── Services/
│   └── Notifications/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── livewire/
│   │   ├── auth/
│   │   └── layouts/
│   └── js/
└── routes/
```

## Consideraciones de Seguridad

1. **Autenticación Multi-Factor**: Implementar 2FA para roles administrativos
2. **Encriptación**: Datos sensibles encriptados en base de datos
3. **Validación**: Validación exhaustiva en frontend y backend
4. **Auditoría**: Registro completo de todas las acciones críticas
5. **Backup**: Respaldos automáticos diarios de la base de datos

## Testing

### Pruebas Unitarias
```bash
./vendor/bin/sail artisan test
```

### Pruebas de Integración
```bash
./vendor/bin/sail artisan test --group=integration
```

### Pruebas de Rendimiento
```bash
./vendor/bin/sail artisan test --group=performance
```

## Mantenimiento

### Backup Diario
```bash
./vendor/bin/sail artisan db:backup
```

### Limpieza de Logs
```bash
./vendor/bin/sail artisan log:clear
```

### Optimización
```bash
./vendor/bin/sail artisan optimize
```

## Soporte y Contacto

Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo:
- Email: [email-soporte]
- WhatsApp: [numero-soporte]
- Portal de incidencias: [url-portal]

---

**Versión**: 1.0.0  
**Última actualización**: Septiembre 2025  
**Desarrollado para**: Gobernación de Cochabamba, Bolivia
