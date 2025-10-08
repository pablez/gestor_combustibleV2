# 🛣️ Sistema de Rutas - Gestor de Combustible V2

## 📋 **Rutas Organizadas por Módulos**

### 🏠 **Dashboard Principal**
- `GET /dashboard` → Vista principal del dashboard
- `POST /profile/photo` → Subida de foto de perfil

---

### 📊 **KPIs y Reportes**
- `GET /kpis/dashboard` → Dashboard general de KPIs  
- `GET /kpis/vehiculos` → KPIs específicos de vehículos
- `GET /kpis/users` → KPIs específicos de usuarios

**Componentes Livewire:**
- `App\Livewire\DashboardKpis`
- `App\Livewire\Kpis\VehiculosKpis`
- `App\Livewire\Kpis\UsersKpis`

---

### 🏢 **Unidades Organizacionales**
- `GET /unidades/` → Lista de unidades organizacionales
- `GET /unidades/create` → Crear nueva unidad
- `GET /unidades/{id}/edit` → Editar unidad específica
- `GET /unidades/{id}` → Ver detalle de unidad

**Componentes Livewire:**
- `App\Livewire\Unidades\Index`
- `App\Livewire\Unidades\Create`
- `App\Livewire\Unidades\Edit`
- `App\Livewire\Unidades\Show`

---

### 👥 **Gestión de Usuarios**
- `GET /users/dashboard` → Dashboard de gestión de usuarios
- `GET /users/` → Lista de usuarios
- `GET /users/create` → Crear nuevo usuario
- `GET /users/{id}/edit` → Editar usuario específico
- `GET /users/{id}` → Ver perfil de usuario

**Componentes Livewire:**
- `App\Livewire\DashboardUser`
- `App\Livewire\Users\UserIndex`
- `App\Livewire\Users\UserCreate`
- `App\Livewire\Users\UserEdit`
- `App\Livewire\Users\UserShow`

---

### 🚗 **Tipos de Vehículos**
- `GET /tipos-vehiculo/` → Lista de tipos de vehículos

**Componentes Livewire:**
- `App\Livewire\TipoVehiculo\Index`
- `App\Livewire\TipoVehiculo\Create` (Modal)
- `App\Livewire\TipoVehiculo\Edit` (Modal)

**Nota:** Create y Edit se manejan mediante modales flotantes integrados en la vista Index.

---

### 🚚 **Unidades de Transporte**
- `GET /unidades-transporte/` → Lista de unidades de transporte

**Componentes Livewire:**
- `App\Livewire\UnidadTransporte\Index`

---

### 📝 **Solicitudes de Combustible**
- `GET /solicitudes/` → Lista de solicitudes
- `GET /solicitudes/create` → Crear nueva solicitud

**Componentes Livewire:**
- `App\Livewire\Solicitud\Index`
- `App\Livewire\Solicitud\Create`

---

### 📂 **Categorías Programáticas**
- `GET /categorias-programaticas/` → Lista de categorías
- `GET /categorias-programaticas/create` → Crear nueva categoría

**Componentes Livewire:**
- `App\Livewire\CategoriaProgramatica\Index`
- `App\Livewire\CategoriaProgramatica\Create`

---

### 💰 **Fuentes de Organismo Financiero**
- `GET /fuentes-organismo-financiero/` → Lista de fuentes
- `GET /fuentes-organismo-financiero/create` → Crear nueva fuente

**Componentes Livewire:**
- `App\Livewire\FuenteOrganismoFinanciero\Index`
- `App\Livewire\FuenteOrganismoFinanciero\Create`

---

### 🔄 **Rutas de Compatibilidad (Legacy)**
- `GET /tipos-vehiculo` → Redirige a tipos-vehiculo.index
- `GET /unidades` → Redirige a unidades.index  
- `GET /users` → Redirige a users.index

---

## 🔐 **Control de Acceso por Permisos**

### **Rutas Protegidas por Middleware:**
- Todas las rutas están protegidas por `auth` middleware
- Verificación de permisos específicos usando Spatie Laravel Permission
- Roles soportados: `Admin_General`, `Admin_Secretaria`, `Supervisor`, `Conductor`

### **Permisos por Módulo:**
- **Unidades:** `unidades.ver`, `unidades.crear`, `unidades.editar`
- **Usuarios:** `usuarios.ver`, `usuarios.crear`, `usuarios.editar`
- **Solicitudes:** `solicitudes.ver`, `solicitudes.crear`, `solicitudes.aprobar`
- **Vehículos:** Acceso basado en permisos de unidades

---

## 🎯 **Características Especiales**

### **Navegación Mejorada:**
- Menús desplegables organizados por módulos
- Navegación con `wire:navigate` para SPA behavior
- Indicadores de rutas activas
- Responsive design para móviles

### **Sistema de Modales:**
- Tipos de Vehículos usa modales flotantes
- Alpine.js para interacciones dinámicas
- Componente `<x-livewire-modal>` personalizado

### **URLs Amigables:**
- Prefijos organizados por módulo
- Nombres de rutas consistentes
- Soporte para parámetros dinámicos

---

## 🚀 **Comandos Útiles**

```bash
# Ver todas las rutas registradas
php artisan route:list

# Ver rutas específicas de un módulo
php artisan route:list | grep "tipos-vehiculo"

# Limpiar cache de rutas
php artisan route:clear

# Verificar sintaxis de rutas
php -l routes/web.php
```

---

## 📱 **Acceso Móvil**

Todas las rutas están optimizadas para:
- ✅ Navegación responsive
- ✅ Menús desplegables en móviles  
- ✅ Modales adaptativos
- ✅ Carga rápida con Livewire navigation

---

## 🔧 **Próximas Expansiones**

**Rutas Pendientes a Implementar:**
- `/reportes/` → Sistema de reportes
- `/mantenimiento/` → Gestión de mantenimiento
- `/combustible/` → Gestión directa de combustible
- `/api/` → API REST para integraciones externas