<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAprobacionUsuario extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitudAprobacionUsuarioFactory> */
    use HasFactory;

    protected $fillable = [
        'id_usuario',
        'id_creador',
        'id_supervisor_asignado',
        'tipo_solicitud',
        'estado_solicitud',
        'rol_solicitado',
        'justificacion',
        'observaciones_aprobacion',
        'fecha_aprobacion',
        'id_usuario_aprobador',
    ];

    protected $casts = [
        'fecha_aprobacion' => 'datetime',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'id_creador');
    }

    public function supervisorAsignado()
    {
        return $this->belongsTo(User::class, 'id_supervisor_asignado');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'id_usuario_aprobador');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado_solicitud', 'pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado_solicitud', 'aprobado');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado_solicitud', 'rechazado');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_solicitud', $tipo);
    }

    // Métodos útiles
    public function aprobar($usuario_aprobador, $observaciones = null)
    {
        $this->update([
            'estado_solicitud' => 'aprobado',
            'fecha_aprobacion' => now(),
            'id_usuario_aprobador' => $usuario_aprobador,
            'observaciones_aprobacion' => $observaciones,
        ]);
    }

    public function rechazar($usuario_aprobador, $observaciones)
    {
        $this->update([
            'estado_solicitud' => 'rechazado',
            'fecha_aprobacion' => now(),
            'id_usuario_aprobador' => $usuario_aprobador,
            'observaciones_aprobacion' => $observaciones,
        ]);
    }
}
