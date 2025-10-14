<?php

namespace App\Livewire\Presupuesto;

use App\Models\Presupuesto;
use Livewire\Component;

class Show extends Component
{
    public Presupuesto $presupuesto;

    public function mount(Presupuesto $presupuesto)
    {
        $this->presupuesto = $presupuesto->load([
            'unidadOrganizacional',
            'categoriaProgramatica', 
            'fuenteOrganismoFinanciero'
        ]);
    }
    
    public function toggleActivo()
    {
        $this->presupuesto->update(['activo' => !$this->presupuesto->activo]);
        
        $this->presupuesto->refresh();
        
        session()->flash('message', 'Estado del presupuesto actualizado exitosamente.');
    }

    public function render()
    {
        return view('livewire.presupuesto.show');
    }
}
