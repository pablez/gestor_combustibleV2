-- =====================================================
-- SISTEMA DE GESTIÓN DE COMBUSTIBLE
-- GOBERNACIÓN DE COCHABAMBA - BOLIVIA
-- Base de Datos MySQL 8.0
-- =====================================================

-- Configuración inicial
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `sistema_combustible_gobernacion` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `sistema_combustible_gobernacion`;

-- =====================================================
-- TABLAS DE CONFIGURACIÓN Y CATÁLOGOS
-- =====================================================

-- Tabla: tipos_combustible
CREATE TABLE `tipos_combustible` (
  `id_tipo_combustible` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_tipo_combustible`),
  UNIQUE KEY `uk_tipo_combustible_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: tipos_vehiculo
CREATE TABLE `tipos_vehiculo` (
  `id_tipo_vehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_tipo_vehiculo`),
  UNIQUE KEY `uk_tipo_vehiculo_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: tipos_servicio_proveedor
CREATE TABLE `tipos_servicio_proveedor` (
  `id_tipo_servicio_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_tipo_servicio_proveedor`),
  UNIQUE KEY `uk_tipo_servicio_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: unidades_organizacionales
CREATE TABLE `unidades_organizacionales` (
  `id_unidad_organizacional` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_unidad` varchar(100) NOT NULL,
  `tipo_unidad` enum('Superior','Ejecutiva') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_unidad_organizacional`),
  UNIQUE KEY `uk_unidad_organizacional_nombre` (`nombre_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: categorias_programaticas
CREATE TABLE `categorias_programaticas` (
  `id_cat_programatica` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(60) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_cat_programatica`),
  UNIQUE KEY `uk_categoria_programatica_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: fuentes_organismo_financiero
CREATE TABLE `fuentes_organismo_financiero` (
  `id_fuente_org_fin` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(60) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_fuente_org_fin`),
  UNIQUE KEY `uk_fuente_organismo_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE GESTIÓN DE USUARIOS
-- =====================================================

-- Tabla: usuarios
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('Admin_General','Admin_Secretaria','Supervisor','Conductor','Operator') NOT NULL,
  `id_supervisor` int(11) DEFAULT NULL,
  `id_unidad_organizacional` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_usuario_username` (`username`),
  UNIQUE KEY `uk_usuario_email` (`email`),
  KEY `fk_usuario_supervisor` (`id_supervisor`),
  KEY `fk_usuario_unidad_organizacional` (`id_unidad_organizacional`),
  CONSTRAINT `fk_usuario_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_unidad_organizacional` FOREIGN KEY (`id_unidad_organizacional`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: codigos_registro
CREATE TABLE `codigos_registro` (
  `id_codigo_registro` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(15) NOT NULL,
  `vigente_hasta` timestamp NOT NULL,
  `id_usuario_creador` int(11) NOT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_uso` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_codigo_registro`),
  UNIQUE KEY `uk_codigo_registro_codigo` (`codigo`),
  KEY `fk_codigo_registro_usuario` (`id_usuario_creador`),
  CONSTRAINT `fk_codigo_registro_usuario` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: solicitudes_aprobacion_usuario
CREATE TABLE `solicitudes_aprobacion_usuario` (
  `id_solicitud_aprobacion_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_creador` int(11) NOT NULL,
  `id_supervisor_asignado` int(11) DEFAULT NULL,
  `id_codigo_registro` int(11) NOT NULL,
  `username_solicitado` varchar(50) NOT NULL,
  `nombre_solicitado` varchar(100) NOT NULL,
  `email_solicitado` varchar(100) DEFAULT NULL,
  `telefono_solicitado` varchar(20) DEFAULT NULL,
  `tipo_solicitud` enum('nuevo_usuario','cambio_rol','reactivacion') NOT NULL DEFAULT 'nuevo_usuario',
  `estado_solicitud` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `rol_solicitado` varchar(50) DEFAULT NULL,
  `observaciones_solicitud` text DEFAULT NULL,
  `observaciones_aprobacion` text DEFAULT NULL,
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `id_usuario_aprobador` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_solicitud_aprobacion_usuario`),
  KEY `fk_solicitud_usuario` (`id_usuario`),
  KEY `fk_solicitud_creador` (`id_creador`),
  KEY `fk_solicitud_supervisor` (`id_supervisor_asignado`),
  KEY `fk_solicitud_codigo_registro` (`id_codigo_registro`),
  KEY `fk_solicitud_aprobador` (`id_usuario_aprobador`),
  CONSTRAINT `fk_solicitud_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_creador` FOREIGN KEY (`id_creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_supervisor` FOREIGN KEY (`id_supervisor_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_codigo_registro` FOREIGN KEY (`id_codigo_registro`) REFERENCES `codigos_registro` (`id_codigo_registro`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_aprobador` FOREIGN KEY (`id_usuario_aprobador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: registro_auditoria
CREATE TABLE `registro_auditoria` (
  `id_registro_auditoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `accion_realizada` varchar(100) NOT NULL,
  `tabla_afectada` varchar(100) NOT NULL,
  `registro_afectado` text NOT NULL,
  `datos_anteriores` json DEFAULT NULL,
  `datos_nuevos` json DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_registro_auditoria`),
  KEY `fk_auditoria_usuario` (`id_usuario`),
  KEY `idx_auditoria_fecha` (`fecha_hora`),
  KEY `idx_auditoria_tabla` (`tabla_afectada`),
  CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE PROVEEDORES
-- =====================================================

-- Tabla: proveedores
CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_proveedor` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nit` varchar(20) NOT NULL,
  `id_tipo_servicio_proveedor` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_proveedor`),
  UNIQUE KEY `uk_proveedor_nit` (`nit`),
  KEY `fk_proveedor_tipo_servicio` (`id_tipo_servicio_proveedor`),
  CONSTRAINT `fk_proveedor_tipo_servicio` FOREIGN KEY (`id_tipo_servicio_proveedor`) REFERENCES `tipos_servicio_proveedor` (`id_tipo_servicio_proveedor`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE GESTIÓN DE FLOTA
-- =====================================================

-- Tabla: unidades_transporte
CREATE TABLE `unidades_transporte` (
  `id_unidad_transporte` int(11) NOT NULL AUTO_INCREMENT,
  `placa` varchar(20) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` int(4) DEFAULT NULL,
  `color` varchar(30) NOT NULL,
  `id_tipo_vehiculo` int(11) NOT NULL,
  `id_tipo_combustible` int(11) NOT NULL,
  `capacidad_tanque` decimal(10,2) NOT NULL,
  `rendimiento_promedio` decimal(8,2) DEFAULT NULL COMMENT 'km por litro',
  `kilometraje_actual` int(11) NOT NULL DEFAULT 0,
  `id_unidad_organizacional` int(11) NOT NULL,
  `id_conductor_asignado` int(11) DEFAULT NULL,
  `estado_operativo` varchar(50) NOT NULL DEFAULT 'Operativo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_unidad_transporte`),
  UNIQUE KEY `uk_unidad_transporte_placa` (`placa`),
  KEY `fk_unidad_tipo_vehiculo` (`id_tipo_vehiculo`),
  KEY `fk_unidad_tipo_combustible` (`id_tipo_combustible`),
  KEY `fk_unidad_organizacional` (`id_unidad_organizacional`),
  KEY `fk_unidad_conductor` (`id_conductor_asignado`),
  CONSTRAINT `fk_unidad_tipo_vehiculo` FOREIGN KEY (`id_tipo_vehiculo`) REFERENCES `tipos_vehiculo` (`id_tipo_vehiculo`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_unidad_tipo_combustible` FOREIGN KEY (`id_tipo_combustible`) REFERENCES `tipos_combustible` (`id_tipo_combustible`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_unidad_organizacional` FOREIGN KEY (`id_unidad_organizacional`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_unidad_conductor` FOREIGN KEY (`id_conductor_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE GESTIÓN PRESUPUESTARIA
-- =====================================================

-- Tabla: presupuestos
CREATE TABLE `presupuestos` (
  `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat_programatica` int(11) NOT NULL,
  `id_fuente_org_fin` int(11) NOT NULL,
  `id_unidad_organizacional` int(11) NOT NULL,
  `presupuesto_inicial` decimal(14,2) NOT NULL,
  `presupuesto_actual` decimal(14,2) NOT NULL,
  `total_gastado` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_comprometido` decimal(14,2) NOT NULL DEFAULT 0.00,
  `num_documento` varchar(100) NOT NULL,
  `numero_comprobante` varchar(100) NOT NULL,
  `porcentaje_preventivo` decimal(5,2) DEFAULT 10.00 COMMENT 'Porcentaje de alerta preventiva',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `anio_fiscal` int(4) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_presupuesto`),
  KEY `fk_presupuesto_categoria` (`id_cat_programatica`),
  KEY `fk_presupuesto_fuente` (`id_fuente_org_fin`),
  KEY `fk_presupuesto_unidad` (`id_unidad_organizacional`),
  KEY `idx_presupuesto_anio` (`anio_fiscal`),
  CONSTRAINT `fk_presupuesto_categoria` FOREIGN KEY (`id_cat_programatica`) REFERENCES `categorias_programaticas` (`id_cat_programatica`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presupuesto_fuente` FOREIGN KEY (`id_fuente_org_fin`) REFERENCES `fuentes_organismo_financiero` (`id_fuente_org_fin`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_presupuesto_unidad` FOREIGN KEY (`id_unidad_organizacional`) REFERENCES `unidades_organizacionales` (`id_unidad_organizacional`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS DE GESTIÓN DE COMBUSTIBLE
-- =====================================================

-- Tabla: solicitudes_combustible
CREATE TABLE `solicitudes_combustible` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `numero_solicitud` varchar(20) NOT NULL,
  `id_usuario_solicitante` int(11) NOT NULL,
  `id_unidad_transporte` int(11) NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `cantidad_litros_solicitados` decimal(10,2) NOT NULL,
  `motivo` text NOT NULL,
  `destino` varchar(255) DEFAULT NULL,
  `fecha_viaje_programado` date DEFAULT NULL,
  `estado_solicitud` enum('Pendiente','Aprobada','Rechazada','Despachada','Cancelada') NOT NULL DEFAULT 'Pendiente',
  `id_usuario_aprobador` int(11) DEFAULT NULL,
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `id_cat_programatica` int(11) NOT NULL,
  `id_fuente_org_fin` int(11) NOT NULL,
  `saldo_actual_combustible` decimal(10,2) DEFAULT NULL,
  `km_actual` int(11) NOT NULL,
  `km_recorr` int(11) NOT NULL,
  `observaciones_solicitud` text DEFAULT NULL,
  `observaciones_aprobacion` text DEFAULT NULL,
  `prioridad` enum('Baja','Normal','Alta','Urgente') NOT NULL DEFAULT 'Normal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_solicitud`),
  UNIQUE KEY `uk_solicitud_numero` (`numero_solicitud`),
  KEY `fk_solicitud_usuario_solicitante` (`id_usuario_solicitante`),
  KEY `fk_solicitud_unidad_transporte` (`id_unidad_transporte`),
  KEY `fk_solicitud_usuario_aprobador` (`id_usuario_aprobador`),
  KEY `fk_solicitud_categoria_programatica` (`id_cat_programatica`),
  KEY `fk_solicitud_fuente_financiera` (`id_fuente_org_fin`),
  KEY `idx_solicitud_estado` (`estado_solicitud`),
  KEY `idx_solicitud_fecha` (`fecha_solicitud`),
  CONSTRAINT `fk_solicitud_usuario_solicitante` FOREIGN KEY (`id_usuario_solicitante`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_unidad_transporte` FOREIGN KEY (`id_unidad_transporte`) REFERENCES `unidades_transporte` (`id_unidad_transporte`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_usuario_aprobador` FOREIGN KEY (`id_usuario_aprobador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_categoria_programatica` FOREIGN KEY (`id_cat_programatica`) REFERENCES `categorias_programaticas` (`id_cat_programatica`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_fuente_financiera` FOREIGN KEY (`id_fuente_org_fin`) REFERENCES `fuentes_organismo_financiero` (`id_fuente_org_fin`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: despachos_combustible
CREATE TABLE `despachos_combustible` (
  `id_despacho` int(11) NOT NULL AUTO_INCREMENT,
  `numero_despacho` varchar(20) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_despacho` timestamp NOT NULL DEFAULT current_timestamp(),
  `litros_despachados` decimal(10,2) NOT NULL,
  `precio_por_litro` decimal(8,2) NOT NULL,
  `costo_total` decimal(10,2) NOT NULL,
  `numero_vale` varchar(50) NOT NULL,
  `numero_factura` varchar(50) DEFAULT NULL,
  `id_usuario_despachador` int(11) NOT NULL,
  `kilometraje_al_despacho` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado_despacho` enum('Completado','Parcial','Cancelado') NOT NULL DEFAULT 'Completado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_despacho`),
  UNIQUE KEY `uk_despacho_numero` (`numero_despacho`),
  UNIQUE KEY `uk_despacho_vale` (`numero_vale`),
  KEY `fk_despacho_solicitud` (`id_solicitud`),
  KEY `fk_despacho_proveedor` (`id_proveedor`),
  KEY `fk_despacho_usuario_despachador` (`id_usuario_despachador`),
  KEY `idx_despacho_fecha` (`fecha_despacho`),
  CONSTRAINT `fk_despacho_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_combustible` (`id_solicitud`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_despacho_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_despacho_usuario_despachador` FOREIGN KEY (`id_usuario_despachador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: consumos_combustible
CREATE TABLE `consumos_combustible` (
  `id_consumo` int(11) NOT NULL AUTO_INCREMENT,
  `id_unidad_transporte` int(11) NOT NULL,
  `id_despacho` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `kilometraje_inicial` int(11) NOT NULL,
  `kilometraje_fin` int(11) NOT NULL,
  `kilometros_recorridos` int(11) GENERATED ALWAYS AS (`kilometraje_fin` - `kilometraje_inicial`) STORED,
  `litros_cargados` decimal(10,2) NOT NULL,
  `rendimiento_calculado` decimal(8,2) GENERATED ALWAYS AS (`kilometros_recorridos` / `litros_cargados`) STORED COMMENT 'km por litro',
  `tipo_recorrido` enum('Urbano','Carretera','Mixto') DEFAULT 'Mixto',
  `observaciones` text DEFAULT NULL,
  `validado` tinyint(1) NOT NULL DEFAULT 0,
  `id_usuario_validador` int(11) DEFAULT NULL,
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_consumo`),
  KEY `fk_consumo_unidad_transporte` (`id_unidad_transporte`),
  KEY `fk_consumo_despacho` (`id_despacho`),
  KEY `fk_consumo_validador` (`id_usuario_validador`),
  KEY `idx_consumo_fecha` (`fecha_registro`),
  CONSTRAINT `fk_consumo_unidad_transporte` FOREIGN KEY (`id_unidad_transporte`) REFERENCES `unidades_transporte` (`id_unidad_transporte`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_consumo_despacho` FOREIGN KEY (`id_despacho`) REFERENCES `despachos_combustible` (`id_despacho`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_consumo_validador` FOREIGN KEY (`id_usuario_validador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS ADICIONALES PARA SPATIE LARAVEL PERMISSION
-- =====================================================

-- Tabla: permissions
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: roles
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: model_has_permissions
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: model_has_roles
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: role_has_permissions
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices compuestos para consultas frecuentes
CREATE INDEX `idx_solicitud_estado_fecha` ON `solicitudes_combustible` (`estado_solicitud`, `fecha_solicitud`);
CREATE INDEX `idx_despacho_proveedor_fecha` ON `despachos_combustible` (`id_proveedor`, `fecha_despacho`);
CREATE INDEX `idx_consumo_unidad_fecha` ON `consumos_combustible` (`id_unidad_transporte`, `fecha_registro`);
CREATE INDEX `idx_presupuesto_unidad_anio` ON `presupuestos` (`id_unidad_organizacional`, `anio_fiscal`);
CREATE INDEX `idx_usuario_rol_activo` ON `usuarios` (`rol`, `activo`);

-- =====================================================
-- TRIGGERS PARA AUDITORÍA AUTOMÁTICA
-- =====================================================

DELIMITER $$

-- Trigger para auditoría en solicitudes_combustible
CREATE TRIGGER `tr_solicitudes_combustible_audit_insert` 
AFTER INSERT ON `solicitudes_combustible`
FOR EACH ROW 
BEGIN
  INSERT INTO `registro_auditoria` (
    `id_usuario`, `accion_realizada`, `tabla_afectada`, 
    `registro_afectado`, `datos_nuevos`, `ip_origen`
  ) VALUES (
    NEW.id_usuario_solicitante, 'INSERT', 'solicitudes_combustible',
    NEW.id_solicitud, JSON_OBJECT('nueva_solicitud', NEW.numero_solicitud), 
    @user_ip
  );
END$$

CREATE TRIGGER `tr_solicitudes_combustible_audit_update` 
AFTER UPDATE ON `solicitudes_combustible`
FOR EACH ROW 
BEGIN
  INSERT INTO `registro_auditoria` (
    `id_usuario`, `accion_realizada`, `tabla_afectada`, 
    `registro_afectado`, `datos_anteriores`, `datos_nuevos`, `ip_origen`
  ) VALUES (
    COALESCE(NEW.id_usuario_aprobador, NEW.id_usuario_solicitante), 'UPDATE', 'solicitudes_combustible',
    NEW.id_solicitud, 
    JSON_OBJECT('estado_anterior', OLD.estado_solicitud),
    JSON_OBJECT('estado_nuevo', NEW.estado_solicitud),
    @user_ip
  );
END$$

-- Trigger para auditoría en despachos_combustible
CREATE TRIGGER `tr_despachos_combustible_audit_insert` 
AFTER INSERT ON `despachos_combustible`
FOR EACH ROW 
BEGIN
  INSERT INTO `registro_auditoria` (
    `id_usuario`, `accion_realizada`, `tabla_afectada`, 
    `registro_afectado`, `datos_nuevos`, `ip_origen`
  ) VALUES (
    NEW.id_usuario_despachador, 'INSERT', 'despachos_combustible',
    NEW.id_despacho, 
    JSON_OBJECT('nuevo_despacho', NEW.numero_despacho, 'litros', NEW.litros_despachados), 
    @user_ip
  );
END$$

-- Trigger para actualizar presupuesto al despachar
CREATE TRIGGER `tr_actualizar_presupuesto_despacho` 
AFTER INSERT ON `despachos_combustible`
FOR EACH ROW 
BEGIN
  DECLARE v_cat_programatica INT;
  DECLARE v_fuente_org_fin INT;
  
  -- Obtener categoría programática y fuente de la solicitud
  SELECT id_cat_programatica, id_fuente_org_fin 
  INTO v_cat_programatica, v_fuente_org_fin
  FROM solicitudes_combustible 
  WHERE id_solicitud = NEW.id_solicitud;
  
  -- Actualizar presupuesto
  UPDATE presupuestos 
  SET total_gastado = total_gastado + NEW.costo_total,
      presupuesto_actual = presupuesto_actual - NEW.costo_total
  WHERE id_cat_programatica = v_cat_programatica 
    AND id_fuente_org_fin = v_fuente_org_fin
    AND anio_fiscal = YEAR(CURRENT_DATE);
END$$

-- Trigger para actualizar kilometraje del vehículo
CREATE TRIGGER `tr_actualizar_kilometraje_vehiculo` 
AFTER INSERT ON `consumos_combustible`
FOR EACH ROW 
BEGIN
  UPDATE unidades_transporte 
  SET kilometraje_actual = NEW.kilometraje_fin
  WHERE id_unidad_transporte = NEW.id_unidad_transporte;
END$$

DELIMITER ;

-- =====================================================
-- VISTAS PARA REPORTES FRECUENTES
-- =====================================================

-- Vista: Resumen de solicitudes por estado
CREATE VIEW `v_resumen_solicitudes_estado` AS
SELECT 
  s.estado_solicitud,
  COUNT(*) as total_solicitudes,
  SUM(s.cantidad_litros_solicitados) as total_litros_solicitados,
  AVG(s.cantidad_litros_solicitados) as promedio_litros,
  MONTH(s.fecha_solicitud) as mes,
  YEAR(s.fecha_solicitud) as anio
FROM solicitudes_combustible s
GROUP BY s.estado_solicitud, YEAR(s.fecha_solicitud), MONTH(s.fecha_solicitud);

-- Vista: Consumo por vehículo
CREATE VIEW `v_consumo_por_vehiculo` AS
SELECT 
  ut.placa,
  ut.marca,
  ut.modelo,
  uo.nombre_unidad,
  COUNT(c.id_consumo) as total_registros_consumo,
  SUM(c.kilometros_recorridos) as total_km_recorridos,
  SUM(c.litros_cargados) as total_litros_consumidos,
  AVG(c.rendimiento_calculado) as rendimiento_promedio,
  MAX(c.fecha_registro) as ultimo_registro
FROM unidades_transporte ut
LEFT JOIN consumos_combustible c ON ut.id_unidad_transporte = c.id_unidad_transporte
LEFT JOIN unidades_organizacionales uo ON ut.id_unidad_organizacional = uo.id_unidad_organizacional
GROUP BY ut.id_unidad_transporte;

-- Vista: Estado presupuestario
CREATE VIEW `v_estado_presupuestario` AS
SELECT 
  p.id_presupuesto,
  cp.descripcion as categoria_programatica,
  fof.descripcion as fuente_financiera,
  uo.nombre_unidad,
  p.presupuesto_inicial,
  p.presupuesto_actual,
  p.total_gastado,
  p.total_comprometido,
  ROUND((p.total_gastado / p.presupuesto_inicial) * 100, 2) as porcentaje_ejecutado,
  p.anio_fiscal,
  CASE 
    WHEN (p.total_gastado / p.presupuesto_inicial) * 100 >= 90 THEN 'CRÍTICO'
    WHEN (p.total_gastado / p.presupuesto_inicial) * 100 >= 70 THEN 'ALERTA'
    ELSE 'NORMAL'
  END as estado_presupuestario
FROM presupuestos p
JOIN categorias_programaticas cp ON p.id_cat_programatica = cp.id_cat_programatica
JOIN fuentes_organismo_financiero fof ON p.id_fuente_org_fin = fof.id_fuente_org_fin
JOIN unidades_organizacionales uo ON p.id_unidad_organizacional = uo.id_unidad_organizacional;

-- =====================================================
-- FUNCIONES UTILITARIAS
-- =====================================================

DELIMITER $$

-- Función para generar código de registro único
CREATE FUNCTION `f_generar_codigo_registro`() 
RETURNS VARCHAR(15)
READS SQL DATA
DETERMINISTIC
BEGIN
  DECLARE codigo VARCHAR(15);
  DECLARE existe INT DEFAULT 1;
  
  WHILE existe > 0 DO
    SET codigo = CONCAT(
      'GC',
      YEAR(CURRENT_DATE),
      LPAD(FLOOR(RAND() * 999999), 6, '0'),
      CHAR(65 + FLOOR(RAND() * 26)),
      CHAR(65 + FLOOR(RAND() * 26))
    );
    
    SELECT COUNT(*) INTO existe 
    FROM codigos_registro 
    WHERE codigo = codigo;
  END WHILE;
  
  RETURN codigo;
END$$

-- Función para calcular rendimiento promedio de vehículo
CREATE FUNCTION `f_calcular_rendimiento_promedio`(vehiculo_id INT, meses INT)
RETURNS DECIMAL(8,2)
READS SQL DATA
DETERMINISTIC
BEGIN
  DECLARE rendimiento_promedio DECIMAL(8,2);
  
  SELECT AVG(rendimiento_calculado)
  INTO rendimiento_promedio
  FROM consumos_combustible
  WHERE id_unidad_transporte = vehiculo_id
    AND fecha_registro >= DATE_SUB(CURRENT_DATE, INTERVAL meses MONTH)
    AND validado = 1;
    
  RETURN COALESCE(rendimiento_promedio, 0);
END$$

DELIMITER ;

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS
-- =====================================================

DELIMITER $$

-- Procedimiento para aprobar solicitud de combustible
CREATE PROCEDURE `sp_aprobar_solicitud_combustible`(
  IN p_id_solicitud INT,
  IN p_id_usuario_aprobador INT,
  IN p_observaciones TEXT
)
BEGIN
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;
  
  START TRANSACTION;
  
  UPDATE solicitudes_combustible 
  SET estado_solicitud = 'Aprobada',
      id_usuario_aprobador = p_id_usuario_aprobador,
      fecha_aprobacion = CURRENT_TIMESTAMP,
      observaciones_aprobacion = p_observaciones
  WHERE id_solicitud = p_id_solicitud
    AND estado_solicitud = 'Pendiente';
  
  IF ROW_COUNT() = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se pudo aprobar la solicitud';
  END IF;
  
  COMMIT;
END$$

-- Procedimiento para rechazar solicitud de combustible
CREATE PROCEDURE `sp_rechazar_solicitud_combustible`(
  IN p_id_solicitud INT,
  IN p_id_usuario_aprobador INT,
  IN p_observaciones TEXT
)
BEGIN
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;
  
  START TRANSACTION;
  
  UPDATE solicitudes_combustible 
  SET estado_solicitud = 'Rechazada',
      id_usuario_aprobador = p_id_usuario_aprobador,
      fecha_aprobacion = CURRENT_TIMESTAMP,
      observaciones_aprobacion = p_observaciones
  WHERE id_solicitud = p_id_solicitud
    AND estado_solicitud = 'Pendiente';
  
  IF ROW_COUNT() = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se pudo rechazar la solicitud';
  END IF;
  
  COMMIT;
END$$

DELIMITER ;

-- =====================================================
-- CONFIGURACIÓN FINAL
-- =====================================================

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- Comentarios finales sobre la estructura
-- Esta base de datos está optimizada para:
-- 1. Trazabilidad completa de todas las operaciones
-- 2. Control presupuestario en tiempo real  
-- 3. Gestión eficiente de roles y permisos
-- 4. Flujo controlado de registro de usuarios
-- 5. Integración con WhatsApp para notificaciones
-- 6. Reportes automatizados y análisis de rendimiento
-- 7. Compatibilidad total con Laravel 12 y Spatie Permissions
