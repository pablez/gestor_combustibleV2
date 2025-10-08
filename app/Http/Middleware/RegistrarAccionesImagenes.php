<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrarAccionesImagenes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo procesar respuestas exitosas en rutas de imágenes de vehículos
        if (
            $response->getStatusCode() >= 200 && 
            $response->getStatusCode() < 300 &&
            str_contains($request->path(), 'vehiculos') &&
            str_contains($request->path(), 'imagenes')
        ) {
            $this->registrarAccion($request, $response);
        }

        return $response;
    }

    /**
     * Registrar la acción realizada
     */
    private function registrarAccion(Request $request, Response $response): void
    {
        try {
            $metodo = $request->method();
            $ruta = $request->path();
            $parametros = $request->all();

            // Extraer información relevante
            $accionData = [
                'metodo_http' => $metodo,
                'ruta' => $ruta,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
                'usuario_id' => auth()->id(),
                'parametros' => $this->filtrarParametrosSensibles($parametros)
            ];

            // Determinar el tipo de acción basado en el método HTTP
            $tipoAccion = match($metodo) {
                'POST' => 'SUBIR_IMAGEN',
                'DELETE' => 'ELIMINAR_IMAGEN',
                'PUT', 'PATCH' => 'ACTUALIZAR_IMAGEN',
                'GET' => 'CONSULTAR_IMAGEN',
                default => 'ACCION_IMAGEN'
            };

            // Si hay archivos subidos, registrar información adicional
            if ($request->hasFile('imagen') || $request->hasFile('imagenes')) {
                $accionData['archivos_subidos'] = $this->procesarArchivosSubidos($request);
            }

            // Almacenar en log específico para imágenes
            \Log::channel('daily')->info("Acción de imagen registrada", [
                'tipo_accion' => $tipoAccion,
                'datos' => $accionData
            ]);

        } catch (\Exception $e) {
            // No interrumpir la aplicación por errores de logging
            \Log::error('Error al registrar acción de imagen: ' . $e->getMessage());
        }
    }

    /**
     * Filtrar parámetros sensibles de la request
     */
    private function filtrarParametrosSensibles(array $parametros): array
    {
        $parametrosSensibles = ['password', 'token', '_token', 'api_key'];
        
        return collect($parametros)
            ->except($parametrosSensibles)
            ->map(function ($valor) {
                // Truncar valores muy largos
                if (is_string($valor) && strlen($valor) > 255) {
                    return substr($valor, 0, 255) . '...';
                }
                return $valor;
            })
            ->toArray();
    }

    /**
     * Procesar información de archivos subidos
     */
    private function procesarArchivosSubidos(Request $request): array
    {
        $archivosInfo = [];

        $archivos = collect($request->allFiles())->flatten();

        foreach ($archivos as $archivo) {
            if ($archivo->isValid()) {
                $archivosInfo[] = [
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'extension' => $archivo->getClientOriginalExtension(),
                    'tamaño' => $archivo->getSize(),
                    'tipo_mime' => $archivo->getMimeType(),
                    'temporal_path' => $archivo->getPathname()
                ];
            }
        }

        return $archivosInfo;
    }
}