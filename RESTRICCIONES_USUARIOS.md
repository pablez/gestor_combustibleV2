# Restricciones de Usuarios por Roles - Sistema de Gestión de Combustible

Este documento describe las restricciones implementadas en el listado de usuarios según el rol del usuario autenticado.

## Resumen de Restricciones

| Rol | Puede Ver Usuarios | Puede Crear | Puede Editar/Eliminar | Filtros Disponibles |
|-----|-------------------|-------------|----------------------|-------------------|
| **Admin General** | Todos los usuarios | ✅ Sí | ✅ Todos | Todas las unidades y roles |
| **Admin Secretaría** | Solo de su unidad organizacional | ✅ Sí | ✅ Solo de su unidad | Solo su unidad |
| **Supervisor** | Solo conductores bajo su supervisión | ❌ No | ✅ Solo sus conductores | Solo su unidad, solo rol Conductor |
| **Conductor** | Ningún usuario | ❌ No | ❌ No | Sin acceso a la gestión |

## Detalles por Rol

### 1. Admin General
- **Acceso:** Completo a todos los usuarios del sistema
- **Restricciones:** Ninguna
- **Filtros:** Puede filtrar por cualquier unidad organizacional y rol
- **Acciones:** Ver, crear, editar, eliminar cualquier usuario

### 2. Admin Secretaría
- **Acceso:** Solo usuarios de su misma unidad organizacional
- **Restricciones:** 
  - Solo ve usuarios donde `id_unidad_organizacional` = su propia unidad
  - Si no tiene unidad asignada, no ve ningún usuario
- **Filtros:** Solo puede filtrar por su propia unidad
- **Acciones:** Ver, crear, editar, eliminar usuarios de su unidad

### 3. Supervisor
- **Acceso:** Solo conductores bajo su supervisión directa
- **Restricciones:**
  - Solo ve usuarios donde `id_supervisor` = su propio ID
  - Solo usuarios con rol "Conductor"
  - Solo usuarios de su misma unidad organizacional
- **Filtros:** Solo su unidad, solo rol "Conductor"
- **Acciones:** Ver, editar, eliminar solo sus conductores supervisados

### 4. Conductor
- **Acceso:** Sin acceso al listado de usuarios
- **Restricciones:** No puede ver la gestión de usuarios
- **Filtros:** N/A
- **Acciones:** Sin acceso a la funcionalidad

## Validaciones Implementadas

### En el Componente UserIndex.php

1. **`applyRoleBasedRestrictions()`**
   - Aplica filtros automáticos en la consulta según el rol
   - Se ejecuta antes de aplicar filtros de búsqueda

2. **`getAvailableUnidades()`**
   - Retorna solo las unidades que el usuario puede filtrar
   - Admin General: todas las unidades
   - Admin Secretaría/Supervisor: solo su unidad
   - Otros roles: colección vacía

3. **`getAvailableRoles()`**
   - Retorna solo los roles que el usuario puede filtrar
   - Admin General/Secretaría: todos los roles
   - Supervisor: solo rol "Conductor"
   - Otros roles: colección vacía

4. **`canManageUser()`**
   - Verifica si el usuario actual puede gestionar un usuario específico
   - Se usa en deleteUser() y en la vista para mostrar/ocultar acciones

### En la Vista user-index.blade.php

1. **Botón "Crear Usuario"**
   - Solo visible para Admin General y Admin Secretaría

2. **Acciones por Fila (Editar/Eliminar)**
   - Se evalúa `canManage` para cada usuario mostrado
   - Solo se muestran las acciones si el usuario actual tiene permisos

3. **Filtros**
   - Los selectores de unidad y rol solo muestran opciones permitidas

## Casos de Uso Ejemplo

### Escenario 1: Admin Secretaría (María Elena - RRHH)
```
- Ve: Todos los usuarios de la unidad RECURSOS HUMANOS
- Puede crear: Nuevos usuarios para RRHH
- Puede editar: Solo usuarios de RRHH
- Filtros: Solo puede filtrar por RRHH
```

### Escenario 2: Supervisor de Transporte (Carlos Roberto)
```
- Ve: Solo conductores1, conductor2, conductor3 (sus supervisados)
- Puede crear: No puede crear usuarios
- Puede editar: Solo sus 3 conductores
- Filtros: Solo UNIDAD DE TRANSPORTE, solo rol Conductor
```

### Escenario 3: Conductor (Juan Pablo)
```
- Ve: Nada (sin acceso al módulo)
- Puede crear: No
- Puede editar: No
- Acceso: Redirigido o sin acceso al listado
```

## Verificación de Funcionamiento

Para probar las restricciones:

1. **Login como Admin General (admin)**
   ```
   Email: admin@example.com
   Password: password
   Resultado: Ve todos los usuarios
   ```

2. **Login como Admin Secretaría (secretaria.admin)**
   ```
   Email: secretaria@example.com  
   Password: password
   Resultado: Ve solo usuarios de RECURSOS HUMANOS
   ```

3. **Login como Supervisor (supervisor.trans)**
   ```
   Email: supervisor.transporte@example.com
   Password: password
   Resultado: Ve solo conductor1, conductor2, conductor3
   ```

4. **Login como Conductor (conductor1)**
   ```
   Email: conductor1@example.com
   Password: password
   Resultado: Sin acceso al módulo de usuarios
   ```

## Seguridad Adicional

Las restricciones se aplican tanto en:
- **Nivel de Base de Datos:** Consultas filtradas automáticamente
- **Nivel de Interfaz:** Botones y acciones ocultas según permisos
- **Nivel de Validación:** Verificación en métodos de acción (delete, edit)

Esto asegura que incluso si alguien intenta manipular la URL o hacer peticiones directas, las restricciones se mantienen.