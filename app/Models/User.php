<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'ci',
        'telefono',
        'profile_photo_path',
        'email',
        'password',
        'id_supervisor',
        'id_unidad_organizacional',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fecha_ultimo_acceso' => 'datetime',
            'bloqueado_hasta' => 'datetime',
            'activo' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Supervisor relationship (optional)
     */
    public function supervisor()
    {
        return $this->belongsTo(self::class, 'id_supervisor');
    }

    /**
     * Users supervised by this user
     */
    public function supervisados()
    {
        return $this->hasMany(self::class, 'id_supervisor');
    }

    /**
     * Unidad organizacional a la que pertenece el usuario
     */
    public function unidad()
    {
        return $this->belongsTo(\App\Models\UnidadOrganizacional::class, 'id_unidad_organizacional', 'id_unidad_organizacional');
    }

    /**
     * Helper to get full name
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->nombre ?? $this->name) . ' ' . ($this->apellido_paterno ?? '') . ' ' . ($this->apellido_materno ?? ''));
    }

    /**
     * Obtener el rol primario del usuario (primer nombre de role asignado) o null.
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        $roles = $this->getRoleNames();

        return $roles->first() ? (string) $roles->first() : null;
    }

    /**
     * Devuelve la URL pública de la foto de perfil si existe
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (! $this->profile_photo_path) {
            return null;
        }

        return \Storage::disk(config('filesystems.default'))->url($this->profile_photo_path);
    }
}
