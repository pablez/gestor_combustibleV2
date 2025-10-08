<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumoCombustible extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumoCombustibleFactory> */
    use HasFactory;

    protected $fillable = [
        'id_unidad_transporte',
        'id_despacho',
        'id_usuario_conductor',
        'fecha_registro',
        'kilometraje_inicial',
        'kilometraje_fin',
        'litros_cargados',
        'tipo_carga',
        'lugar_carga',
        'numero_ticket',
        'observaciones',
        'validado',
        'fecha_validacion',
        'id_usuario_validador',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'fecha_validacion' => 'datetime',
        'validado' => 'boolean',
        'litros_cargados' => 'decimal:3',
    ];

    // Relaciones
    public function unidadTransporte()
    {
        return $this->belongsTo(UnidadTransporte::class, 'id_unidad_transporte');
    }

    public function despacho()
    {
        return $this->belongsTo(DespachoCombustible::class, 'id_despacho');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'id_usuario_conductor');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'id_usuario_validador');
    }

    // Accessor para kilometros recorridos
    public function getKilometrosRecorridosAttribute()
    {
        return $this->kilometraje_fin - $this->kilometraje_inicial;
    }

    // Accessor para rendimiento (km por litro)
    public function getRendimientoAttribute()
    {
        if ($this->litros_cargados > 0) {
            return round($this->kilometros_recorridos / $this->litros_cargados, 2);
        }
        return 0;
    }
}
