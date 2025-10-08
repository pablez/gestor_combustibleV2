<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    /** @use HasFactory<\Database\Factories\TipoVehiculoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'consumo_promedio_ciudad',
        'consumo_promedio_carretera',
        'capacidad_carga_kg',
        'numero_pasajeros',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'consumo_promedio_ciudad' => 'decimal:2',
        'consumo_promedio_carretera' => 'decimal:2',
    ];

    /**
     * Relación con UnidadTransporte
     */
    public function unidadesTransporte()
    {
        return $this->hasMany(UnidadTransporte::class, 'id_tipo_vehiculo');
    }
}
