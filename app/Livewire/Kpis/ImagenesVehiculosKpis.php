<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\UnidadTransporte;
use Illuminate\Support\Facades\DB;

class ImagenesVehiculosKpis extends Component
{
    public $vehiculosConFotoPrincipal = 0;
    public $vehiculosConGaleria = 0;
    public $promedioFotosPorVehiculo = 0;
    public $porcentajeDocumentosCompletos = 0;

    public function mount()
    {
        $this->loadKpis();
    }

    public function loadKpis()
    {
        $totalVehiculos = UnidadTransporte::count() ?: 1; // evitar división por cero

        $this->vehiculosConFotoPrincipal = UnidadTransporte::whereNotNull('foto_principal')->count();
        $this->vehiculosConGaleria = UnidadTransporte::whereNotNull('galeria_fotos')->count();

        // Promedio de imágenes por vehículo (usa el accessor total_fotos en el modelo)
        // Evitamos funciones SQL específicas (JSON_LENGTH, COALESCE) para compatibilidad con sqlite en tests
        $totalVehiculosCount = UnidadTransporte::count();
        $sumaFotos = UnidadTransporte::all()->sum(function ($vehiculo) {
            return $vehiculo->total_fotos ?? 0;
        });

        $this->promedioFotosPorVehiculo = $totalVehiculosCount > 0 ? round($sumaFotos / $totalVehiculosCount, 2) : 0;

        $vehiculosConDocs = UnidadTransporte::whereNotNull('foto_tarjeton_propiedad')
            ->whereNotNull('foto_cedula_identidad')
            ->whereNotNull('foto_seguro')
            ->whereNotNull('foto_revision_tecnica')
            ->count();

        $this->porcentajeDocumentosCompletos = round(($vehiculosConDocs / $totalVehiculos) * 100, 1);
    }

    public function render()
    {
        return view('livewire.kpis.imagenes-vehiculos-kpis');
    }
}
