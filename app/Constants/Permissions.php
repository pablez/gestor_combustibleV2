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
    public const DESPACHOS_VALIDAR = 'despachos.validar';
}
