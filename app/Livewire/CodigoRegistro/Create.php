<?php

namespace App\Livewire\CodigoRegistro;

use App\Models\CodigoRegistro;
use Livewire\Component;

class Create extends Component
{
    public $diasVigencia = 7;
    public $cantidad = 1;
    public $codigosGenerados = [];
    public $mostrarCodigos = false;

    protected $rules = [
        'diasVigencia' => 'required|integer|min:1|max:365',
        'cantidad' => 'required|integer|min:1|max:50'
    ];

    protected $messages = [
        'diasVigencia.required' => 'Los días de vigencia son obligatorios.',
        'diasVigencia.integer' => 'Los días de vigencia deben ser un número entero.',
        'diasVigencia.min' => 'Los días de vigencia deben ser al menos 1.',
        'diasVigencia.max' => 'Los días de vigencia no pueden ser más de 365.',
        'cantidad.required' => 'La cantidad es obligatoria.',
        'cantidad.integer' => 'La cantidad debe ser un número entero.',
        'cantidad.min' => 'La cantidad debe ser al menos 1.',
        'cantidad.max' => 'No se pueden generar más de 50 códigos a la vez.'
    ];

    public function mount()
    {
        $this->authorize('codigos_registro.crear');
    }

    public function render()
    {
        return view('livewire.codigo-registro.create');
    }

    public function generarCodigos()
    {
        $this->validate();

        try {
            $this->codigosGenerados = [];
            
            for ($i = 0; $i < $this->cantidad; $i++) {
                $codigo = CodigoRegistro::crear(auth()->id(), $this->diasVigencia);
                $this->codigosGenerados[] = $codigo;
            }

            $this->mostrarCodigos = true;
            session()->flash('message', "Se generaron {$this->cantidad} código(s) exitosamente.");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar códigos: ' . $e->getMessage());
        }
    }

    public function nuevaGeneracion()
    {
        $this->reset(['codigosGenerados', 'mostrarCodigos']);
        $this->cantidad = 1;
        $this->diasVigencia = 7;
    }

    public function volver()
    {
        return redirect()->route('codigos-registro.index');
    }
}
