# Análisis y Recomendaciones para Sistema de Combustible - MySQL Optimizado

## 📊 Análisis del Diagrama PlantUML

### Entidades Identificadas y Relaciones

#### **Entidades Principales (17 tablas)**
1. **Gestión de Usuarios**: Usuario, SolicitudAprobacionUsuario, CodigoRegistro, RegistroAuditoria
2. **Gestión de Combustible**: SolicitudCombustible, DespachoCombustible, ConsumoCombustible
3. **Gestión de Flota**: UnidadTransporte, TipoCombustible, TipoVehiculo
4. **Gestión Organizacional**: UnidadOrganizacional, Proveedor, TipoServicioProveedor
5. **Gestión Presupuestaria**: Presupuesto, CategoriaProgramatica, FuenteOrganismoFinanciero

#### **Relaciones Críticas Identificadas**
- **1:N Usuario → SolicitudCombustible** (Un usuario puede hacer múltiples solicitudes)
- **1:N UnidadTransporte → ConsumoCombustible** (Un vehículo tiene múltiples registros de consumo)
- **1:1 SolicitudCombustible → DespachoCombustible** (Cada solicitud aprobada tiene un despacho)
- **M:N Usuario → Usuario** (Relación de supervisión jerárquica)

## 🎯 Recomendaciones Específicas para MySQL

### 1. **Optimización de Tipos de Datos**

#### ❌ **Problemas Actuales en el SQL**
```sql
-- Menos eficiente
`id_usuario` int(11) NOT NULL AUTO_INCREMENT

-- Campos sin índices en consultas frecuentes
`estado_solicitud` ENUM('Pendiente','Aprobada','Rechazada','Despachada')
```

#### ✅ **Soluciones Recomendadas**
```sql
-- Usar BIGINT UNSIGNED para IDs (más eficiente y escalable)
`id_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT

-- Crear índices compuestos para consultas frecuentes
CREATE INDEX `idx_solicitud_estado_fecha_usuario` ON `solicitudes_combustible` 
(`estado_solicitud`, `fecha_solicitud`, `id_usuario_solicitante`);

-- Usar DECIMAL con precisión específica para montos
`costo_total` DECIMAL(12,2) NOT NULL COMMENT 'Bolivianos con 2 decimales'
`cantidad_litros` DECIMAL(8,3) NOT NULL COMMENT 'Litros con 3 decimales de precisión'

-- Optimizar campos de texto
`numero_vale` VARCHAR(30) NOT NULL COMMENT 'Suficiente para números de vale'
`placa` VARCHAR(15) NOT NULL COMMENT 'Placas bolivianas formato ABC-1234'
```

### 2. **Estrategia de Particionamiento**

```sql
-- Particionar tabla de auditoría por fecha (datos crecen rápidamente)
CREATE TABLE `registro_auditoria` (
  `id_registro_auditoria` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` BIGINT UNSIGNED NOT NULL,
  `fecha_hora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accion_realizada` VARCHAR(50) NOT NULL,
  `tabla_afectada` VARCHAR(50) NOT NULL,
  `registro_afectado` JSON NOT NULL,
  `ip_origen` VARCHAR(45),
  PRIMARY KEY (`id_registro_auditoria`, `fecha_hora`),
  KEY `idx_usuario_fecha` (`id_usuario`, `fecha_hora`),
  KEY `idx_tabla_fecha` (`tabla_afectada`, `fecha_hora`)
) ENGINE=InnoDB
PARTITION BY RANGE (YEAR(fecha_hora)) (
  PARTITION p2024 VALUES LESS THAN (2025),
  PARTITION p2025 VALUES LESS THAN (2026),
  PARTITION p2026 VALUES LESS THAN (2027),
  PARTITION p_future VALUES LESS THAN MAXVALUE
);

-- Particionar solicitudes de combustible por año fiscal
CREATE TABLE `solicitudes_combustible` (
  -- ... campos existentes ...
  `anio_fiscal` YEAR NOT NULL DEFAULT (YEAR(CURDATE())),
  PRIMARY KEY (`id_solicitud`, `anio_fiscal`),
  KEY `idx_estado_anio` (`estado_solicitud`, `anio_fiscal`)
) ENGINE=InnoDB
PARTITION BY RANGE (anio_fiscal) (
  PARTITION p2024 VALUES LESS THAN (2025),
  PARTITION p2025 VALUES LESS THAN (2026),
  PARTITION p2026 VALUES LESS THAN (2027),
  PARTITION p_future VALUES LESS THAN MAXVALUE
);
```

### 3. **Índices Optimizados Basados en Consultas Frecuentes**

```sql
-- Índices para dashboard y reportes frecuentes
CREATE INDEX `idx_solicitud_dashboard` ON `solicitudes_combustible` 
(`id_usuario_solicitante`, `estado_solicitud`, `fecha_solicitud` DESC);

CREATE INDEX `idx_vehiculo_consumo` ON `consumos_combustible` 
(`id_unidad_transporte`, `fecha_registro` DESC, `validado`);

CREATE INDEX `idx_presupuesto_actual` ON `presupuestos` 
(`id_unidad_organizacional`, `anio_fiscal`, `activo`);

-- Índice para búsquedas de vehículos por placa
CREATE INDEX `idx_vehiculo_placa_activo` ON `unidades_transporte` 
(`placa`, `estado_operativo`);

-- Índice para jerarquía de usuarios (supervisión)
CREATE INDEX `idx_usuario_jerarquia` ON `usuarios` 
(`id_supervisor`, `activo`, `rol`);

-- Índice para códigos de registro activos
CREATE INDEX `idx_codigo_vigente` ON `codigos_registro` 
(`vigente_hasta`, `usado`, `codigo`);
```

### 4. **Campos Calculados y Columnas Generadas**

```sql
-- Optimizar cálculos frecuentes con columnas generadas
ALTER TABLE `consumos_combustible` 
ADD COLUMN `kilometros_recorridos` INT GENERATED ALWAYS AS 
(`kilometraje_fin` - `kilometraje_inicial`) STORED,
ADD COLUMN `rendimiento_km_por_litro` DECIMAL(6,2) GENERATED ALWAYS AS 
(CASE WHEN `litros_cargados` > 0 THEN (`kilometraje_fin` - `kilometraje_inicial`) / `litros_cargados` ELSE 0 END) STORED;

-- Porcentaje de ejecución presupuestaria
ALTER TABLE `presupuestos` 
ADD COLUMN `porcentaje_ejecutado` DECIMAL(5,2) GENERATED ALWAYS AS 
(CASE WHEN `presupuesto_inicial` > 0 THEN (`total_gastado` / `presupuesto_inicial`) * 100 ELSE 0 END) STORED,
ADD COLUMN `saldo_disponible` DECIMAL(14,2) GENERATED ALWAYS AS 
(`presupuesto_inicial` - `total_gastado` - `total_comprometido`) STORED;

-- Crear índices en columnas calculadas
CREATE INDEX `idx_rendimiento_vehiculo` ON `consumos_combustible` 
(`id_unidad_transporte`, `rendimiento_km_por_litro`);

CREATE INDEX `idx_presupuesto_estado` ON `presupuestos` 
(`porcentaje_ejecutado`, `activo`);
```

### 5. **Triggers Optimizados para Auditoría**

```sql
DELIMITER $$

-- Trigger optimizado para auditoría (solo cambios significativos)
CREATE TRIGGER `tr_solicitud_cambio_estado` 
AFTER UPDATE ON `solicitudes_combustible`
FOR EACH ROW 
BEGIN
  -- Solo auditar cambios de estado importantes
  IF OLD.estado_solicitud != NEW.estado_solicitud THEN
    INSERT INTO `registro_auditoria` (
      `id_usuario`, `accion_realizada`, `tabla_afectada`, 
      `registro_afectado`, `ip_origen`
    ) VALUES (
      COALESCE(NEW.id_usuario_aprobador, NEW.id_usuario_solicitante),
      CONCAT('CAMBIO_ESTADO: ', OLD.estado_solicitud, ' → ', NEW.estado_solicitud),
      'solicitudes_combustible',
      JSON_OBJECT(
        'id_solicitud', NEW.id_solicitud,
        'numero_solicitud', NEW.numero_solicitud,
        'estado_anterior', OLD.estado_solicitud,
        'estado_nuevo', NEW.estado_solicitud,
        'usuario_aprobador', NEW.id_usuario_aprobador
      ),
      @user_ip
    );
  END IF;
END$$

-- Trigger para actualizar automáticamente el presupuesto
CREATE TRIGGER `tr_actualizar_presupuesto_despacho` 
AFTER INSERT ON `despachos_combustible`
FOR EACH ROW 
BEGIN
  DECLARE v_cat_programatica BIGINT;
  DECLARE v_fuente_org_fin BIGINT;
  DECLARE v_unidad_organizacional BIGINT;
  
  -- Obtener datos de la solicitud relacionada
  SELECT 
    s.id_cat_programatica, 
    s.id_fuente_org_fin,
    ut.id_unidad_organizacional
  INTO v_cat_programatica, v_fuente_org_fin, v_unidad_organizacional
  FROM solicitudes_combustible s
  JOIN unidades_transporte ut ON s.id_unidad_transporte = ut.id_unidad_transporte
  WHERE s.id_solicitud = NEW.id_solicitud;
  
  -- Actualizar presupuesto específico
  UPDATE presupuestos 
  SET 
    total_gastado = total_gastado + NEW.costo_total,
    updated_at = CURRENT_TIMESTAMP
  WHERE 
    id_cat_programatica = v_cat_programatica 
    AND id_fuente_org_fin = v_fuente_org_fin
    AND id_unidad_organizacional = v_unidad_organizacional
    AND anio_fiscal = YEAR(NEW.fecha_despacho)
    AND activo = 1;
    
  -- Actualizar estado de solicitud a 'Despachada'
  UPDATE solicitudes_combustible 
  SET estado_solicitud = 'Despachada'
  WHERE id_solicitud = NEW.id_solicitud;
END$$

DELIMITER ;
```

### 6. **Vistas Materializadas para Reportes**

```sql
-- Vista para dashboard de supervisores
CREATE VIEW `v_dashboard_supervisor` AS
SELECT 
  u.id_usuario as supervisor_id,
  u.nombre as supervisor_nombre,
  uo.nombre_unidad,
  COUNT(DISTINCT ut.id_unidad_transporte) as total_vehiculos,
  COUNT(DISTINCT CASE WHEN s.estado_solicitud = 'Pendiente' THEN s.id_solicitud END) as solicitudes_pendientes,
  COUNT(DISTINCT CASE WHEN s.fecha_solicitud >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN s.id_solicitud END) as solicitudes_semana,
  SUM(CASE WHEN d.fecha_despacho >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN d.litros_despachados ELSE 0 END) as litros_mes,
  SUM(CASE WHEN d.fecha_despacho >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN d.costo_total ELSE 0 END) as costo_mes
FROM usuarios u
LEFT JOIN unidades_organizacionales uo ON u.id_unidad_organizacional = uo.id_unidad_organizacional
LEFT JOIN unidades_transporte ut ON ut.id_unidad_organizacional = uo.id_unidad_organizacional
LEFT JOIN solicitudes_combustible s ON s.id_unidad_transporte = ut.id_unidad_transporte
LEFT JOIN despachos_combustible d ON d.id_solicitud = s.id_solicitud
WHERE u.rol IN ('Supervisor', 'Admin_Secretaria', 'Admin_General')
  AND u.activo = 1
GROUP BY u.id_usuario, u.nombre, uo.nombre_unidad;

-- Vista para control de rendimiento por vehículo
CREATE VIEW `v_rendimiento_vehiculos` AS
SELECT 
  ut.id_unidad_transporte,
  ut.placa,
  ut.marca,
  ut.modelo,
  tc.nombre as tipo_combustible,
  tv.nombre as tipo_vehiculo,
  uo.nombre_unidad,
  COUNT(c.id_consumo) as total_registros,
  AVG(c.rendimiento_km_por_litro) as rendimiento_promedio,
  MIN(c.rendimiento_km_por_litro) as rendimiento_minimo,
  MAX(c.rendimiento_km_por_litro) as rendimiento_maximo,
  SUM(c.litros_cargados) as total_litros_consumidos,
  SUM(c.kilometros_recorridos) as total_kilometros,
  MAX(c.fecha_registro) as ultimo_consumo,
  ut.kilometraje_actual,
  -- Alertas de rendimiento
  CASE 
    WHEN AVG(c.rendimiento_km_por_litro) < 5 THEN 'CRÍTICO'
    WHEN AVG(c.rendimiento_km_por_litro) < 8 THEN 'BAJO'
    WHEN AVG(c.rendimiento_km_por_litro) > 15 THEN 'EXCELENTE'
    ELSE 'NORMAL'
  END as categoria_rendimiento
FROM unidades_transporte ut
LEFT JOIN tipos_combustible tc ON ut.id_tipo_combustible = tc.id_tipo_combustible
LEFT JOIN tipos_vehiculo tv ON ut.id_tipo_vehiculo = tv.id_tipo_vehiculo
LEFT JOIN unidades_organizacionales uo ON ut.id_unidad_organizacional = uo.id_unidad_organizacional
LEFT JOIN consumos_combustible c ON ut.id_unidad_transporte = c.id_unidad_transporte 
  AND c.fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
  AND c.validado = 1
WHERE ut.estado_operativo = 'Operativo'
GROUP BY ut.id_unidad_transporte, ut.placa, ut.marca, ut.modelo, tc.nombre, tv.nombre, uo.nombre_unidad;
```

### 7. **Configuración MySQL Recomendada**

#### **my.cnf optimizado para el sistema:**
```ini
[mysqld]
# Configuración básica
innodb_buffer_pool_size = 2G
innodb_log_file_size = 512M
innodb_flush_log_at_trx_commit = 2
innodb_file_per_table = 1

# Para auditoría y logs
max_binlog_size = 100M
binlog_expire_logs_seconds = 604800  # 7 días
log_bin = mysql-bin

# Optimización para consultas
query_cache_type = 1
query_cache_size = 256M
tmp_table_size = 256M
max_heap_table_size = 256M

# Conexiones
max_connections = 500
wait_timeout = 28800

# Configuración específica para particiones
innodb_open_files = 2000

# Para JSON (auditoría)
innodb_default_row_format = DYNAMIC
```

### 8. **Estrategia de Mantenimiento Automatizado**

```sql
-- Evento para limpiar auditoría antigua
DELIMITER $$
CREATE EVENT `ev_limpiar_auditoria_antigua`
ON SCHEDULE EVERY 1 MONTH
STARTS '2025-01-01 02:00:00'
DO
BEGIN
  -- Mantener solo 2 años de auditoría
  DELETE FROM registro_auditoria 
  WHERE fecha_hora < DATE_SUB(NOW(), INTERVAL 2 YEAR);
  
  -- Optimizar tabla después de la limpieza
  OPTIMIZE TABLE registro_auditoria;
END$$

-- Evento para actualizar estadísticas de rendimiento
CREATE EVENT `ev_actualizar_estadisticas_vehiculos`
ON SCHEDULE EVERY 1 DAY
STARTS '2025-01-01 01:00:00'
DO
BEGIN
  -- Actualizar rendimiento promedio de vehículos
  UPDATE unidades_transporte ut
  SET rendimiento_promedio = (
    SELECT AVG(c.rendimiento_km_por_litro)
    FROM consumos_combustible c
    WHERE c.id_unidad_transporte = ut.id_unidad_transporte
      AND c.fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
      AND c.validado = 1
  )
  WHERE ut.estado_operativo = 'Operativo';
END$$

DELIMITER ;

-- Habilitar el scheduler de eventos
SET GLOBAL event_scheduler = ON;
```

### 9. **Backup y Recuperación**

```bash
#!/bin/bash
# Script de backup diario específico para el sistema

# Backup completo (estructura + datos)
mysqldump --single-transaction --routines --triggers \
  --add-drop-table --add-locks \
  sistema_combustible_gobernacion > backup_completo_$(date +%Y%m%d).sql

# Backup solo de datos críticos (transaccionales)
mysqldump --single-transaction --no-create-info \
  --where="fecha_registro >= DATE_SUB(NOW(), INTERVAL 30 DAY)" \
  sistema_combustible_gobernacion \
  solicitudes_combustible despachos_combustible consumos_combustible \
  > backup_transaccional_$(date +%Y%m%d).sql

# Backup de configuración (catálogos)
mysqldump --single-transaction \
  sistema_combustible_gobernacion \
  usuarios unidades_organizacionales tipos_combustible tipos_vehiculo \
  categorias_programaticas fuentes_organismo_financiero \
  > backup_configuracion_$(date +%Y%m%d).sql
```

### 10. **Consideraciones de Seguridad específicas**

```sql
-- Crear usuario específico para la aplicación Laravel
CREATE USER 'combustible_app'@'%' IDENTIFIED BY 'password_seguro_aqui';

-- Permisos mínimos necesarios
GRANT SELECT, INSERT, UPDATE ON sistema_combustible_gobernacion.* TO 'combustible_app'@'%';
GRANT DELETE ON sistema_combustible_gobernacion.codigos_registro TO 'combustible_app'@'%';
GRANT DELETE ON sistema_combustible_gobernacion.registro_auditoria TO 'combustible_app'@'%';

-- Usuario solo lectura para reportes
CREATE USER 'combustible_reportes'@'%' IDENTIFIED BY 'password_reportes_aqui';
GRANT SELECT ON sistema_combustible_gobernacion.* TO 'combustible_reportes'@'%';

-- Encriptación a nivel de campo para datos sensibles
-- Usar funciones de Laravel para encriptar datos como teléfonos y emails
```

## 🎯 **Resumen de Optimizaciones Clave**

1. **📊 Particionamiento**: Auditoría y solicitudes por fecha/año
2. **🚀 Índices Inteligentes**: Basados en consultas reales del sistema
3. **⚡ Columnas Calculadas**: Para evitar cálculos repetitivos
4. **🔄 Triggers Eficientes**: Solo para cambios críticos
5. **📈 Vistas Optimizadas**: Para dashboards y reportes frecuentes
6. **🛠️ Mantenimiento Automático**: Limpieza y optimización programada
7. **🔒 Seguridad**: Usuarios específicos con permisos mínimos
8. **💾 Backup Estratégico**: Diferenciado por criticidad de datos

Estas optimizaciones garantizarán que el sistema sea escalable, eficiente y mantenga un rendimiento óptimo para la Gobernación de Cochabamba, incluso con grandes volúmenes de datos de combustible y auditoría.
