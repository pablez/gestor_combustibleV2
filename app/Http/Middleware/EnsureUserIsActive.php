<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si el usuario no está activo, cerrar sesión y redirigir
            if (!$user->activo) {
                Auth::logout();
                
                // Verificar si tiene solicitud pendiente
                $solicitudPendiente = \App\Models\SolicitudAprobacionUsuario::where('id_usuario', $user->id)
                    ->where('estado_solicitud', 'pendiente')
                    ->where('tipo_solicitud', 'nuevo_usuario')
                    ->exists();
                
                if ($solicitudPendiente) {
                    // Redirigir a página específica de cuenta pendiente
                    return redirect()->route('pending-approval');
                } else {
                    return redirect()->route('login')->with('error', 
                        'Su cuenta ha sido desactivada. Contacte al administrador para más información.');
                }
            }
        }

        return $next($request);
    }
}
