<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenVehiculo extends Model
{
    /** @use HasFactory<\Database\Factories\ImagenVehiculoFactory> */
    use HasFactory;

    protected $fillable = [
        'unidad_transporte_id',
        'ruta',
        'disk',
        'tipo',
        'metadatos',
        'original_nombre',
        'mime',
        'size',
        'creado_por'
    ];

    protected $casts = [
        'metadatos' => 'array'
    ];

    public function unidadTransporte()
    {
        return $this->belongsTo(UnidadTransporte::class, 'unidad_transporte_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
