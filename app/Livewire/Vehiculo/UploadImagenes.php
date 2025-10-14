<?php

namespace App\Livewire\Vehiculo;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use App\Services\ImagenVehiculoService;

class UploadImagenes extends Component
{
    use WithFileUploads;

    public $archivo;
    public $tipo = 'foto_principal';
    public $placa;

    protected $rules = [
        'archivo' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        'tipo' => 'required|string',
        'placa' => 'required|string'
    ];

    public function mount(?string $placa = null)
    {
        $this->placa = $placa;
    }

    public function subir()
    {
        $this->validate();

        // Requerir autenticación explícita para subir imágenes
        if (! auth()->check()) {
            $this->addError('auth', 'Debe iniciar sesión para subir imágenes.');
            session()->flash('error', 'Debe iniciar sesión para subir imágenes.');
            return;
        }

        try {
            $servicio = app(ImagenVehiculoService::class);
            $resultado = $servicio->guardarImagen($this->archivo, $this->tipo, $this->placa);

            // Emitir hacia el componente padre para que recargue la galería
            $payload = [
                'resultado' => $resultado,
                'tipo' => $this->tipo,
                'placa' => $this->placa,
            ];

            // Usar dispatch para Livewire 3
            $this->dispatch('imagenSubida', $payload);
            
            session()->flash('success', 'Imagen subida correctamente.');

            // Limpiar input
            $this->reset(['archivo']);
        } catch (\Exception $e) {
            Log::error('UploadImagenes: fallo al subir imagen: ' . $e->getMessage());
            // Añadir error al bag de errores de Livewire para tests y UI
            $this->addError('upload', $e->getMessage());
            session()->flash('error', 'Error al subir la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar la subida (cliente pide cancelar)
     */
    public function cancelarUpload()
    {
        $this->reset('archivo');
        session()->flash('message', 'Subida cancelada.');
    }

    public function render()
    {
        return view('livewire.vehiculo.upload-imagenes', [
            'tipos' => app(ImagenVehiculoService::class)->getTiposImagen()
        ]);
    }
}
