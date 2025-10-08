<?php

use App\Http\Controllers\VehiculoImagenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Rutas API para Gestión de Imágenes de Vehículos
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('vehiculos')->group(function () {
    
    // Estadísticas generales de imágenes
    Route::get('imagenes/estadisticas', [VehiculoImagenController::class, 'estadisticas'])
        ->name('api.vehiculos.imagenes.estadisticas');
    
    // Rutas específicas por vehículo
    Route::prefix('{vehiculo}')->group(function () {
        
        // Obtener todas las imágenes de un vehículo
        Route::get('imagenes', [VehiculoImagenController::class, 'show'])
            ->name('api.vehiculos.imagenes.show');
            
        // Obtener imágenes por tipo específico
        Route::get('imagenes/{tipo_imagen}', [VehiculoImagenController::class, 'show'])
            ->name('api.vehiculos.imagenes.por-tipo');
        
        // Subir imagen(es)
        Route::post('imagenes/{tipo_imagen}', [VehiculoImagenController::class, 'store'])
            ->name('api.vehiculos.imagenes.store');
        
        // Eliminar imagen específica
        Route::delete('imagenes/{tipo_imagen}', [VehiculoImagenController::class, 'destroy'])
            ->name('api.vehiculos.imagenes.destroy');
            
        // Eliminar imagen de galería por índice
        Route::delete('imagenes/galeria_fotos/{indice}', [VehiculoImagenController::class, 'destroy'])
            ->name('api.vehiculos.imagenes.galeria.destroy');
        
        // Generar thumbnail
        Route::post('imagenes/thumbnail', [VehiculoImagenController::class, 'thumbnail'])
            ->name('api.vehiculos.imagenes.thumbnail');
    });
});