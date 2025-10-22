<?php

namespace App\Livewire\Solicitud;

use App\Models\SolicitudCombustible;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Show extends Component
{
    use AuthorizesRequests;

    public SolicitudCombustible $solicitud;
    public $showApprovalModal = false;
    public $showRejectionModal = false;
    public $observaciones = '';
    public $accion = '';

    public function mount(SolicitudCombustible $solicitud)
    {
        // Cargar las relaciones necesarias con información detallada
        $this->solicitud = $solicitud->load([
            'solicitante.unidad', 
            'unidadTransporte.tipoVehiculo', 
            'unidadTransporte.tipoCombustible',
            'unidadTransporte.unidadOrganizacional',
            'unidadTransporte.conductorAsignado',
            'aprobador',
            'categoriaProgramatica',
            'fuenteOrganismoFinanciero',
            'despachos.usuario'
        ]);

        // Verificar permisos usando la política
        $this->authorize('view', $this->solicitud);
    }

    public function aprobar()
    {
        $this->authorize('update', $this->solicitud);
        
        $this->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        try {
            $this->solicitud->aprobar(auth()->id(), $this->observaciones);
            
            session()->flash('success', '✅ Solicitud aprobada exitosamente');
            $this->showApprovalModal = false;
            $this->observaciones = '';
            
            // Actualizar los datos
            $this->solicitud->refresh();
            
            // Emitir evento para actualizar otros componentes
            $this->dispatch('solicitudApproved', $this->solicitud->id);
            
        } catch (\Exception $e) {
            session()->flash('error', '❌ Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    public function rechazar()
    {
        $this->authorize('update', $this->solicitud);
        
        $this->validate([
            'observaciones' => 'required|string|max:500',
        ], [
            'observaciones.required' => 'Las observaciones son obligatorias para rechazar una solicitud',
            'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres',
        ]);

        try {
            $this->solicitud->rechazar(auth()->id(), $this->observaciones);
            
            session()->flash('success', '❌ Solicitud rechazada exitosamente');
            $this->showRejectionModal = false;
            $this->observaciones = '';
            
            // Actualizar los datos
            $this->solicitud->refresh();
            
            // Emitir evento para actualizar otros componentes
            $this->dispatch('solicitudRejected', $this->solicitud->id);
            
        } catch (\Exception $e) {
            session()->flash('error', '❌ Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    public function mostrarModalAprobacion()
    {
        $this->authorize('update', $this->solicitud);
        $this->accion = 'aprobar';
        $this->showApprovalModal = true;
        $this->observaciones = '';
    }

    public function mostrarModalRechazo()
    {
        $this->authorize('update', $this->solicitud);
        $this->accion = 'rechazar';
        $this->showRejectionModal = true;
        $this->observaciones = '';
    }

    public function cerrarModales()
    {
        $this->showApprovalModal = false;
        $this->showRejectionModal = false;
        $this->observaciones = '';
        $this->accion = '';
    }

    public function getEstadoBadgeColorProperty()
    {
        return match($this->solicitud->estado_solicitud) {
            'Pendiente' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-300',
            'Aprobada' => 'bg-green-100 text-green-800 ring-1 ring-green-300',
            'Rechazada' => 'bg-red-100 text-red-800 ring-1 ring-red-300',
            'En_Proceso' => 'bg-blue-100 text-blue-800 ring-1 ring-blue-300',
            'Despachada' => 'bg-purple-100 text-purple-800 ring-1 ring-purple-300',
            'Completada' => 'bg-gray-100 text-gray-800 ring-1 ring-gray-300',
            default => 'bg-gray-100 text-gray-800 ring-1 ring-gray-300',
        };
    }

    public function getEstadoIconProperty()
    {
        return match($this->solicitud->estado_solicitud) {
            'Pendiente' => '⏳',
            'Aprobada' => '✅',
            'Rechazada' => '❌',
            'En_Proceso' => '🔄',
            'Despachada' => '🚛',
            'Completada' => '✔️',
            default => '📋',
        };
    }

    public function render()
    {
        return view('livewire.solicitud.show')
            ->layout('layouts.app')
            ->title('Solicitud #' . $this->solicitud->numero_solicitud . ' - Gestión de Combustible');
    }
}