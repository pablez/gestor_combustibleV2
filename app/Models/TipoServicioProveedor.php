<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicioProveedor extends Model
{
    /** @use HasFactory<\Database\Factories\TipoServicioProveedorFactory> */
    use HasFactory;

    protected $table = 'tipo_servicio_proveedors';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'requiere_autorizacion_especial',
        'dias_credito_maximo',
        'activo',
    ];

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class, 'id_tipo_servicio_proveedor');
    }
}
