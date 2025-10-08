<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DespachoCombustible extends Model
{
    /** @use HasFactory<\Database\Factories\DespachoCombustibleFactory> */
    use HasFactory;

    protected $fillable = [
        'id_solicitud',
        'id_proveedor',
        'fecha_despacho',
        'litros_despachados',
        'precio_por_litro',
        'costo_total',
        'numero_vale',
        'numero_factura',
        'id_usuario_despachador',
        'ubicacion_despacho',
        'observaciones',
        'validado',
        'fecha_validacion',
        'id_usuario_validador',
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudCombustible::class, 'id_solicitud');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function despachador()
    {
        return $this->belongsTo(User::class, 'id_usuario_despachador');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'id_usuario_validador');
    }

    public function consumos()
    {
        return $this->hasMany(ConsumoCombustible::class, 'id_despacho');
    }
}
