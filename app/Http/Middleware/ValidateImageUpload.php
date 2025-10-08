<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateImageUpload
{
    public function handle(Request $request, Closure $next): Response
    {
        // Validar tamaño total de archivos por solicitud
        $maxTotalSize = config('vehiculos-imagenes.validaciones.max_total_size_per_vehicle_mb', 50) * 1024 * 1024;
        $totalSize = 0;
        
        if ($request->hasFile('imagen')) {
            $totalSize += $request->file('imagen')->getSize();
        }
        
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $totalSize += $file->getSize();
            }
        }
        
        if ($totalSize > $maxTotalSize) {
            return response()->json([
                'success' => false,
                'message' => 'El tamaño total de archivos excede el límite permitido.'
            ], 413);
        }

        // Validar tipos MIME
        $allowedMimes = config('vehiculos-imagenes.mime_types_permitidos', [
            'image/jpeg', 'image/png', 'image/webp'
        ]);
        
        $files = [];
        if ($request->hasFile('imagen')) {
            $files[] = $request->file('imagen');
        }
        if ($request->hasFile('imagenes')) {
            $files = array_merge($files, $request->file('imagenes'));
        }
        
        foreach ($files as $file) {
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'message' => "Tipo de archivo no permitido: {$file->getMimeType()}"
                ], 422);
            }
        }

        return $next($request);
    }
}