# KPIs del Dashboard - Sistema de Gestión de Combustible

Este documento describe los KPIs (Indicadores Clave de Rendimiento) implementados en el dashboard, que respetan las restricciones por roles de usuario.

## 📊 KPIs Implementados

### 1. **Métricas Principales**
- **Total de Usuarios:** Cantidad total de usuarios visibles según el rol
- **Usuarios Activos:** Usuarios con estado `activo = true`
- **Usuarios Inactivos:** Usuarios con estado `activo = false`
- **Usuarios Supervisados:** Cantidad específica según el rol del usuario

### 2. **Distribuciones**
- **Por Roles:** Gráfico de barras con distribución de usuarios por rol
- **Por Unidad Organizacional:** Distribución por unidades con códigos y nombres
- **Usuarios Recientes:** Últimos usuarios registrados en los últimos 7 días

### 3. **Acceso Rápido**
- **Gestionar Usuarios:** Enlace directo al listado
- **Crear Usuario:** Botón para crear nuevos usuarios (según permisos)
- **Gestión de Unidades:** Enlaces a funcionalidades relacionadas

## 🔒 Restricciones por Rol

### Admin General
```php
// Ve TODOS los usuarios del sistema
$baseQuery = User::where('id', '!=', $currentUser->id);
// Sin restricciones adicionales
```
**KPIs disponibles:**
- ✅ Total usuarios: Todos menos él mismo
- ✅ Distribución por roles: Todos los roles
- ✅ Distribución por unidades: Todas las unidades
- ✅ Usuarios supervisados: Total con supervisión

### Admin Secretaría
```php
// Ve solo usuarios de su unidad organizacional
$baseQuery->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
```
**KPIs disponibles:**
- ✅ Total usuarios: Solo de su unidad
- ✅ Distribución por roles: Roles en su unidad
- ✅ Distribución por unidades: Solo su unidad
- ✅ Usuarios supervisados: Con supervisión en su unidad

### Supervisor
```php
// Ve solo conductores bajo su supervisión
$baseQuery->where('id_supervisor', $currentUser->id)
          ->whereHas('roles', function ($q) {
              $q->where('name', 'Conductor');
          })
          ->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
```
**KPIs disponibles:**
- ✅ Total usuarios: Solo sus conductores
- ✅ Distribución por roles: Solo "Conductor"
- ✅ Distribución por unidades: Solo su unidad
- ✅ Usuarios supervisados: Conductores bajo su cargo

### Conductor
```php
// Sin acceso a gestión de usuarios
$baseQuery->where('id', -1); // Resultado vacío
```
**KPIs disponibles:**
- ❌ Sin acceso al dashboard de usuarios
- ❌ KPIs no disponibles para este rol

## 🎨 Elementos Visuales

### Tarjetas de Métricas
- **Azul:** Total de usuarios
- **Verde:** Usuarios activos
- **Rojo:** Usuarios inactivos  
- **Púrpura:** Usuarios supervisados

### Gráficos de Distribución
- **Barras de progreso:** Porcentajes por rol
- **Colores por rol:**
  - Admin General: Rojo
  - Admin Secretaría: Azul
  - Supervisor: Amarillo
  - Conductor: Verde

### Mensaje Contextual
El dashboard muestra un mensaje personalizado según el rol:

```php
@if(auth()->user()->hasRole('Admin_General'))
    "Panel de Administración General"
@elseif(auth()->user()->hasRole('Admin_Secretaria'))
    "Panel de Administración - [Unidad]"
@elseif(auth()->user()->hasRole('Supervisor'))
    "Panel de Supervisión - [Unidad]"
@elseif(auth()->user()->hasRole('Conductor'))
    "Panel de Conductor"
@endif
```

## 🔄 Actualización de Datos

Los KPIs se cargan dinámicamente en tiempo real usando el componente Livewire `DashboardKpis`, aplicando las mismas restricciones que el listado de usuarios.

### Métodos Principales
1. **`applyRoleBasedRestrictions()`** - Aplica filtros según rol
2. **`getUsersByRole()`** - Distribución por roles
3. **`getUsersByUnidad()`** - Distribución por unidades
4. **`getRecentUsers()`** - Usuarios recientes
5. **`getSupervisedUsersCount()`** - Conteo de supervisados

## 📈 Casos de Uso Ejemplo

### Escenario: Admin Secretaría (María Elena - RRHH)
```
KPIs mostrados:
- Total usuarios: 2 (solo de RRHH)
- Activos: 2
- Inactivos: 0
- Roles: Admin Secretaría (1), Supervisor (1)
- Unidades: Solo RECURSOS HUMANOS
- Supervisados: 1 (supervisor de finanzas)
```

### Escenario: Supervisor de Transporte (Carlos Roberto)
```
KPIs mostrados:
- Total usuarios: 3 (sus conductores)
- Activos: 3
- Inactivos: 0
- Roles: Solo Conductor (3)
- Unidades: Solo UNIDAD DE TRANSPORTE
- Supervisados: 3 (conductor1, conductor2, conductor3)
```

## 🚀 Instalación y Uso

1. **El componente ya está implementado** en `app/Livewire/DashboardKpis.php`
2. **La vista está creada** en `resources/views/livewire/dashboard-kpis.blade.php`
3. **El dashboard principal** incluye el componente con `@livewire('dashboard-kpis')`

### Para Probar:
```bash
# Acceder como diferentes usuarios
# Admin General
http://localhost/dashboard (admin@example.com)

# Admin Secretaría
http://localhost/dashboard (secretaria@example.com)

# Supervisor
http://localhost/dashboard (supervisor.transporte@example.com)

# Conductor
http://localhost/dashboard (conductor1@example.com)
```

Cada usuario verá KPIs diferentes según sus restricciones de rol, manteniendo la consistencia con el sistema de permisos implementado.

## 📊 Performance

Los KPIs utilizan consultas optimizadas:
- **Consultas separadas** para cada métrica
- **Índices en campos** de filtrado (roles, unidades)
- **Cache potencial** para futuras mejoras
- **Lazy loading** de relaciones cuando es necesario

El dashboard mantiene el rendimiento mientras proporciona información relevante y segura según el contexto del usuario.