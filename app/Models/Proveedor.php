<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    use HasFactory;

    protected $table = 'proveedors';

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

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tipoServicioProveedor()
    {
        return $this->belongsTo(TipoServicioProveedor::class, 'id_tipo_servicio_proveedor');
    }

    // Mantener compatibilidad con nombre anterior
    public function tipoServicio()
    {
        return $this->tipoServicioProveedor();
    }

    // Accessors para compatibilidad con los componentes Livewire
    public function getRutAttribute()
    {
        return $this->nit;
    }

    public function setRutAttribute($value)
    {
        $this->nit = $value;
    }

    public function getRazonSocialAttribute()
    {
        return $this->nombre_proveedor;
    }

    public function setRazonSocialAttribute($value)
    {
        $this->nombre_proveedor = $value;
    }

    public function getTipoServicioProveedorIdAttribute()
    {
        return $this->id_tipo_servicio_proveedor;
    }

    public function setTipoServicioProveedorIdAttribute($value)
    {
        $this->id_tipo_servicio_proveedor = $value;
    }

    public function despachos()
    {
        return $this->hasMany(DespachoCombustible::class, 'id_proveedor');
    }
}
