<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuenteOrganismoFinanciero extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'fuente_organismo_financieros';

    /**
     * Clave primaria de la tabla
     */
    protected $primaryKey = 'id';

    /**
     * Los atributos que son asignables en masa
     */
    protected $fillable = [
        'codigo',
        'descripcion',
        'tipo_fuente',
        'organismo_financiador',
        'requiere_contrapartida',
        'porcentaje_contrapartida',
        'activo',
        'fecha_vigencia_inicio',
        'fecha_vigencia_fin',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'requiere_contrapartida' => 'boolean',
        'porcentaje_contrapartida' => 'decimal:2',
        'activo' => 'boolean',
        'fecha_vigencia_inicio' => 'date',
        'fecha_vigencia_fin' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Valores por defecto para los atributos
     */
    protected $attributes = [
        'requiere_contrapartida' => false,
        'porcentaje_contrapartida' => 0,
        'activo' => true,
    ];

    /**
     * Constantes para los tipos de fuente
     */
    public const TIPO_FUENTE_NACIONAL = 'Nacional';
    public const TIPO_FUENTE_DEPARTAMENTAL = 'Departamental';
    public const TIPO_FUENTE_MUNICIPAL = 'Municipal';
    public const TIPO_FUENTE_INTERNACIONAL = 'Internacional';
    public const TIPO_FUENTE_OTROS = 'Otros';

    /**
     * Obtener todos los tipos de fuente disponibles
     */
    public static function getTiposFuente(): array
    {
        return [
            self::TIPO_FUENTE_NACIONAL,
            self::TIPO_FUENTE_DEPARTAMENTAL,
            self::TIPO_FUENTE_MUNICIPAL,
            self::TIPO_FUENTE_INTERNACIONAL,
            self::TIPO_FUENTE_OTROS,
        ];
    }

    /**
     * Scope para obtener solo las fuentes activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener fuentes por tipo
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_fuente', $tipo);
    }

    /**
     * Scope para obtener fuentes vigentes
     */
    public function scopeVigentes($query)
    {
        $hoy = now()->toDateString();
        
        return $query->where('activo', true)
                    ->where(function ($q) use ($hoy) {
                        $q->where(function ($subQ) use ($hoy) {
                            // Casos donde tiene fecha de inicio y fin
                            $subQ->whereNotNull('fecha_vigencia_inicio')
                                 ->whereNotNull('fecha_vigencia_fin')
                                 ->where('fecha_vigencia_inicio', '<=', $hoy)
                                 ->where('fecha_vigencia_fin', '>=', $hoy);
                        })
                        ->orWhere(function ($subQ) use ($hoy) {
                            // Casos donde solo tiene fecha de inicio
                            $subQ->whereNotNull('fecha_vigencia_inicio')
                                 ->whereNull('fecha_vigencia_fin')
                                 ->where('fecha_vigencia_inicio', '<=', $hoy);
                        })
                        ->orWhere(function ($subQ) {
                            // Casos donde no tiene fechas de vigencia
                            $subQ->whereNull('fecha_vigencia_inicio')
                                 ->whereNull('fecha_vigencia_fin');
                        });
                    });
    }

    /**
     * Relación: Solicitudes de combustible que usan esta fuente
     */
    public function solicitudesCombustible(): HasMany
    {
        return $this->hasMany(SolicitudCombustible::class, 'id_fuente_org_fin');
    }

    /**
     * Relación: Presupuestos asignados a esta fuente
     */
    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class, 'id_fuente_org_fin');
    }

    /**
     * Verificar si la fuente está vigente en una fecha específica
     */
    public function estaVigente($fecha = null): bool
    {
        $fecha = $fecha ? $fecha : now()->toDateString();

        if (!$this->activo) {
            return false;
        }

        // Si no tiene fechas de vigencia, está vigente
        if (!$this->fecha_vigencia_inicio && !$this->fecha_vigencia_fin) {
            return true;
        }

        // Si solo tiene fecha de inicio
        if ($this->fecha_vigencia_inicio && !$this->fecha_vigencia_fin) {
            return $this->fecha_vigencia_inicio <= $fecha;
        }

        // Si solo tiene fecha de fin
        if (!$this->fecha_vigencia_inicio && $this->fecha_vigencia_fin) {
            return $this->fecha_vigencia_fin >= $fecha;
        }

        // Si tiene ambas fechas
        return $this->fecha_vigencia_inicio <= $fecha && $this->fecha_vigencia_fin >= $fecha;
    }

    /**
     * Obtener el estado de vigencia como texto
     */
    public function getEstadoVigenciaAttribute(): string
    {
        if (!$this->activo) {
            return 'Inactiva';
        }

        if ($this->estaVigente()) {
            return 'Vigente';
        }

        $hoy = now()->toDateString();

        if ($this->fecha_vigencia_inicio && $this->fecha_vigencia_inicio > $hoy) {
            return 'Por iniciar';
        }

        if ($this->fecha_vigencia_fin && $this->fecha_vigencia_fin < $hoy) {
            return 'Vencida';
        }

        return 'No vigente';
    }

    /**
     * Obtener el nombre completo de la fuente (código + descripción)
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->codigo . ' - ' . $this->descripcion;
    }

    /**
     * Verificar si requiere contrapartida
     */
    public function requiereContrapartida(): bool
    {
        return $this->requiere_contrapartida && $this->porcentaje_contrapartida > 0;
    }

    /**
     * Calcular el monto de contrapartida para un presupuesto dado
     */
    public function calcularContrapartida(float $montoTotal): float
    {
        if (!$this->requiereContrapartida()) {
            return 0;
        }

        return $montoTotal * ($this->porcentaje_contrapartida / 100);
    }

    /**
     * Obtener información resumida de la fuente
     */
    public function getInformacionResumida(): array
    {
        return [
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
            'tipo_fuente' => $this->tipo_fuente,
            'organismo_financiador' => $this->organismo_financiador,
            'estado_vigencia' => $this->estado_vigencia,
            'requiere_contrapartida' => $this->requiere_contrapartida,
            'porcentaje_contrapartida' => $this->porcentaje_contrapartida,
            'activo' => $this->activo,
        ];
    }

    /**
     * Validaciones personalizadas del modelo
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Validar que el código no esté vacío
            if (empty($model->codigo)) {
                throw new \InvalidArgumentException('El código de la fuente es obligatorio');
            }

            // Validar porcentaje de contrapartida
            if ($model->porcentaje_contrapartida < 0 || $model->porcentaje_contrapartida > 100) {
                throw new \InvalidArgumentException('El porcentaje de contrapartida debe estar entre 0 y 100');
            }

            // Validar fechas de vigencia
            if ($model->fecha_vigencia_inicio && $model->fecha_vigencia_fin) {
                if ($model->fecha_vigencia_inicio > $model->fecha_vigencia_fin) {
                    throw new \InvalidArgumentException('La fecha de inicio no puede ser mayor a la fecha de fin');
                }
            }
        });

        static::updating(function ($model) {
            // Validar porcentaje de contrapartida
            if ($model->porcentaje_contrapartida < 0 || $model->porcentaje_contrapartida > 100) {
                throw new \InvalidArgumentException('El porcentaje de contrapartida debe estar entre 0 y 100');
            }

            // Validar fechas de vigencia
            if ($model->fecha_vigencia_inicio && $model->fecha_vigencia_fin) {
                if ($model->fecha_vigencia_inicio > $model->fecha_vigencia_fin) {
                    throw new \InvalidArgumentException('La fecha de inicio no puede ser mayor a la fecha de fin');
                }
            }
        });
    }
}
