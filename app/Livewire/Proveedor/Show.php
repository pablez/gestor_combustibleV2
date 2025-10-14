<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;
use App\Models\Proveedor;
use Livewire\Attributes\Title;

#[Title('Ver Proveedor')]
class Show extends Component
{
    public Proveedor $proveedor;

    public function mount(Proveedor $proveedor)
    {
        $this->proveedor = $proveedor->load('tipoServicioProveedor');
    }

    public function toggleStatus()
    {
        try {
            $this->proveedor->update(['activo' => !$this->proveedor->activo]);
            $this->proveedor->refresh();
            session()->flash('message', 'Estado del proveedor actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $this->proveedor->delete();
            session()->flash('message', 'Proveedor eliminado correctamente.');
            return redirect()->route('proveedores.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.proveedor.show');
    }
}
