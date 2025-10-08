<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre_proveedor',
        'nombre_comercial',
        'nit',
        'direccion',
        'telefono',
        'email',
        'id_tipo_servicio_proveedor',
        'contacto_principal',
        'calificacion',
        'observaciones',
        'activo',
    ];

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicioProveedor::class, 'id_tipo_servicio_proveedor');
    }

    public function despachos()
    {
        return $this->hasMany(DespachoCombustible::class, 'id_proveedor');
    }
}
