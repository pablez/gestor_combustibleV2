<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Solicitud\Show as SolicitudShow;
use App\Models\SolicitudCombustible;

// Ruta de prueba temporal para debug
Route::get('/debug/solicitud/{id}', function ($id) {
    try {
        $solicitud = SolicitudCombustible::findOrFail($id);
        
        // Verificar autenticación
        if (!auth()->check()) {
            return "❌ Usuario no autenticado. Por favor, inicia sesión primero.";
        }
        
        // Mostrar información de debug
        $debug = [
            'Usuario autenticado' => auth()->user()->name,
            'Solicitud encontrada' => $solicitud->numero_solicitud,
            'Estado' => $solicitud->estado_solicitud,
            'Componente existe' => class_exists('App\Livewire\Solicitud\Show') ? 'Sí' : 'No',
            'Vista existe' => view()->exists('livewire.solicitud.show') ? 'Sí' : 'No',
        ];
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
})->middleware('auth');