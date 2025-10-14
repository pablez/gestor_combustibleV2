<?php

namespace App\Http\Controllers;

use App\Models\UnidadTransporte;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehiculoImagenesFrontendController extends Controller
{
    /**
     * Mostrar imágenes del vehículo para acceso público usando Livewire
     */
    public function show(UnidadTransporte $vehiculo): View
    {
        return view('vehiculos.frontend-imagenes-livewire', [
            'vehiculo' => $vehiculo
        ]);
    }
}