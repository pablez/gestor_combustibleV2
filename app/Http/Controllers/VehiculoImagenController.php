<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehiculoImagenRequest;
use App\Models\UnidadTransporte;
use App\Services\ImagenVehiculoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehiculoImagenController extends Controller
{
    public function __construct(
        private ImagenVehiculoService $imagenService
    ) {}

    /**
     * Subir imagen para un vehículo
     */
    public function store(VehiculoImagenRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $vehiculo = UnidadTransporte::findOrFail($request->vehiculo_id);
            $tipoImagen = $request->tipo_imagen;
            $config = config("vehiculos-imagenes.tipos.{$tipoImagen}");

            if ($config['multiple'] ?? false) {
                return $this->subirMultiplesImagenes($vehiculo, $request->imagenes, $tipoImagen);
            } else {
                return $this->subirImagenUnica($vehiculo, $request->imagen, $tipoImagen);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al subir imagen de vehículo', [
                'vehiculo_id' => $request->vehiculo_id,
                'tipo_imagen' => $request->tipo_imagen,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener imágenes de un vehículo
     */
    public function show(UnidadTransporte $vehiculo, string $tipoImagen = null): JsonResponse
    {
        $imagenes = [];

        if ($tipoImagen) {
            // Obtener tipo específico
            $imagenes = $this->obtenerImagenesPorTipo($vehiculo, $tipoImagen);
        } else {
            // Obtener todas las imágenes
            $tipos = array_keys(config('vehiculos-imagenes.tipos'));
            foreach ($tipos as $tipo) {
                $imagenes[$tipo] = $this->obtenerImagenesPorTipo($vehiculo, $tipo);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $imagenes,
            'total_fotos' => $vehiculo->total_fotos,
            'documentos_completos' => $vehiculo->hasDocumentosCompletos()
        ]);
    }

    /**
     * Eliminar imagen específica
     */
    public function destroy(UnidadTransporte $vehiculo, string $tipoImagen, int $indice = null): JsonResponse
    {
        try {
            DB::beginTransaction();

            if ($tipoImagen === 'galeria_fotos' && $indice !== null) {
                $vehiculo->eliminarFotoDeGaleria($indice);
            } else {
                // Eliminar imagen única
                $campo = "foto_{$tipoImagen}";
                if ($vehiculo->$campo) {
                    $this->imagenService->eliminarImagen($vehiculo->$campo);
                    $vehiculo->update([$campo => null]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar imagen', [
                'vehiculo_id' => $vehiculo->id,
                'tipo_imagen' => $tipoImagen,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen'
            ], 500);
        }
    }

    /**
     * Generar thumbnail de una imagen
     */
    public function thumbnail(UnidadTransporte $vehiculo, string $rutaImagen, string $tamaño = 'medium'): JsonResponse
    {
        try {
            $thumbnailPath = $this->imagenService->generarThumbnail($rutaImagen, ...config("vehiculos-imagenes.thumbnails.{$tamaño}"));
            
            return response()->json([
                'success' => true,
                'thumbnail_url' => asset('storage/' . $thumbnailPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar thumbnail'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de imágenes
     */
    public function estadisticas(): JsonResponse
    {
        $stats = [
            'total_vehiculos' => UnidadTransporte::count(),
            'vehiculos_con_foto_principal' => UnidadTransporte::whereNotNull('foto_principal')->count(),
            'vehiculos_con_galeria' => UnidadTransporte::whereNotNull('galeria_fotos')->count(),
            'documentos_completos' => UnidadTransporte::whereNotNull('foto_tarjeton_propiedad')
                ->whereNotNull('foto_cedula_identidad')
                ->whereNotNull('foto_seguro')
                ->whereNotNull('foto_revision_tecnica')
                ->count(),
        ];

        $stats['porcentaje_con_foto'] = $stats['total_vehiculos'] > 0 
            ? round(($stats['vehiculos_con_foto_principal'] / $stats['total_vehiculos']) * 100, 1)
            : 0;

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    // Métodos privados

    private function subirImagenUnica($vehiculo, $imagen, $tipoImagen): JsonResponse
    {
        $resultado = $this->imagenService->guardarImagen($imagen, $tipoImagen, $vehiculo->placa);
        
        $vehiculo->update([
            $tipoImagen => $resultado['ruta'],
            'metadatos_imagenes' => array_merge(
                $vehiculo->metadatos_imagenes ?? [],
                [$tipoImagen => $resultado['metadatos']]
            )
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Imagen subida correctamente',
            'data' => [
                'url' => $resultado['url'],
                'ruta' => $resultado['ruta']
            ]
        ]);
    }

    private function subirMultiplesImagenes($vehiculo, $imagenes, $tipoImagen): JsonResponse
    {
        $resultados = [];
        
        foreach ($imagenes as $imagen) {
            $resultado = $this->imagenService->guardarImagen($imagen, $tipoImagen, $vehiculo->placa);
            $vehiculo->agregarFotoAGaleria($resultado['ruta'], $resultado['metadatos']);
            $resultados[] = $resultado;
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => count($imagenes) . ' imágenes subidas correctamente',
            'data' => $resultados
        ]);
    }

    private function obtenerImagenesPorTipo($vehiculo, $tipoImagen): array
    {
        if ($tipoImagen === 'galeria_fotos') {
            return $vehiculo->galeria_fotos_urls;
        }

        // Para foto_principal, usar el accessor directo
        if ($tipoImagen === 'foto_principal') {
            $url = $vehiculo->foto_principal_url;
            return $url ? [$url] : [];
        }

        // Para otros tipos de documentos, remover el prefijo 'foto_'
        $tipo = str_replace('foto_', '', $tipoImagen);
        $url = $vehiculo->getFotoDocumentoUrl($tipo);
        return $url ? [$url] : [];
    }
}
