<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnidadTransporte;
use App\Http\Controllers\VehiculoImagenController;

class VehiculoImagenesFrontend extends Component
{
    public UnidadTransporte $vehiculo;
    public array $imagenes = [];
    public int $totalFotos = 0;
    public bool $documentosCompletos = false;
    public int $documentosCompletadosPorcentaje = 0;
    
    public function mount(UnidadTransporte $vehiculo)
    {
        $this->vehiculo = $vehiculo;
        $this->cargarImagenes();
    }
    
    public function cargarImagenes()
    {
        try {
            // Obtener datos de imágenes usando el controlador existente
            $vehiculoImagenController = app(VehiculoImagenController::class);
            $response = $vehiculoImagenController->show($this->vehiculo);
            $data = $response->getData(true);
            
            if ($data['success']) {
                $this->imagenes = $data['data'];
                $this->totalFotos = $data['total_fotos'];
                $this->documentosCompletos = $data['documentos_completos'];
                
                // Calcular porcentaje de documentos completados
                $tiposDocumentos = ['foto_tarjeton_propiedad', 'foto_cedula_identidad', 'foto_seguro', 'foto_revision_tecnica'];
                $documentosCompletados = 0;
                
                foreach ($tiposDocumentos as $tipo) {
                    if (!empty($this->imagenes[$tipo])) {
                        $documentosCompletados++;
                    }
                }
                
                $this->documentosCompletadosPorcentaje = count($tiposDocumentos) > 0 
                    ? round(($documentosCompletados / count($tiposDocumentos)) * 100) 
                    : 0;
            }
        } catch (\Exception $e) {
            // En caso de error, inicializar con valores por defecto
            $this->imagenes = [];
            $this->totalFotos = 0;
            $this->documentosCompletos = false;
            $this->documentosCompletadosPorcentaje = 0;
        }
    }

    public function render()
    {
        return view('livewire.vehiculo-imagenes-frontend');
    }
}
