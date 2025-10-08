<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    /** @use HasFactory<\Database\Factories\PresupuestoFactory> */
    use HasFactory;

    protected $fillable = [
        'id_cat_programatica',
        'id_fuente_org_fin',
        'id_unidad_organizacional',
        'anio_fiscal',
        'trimestre',
        'presupuesto_inicial',
        'presupuesto_actual',
        'total_gastado',
        'total_comprometido',
        'num_documento',
        'numero_comprobante',
        'fecha_aprobacion',
        'porcentaje_preventivo',
        'alerta_porcentaje',
        'activo',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aprobacion' => 'date',
        'activo' => 'boolean',
        'presupuesto_inicial' => 'decimal:2',
        'presupuesto_actual' => 'decimal:2',
        'total_gastado' => 'decimal:2',
        'total_comprometido' => 'decimal:2',
        'porcentaje_preventivo' => 'decimal:2',
        'alerta_porcentaje' => 'decimal:2',
    ];

    // Relaciones
    public function categoriaProgramatica()
    {
        return $this->belongsTo(CategoriaProgramatica::class, 'id_cat_programatica');
    }

    public function fuenteOrganismoFinanciero()
    {
        return $this->belongsTo(FuenteOrganismoFinanciero::class, 'id_fuente_org_fin');
    }

    public function unidadOrganizacional()
    {
        return $this->belongsTo(UnidadOrganizacional::class, 'id_unidad_organizacional');
    }

    // Accessors y métodos útiles
    public function getSaldoDisponibleAttribute()
    {
        return $this->presupuesto_actual - $this->total_gastado - $this->total_comprometido;
    }

    public function getPorcentajeEjecutadoAttribute()
    {
        if ($this->presupuesto_inicial > 0) {
            return round(($this->total_gastado / $this->presupuesto_inicial) * 100, 2);
        }
        return 0;
    }

    public function getEstaCercaLimiteAttribute()
    {
        return $this->porcentaje_ejecutado >= $this->alerta_porcentaje;
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorAnio($query, $anio)
    {
        return $query->where('anio_fiscal', $anio);
    }
}
