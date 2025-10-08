<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    /** @use HasFactory<\Database\Factories\TipoCombustibleFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo_comercial',
        'descripcion',
        'octanaje',
        'precio_referencial',
        'unidad_medida',
        'activo',
    ];
}
