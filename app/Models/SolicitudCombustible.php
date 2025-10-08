<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCombustible extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitudCombustibleFactory> */
    use HasFactory;

    protected $table = 'solicitud_combustibles';

    protected $fillable = [
        'numero_solicitud',
        'id_usuario_solicitante',
        'id_unidad_transporte',
        'fecha_solicitud',
        'cantidad_litros_solicitados',
        'motivo',
        'urgente',
        'justificacion_urgencia',
        'estado_solicitud',
        'id_usuario_aprobador',
        'fecha_aprobacion',
        'observaciones_aprobacion',
        'id_cat_programatica',
        'id_fuente_org_fin',
        'saldo_actual_combustible',
        'km_actual',
        'km_proyectado',
        'rendimiento_estimado',
    ];

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'id_usuario_solicitante');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'id_usuario_aprobador');
    }

    public function unidadTransporte()
    {
        return $this->belongsTo(UnidadTransporte::class, 'id_unidad_transporte');
    }

    public function categoriaProgramatica()
    {
        return $this->belongsTo(CategoriaProgramatica::class, 'id_cat_programatica');
    }

    public function fuenteOrganismoFinanciero()
    {
        return $this->belongsTo(FuenteOrganismoFinanciero::class, 'id_fuente_org_fin');
    }

    public function despachos()
    {
        return $this->hasMany(DespachoCombustible::class, 'id_solicitud');
    }

    // Scopes útiles
    public function scopePendientes($query)
    {
        return $query->where('estado_solicitud', 'Pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado_solicitud', 'Aprobada');
    }

    public function scopeUrgentes($query)
    {
        return $query->where('urgente', true);
    }

    // Métodos útiles
    public function aprobar($usuario_aprobador, $observaciones = null)
    {
        $this->update([
            'estado_solicitud' => 'Aprobada',
            'fecha_aprobacion' => now(),
            'id_usuario_aprobador' => $usuario_aprobador,
            'observaciones_aprobacion' => $observaciones,
        ]);
    }

    public function rechazar($usuario_aprobador, $observaciones)
    {
        $this->update([
            'estado_solicitud' => 'Rechazada',
            'fecha_aprobacion' => now(),
            'id_usuario_aprobador' => $usuario_aprobador,
            'observaciones_aprobacion' => $observaciones,
        ]);
    }
}
