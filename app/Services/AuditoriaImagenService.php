<?php

namespace App\Services;

use App\Models\UnidadTransporte;
use App\Models\RegistroAuditoria;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AuditoriaImagenService
{
    private const DISK = 'public';
    
    /**
     * Registrar acción de imagen en auditoría
     */
    public function registrarAccion(string $accion, UnidadTransporte $vehiculo, string $tipoImagen, array $datos = []): void
    {
        try {
            $detalles = [
                'vehiculo_id' => $vehiculo->id,
                'placa' => $vehiculo->placa,
                'tipo_imagen' => $tipoImagen,
                'accion' => $accion,
                'datos_adicionales' => $datos,
                'timestamp' => now(),
                'usuario_id' => auth()->id(),
                'usuario_nombre' => auth()->user()?->nombre ?? 'Sistema'
            ];

            RegistroAuditoria::create([
                'tabla_afectada' => 'unidad_transportes',
                // Pasar arrays y dejar que Eloquent los casteé a JSON correctamente
                'registro_afectado' => ['id' => $vehiculo->id, 'placa' => $vehiculo->placa],
                'accion_realizada' => strtoupper($accion),
                'valores_anteriores' => $this->obtenerDatosAnteriores($vehiculo, $tipoImagen),
                'valores_nuevos' => $detalles,
                'id_usuario' => auth()->id() ?? 1,
                'fecha_hora' => now(),
                'ip_origen' => (function () { $r = request(); return $r ? $r->ip() : null; })(),
                'user_agent' => (function () { $r = request(); return $r ? $r->userAgent() : null; })(),
                'modulo_sistema' => 'IMAGENES_VEHICULOS',
                'nivel_criticidad' => 'MEDIO'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al registrar auditoría de imagen (Eloquent): ' . $e->getMessage(), [
                'vehiculo_id' => $vehiculo->id,
                'tipo_imagen' => $tipoImagen,
                'accion' => $accion
            ]);

            // Durante la ejecución de tests queremos ver la excepción para depurar
            // en lugar de silenciarla; relanzarla hará que el test falle con el
            // error original y muestre la causa.
            if (app()->runningUnitTests() || app()->environment('testing')) {
                throw $e;
            }

            // Fallback directo a DB para asegurar que la auditoría quede registrada incluso
            // cuando el contexto HTTP/Model cause errores dentro de jobs.
            try {
                \DB::table('registro_auditorias')->insert([
                    'id_usuario' => auth()->id() ?? 1,
                    'fecha_hora' => now(),
                    'accion_realizada' => strtoupper($accion),
                    'tabla_afectada' => 'unidad_transportes',
                    'registro_afectado' => json_encode(['id' => $vehiculo->id, 'placa' => $vehiculo->placa]),
                    'valores_anteriores' => null,
                    'valores_nuevos' => json_encode($detalles),
                    'ip_origen' => null,
                    'user_agent' => null,
                    'modulo_sistema' => 'IMAGENES_VEHICULOS',
                    'nivel_criticidad' => 'MEDIO'
                ]);
            } catch (\Exception $inner) {
                \Log::error('Fallback DB insert failed for auditoría: ' . $inner->getMessage(), [
                    'vehiculo_id' => $vehiculo->id,
                ]);
            }
        }
    }

    /**
     * Obtener historial de imágenes de un vehículo
     */
    public function obtenerHistorialImagenes(UnidadTransporte $vehiculo): array
    {
        $query = RegistroAuditoria::where('tabla_afectada', 'unidad_transportes')
            ->where('modulo_sistema', 'IMAGENES_VEHICULOS')
            ->whereIn('accion_realizada', ['SUBIR_IMAGEN', 'ELIMINAR_IMAGEN', 'OPTIMIZAR_IMAGEN'])
            ->orderBy('fecha_hora', 'desc');

        // Preferimos usar la columna generada registro_afectado_id para filtrar por id
        if (\Schema::hasColumn('registro_auditorias', 'registro_afectado_id')) {
            $query->where('registro_afectado_id', $vehiculo->id);
        } else {
            // Fallback: extraer por JSON path usando JSON_EXTRACT en raw
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(registro_afectado, '$.id')) + 0 = ?", [$vehiculo->id]);
        }

        $registros = $query->get();

        return $registros->map(function ($registro) {
            $valoresNuevos = json_decode($registro->valores_nuevos, true);
            return [
                'fecha' => $registro->fecha_hora,
                'accion' => $registro->accion_realizada,
                'tipo_imagen' => $valoresNuevos['tipo_imagen'] ?? 'N/A',
                'usuario' => $valoresNuevos['usuario_nombre'] ?? 'N/A',
                'detalles' => $valoresNuevos['datos_adicionales'] ?? []
            ];
        })->toArray();
    }

    /**
     * Generar reporte de integridad de imágenes
     */
    public function generarReporteIntegridad(): array
    {
        $vehiculos = UnidadTransporte::all();
        $problemasIntegridad = [];

        foreach ($vehiculos as $vehiculo) {
            $problemas = $this->verificarIntegridadVehiculo($vehiculo);
            if (!empty($problemas)) {
                $problemasIntegridad[$vehiculo->placa] = $problemas;
            }
        }

        return [
            'fecha_reporte' => now(),
            'total_vehiculos_verificados' => $vehiculos->count(),
            'vehiculos_con_problemas' => count($problemasIntegridad),
            'problemas_encontrados' => $problemasIntegridad,
            'resumen' => $this->generarResumenProblemas($problemasIntegridad)
        ];
    }

    /**
     * Verificar integridad de imágenes de un vehículo
     */
    public function verificarIntegridadVehiculo(UnidadTransporte $vehiculo): array
    {
        $problemas = [];
        $tiposImagen = ['foto_principal', 'foto_tarjeton_propiedad', 'foto_cedula_identidad', 'foto_seguro', 'foto_revision_tecnica'];

        // Verificar imágenes individuales
        foreach ($tiposImagen as $tipo) {
            $ruta = $vehiculo->{$tipo};
            if ($ruta && !Storage::disk(self::DISK)->exists($ruta)) {
                $problemas[] = [
                    'tipo' => 'archivo_faltante',
                    'campo' => $tipo,
                    'ruta' => $ruta,
                    'descripcion' => "Archivo referenciado en BD no existe en storage"
                ];
            }
        }

        // Verificar galería de fotos
        $galeria = $vehiculo->galeria_fotos ?? [];
        foreach ($galeria as $index => $ruta) {
            if (!Storage::disk(self::DISK)->exists($ruta)) {
                $problemas[] = [
                    'tipo' => 'archivo_faltante_galeria',
                    'campo' => 'galeria_fotos',
                    'indice' => $index,
                    'ruta' => $ruta,
                    'descripcion' => "Imagen de galería referenciada no existe"
                ];
            }
        }

        // Verificar archivos huérfanos
        $carpetaVehiculo = 'vehiculos/' . preg_replace('/[^A-Za-z0-9]/', '', $vehiculo->placa);
        if (Storage::disk(self::DISK)->exists($carpetaVehiculo)) {
            $archivosEnCarpeta = collect(Storage::disk(self::DISK)->allFiles($carpetaVehiculo));
            $archivosReferenciados = collect([$vehiculo->foto_principal])
                ->merge($galeria)
                ->merge([$vehiculo->foto_tarjeton_propiedad, $vehiculo->foto_cedula_identidad, 
                        $vehiculo->foto_seguro, $vehiculo->foto_revision_tecnica])
                ->filter()
                ->toArray();

            $huerfanos = $archivosEnCarpeta->diff($archivosReferenciados)->toArray();
            if (!empty($huerfanos)) {
                $problemas[] = [
                    'tipo' => 'archivos_huerfanos',
                    'archivos' => $huerfanos,
                    'descripcion' => "Archivos en storage sin referencia en BD"
                ];
            }
        }

        return $problemas;
    }

    /**
     * Reparar problemas de integridad automaticamente
     */
    public function repararIntegridad(UnidadTransporte $vehiculo): array
    {
        $problemas = $this->verificarIntegridadVehiculo($vehiculo);
        $reparaciones = [];

        foreach ($problemas as $problema) {
            switch ($problema['tipo']) {
                case 'archivo_faltante':
                    // Limpiar referencia en BD
                    $vehiculo->update([$problema['campo'] => null]);
                    $reparaciones[] = "Eliminada referencia BD para {$problema['campo']}";
                    $this->registrarAccion('REPARAR_INTEGRIDAD', $vehiculo, $problema['campo'], $problema);
                    break;

                case 'archivo_faltante_galeria':
                    // Remover de galería
                    $galeria = $vehiculo->galeria_fotos ?? [];
                    unset($galeria[$problema['indice']]);
                    $vehiculo->update(['galeria_fotos' => array_values($galeria)]);
                    $reparaciones[] = "Eliminada imagen faltante de galería índice {$problema['indice']}";
                    $this->registrarAccion('REPARAR_INTEGRIDAD', $vehiculo, 'galeria_fotos', $problema);
                    break;

                case 'archivos_huerfanos':
                    // Eliminar archivos huérfanos
                    foreach ($problema['archivos'] as $archivo) {
                        Storage::disk(self::DISK)->delete($archivo);
                        $reparaciones[] = "Eliminado archivo huérfano: " . basename($archivo);
                    }
                    $this->registrarAccion('LIMPIAR_HUERFANOS', $vehiculo, 'sistema', $problema);
                    break;
            }
        }

        return $reparaciones;
    }

    /**
     * Obtener estadísticas detalladas de uso de imágenes
     */
    public function obtenerEstadisticasDetalladas(): array
    {
        $vehiculos = UnidadTransporte::all();
        $estadisticas = [
            'total_vehiculos' => $vehiculos->count(),
            'por_tipo_imagen' => [],
            'uso_almacenamiento' => [
                'total_archivos' => 0,
                'total_bytes' => 0,
                'por_tipo' => []
            ],
            'completitud_documentos' => [
                'completos' => 0,
                'parciales' => 0,
                'sin_documentos' => 0
            ]
        ];

        $tiposImagen = ['foto_principal', 'galeria_fotos', 'foto_tarjeton_propiedad', 
                       'foto_cedula_identidad', 'foto_seguro', 'foto_revision_tecnica'];

        foreach ($tiposImagen as $tipo) {
            $estadisticas['por_tipo_imagen'][$tipo] = [
                'cantidad' => 0,
                'porcentaje' => 0,
                'tamaño_total' => 0
            ];
        }

        foreach ($vehiculos as $vehiculo) {
            $documentosCompletos = 0;
            $tieneAlgunDocumento = false;

            foreach ($tiposImagen as $tipo) {
                if ($tipo === 'galeria_fotos') {
                    $galeria = $vehiculo->galeria_fotos ?? [];
                    if (!empty($galeria)) {
                        $estadisticas['por_tipo_imagen'][$tipo]['cantidad'] += count($galeria);
                        $tieneAlgunDocumento = true;
                    }
                } else {
                    if ($vehiculo->{$tipo}) {
                        $estadisticas['por_tipo_imagen'][$tipo]['cantidad']++;
                        $documentosCompletos++;
                        $tieneAlgunDocumento = true;

                        // Calcular tamaño del archivo
                        if (Storage::disk(self::DISK)->exists($vehiculo->{$tipo})) {
                            $tamaño = Storage::disk(self::DISK)->size($vehiculo->{$tipo});
                            $estadisticas['por_tipo_imagen'][$tipo]['tamaño_total'] += $tamaño;
                            $estadisticas['uso_almacenamiento']['total_bytes'] += $tamaño;
                            $estadisticas['uso_almacenamiento']['total_archivos']++;
                        }
                    }
                }
            }

            // Clasificar por completitud (excluyendo galería y foto principal)
            $documentosRequeridos = 4; // tarjeton, cedula, seguro, revision
            if ($documentosCompletos >= $documentosRequeridos) {
                $estadisticas['completitud_documentos']['completos']++;
            } elseif ($tieneAlgunDocumento) {
                $estadisticas['completitud_documentos']['parciales']++;
            } else {
                $estadisticas['completitud_documentos']['sin_documentos']++;
            }
        }

        // Calcular porcentajes
        foreach ($tiposImagen as $tipo) {
            if ($tipo !== 'galeria_fotos') {
                $estadisticas['por_tipo_imagen'][$tipo]['porcentaje'] = 
                    $estadisticas['total_vehiculos'] > 0 
                    ? round(($estadisticas['por_tipo_imagen'][$tipo]['cantidad'] / $estadisticas['total_vehiculos']) * 100, 1)
                    : 0;
            }
        }

        return $estadisticas;
    }

    /**
     * Obtener datos anteriores para auditoría
     */
    private function obtenerDatosAnteriores(UnidadTransporte $vehiculo, string $tipoImagen): array
    {
        return [
            'valor_anterior' => $vehiculo->{$tipoImagen},
            'metadatos_anteriores' => $vehiculo->metadatos_imagenes[$tipoImagen] ?? null
        ];
    }

    /**
     * Generar resumen de problemas encontrados
     */
    private function generarResumenProblemas(array $problemasIntegridad): array
    {
        $resumen = [
            'archivo_faltante' => 0,
            'archivo_faltante_galeria' => 0,
            'archivos_huerfanos' => 0
        ];

        foreach ($problemasIntegridad as $vehiculo => $problemas) {
            foreach ($problemas as $problema) {
                $resumen[$problema['tipo']]++;
            }
        }

        return $resumen;
    }

    /**
     * Exportar auditoría completa a archivo
     */
    public function exportarAuditoria(Carbon $fechaInicio = null, Carbon $fechaFin = null): string
    {
        $fechaInicio = $fechaInicio ?? now()->subDays(30);
        $fechaFin = $fechaFin ?? now();

        // Formatear fechas a strings para evitar problemas con bindings/formatos
        $desdeStr = $fechaInicio->toDateTimeString();
        $hastaStr = $fechaFin->toDateTimeString();

        try {
            $registros = RegistroAuditoria::where('tabla_afectada', 'unidad_transportes')
                ->whereIn('accion_realizada', ['SUBIR_IMAGEN', 'ELIMINAR_IMAGEN', 'OPTIMIZAR_IMAGEN'])
                ->whereBetween('fecha_hora', [$desdeStr, $hastaStr])
                ->orderBy('fecha_hora', 'desc')
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // Si la consulta falla por problemas con JSON path u otra razón en el motor,
            // recuperamos un conjunto seguro por rango de fechas y filtramos en PHP.
            \Log::warning('Consulta exportarAuditoria falló, aplicando fallback: ' . $e->getMessage());

            $posibles = RegistroAuditoria::whereBetween('fecha_hora', [$desdeStr, $hastaStr])
                ->orderBy('fecha_hora', 'desc')
                ->get();

            // Filtrar en PHP por los criterios deseados
            $registros = $posibles->filter(function ($r) {
                return $r->tabla_afectada === 'unidad_transportes' && in_array($r->accion_realizada, ['SUBIR_IMAGEN', 'ELIMINAR_IMAGEN', 'OPTIMIZAR_IMAGEN']);
            })->values();
        }

        $nombreArchivo = 'auditoria_imagenes_' . now()->format('Y-m-d_H-i-s') . '.json';
        $rutaArchivo = 'reportes/auditorias/' . $nombreArchivo;

        $datosExportar = [
            'periodo' => [
                'fecha_inicio' => $fechaInicio->toDateString(),
                'fecha_fin' => $fechaFin->toDateString()
            ],
            'total_registros' => $registros->count(),
            'fecha_exportacion' => now(),
            'registros' => $registros->toArray()
        ];

        Storage::disk(self::DISK)->put($rutaArchivo, json_encode($datosExportar, JSON_PRETTY_PRINT));

        return Storage::disk(self::DISK)->url($rutaArchivo);
    }
}