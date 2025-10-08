<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadTransporte extends Model
{
    /** @use HasFactory<\Database\Factories\UnidadTransporteFactory> */
    use HasFactory;

    protected $fillable = [
        'placa',
        'numero_chasis',
        'numero_motor',
        'marca',
        'modelo',
        'anio_fabricacion',
        'color',
        'id_tipo_vehiculo',
        'id_tipo_combustible',
        'capacidad_tanque',
        'kilometraje_actual',
        'kilometraje_ultimo_mantenimiento',
        'proximo_mantenimiento_km',
        'id_unidad_organizacional',
        'id_conductor_asignado',
        'estado_operativo',
        'seguro_vigente_hasta',
        'revision_tecnica_hasta',
        'fecha_ultimo_servicio',
        'observaciones',
        'activo',
        // Campos de imágenes
        'foto_principal',
        'galeria_fotos',
        'foto_tarjeton_propiedad',
        'foto_cedula_identidad',
        'foto_seguro',
        'foto_revision_tecnica',
        'metadatos_imagenes',
    ];

    protected $casts = [
        'seguro_vigente_hasta' => 'date',
        'revision_tecnica_hasta' => 'date',
        'fecha_ultimo_servicio' => 'date',
        'activo' => 'boolean',
        // Casts para campos JSON
        'galeria_fotos' => 'array',
        'metadatos_imagenes' => 'array',
    ];

    // Relaciones
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo');
    }

    public function tipoCombustible()
    {
        return $this->belongsTo(TipoCombustible::class, 'id_tipo_combustible');
    }

    public function unidadOrganizacional()
    {
        return $this->belongsTo(UnidadOrganizacional::class, 'id_unidad_organizacional', 'id_unidad_organizacional');
    }

    public function conductorAsignado()
    {
        return $this->belongsTo(User::class, 'id_conductor_asignado');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOperativos($query)
    {
        return $query->where('estado_operativo', 'Operativo');
    }

    // Accessors
    public function getEstadoColorAttribute()
    {
        return match($this->estado_operativo) {
            'Operativo' => 'green',
            'Mantenimiento' => 'yellow',
            'Taller' => 'orange',
            'Baja' => 'red',
            'Reserva' => 'blue',
            default => 'gray'
        };
    }

    // Métodos para manejo de imágenes
    public function getFotoPrincipalUrlAttribute()
    {
        return $this->foto_principal ? asset('storage/' . $this->foto_principal) : null;
    }

    public function getGaleriaFotosUrlsAttribute()
    {
        if (!$this->galeria_fotos) {
            return [];
        }
        
        return collect($this->galeria_fotos)->map(function ($foto) {
            return asset('storage/' . $foto);
        })->toArray();
    }

    public function getFotoDocumentoUrl($tipo)
    {
        $campo = "foto_{$tipo}";
        return $this->$campo ? asset('storage/' . $this->$campo) : null;
    }

    public function getTotalFotosAttribute()
    {
        $total = 0;
        
        if ($this->foto_principal) $total++;
        if ($this->galeria_fotos) $total += count($this->galeria_fotos);
        if ($this->foto_tarjeton_propiedad) $total++;
        if ($this->foto_cedula_identidad) $total++;
        if ($this->foto_seguro) $total++;
        if ($this->foto_revision_tecnica) $total++;
        
        return $total;
    }

    public function hasDocumentosCompletos()
    {
        return $this->foto_tarjeton_propiedad && 
               $this->foto_cedula_identidad && 
               $this->foto_seguro && 
               $this->foto_revision_tecnica;
    }

    // Métodos para agregar fotos a la galería con auditoría
    public function agregarFotoAGaleria($rutaFoto, $metadatos = [])
    {
        $galeriaActual = $this->galeria_fotos ?? [];
        $galeriaActual[] = $rutaFoto;
        
        $metadatosActuales = $this->metadatos_imagenes ?? [];
        $metadatosActuales['galeria'][] = array_merge([
            'ruta' => $rutaFoto,
            'fecha_subida' => now(),
            'usuario_id' => auth()->id(),
        ], $metadatos);
        
        $this->update([
            'galeria_fotos' => $galeriaActual,
            'metadatos_imagenes' => $metadatosActuales
        ]);

        // Registrar en auditoría
        app(\App\Services\AuditoriaImagenService::class)->registrarAccion(
            'SUBIR_IMAGEN', 
            $this, 
            'galeria_fotos', 
            ['ruta' => $rutaFoto, 'metadatos' => $metadatos]
        );
    }

    public function eliminarFotoDeGaleria($indice)
    {
        $galeriaActual = $this->galeria_fotos ?? [];
        
        if (isset($galeriaActual[$indice])) {
            $rutaImagen = $galeriaActual[$indice];
            
            // Eliminar archivo físico
            $rutaCompleta = storage_path('app/public/' . $rutaImagen);
            if (file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
            }
            
            // Eliminar de la galería
            unset($galeriaActual[$indice]);
            $galeriaActual = array_values($galeriaActual); // Reindexar
            
            $this->update(['galeria_fotos' => $galeriaActual]);

            // Registrar en auditoría
            app(\App\Services\AuditoriaImagenService::class)->registrarAccion(
                'ELIMINAR_IMAGEN', 
                $this, 
                'galeria_fotos', 
                ['ruta_eliminada' => $rutaImagen, 'indice' => $indice]
            );
        }
    }

    // Métodos para auditoría de imágenes individuales
    public function actualizarImagenConAuditoria($tipoImagen, $rutaImagen, $metadatos = [])
    {
        $rutaAnterior = $this->{$tipoImagen};
        
        $metadatosActuales = $this->metadatos_imagenes ?? [];
        $metadatosActuales[$tipoImagen] = array_merge([
            'ruta' => $rutaImagen,
            'fecha_subida' => now(),
            'usuario_id' => auth()->id(),
        ], $metadatos);

        $this->update([
            $tipoImagen => $rutaImagen,
            'metadatos_imagenes' => $metadatosActuales
        ]);

        // Registrar en auditoría
        app(\App\Services\AuditoriaImagenService::class)->registrarAccion(
            'SUBIR_IMAGEN', 
            $this, 
            $tipoImagen, 
            [
                'ruta_nueva' => $rutaImagen, 
                'ruta_anterior' => $rutaAnterior,
                'metadatos' => $metadatos
            ]
        );
    }

    public function eliminarImagenConAuditoria($tipoImagen)
    {
        $rutaAnterior = $this->{$tipoImagen};
        
        if ($rutaAnterior) {
            // Limpiar metadatos
            $metadatos = $this->metadatos_imagenes ?? [];
            unset($metadatos[$tipoImagen]);
            
            $this->update([
                $tipoImagen => null,
                'metadatos_imagenes' => $metadatos
            ]);

            // Registrar en auditoría
            app(\App\Services\AuditoriaImagenService::class)->registrarAccion(
                'ELIMINAR_IMAGEN', 
                $this, 
                $tipoImagen, 
                ['ruta_eliminada' => $rutaAnterior]
            );
        }
    }

    // Validaciones de integridad
    public function verificarIntegridadImagenes()
    {
        return app(\App\Services\AuditoriaImagenService::class)->verificarIntegridadVehiculo($this);
    }

    public function repararIntegridadImagenes()
    {
        return app(\App\Services\AuditoriaImagenService::class)->repararIntegridad($this);
    }

    public function obtenerHistorialImagenes()
    {
        return app(\App\Services\AuditoriaImagenService::class)->obtenerHistorialImagenes($this);
    }
}
