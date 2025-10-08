<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroAuditoria extends Model
{
    /** @use HasFactory<\Database\Factories\RegistroAuditoriaFactory> */
    use HasFactory;

    // Deshabilitamos timestamps automáticos ya que usamos fecha_hora
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'fecha_hora',
        'accion_realizada',
        'tabla_afectada',
        'registro_afectado',
        'valores_anteriores',
        'valores_nuevos',
        'ip_origen',
        'user_agent',
        'sesion_id',
        'modulo_sistema',
        'nivel_criticidad',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'registro_afectado' => 'array',
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
    ];

    // Añadir atributo virtual para exponer el id del registro afectado incluso
    // cuando la columna generada 'registro_afectado_id' no exista en la DB.
    protected $appends = ['registro_afectado_id'];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Scopes
    public function scopePorUsuario($query, $usuario_id)
    {
        return $query->where('id_usuario', $usuario_id);
    }

    public function scopePorTabla($query, $tabla)
    {
        return $query->where('tabla_afectada', $tabla);
    }

    public function scopePorAccion($query, $accion)
    {
        return $query->where('accion_realizada', $accion);
    }

    public function scopePorNivelCriticidad($query, $nivel)
    {
        return $query->where('nivel_criticidad', $nivel);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_hora', [$desde, $hasta]);
    }

    public function scopeCriticos($query)
    {
        return $query->where('nivel_criticidad', 'CRÍTICO');
    }

    // Método estático para registrar auditoría
    public static function registrar($datos)
    {
        return self::create([
            'id_usuario' => $datos['id_usuario'] ?? auth()->id(),
            'fecha_hora' => now(),
            'accion_realizada' => $datos['accion'],
            'tabla_afectada' => $datos['tabla'],
            'registro_afectado' => $datos['registro_afectado'],
            'valores_anteriores' => $datos['valores_anteriores'] ?? null,
            'valores_nuevos' => $datos['valores_nuevos'] ?? null,
            'ip_origen' => (function () { $r = request(); return $r ? $r->ip() : null; })(),
            'user_agent' => (function () { $r = request(); return $r ? $r->userAgent() : null; })(),
            'sesion_id' => (function () { try { $s = session(); return $s ? $s->getId() : null; } catch (\Exception $e) { return null; } })(),
            'modulo_sistema' => $datos['modulo'] ?? 'Sistema',
            'nivel_criticidad' => $datos['nivel_criticidad'] ?? 'MEDIO',
        ]);
    }

    /**
     * Accessor para obtener el id dentro de registro_afectado (fallback)
     * Si la columna física 'registro_afectado_id' existe, Eloquent la expondrá
     * automáticamente. Este accesor sirve como fallback para entornos sin
     * columna generada.
     */
    public function getRegistroAfectadoIdAttribute()
    {
        // Si ya existe la columna materializada en la BD y Eloquent la devolvió,
        // retornarla directamente.
        if (array_key_exists('registro_afectado_id', $this->attributes) && $this->attributes['registro_afectado_id'] !== null) {
            return (int) $this->attributes['registro_afectado_id'];
        }

        // Intentar extraer el id desde el JSON casteado
        $registro = $this->registro_afectado ?? null;
        if (is_array($registro) && isset($registro['id'])) {
            return (int) $registro['id'];
        }

        // Fallback null
        return null;
    }

    /**
     * Scope para filtrar por registro_afectado_id de forma agnóstica al motor
     */
    public function scopePorRegistroAfectadoId($query, $id)
    {
        // Si la columna existe en el esquema, usarla
        if (\Schema::hasColumn($this->getTable(), 'registro_afectado_id')) {
            return $query->where('registro_afectado_id', $id);
        }

        // Fallback: comparar JSON extract como string → cast a número
    return $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(registro_afectado, '$.id')) + 0 = ?", [$id]);
    }

    // Métodos útiles para logging
    public static function logCreacion($modelo, $datos_adicionales = [])
    {
        return self::registrar(array_merge([
            'accion' => 'CREATE',
            'tabla' => $modelo->getTable(),
            'registro_afectado' => ['id' => $modelo->id],
            'valores_nuevos' => $modelo->toArray(),
            'nivel_criticidad' => 'MEDIO',
        ], $datos_adicionales));
    }

    public static function logActualizacion($modelo, $valores_anteriores, $datos_adicionales = [])
    {
        return self::registrar(array_merge([
            'accion' => 'UPDATE',
            'tabla' => $modelo->getTable(),
            'registro_afectado' => ['id' => $modelo->id],
            'valores_anteriores' => $valores_anteriores,
            'valores_nuevos' => $modelo->toArray(),
            'nivel_criticidad' => 'MEDIO',
        ], $datos_adicionales));
    }

    public static function logEliminacion($modelo, $datos_adicionales = [])
    {
        return self::registrar(array_merge([
            'accion' => 'DELETE',
            'tabla' => $modelo->getTable(),
            'registro_afectado' => ['id' => $modelo->id],
            'valores_anteriores' => $modelo->toArray(),
            'nivel_criticidad' => 'ALTO',
        ], $datos_adicionales));
    }
}
