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
     * Appended accessors when serializing the model.
     *
     * @var array<int,string>
     */
    protected $appends = [
        'full_name',
        'primary_role',
        'profile_photo_url',
        'initials',
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
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_ultimo_acceso' => 'datetime',
        'bloqueado_hasta' => 'datetime',
        'activo' => 'boolean',
        'password' => 'hashed',
    ];

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
        // Use `name` as canonical display name and append surnames when present
        return trim(($this->name ?? '') . ' ' . ($this->apellido_paterno ?? '') . ' ' . ($this->apellido_materno ?? ''));
    }

    /**
     * Iniciales del nombre completo (2 letras como máximo).
     */
    public function getInitialsAttribute(): string
    {
        $source = trim($this->full_name ?: ($this->name ?? ''));
        if ($source === '') {
            return '';
        }

        $parts = preg_split('/\s+/u', $source);
        $initials = '';
        foreach ($parts as $p) {
            if ($p === '') continue;
            $initials .= mb_substr($p, 0, 1);
            if (mb_strlen($initials) >= 2) break;
        }

        return mb_strtoupper($initials ?: mb_substr($source, 0, 1));
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
