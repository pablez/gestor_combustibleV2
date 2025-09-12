-- =====================================================
-- SISTEMA DE GESTIÓN DE COMBUSTIBLE - VERSIÓN OPTIMIZADA
-- GOBERNACIÓN DE COCHABAMBA - BOLIVIA
-- Base de Datos MySQL 8.0 - Estructura Corregida
-- =====================================================

-- Configuración inicial optimizada
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos con configuración optimizada
CREATE DATABASE IF NOT EXISTS `sistema_combustible_gobernacion` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `sistema_combustible_gobernacion`;

-- =====================================================
-- TABLAS DE CONFIGURACIÓN Y CATÁLOGOS
-- =====================================================

-- Tabla: tipos_combustible (mejorada)
CREATE TABLE `tipos_combustible` (
  `id_tipo_combustible` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `codigo_comercial` VARCHAR(10) UNIQUE NULL,
  `descripcion` TEXT(200) NULL,
  `octanaje` TINYINT UNSIGNED NULL,
  `precio_referencial` DECIMAL(6,2) NULL COMMENT 'Precio referencial en Bs',
  `unidad_medida` VARCHAR(20) DEFAULT 'Litros',
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipo_combustible`),
  UNIQUE KEY `uk_tipo_combustible_nombre` (`nombre`),
  KEY `idx_tipo_combustible_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de tipos de combustible';

-- Tabla: tipos_vehiculo (mejorada)
CREATE TABLE `tipos_vehiculo` (
  `id_tipo_vehiculo` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `categoria` ENUM('Liviano','Pesado','Motocicleta','Especializado') NOT NULL,
  `descripcion` TEXT(200) NULL,
  `consumo_promedio_ciudad` DECIMAL(4,2) NULL COMMENT 'Km por litro en ciudad',
  `consumo_promedio_carretera` DECIMAL(4,2) NULL COMMENT 'Km por litro en carretera',
  `capacidad_carga_kg` INT UNSIGNED NULL,
  `numero_pasajeros` TINYINT UNSIGNED NULL,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipo_vehiculo`),
  UNIQUE KEY `uk_tipo_vehiculo_nombre` (`nombre`),
  KEY `idx_tipo_vehiculo_categoria` (`categoria`, `activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de tipos de vehículo';

-- Tabla: tipos_servicio_proveedor (mejorada)
CREATE TABLE `tipos_servicio_proveedor` (
  `id_tipo_servicio_proveedor` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(10) UNIQUE NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT(200) NULL,
  `requiere_autorizacion_especial` BOOLEAN DEFAULT FALSE,
  `dias_credito_maximo` TINYINT UNSIGNED DEFAULT 0,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipo_servicio_proveedor`),
  UNIQUE KEY `uk_tipo_servicio_nombre` (`nombre`),
  KEY `idx_tipo_servicio_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de tipos de servicio de proveedores';

-- =====================================================
-- ESTRUCTURA ORGANIZACIONAL
-- =====================================================

-- Tabla: unidades_organizacionales (mejorada con jerarquía)
CREATE TABLE `unidades_organizacionales` (
  `id_unidad_organizacional` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo_unidad` VARCHAR(20) UNIQUE NOT NULL,
  `nombre_unidad` VARCHAR(100) NOT NULL,
  `tipo_unidad` ENUM('Superior','Ejecutiva','Operativa') NOT NULL,
  `id_unidad_padre` BIGINT UNSIGNED NULL,
  `nivel_jerarquico` TINYINT UNSIGNED DEFAULT 1,
  `responsable_unidad` VARCHAR(100) NULL,
  `telefono` VARCHAR(15) NULL,
  `direccion` VARCHAR(200) NULL,
  `presupuesto_asignado` DECIMAL(14,2) DEFAULT 0,
  `descripcion` TEXT(300) NULL,
  `activa` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_unidad_organizacional`),
  UNIQUE KEY `uk_unidad_organizacional_nombre` (`nombre_unidad`),
  UNIQUE KEY `uk_unidad_organizacional_codigo` (`codigo_unidad`),
  KEY `idx_unidad_padre` (`id_unidad_padre`),
  KEY `idx_unidad_activa_tipo` (`activa`, `tipo_unidad`),
  CONSTRAINT `fk_unidad_padre` FOREIGN KEY (`id_unidad_padre`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Estructura organizacional jerárquica';

-- =====================================================
-- GESTIÓN PRESUPUESTARIA
-- =====================================================

-- Tabla: categorias_programaticas (mejorada con jerarquía)
CREATE TABLE `categorias_programaticas` (
  `id_cat_programatica` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(30) NOT NULL,
  `descripcion` VARCHAR(200) NOT NULL,
  `tipo_categoria` ENUM('Programa','Proyecto','Actividad') NOT NULL,
  `id_categoria_padre` BIGINT UNSIGNED NULL,
  `nivel` TINYINT UNSIGNED DEFAULT 1,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `fecha_inicio` DATE NULL,
  `fecha_fin` DATE NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cat_programatica`),
  UNIQUE KEY `uk_categoria_programatica_codigo` (`codigo`),
  KEY `idx_categoria_padre` (`id_categoria_padre`),
  KEY `idx_categoria_activa_tipo` (`activo`, `tipo_categoria`),
  CONSTRAINT `fk_categoria_padre` FOREIGN KEY (`id_categoria_padre`) REFERENCES `categorias_programaticas` (`id_cat_programatica`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Estructura programática presupuestaria';

-- Tabla: fuentes_organismo_financiero (mejorada)
CREATE TABLE `fuentes_organismo_financiero` (
  `id_fuente_org_fin` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(30) NOT NULL,
  `descripcion` VARCHAR(200) NOT NULL,
  `tipo_fuente` ENUM('Nacional','Departamental','Municipal','Internacional','Otros') NOT NULL,
  `organismo_financiador` VARCHAR(100) NULL,
  `requiere_contrapartida` BOOLEAN DEFAULT FALSE,
  `porcentaje_contrapartida` DECIMAL(5,2) DEFAULT 0,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `fecha_vigencia_inicio` DATE NULL,
  `fecha_vigencia_fin` DATE NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_fuente_org_fin`),
  UNIQUE KEY `uk_fuente_organismo_codigo` (`codigo`),
  KEY `idx_fuente_activa_tipo` (`activo`, `tipo_fuente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fuentes de financiamiento';

-- Tabla: presupuestos (mejorada)
CREATE TABLE `presupuestos` (
  `id_presupuesto` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cat_programatica` BIGINT UNSIGNED NOT NULL,
  `id_fuente_org_fin` BIGINT UNSIGNED NOT NULL,
  `id_unidad_organizacional` BIGINT UNSIGNED NOT NULL,
  `anio_fiscal` YEAR NOT NULL,
  `trimestre` TINYINT UNSIGNED NULL,
  `presupuesto_inicial` DECIMAL(14,2) NOT NULL,
  `presupuesto_actual` DECIMAL(14,2) NOT NULL,
  `total_gastado` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `total_comprometido` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `num_documento` VARCHAR(50) NOT NULL,
  `numero_comprobante` VARCHAR(50) NULL,
  `fecha_aprobacion` DATE NULL,
  `porcentaje_preventivo` DECIMAL(5,2) DEFAULT 10.00,
  `alerta_porcentaje` DECIMAL(5,2) DEFAULT 80.00,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `observaciones` TEXT(300) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_presupuesto`),
  UNIQUE KEY `uk_presupuesto_completo` (`id_cat_programatica`, `id_fuente_org_fin`, `id_unidad_organizacional`, `anio_fiscal`, `trimestre`),
  KEY `idx_presupuesto_activo_anio` (`activo`, `anio_fiscal`),
  KEY `idx_presupuesto_unidad_anio` (`id_unidad_organizacional`, `anio_fiscal`),
  CONSTRAINT `fk_presupuesto_categoria` FOREIGN KEY (`id_cat_programatica`) REFERENCES `categorias_programaticas` (`id_cat_programatica`),
  CONSTRAINT `fk_presupuesto_fuente` FOREIGN KEY (`id_fuente_org_fin`) REFERENCES `fuentes_organismo_financiero` (`id_fuente_org_fin`),
  CONSTRAINT `fk_presupuesto_unidad` FOREIGN KEY (`id_unidad_organizacional`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Presupuestos por categoría programática y fuente';

-- =====================================================
-- GESTIÓN DE USUARIOS
-- =====================================================

-- Tabla: usuarios (completamente rediseñada)
CREATE TABLE `usuarios` (
  `id_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido_paterno` VARCHAR(50) NOT NULL,
  `apellido_materno` VARCHAR(50) NULL,
  `ci` VARCHAR(15) NOT NULL COMMENT 'Cédula de Identidad',
  `telefono` VARCHAR(15) NULL COMMENT 'Formato: +59177777777',
  `rol` ENUM('Admin_General','Admin_Secretaria','Supervisor','Conductor') NOT NULL,
  `id_supervisor` BIGINT UNSIGNED NULL,
  `id_unidad_organizacional` BIGINT UNSIGNED NOT NULL,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `fecha_ultimo_acceso` TIMESTAMP NULL,
  `intentos_fallidos` TINYINT UNSIGNED DEFAULT 0,
  `bloqueado_hasta` TIMESTAMP NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_usuario_username` (`username`),
  UNIQUE KEY `uk_usuario_email` (`email`),
  UNIQUE KEY `uk_usuario_ci` (`ci`),
  KEY `idx_usuario_supervisor` (`id_supervisor`),
  KEY `idx_usuario_unidad_rol` (`id_unidad_organizacional`, `rol`),
  KEY `idx_usuario_activo_rol` (`activo`, `rol`),
  CONSTRAINT `fk_usuario_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `fk_usuario_unidad` FOREIGN KEY (`id_unidad_organizacional`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Usuarios del sistema con jerarquía';

-- Tabla: solicitudes_aprobacion_usuario (mejorada)
CREATE TABLE `solicitudes_aprobacion_usuario` (
  `id_solicitud_aprobacion_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` BIGINT UNSIGNED NOT NULL COMMENT 'Usuario sobre el que se solicita la acción',
  `id_creador` BIGINT UNSIGNED NOT NULL COMMENT 'Usuario que crea la solicitud',
  `id_supervisor_asignado` BIGINT UNSIGNED NULL,
  `tipo_solicitud` ENUM('nuevo_usuario','cambio_rol','reactivacion','cambio_supervisor') NOT NULL DEFAULT 'nuevo_usuario',
  `estado_solicitud` ENUM('pendiente','aprobado','rechazado','en_revision') NOT NULL DEFAULT 'pendiente',
  `rol_solicitado` VARCHAR(50) NULL,
  `justificacion` TEXT(500) NOT NULL,
  `observaciones_aprobacion` TEXT(500) NULL,
  `fecha_aprobacion` TIMESTAMP NULL,
  `id_usuario_aprobador` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_solicitud_aprobacion_usuario`),
  KEY `idx_solicitud_usuario_estado` (`id_usuario`, `estado_solicitud`),
  KEY `idx_solicitud_supervisor_estado` (`id_supervisor_asignado`, `estado_solicitud`),
  KEY `idx_solicitud_creador_fecha` (`id_creador`, `created_at`),
  CONSTRAINT `fk_solicitud_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_solicitud_creador` FOREIGN KEY (`id_creador`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_solicitud_supervisor` FOREIGN KEY (`id_supervisor_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `fk_solicitud_aprobador` FOREIGN KEY (`id_usuario_aprobador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Solicitudes de aprobación para usuarios';

-- Tabla: codigos_registro (mejorada)
CREATE TABLE `codigos_registro` (
  `id_codigo_registro` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(15) NOT NULL,
  `id_usuario_generador` BIGINT UNSIGNED NOT NULL,
  `vigente_hasta` DATE NOT NULL,
  `usado` BOOLEAN NOT NULL DEFAULT FALSE,
  `id_usuario_usado` BIGINT UNSIGNED NULL,
  `fecha_uso` TIMESTAMP NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_codigo_registro`),
  UNIQUE KEY `uk_codigo_registro_codigo` (`codigo`),
  KEY `idx_codigo_vigente_usado` (`vigente_hasta`, `usado`),
  KEY `idx_codigo_generador` (`id_usuario_generador`),
  CONSTRAINT `fk_codigo_generador` FOREIGN KEY (`id_usuario_generador`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_codigo_usuario_usado` FOREIGN KEY (`id_usuario_usado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Códigos de registro para nuevos usuarios';

-- =====================================================
-- GESTIÓN DE PROVEEDORES
-- =====================================================

-- Tabla: proveedores (mejorada)
CREATE TABLE `proveedores` (
  `id_proveedor` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_proveedor` VARCHAR(100) NOT NULL,
  `nombre_comercial` VARCHAR(100) NULL,
  `nit` VARCHAR(20) NOT NULL,
  `direccion` VARCHAR(200) NULL,
  `telefono` VARCHAR(15) NULL,
  `email` VARCHAR(100) NULL,
  `id_tipo_servicio_proveedor` BIGINT UNSIGNED NOT NULL,
  `contacto_principal` VARCHAR(100) NULL,
  `calificacion` ENUM('A','B','C','D') DEFAULT 'C' COMMENT 'A=Excelente, B=Bueno, C=Regular, D=Deficiente',
  `observaciones` TEXT(500) NULL,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_proveedor`),
  UNIQUE KEY `uk_proveedor_nit` (`nit`),
  KEY `idx_proveedor_tipo_activo` (`id_tipo_servicio_proveedor`, `activo`),
  KEY `idx_proveedor_calificacion` (`calificacion`, `activo`),
  CONSTRAINT `fk_proveedor_tipo_servicio` FOREIGN KEY (`id_tipo_servicio_proveedor`) REFERENCES `tipos_servicio_proveedor` (`id_tipo_servicio_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Proveedores de combustible y servicios';

-- =====================================================
-- GESTIÓN DE FLOTA VEHICULAR
-- =====================================================

-- Tabla: unidades_transporte (completamente rediseñada)
CREATE TABLE `unidades_transporte` (
  `id_unidad_transporte` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `placa` VARCHAR(15) NOT NULL COMMENT 'Formato boliviano: ABC-1234',
  `numero_chasis` VARCHAR(30) UNIQUE NULL,
  `numero_motor` VARCHAR(30) NULL,
  `marca` VARCHAR(50) NOT NULL,
  `modelo` VARCHAR(50) NOT NULL,
  `anio_fabricacion` YEAR NULL,
  `color` VARCHAR(30) NOT NULL,
  `id_tipo_vehiculo` BIGINT UNSIGNED NOT NULL,
  `id_tipo_combustible` BIGINT UNSIGNED NOT NULL,
  `capacidad_tanque` DECIMAL(6,2) NOT NULL COMMENT 'Litros',
  `kilometraje_actual` INT UNSIGNED NOT NULL DEFAULT 0,
  `kilometraje_ultimo_mantenimiento` INT UNSIGNED DEFAULT 0,
  `proximo_mantenimiento_km` INT UNSIGNED NULL,
  `id_unidad_organizacional` BIGINT UNSIGNED NOT NULL,
  `id_conductor_asignado` BIGINT UNSIGNED NULL,
  `estado_operativo` ENUM('Operativo','Mantenimiento','Taller','Baja','Reserva') NOT NULL DEFAULT 'Operativo',
  `seguro_vigente_hasta` DATE NULL,
  `revision_tecnica_hasta` DATE NULL,
  `fecha_ultimo_servicio` DATE NULL,
  `observaciones` TEXT(500) NULL,
  `activo` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_unidad_transporte`),
  UNIQUE KEY `uk_unidad_transporte_placa` (`placa`),
  KEY `idx_vehiculo_unidad_estado` (`id_unidad_organizacional`, `estado_operativo`),
  KEY `idx_vehiculo_conductor` (`id_conductor_asignado`),
  KEY `idx_vehiculo_tipo_combustible` (`id_tipo_vehiculo`, `id_tipo_combustible`),
  KEY `idx_vehiculo_mantenimiento` (`proximo_mantenimiento_km`, `estado_operativo`),
  CONSTRAINT `fk_vehiculo_tipo` FOREIGN KEY (`id_tipo_vehiculo`) REFERENCES `tipos_vehiculo` (`id_tipo_vehiculo`),
  CONSTRAINT `fk_vehiculo_combustible` FOREIGN KEY (`id_tipo_combustible`) REFERENCES `tipos_combustible` (`id_tipo_combustible`),
  CONSTRAINT `fk_vehiculo_unidad` FOREIGN KEY (`id_unidad_organizacional`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`),
  CONSTRAINT `fk_vehiculo_conductor` FOREIGN KEY (`id_conductor_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Unidades de transporte de la gobernación';

-- =====================================================
-- GESTIÓN DE SOLICITUDES Y DESPACHOS
-- =====================================================

-- Tabla: solicitudes_combustible (completamente rediseñada)
CREATE TABLE `solicitudes_combustible` (
  `id_solicitud` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_solicitud` VARCHAR(20) NOT NULL COMMENT 'Número visible para usuarios',
  `id_usuario_solicitante` BIGINT UNSIGNED NOT NULL,
  `id_unidad_transporte` BIGINT UNSIGNED NOT NULL,
  `fecha_solicitud` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cantidad_litros_solicitados` DECIMAL(8,3) NOT NULL,
  `motivo` TEXT(500) NOT NULL,
  `urgente` BOOLEAN NOT NULL DEFAULT FALSE,
  `justificacion_urgencia` TEXT(300) NULL,
  `estado_solicitud` ENUM('Pendiente','En_Revision','Aprobada','Rechazada','Despachada','Cancelada') NOT NULL DEFAULT 'Pendiente',
  `id_usuario_aprobador` BIGINT UNSIGNED NULL,
  `fecha_aprobacion` TIMESTAMP NULL,
  `observaciones_aprobacion` TEXT(500) NULL,
  `id_cat_programatica` BIGINT UNSIGNED NOT NULL,
  `id_fuente_org_fin` BIGINT UNSIGNED NOT NULL,
  `saldo_actual_combustible` DECIMAL(12,2) NULL COMMENT 'Saldo presupuestario al momento de la solicitud',
  `km_actual` INT UNSIGNED NOT NULL,
  `km_proyectado` INT UNSIGNED NOT NULL,
  `rendimiento_estimado` DECIMAL(6,2) NULL COMMENT 'Km por litro estimado',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_solicitud`),
  UNIQUE KEY `uk_solicitud_numero` (`numero_solicitud`),
  KEY `idx_solicitud_estado_fecha` (`estado_solicitud`, `fecha_solicitud`),
  KEY `idx_solicitud_solicitante_fecha` (`id_usuario_solicitante`, `fecha_solicitud` DESC),
  KEY `idx_solicitud_vehiculo_estado` (`id_unidad_transporte`, `estado_solicitud`),
  KEY `idx_solicitud_aprobador` (`id_usuario_aprobador`),
  KEY `idx_solicitud_presupuesto` (`id_cat_programatica`, `id_fuente_org_fin`),
  CONSTRAINT `fk_solicitud_solicitante` FOREIGN KEY (`id_usuario_solicitante`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_solicitud_vehiculo` FOREIGN KEY (`id_unidad_transporte`) REFERENCES `unidades_transporte` (`id_unidad_transporte`),
  CONSTRAINT `fk_solicitud_aprobador` FOREIGN KEY (`id_usuario_aprobador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `fk_solicitud_categoria` FOREIGN KEY (`id_cat_programatica`) REFERENCES `categorias_programaticas` (`id_cat_programatica`),
  CONSTRAINT `fk_solicitud_fuente` FOREIGN KEY (`id_fuente_org_fin`) REFERENCES `fuentes_organismo_financiero` (`id_fuente_org_fin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Solicitudes de combustible';

-- Tabla: despachos_combustible (completamente rediseñada)
CREATE TABLE `despachos_combustible` (
  `id_despacho` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_solicitud` BIGINT UNSIGNED NOT NULL,
  `id_proveedor` BIGINT UNSIGNED NOT NULL,
  `fecha_despacho` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `litros_despachados` DECIMAL(8,3) NOT NULL,
  `precio_por_litro` DECIMAL(6,2) NOT NULL COMMENT 'Precio en bolivianos',
  `costo_total` DECIMAL(12,2) NOT NULL COMMENT 'Costo total en bolivianos',
  `numero_vale` VARCHAR(20) NOT NULL,
  `numero_factura` VARCHAR(30) NULL,
  `id_usuario_despachador` BIGINT UNSIGNED NOT NULL,
  `ubicacion_despacho` VARCHAR(100) NULL,
  `observaciones` TEXT(500) NULL,
  `validado` BOOLEAN NOT NULL DEFAULT FALSE,
  `fecha_validacion` TIMESTAMP NULL,
  `id_usuario_validador` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_despacho`),
  UNIQUE KEY `uk_despacho_vale` (`numero_vale`),
  UNIQUE KEY `uk_despacho_solicitud` (`id_solicitud`),
  KEY `idx_despacho_proveedor_fecha` (`id_proveedor`, `fecha_despacho`),
  KEY `idx_despacho_despachador` (`id_usuario_despachador`),
  KEY `idx_despacho_validado` (`validado`, `fecha_validacion`),
  CONSTRAINT `fk_despacho_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_combustible` (`id_solicitud`),
  CONSTRAINT `fk_despacho_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`),
  CONSTRAINT `fk_despacho_despachador` FOREIGN KEY (`id_usuario_despachador`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_despacho_validador` FOREIGN KEY (`id_usuario_validador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Despachos de combustible realizados';

-- Tabla: consumos_combustible (completamente rediseñada)
CREATE TABLE `consumos_combustible` (
  `id_consumo` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_unidad_transporte` BIGINT UNSIGNED NOT NULL,
  `id_despacho` BIGINT UNSIGNED NULL COMMENT 'NULL para cargas externas',
  `id_usuario_conductor` BIGINT UNSIGNED NOT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kilometraje_inicial` INT UNSIGNED NOT NULL,
  `kilometraje_fin` INT UNSIGNED NOT NULL,
  `litros_cargados` DECIMAL(8,3) NOT NULL,
  `tipo_carga` ENUM('despacho_oficial','carga_externa','emergencia') NOT NULL DEFAULT 'despacho_oficial',
  `lugar_carga` VARCHAR(100) NOT NULL,
  `numero_ticket` VARCHAR(30) NULL,
  `observaciones` TEXT(500) NULL,
  `validado` BOOLEAN NOT NULL DEFAULT FALSE,
  `fecha_validacion` TIMESTAMP NULL,
  `id_usuario_validador` BIGINT UNSIGNED NULL,
  -- Columnas calculadas automáticamente
  `kilometros_recorridos` INT UNSIGNED GENERATED ALWAYS AS (`kilometraje_fin` - `kilometraje_inicial`) STORED,
  `rendimiento_km_por_litro` DECIMAL(6,3) GENERATED ALWAYS AS (
    CASE 
      WHEN `litros_cargados` > 0 THEN (`kilometraje_fin` - `kilometraje_inicial`) / `litros_cargados` 
      ELSE 0 
    END
  ) STORED,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_consumo`),
  KEY `idx_consumo_vehiculo_fecha` (`id_unidad_transporte`, `fecha_registro` DESC),
  KEY `idx_consumo_despacho` (`id_despacho`),
  KEY `idx_consumo_conductor` (`id_usuario_conductor`),
  KEY `idx_consumo_rendimiento` (`id_unidad_transporte`, `rendimiento_km_por_litro`),
  KEY `idx_consumo_validado` (`validado`, `fecha_validacion`),
  CONSTRAINT `fk_consumo_vehiculo` FOREIGN KEY (`id_unidad_transporte`) REFERENCES `unidades_transporte` (`id_unidad_transporte`),
  CONSTRAINT `fk_consumo_despacho` FOREIGN KEY (`id_despacho`) REFERENCES `despachos_combustible` (`id_despacho`) ON DELETE SET NULL,
  CONSTRAINT `fk_consumo_conductor` FOREIGN KEY (`id_usuario_conductor`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_consumo_validador` FOREIGN KEY (`id_usuario_validador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `chk_kilometraje_valido` CHECK (`kilometraje_fin` >= `kilometraje_inicial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Consumos reales de combustible por vehículo';

-- =====================================================
-- AUDITORÍA Y LOGGING
-- =====================================================

-- Tabla: registro_auditoria (completamente rediseñada)
CREATE TABLE `registro_auditoria` (
  `id_registro_auditoria` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` BIGINT UNSIGNED NOT NULL,
  `fecha_hora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accion_realizada` VARCHAR(50) NOT NULL,
  `tabla_afectada` VARCHAR(50) NOT NULL,
  `registro_afectado` JSON NOT NULL,
  `valores_anteriores` JSON NULL,
  `valores_nuevos` JSON NULL,
  `ip_origen` VARCHAR(45) NULL,
  `user_agent` VARCHAR(200) NULL,
  `sesion_id` VARCHAR(100) NULL,
  `modulo_sistema` VARCHAR(50) NULL,
  `nivel_criticidad` ENUM('BAJO','MEDIO','ALTO','CRÍTICO') DEFAULT 'MEDIO',
  PRIMARY KEY (`id_registro_auditoria`, `fecha_hora`),
  KEY `idx_auditoria_usuario_fecha` (`id_usuario`, `fecha_hora`),
  KEY `idx_auditoria_tabla_fecha` (`tabla_afectada`, `fecha_hora`),
  KEY `idx_auditoria_accion` (`accion_realizada`, `nivel_criticidad`),
  CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de auditoría del sistema'
PARTITION BY RANGE (YEAR(fecha_hora)) (
  PARTITION p2024 VALUES LESS THAN (2025),
  PARTITION p2025 VALUES LESS THAN (2026),
  PARTITION p2026 VALUES LESS THAN (2027),
  PARTITION p_future VALUES LESS THAN MAXVALUE
);

-- =====================================================
-- VISTAS OPTIMIZADAS PARA REPORTES
-- =====================================================

-- Vista: Dashboard de supervisores
CREATE VIEW `v_dashboard_supervisor` AS
SELECT 
  u.id_usuario as supervisor_id,
  CONCAT(u.nombre, ' ', u.apellido_paterno, COALESCE(CONCAT(' ', u.apellido_materno), '')) as supervisor_nombre,
  u.email as supervisor_email,
  uo.nombre_unidad,
  uo.tipo_unidad,
  COUNT(DISTINCT ut.id_unidad_transporte) as total_vehiculos,
  COUNT(DISTINCT CASE WHEN ut.estado_operativo = 'Operativo' THEN ut.id_unidad_transporte END) as vehiculos_operativos,
  COUNT(DISTINCT CASE WHEN s.estado_solicitud = 'Pendiente' THEN s.id_solicitud END) as solicitudes_pendientes,
  COUNT(DISTINCT CASE WHEN s.fecha_solicitud >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN s.id_solicitud END) as solicitudes_semana,
  COALESCE(SUM(CASE WHEN d.fecha_despacho >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN d.litros_despachados ELSE 0 END), 0) as litros_mes,
  COALESCE(SUM(CASE WHEN d.fecha_despacho >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN d.costo_total ELSE 0 END), 0) as costo_mes
FROM usuarios u
LEFT JOIN unidades_organizacionales uo ON u.id_unidad_organizacional = uo.id_unidad_organizacional
LEFT JOIN unidades_transporte ut ON ut.id_unidad_organizacional = uo.id_unidad_organizacional
LEFT JOIN solicitudes_combustible s ON s.id_unidad_transporte = ut.id_unidad_transporte
LEFT JOIN despachos_combustible d ON d.id_solicitud = s.id_solicitud
WHERE u.rol IN ('Supervisor', 'Admin_Secretaria', 'Admin_General')
  AND u.activo = 1
GROUP BY u.id_usuario, u.nombre, u.apellido_paterno, u.apellido_materno, u.email, uo.nombre_unidad, uo.tipo_unidad;

-- Vista: Rendimiento de vehículos
CREATE VIEW `v_rendimiento_vehiculos` AS
SELECT 
  ut.id_unidad_transporte,
  ut.placa,
  ut.marca,
  ut.modelo,
  ut.anio_fabricacion,
  tc.nombre as tipo_combustible,
  tv.nombre as tipo_vehiculo,
  tv.categoria as categoria_vehiculo,
  uo.nombre_unidad,
  CONCAT(COALESCE(cond.nombre, ''), ' ', COALESCE(cond.apellido_paterno, '')) as conductor_asignado,
  ut.estado_operativo,
  ut.kilometraje_actual,
  COUNT(c.id_consumo) as total_registros_consumo,
  COALESCE(AVG(c.rendimiento_km_por_litro), 0) as rendimiento_promedio,
  COALESCE(MIN(c.rendimiento_km_por_litro), 0) as rendimiento_minimo,
  COALESCE(MAX(c.rendimiento_km_por_litro), 0) as rendimiento_maximo,
  COALESCE(SUM(c.litros_cargados), 0) as total_litros_consumidos,
  COALESCE(SUM(c.kilometros_recorridos), 0) as total_kilometros,
  MAX(c.fecha_registro) as ultimo_consumo,
  DATEDIFF(CURDATE(), MAX(c.fecha_registro)) as dias_sin_uso,
  -- Alertas de rendimiento
  CASE 
    WHEN AVG(c.rendimiento_km_por_litro) IS NULL THEN 'SIN_DATOS'
    WHEN AVG(c.rendimiento_km_por_litro) < 5 THEN 'CRÍTICO'
    WHEN AVG(c.rendimiento_km_por_litro) < 8 THEN 'BAJO'
    WHEN AVG(c.rendimiento_km_por_litro) > 15 THEN 'EXCELENTE'
    ELSE 'NORMAL'
  END as categoria_rendimiento,
  -- Alertas de mantenimiento
  CASE
    WHEN ut.proximo_mantenimiento_km IS NOT NULL AND ut.kilometraje_actual >= ut.proximo_mantenimiento_km THEN 'MANTENIMIENTO_VENCIDO'
    WHEN ut.proximo_mantenimiento_km IS NOT NULL AND (ut.proximo_mantenimiento_km - ut.kilometraje_actual) <= 1000 THEN 'MANTENIMIENTO_PRÓXIMO'
    WHEN ut.revision_tecnica_hasta IS NOT NULL AND ut.revision_tecnica_hasta <= CURDATE() THEN 'REVISIÓN_VENCIDA'
    WHEN ut.seguro_vigente_hasta IS NOT NULL AND ut.seguro_vigente_hasta <= CURDATE() THEN 'SEGURO_VENCIDO'
    ELSE 'OK'
  END as estado_mantenimiento
FROM unidades_transporte ut
LEFT JOIN tipos_combustible tc ON ut.id_tipo_combustible = tc.id_tipo_combustible
LEFT JOIN tipos_vehiculo tv ON ut.id_tipo_vehiculo = tv.id_tipo_vehiculo
LEFT JOIN unidades_organizacionales uo ON ut.id_unidad_organizacional = uo.id_unidad_organizacional
LEFT JOIN usuarios cond ON ut.id_conductor_asignado = cond.id_usuario
LEFT JOIN consumos_combustible c ON ut.id_unidad_transporte = c.id_unidad_transporte 
  AND c.fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
  AND c.validado = 1
WHERE ut.activo = 1
GROUP BY ut.id_unidad_transporte, ut.placa, ut.marca, ut.modelo, ut.anio_fabricacion, 
         tc.nombre, tv.nombre, tv.categoria, uo.nombre_unidad, 
         cond.nombre, cond.apellido_paterno, ut.estado_operativo, ut.kilometraje_actual,
         ut.proximo_mantenimiento_km, ut.revision_tecnica_hasta, ut.seguro_vigente_hasta;

-- Vista: Estado presupuestario
CREATE VIEW `v_estado_presupuestario` AS
SELECT 
  p.id_presupuesto,
  p.anio_fiscal,
  p.trimestre,
  uo.nombre_unidad,
  cp.codigo as codigo_categoria,
  cp.descripcion as categoria_programatica,
  fof.codigo as codigo_fuente,
  fof.descripcion as fuente_financiera,
  p.presupuesto_inicial,
  p.presupuesto_actual,
  p.total_gastado,
  p.total_comprometido,
  (p.presupuesto_actual - p.total_gastado - p.total_comprometido) as saldo_disponible,
  CASE 
    WHEN p.presupuesto_actual > 0 THEN (p.total_gastado / p.presupuesto_actual) * 100 
    ELSE 0 
  END as porcentaje_ejecutado,
  CASE 
    WHEN p.presupuesto_actual > 0 THEN ((p.total_gastado + p.total_comprometido) / p.presupuesto_actual) * 100 
    ELSE 0 
  END as porcentaje_comprometido,
  p.alerta_porcentaje,
  CASE
    WHEN (p.total_gastado / p.presupuesto_actual) * 100 >= p.alerta_porcentaje THEN 'ALERTA'
    WHEN (p.total_gastado / p.presupuesto_actual) * 100 >= (p.alerta_porcentaje * 0.8) THEN 'PRECAUCIÓN'
    ELSE 'NORMAL'
  END as estado_presupuestario,
  p.activo,
  p.updated_at as ultima_actualizacion
FROM presupuestos p
JOIN unidades_organizacionales uo ON p.id_unidad_organizacional = uo.id_unidad_organizacional
JOIN categorias_programaticas cp ON p.id_cat_programatica = cp.id_cat_programatica
JOIN fuentes_organismo_financiero fof ON p.id_fuente_org_fin = fof.id_fuente_org_fin
WHERE p.activo = 1;

-- =====================================================
-- TRIGGERS OPTIMIZADOS
-- =====================================================

DELIMITER $$

-- Trigger: Actualizar presupuesto cuando se crea un despacho
CREATE TRIGGER `tr_actualizar_presupuesto_despacho` 
AFTER INSERT ON `despachos_combustible`
FOR EACH ROW 
BEGIN
  DECLARE v_cat_programatica BIGINT UNSIGNED;
  DECLARE v_fuente_org_fin BIGINT UNSIGNED;
  DECLARE v_unidad_organizacional BIGINT UNSIGNED;
  DECLARE v_anio_fiscal YEAR;
  
  -- Obtener datos de la solicitud relacionada
  SELECT 
    s.id_cat_programatica, 
    s.id_fuente_org_fin,
    ut.id_unidad_organizacional,
    YEAR(NEW.fecha_despacho)
  INTO v_cat_programatica, v_fuente_org_fin, v_unidad_organizacional, v_anio_fiscal
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
    AND anio_fiscal = v_anio_fiscal
    AND activo = 1;
    
  -- Actualizar estado de solicitud a 'Despachada'
  UPDATE solicitudes_combustible 
  SET 
    estado_solicitud = 'Despachada',
    updated_at = CURRENT_TIMESTAMP
  WHERE id_solicitud = NEW.id_solicitud;
END$$

-- Trigger: Auditoría para cambios de estado en solicitudes
CREATE TRIGGER `tr_solicitud_cambio_estado` 
AFTER UPDATE ON `solicitudes_combustible`
FOR EACH ROW 
BEGIN
  -- Solo auditar cambios de estado importantes
  IF OLD.estado_solicitud != NEW.estado_solicitud THEN
    INSERT INTO `registro_auditoria` (
      `id_usuario`, `accion_realizada`, `tabla_afectada`, 
      `registro_afectado`, `valores_anteriores`, `valores_nuevos`,
      `ip_origen`, `modulo_sistema`, `nivel_criticidad`
    ) VALUES (
      COALESCE(NEW.id_usuario_aprobador, NEW.id_usuario_solicitante),
      CONCAT('CAMBIO_ESTADO_SOLICITUD'),
      'solicitudes_combustible',
      JSON_OBJECT('id_solicitud', NEW.id_solicitud, 'numero_solicitud', NEW.numero_solicitud),
      JSON_OBJECT('estado_solicitud', OLD.estado_solicitud),
      JSON_OBJECT('estado_solicitud', NEW.estado_solicitud, 'fecha_cambio', NOW()),
      @user_ip,
      'SOLICITUDES',
      CASE 
        WHEN NEW.estado_solicitud IN ('Aprobada', 'Rechazada') THEN 'ALTO'
        WHEN NEW.estado_solicitud = 'Despachada' THEN 'CRÍTICO'
        ELSE 'MEDIO'
      END
    );
  END IF;
END$$

-- Trigger: Actualizar kilometraje del vehículo tras consumo
CREATE TRIGGER `tr_actualizar_kilometraje_vehiculo` 
AFTER INSERT ON `consumos_combustible`
FOR EACH ROW 
BEGIN
  -- Actualizar kilometraje actual del vehículo
  UPDATE unidades_transporte 
  SET 
    kilometraje_actual = GREATEST(kilometraje_actual, NEW.kilometraje_fin),
    updated_at = CURRENT_TIMESTAMP
  WHERE id_unidad_transporte = NEW.id_unidad_transporte;
END$$

-- Trigger: Generar número de solicitud automático
CREATE TRIGGER `tr_generar_numero_solicitud` 
BEFORE INSERT ON `solicitudes_combustible`
FOR EACH ROW 
BEGIN
  DECLARE v_contador INT;
  DECLARE v_anio YEAR;
  
  SET v_anio = YEAR(NEW.fecha_solicitud);
  
  -- Obtener el siguiente número correlativo para el año
  SELECT COALESCE(MAX(CAST(SUBSTRING(numero_solicitud, -6) AS UNSIGNED)), 0) + 1
  INTO v_contador
  FROM solicitudes_combustible 
  WHERE YEAR(fecha_solicitud) = v_anio;
  
  -- Generar número de solicitud formato: SOL-2025-000001
  SET NEW.numero_solicitud = CONCAT('SOL-', v_anio, '-', LPAD(v_contador, 6, '0'));
END$$

DELIMITER ;

-- =====================================================
-- EVENTOS PROGRAMADOS PARA MANTENIMIENTO
-- =====================================================

-- Evento: Limpiar auditoría antigua (mantener 2 años)
DELIMITER $$
CREATE EVENT `ev_limpiar_auditoria_antigua`
ON SCHEDULE EVERY 1 MONTH
STARTS '2025-01-01 02:00:00'
DO
BEGIN
  -- Eliminar registros de auditoría mayores a 2 años
  DELETE FROM registro_auditoria 
  WHERE fecha_hora < DATE_SUB(NOW(), INTERVAL 2 YEAR);
  
  -- Optimizar tabla después de la limpieza
  OPTIMIZE TABLE registro_auditoria;
END$$

-- Evento: Actualizar alertas de mantenimiento vehicular
CREATE EVENT `ev_actualizar_alertas_mantenimiento`
ON SCHEDULE EVERY 1 DAY
STARTS '2025-01-01 06:00:00'
DO
BEGIN
  -- Actualizar alertas para vehículos que necesitan mantenimiento
  UPDATE unidades_transporte 
  SET observaciones = CONCAT(
    COALESCE(observaciones, ''), 
    CASE 
      WHEN proximo_mantenimiento_km IS NOT NULL AND kilometraje_actual >= proximo_mantenimiento_km 
      THEN '\n[ALERTA] Mantenimiento vencido'
      WHEN revision_tecnica_hasta IS NOT NULL AND revision_tecnica_hasta <= CURDATE() 
      THEN '\n[ALERTA] Revisión técnica vencida'
      WHEN seguro_vigente_hasta IS NOT NULL AND seguro_vigente_hasta <= CURDATE() 
      THEN '\n[ALERTA] Seguro vencido'
      ELSE ''
    END
  )
  WHERE estado_operativo = 'Operativo'
    AND (
      (proximo_mantenimiento_km IS NOT NULL AND kilometraje_actual >= proximo_mantenimiento_km) OR
      (revision_tecnica_hasta IS NOT NULL AND revision_tecnica_hasta <= CURDATE()) OR
      (seguro_vigente_hasta IS NOT NULL AND seguro_vigente_hasta <= CURDATE())
    );
END$$

-- Evento: Limpiar códigos de registro vencidos
CREATE EVENT `ev_limpiar_codigos_vencidos`
ON SCHEDULE EVERY 1 DAY
STARTS '2025-01-01 01:00:00'
DO
BEGIN
  -- Eliminar códigos vencidos no utilizados
  DELETE FROM codigos_registro 
  WHERE vigente_hasta < CURDATE() 
    AND usado = FALSE;
END$$

DELIMITER ;

-- Habilitar el scheduler de eventos
SET GLOBAL event_scheduler = ON;

-- =====================================================
-- DATOS INICIALES BÁSICOS
-- =====================================================

-- Insertar tipos de combustible básicos
INSERT INTO `tipos_combustible` (`nombre`, `codigo_comercial`, `descripcion`, `octanaje`, `precio_referencial`) VALUES
('Gasolina Especial', 'GE', 'Gasolina de 93 octanos', 93, 3.74),
('Gasolina Premium', 'GP', 'Gasolina de 97 octanos', 97, 5.33),
('Diésel Oil', 'DO', 'Combustible diésel para vehículos', NULL, 3.72),
('GNV', 'GNV', 'Gas Natural Vehicular', NULL, 1.57);

-- Insertar tipos de vehículo básicos
INSERT INTO `tipos_vehiculo` (`nombre`, `categoria`, `descripcion`, `consumo_promedio_ciudad`, `consumo_promedio_carretera`) VALUES
('Automóvil', 'Liviano', 'Vehículo liviano para transporte de personas', 12.0, 15.0),
('Camioneta', 'Liviano', 'Vehículo utilitario liviano', 10.0, 13.0),
('Microbús', 'Pesado', 'Vehículo para transporte público', 6.0, 8.0),
('Camión', 'Pesado', 'Vehículo pesado para carga', 4.0, 6.0),
('Motocicleta', 'Motocicleta', 'Vehículo de dos ruedas', 30.0, 35.0),
('Ambulancia', 'Especializado', 'Vehículo de emergencia médica', 8.0, 10.0);

-- Insertar tipos de servicio de proveedor
INSERT INTO `tipos_servicio_proveedor` (`codigo`, `nombre`, `descripcion`) VALUES
('COMB', 'Combustible', 'Proveedores de combustible y lubricantes'),
('MANT', 'Mantenimiento', 'Servicios de mantenimiento vehicular'),
('REPU', 'Repuestos', 'Proveedores de repuestos y accesorios'),
('SEGU', 'Seguros', 'Compañías de seguros vehiculares');

-- Insertar unidad organizacional principal
INSERT INTO `unidades_organizacionales` (`codigo_unidad`, `nombre_unidad`, `tipo_unidad`, `descripcion`) VALUES
('GOB-001', 'Gobernación de Cochabamba', 'Superior', 'Unidad organizacional principal'),
('SEC-TRA', 'Secretaría de Transportes', 'Ejecutiva', 'Secretaría encargada del transporte'),
('UNI-FLO', 'Unidad de Flota', 'Operativa', 'Unidad operativa de gestión de flota vehicular');

-- Insertar categorías programáticas básicas
INSERT INTO `categorias_programaticas` (`codigo`, `descripcion`, `tipo_categoria`) VALUES
('PROG-001', 'Gestión Institucional', 'Programa'),
('PROY-001', 'Modernización del Transporte', 'Proyecto'),
('ACT-001', 'Mantenimiento de Flota Vehicular', 'Actividad');

-- Insertar fuentes de financiamiento básicas
INSERT INTO `fuentes_organismo_financiero` (`codigo`, `descripcion`, `tipo_fuente`) VALUES
('TGN', 'Tesoro General de la Nación', 'Nacional'),
('REGALIAS', 'Regalías Departamentales', 'Departamental'),
('IDH', 'Impuesto Directo a los Hidrocarburos', 'Nacional');

-- Confirmar transacción
COMMIT;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- MENSAJE DE FINALIZACIÓN
-- =====================================================
SELECT 'Base de datos del Sistema de Gestión de Combustible creada exitosamente' as mensaje,
       'Versión optimizada para Gobernación de Cochabamba' as version,
       NOW() as fecha_creacion;
