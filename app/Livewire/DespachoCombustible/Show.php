<?php

namespace App\Livewire\DespachoCombustible;

use Livewire\Component;
use App\Models\DespachoCombustible;
use Livewire\Attributes\Title;

#[Title('Ver Despacho de Combustible')]
class Show extends Component
{
    public DespachoCombustible $despacho;

    public function mount(DespachoCombustible $despacho)
    {
        $this->despacho = $despacho->load([
            'solicitud.unidadTransporte.tipoVehiculo',
            'solicitud.unidadTransporte.tipoCombustible', 
            'solicitud.solicitante',
            'proveedor.tipoServicioProveedor',
            'despachador',
            'validador',
            'consumos'
        ]);
    }

    public function validar()
    {
        try {
            $this->despacho->update([
                'validado' => true,
                'fecha_validacion' => now(),
                'id_usuario_validador' => auth()->id(),
            ]);

            $this->despacho->refresh();
            session()->flash('message', 'Despacho validado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al validar el despacho: ' . $e->getMessage());
        }
    }

    public function invalidar()
    {
        try {
            $this->despacho->update([
                'validado' => false,
                'fecha_validacion' => null,
                'id_usuario_validador' => null,
            ]);

            $this->despacho->refresh();
            session()->flash('message', 'Despacho invalidado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al invalidar el despacho: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            // Revertir estado de la solicitud
            if ($this->despacho->solicitud) {
                $this->despacho->solicitud->update(['estado' => 'aprobada']);
            }

            $this->despacho->delete();
            session()->flash('message', 'Despacho eliminado correctamente.');
            return redirect()->route('despachos.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.despacho-combustible.show');
    }
}
