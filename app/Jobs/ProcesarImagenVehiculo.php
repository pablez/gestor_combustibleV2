<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UnidadTransporte;

class ProcesarImagenVehiculo implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public string $archivoPath;
    public string $tipoImagen;
    public string $placa;

    public function __construct(string $archivoPath, string $tipoImagen, string $placa)
    {
        $this->archivoPath = $archivoPath;
        $this->tipoImagen = $tipoImagen;
        $this->placa = $placa;
    }

    public function handle()
    {
        $vehiculo = UnidadTransporte::where('placa', $this->placa)->first();
        if (!$vehiculo) return;

        // Intentar generar un thumbnail/derivado antes de auditar
        try {
            $imagenService = app(\App\Services\ImagenVehiculoService::class);
            // Generar thumbnail (si no existe lo crea)
            $imagenService->generarThumbnail($this->archivoPath);
        } catch (\Exception $e) {
            // Log y continuar con auditoría incluso si la generación falla
            \Log::warning('ProcesarImagenVehiculo: fallo al generar thumbnail: ' . $e->getMessage());
        }

        // Registrar en auditoría que se optimizó/procesó la imagen
        app(\App\Services\AuditoriaImagenService::class)->registrarAccion('OPTIMIZAR_IMAGEN', $vehiculo, $this->tipoImagen, ['ruta' => $this->archivoPath]);
    }
}
