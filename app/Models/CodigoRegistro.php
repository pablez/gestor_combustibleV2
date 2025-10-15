<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CodigoRegistro extends Model
{
    /** @use HasFactory<\Database\Factories\CodigoRegistroFactory> */
    use HasFactory;

    protected $fillable = [
        'codigo',
        'id_usuario_generador',
        'vigente_hasta',
        'usado',
        'id_usuario_usado',
        'fecha_uso',
        'id_unidad_organizacional_asignada',
        'id_supervisor_asignado',
        'rol_asignado',
        'observaciones',
    ];

    protected $casts = [
        'vigente_hasta' => 'date',
        'fecha_uso' => 'datetime',
        'usado' => 'boolean',
    ];

    // Relaciones
    public function generador()
    {
        return $this->belongsTo(User::class, 'id_usuario_generador');
    }

    public function usuarioUsado()
    {
        return $this->belongsTo(User::class, 'id_usuario_usado');
    }

    public function unidadAsignada()
    {
        return $this->belongsTo(\App\Models\UnidadOrganizacional::class, 'id_unidad_organizacional_asignada', 'id_unidad_organizacional');
    }

    public function supervisorAsignado()
    {
        return $this->belongsTo(User::class, 'id_supervisor_asignado');
    }

    // Scopes
    public function scopeVigentes($query)
    {
        return $query->where('vigente_hasta', '>=', now()->toDateString())
                    ->where('usado', false);
    }

    public function scopeUsados($query)
    {
        return $query->where('usado', true);
    }

    public function scopeVencidos($query)
    {
        return $query->where('vigente_hasta', '<', now()->toDateString())
                    ->where('usado', false);
    }

    // Métodos útiles
    public function estaVigente()
    {
        return !$this->usado && $this->vigente_hasta >= now()->toDateString();
    }

    public function usar($usuario_id)
    {
        if (!$this->estaVigente()) {
            throw new \Exception('El código no está vigente o ya fue usado');
        }

        $this->update([
            'usado' => true,
            'id_usuario_usado' => $usuario_id,
            'fecha_uso' => now(),
        ]);
    }

    // Generar código único
    public static function generarCodigo()
    {
        do {
            $codigo = strtoupper(Str::random(6) . '-' . rand(100, 999));
        } while (self::where('codigo', $codigo)->exists());

        return $codigo;
    }

    // Crear nuevo código
    public static function crear($usuario_generador_id, $dias_vigencia = 7, $datos_personalizacion = [])
    {
        return self::create([
            'codigo' => self::generarCodigo(),
            'id_usuario_generador' => $usuario_generador_id,
            'vigente_hasta' => now()->addDays((int) $dias_vigencia)->toDateString(),
            'usado' => false,
            'id_unidad_organizacional_asignada' => $datos_personalizacion['id_unidad_organizacional'] ?? null,
            'id_supervisor_asignado' => $datos_personalizacion['id_supervisor'] ?? null,
            'rol_asignado' => $datos_personalizacion['rol'] ?? null,
            'observaciones' => $datos_personalizacion['observaciones'] ?? null,
        ]);
    }

    // Validar código para registro
    public static function validarParaRegistro($codigo)
    {
        $codigoObj = self::where('codigo', $codigo)->first();
        
        if (!$codigoObj) {
            throw new \Exception('El código de registro no existe.');
        }
        
        if (!$codigoObj->estaVigente()) {
            throw new \Exception('El código de registro ha expirado o ya fue usado.');
        }
        
        return $codigoObj;
    }

    // Obtener datos de personalización
    public function getDatosPersonalizacion()
    {
        return [
            'id_unidad_organizacional' => $this->id_unidad_organizacional_asignada,
            'id_supervisor' => $this->id_supervisor_asignado,
            'rol' => $this->rol_asignado,
            'observaciones' => $this->observaciones,
        ];
    }

    // Verificar si el código tiene datos de personalización
    public function tienePersonalizacion()
    {
        return $this->id_unidad_organizacional_asignada !== null ||
               $this->id_supervisor_asignado !== null ||
               $this->rol_asignado !== null;
    }
}
