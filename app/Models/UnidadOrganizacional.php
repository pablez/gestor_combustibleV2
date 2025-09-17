<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadOrganizacional extends Model
{
    use HasFactory;
    protected $table = 'unidades_organizacionales';
    protected $primaryKey = 'id_unidad_organizacional';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'codigo_unidad',
        'nombre_unidad',
        'tipo_unidad',
        'id_unidad_padre',
        'nivel_jerarquico',
        'responsable_unidad',
        'telefono',
        'direccion',
        'presupuesto_asignado',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'presupuesto_asignado' => 'decimal:2',
    ];

    public $timestamps = true;
}
