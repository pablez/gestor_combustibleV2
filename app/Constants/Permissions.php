<?php

namespace App\Constants;

final class Permissions
{
    // Usuarios
    public const USUARIOS_VER = 'usuarios.ver';
    public const USUARIOS_CREAR = 'usuarios.crear';
    public const USUARIOS_EDITAR = 'usuarios.editar';
    public const USUARIOS_ELIMINAR = 'usuarios.eliminar';
    public const USUARIOS_GESTIONAR = 'usuarios.gestionar';

    // Unidades
    public const UNIDADES_VER = 'unidades.ver';
    public const UNIDADES_CREAR = 'unidades.crear';
    public const UNIDADES_EDITAR = 'unidades.editar';
    public const UNIDADES_ELIMINAR = 'unidades.eliminar';

    // Solicitudes
    public const SOLICITUDES_VER = 'solicitudes.ver';
    public const SOLICITUDES_CREAR = 'solicitudes.crear';
    public const SOLICITUDES_EDITAR = 'solicitudes.editar';
    public const SOLICITUDES_APROBAR = 'solicitudes.aprobar';

    // Despachos
    public const DESPACHOS_VER = 'despachos.ver';
    public const DESPACHOS_CREAR = 'despachos.crear';
    public const DESPACHOS_EDITAR = 'despachos.editar';
    public const DESPACHOS_ELIMINAR = 'despachos.eliminar';
    public const DESPACHOS_VALIDAR = 'despachos.validar';

    // Consumos
    public const CONSUMOS_VER = 'consumos.ver';
    public const CONSUMOS_CREAR = 'consumos.crear';
    public const CONSUMOS_EDITAR = 'consumos.editar';
    public const CONSUMOS_ELIMINAR = 'consumos.eliminar';
    public const CONSUMOS_VALIDAR = 'consumos.validar';

    // Proveedores
    public const PROVEEDORES_VER = 'proveedores.ver';
    public const PROVEEDORES_CREAR = 'proveedores.crear';
    public const PROVEEDORES_EDITAR = 'proveedores.editar';
    public const PROVEEDORES_ELIMINAR = 'proveedores.eliminar';

    // Tipos de Servicio de Proveedor
    public const TIPOS_SERVICIO_PROVEEDOR_VER = 'tipos-servicio-proveedor.ver';
    public const TIPOS_SERVICIO_PROVEEDOR_CREAR = 'tipos-servicio-proveedor.crear';
    public const TIPOS_SERVICIO_PROVEEDOR_EDITAR = 'tipos-servicio-proveedor.editar';
    public const TIPOS_SERVICIO_PROVEEDOR_ELIMINAR = 'tipos-servicio-proveedor.eliminar';

    // Presupuestos
    public const PRESUPUESTOS_VER = 'presupuestos.ver';
    public const PRESUPUESTOS_CREAR = 'presupuestos.crear';
    public const PRESUPUESTOS_EDITAR = 'presupuestos.editar';
    public const PRESUPUESTOS_ELIMINAR = 'presupuestos.eliminar';

    // Solicitudes de Aprobación de Usuario
    public const SOLICITUDES_APROBACION_VER = 'solicitudes_aprobacion.ver';
    public const SOLICITUDES_APROBACION_CREAR = 'solicitudes_aprobacion.crear';
    public const SOLICITUDES_APROBACION_APROBAR = 'solicitudes_aprobacion.aprobar';
    public const SOLICITUDES_APROBACION_RECHAZAR = 'solicitudes_aprobacion.rechazar';

    // Códigos de Registro
    public const CODIGOS_REGISTRO_VER = 'codigos_registro.ver';
    public const CODIGOS_REGISTRO_CREAR = 'codigos_registro.crear';
    public const CODIGOS_REGISTRO_ELIMINAR = 'codigos_registro.eliminar';

    // Reportes
    public const REPORTES_VER = 'reportes.ver';
    public const REPORTES_COMBUSTIBLE = 'reportes.combustible';
    public const REPORTES_PRESUPUESTO = 'reportes.presupuesto';
    public const REPORTES_GENERAR = 'reportes.generar';
}
