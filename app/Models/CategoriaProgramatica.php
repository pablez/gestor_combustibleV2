<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProgramatica extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaProgramaticaFactory> */
    use HasFactory;

    protected $fillable = [
        'codigo',
        'descripcion',
        'tipo_categoria',
        'id_categoria_padre',
        'nivel',
        'activo',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'nivel' => 'integer',
    ];

    // Relación con categoría padre
    public function categoriaPadre()
    {
        return $this->belongsTo(CategoriaProgramatica::class, 'id_categoria_padre');
    }

    // Relación con categorías hijas
    public function categoriasHijas()
    {
        return $this->hasMany(CategoriaProgramatica::class, 'id_categoria_padre');
    }

    // Relación con presupuestos
    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'id_cat_programatica');
    }

    // Relación con solicitudes de combustible
    public function solicitudesCombustible()
    {
        return $this->hasMany(SolicitudCombustible::class, 'id_cat_programatica');
    }

    // Scope para categorías activas
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para filtrar por tipo
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_categoria', $tipo);
    }

    // Scope para categorías padre (nivel 1)
    public function scopePadres($query)
    {
        return $query->whereNull('id_categoria_padre');
    }

    // Accessor para mostrar jerarquía
    public function getDescripcionCompletaAttribute()
    {
        if ($this->categoriaPadre) {
            return $this->categoriaPadre->descripcion . ' > ' . $this->descripcion;
        }
        return $this->descripcion;
    }

    // Accessor para badge de tipo
    public function getTipoBadgeAttribute()
    {
        $badges = [
            'Programa' => 'bg-blue-100 text-blue-800',
            'Proyecto' => 'bg-green-100 text-green-800',
            'Actividad' => 'bg-yellow-100 text-yellow-800',
        ];

        return $badges[$this->tipo_categoria] ?? 'bg-gray-100 text-gray-800';
    }
}
