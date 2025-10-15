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
    public static function crear($usuario_generador_id, $dias_vigencia = 7)
    {
        return self::create([
            'codigo' => self::generarCodigo(),
            'id_usuario_generador' => $usuario_generador_id,
            'vigente_hasta' => now()->addDays($dias_vigencia)->toDateString(),
            'usado' => false,
        ]);
    }
}
