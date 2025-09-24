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
}
