<?php

namespace App\Livewire\ConsumoCombustible;

use App\Models\ConsumoCombustible;
use App\Models\UnidadTransporte;
use App\Models\User;
use App\Models\DespachoCombustible;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;

class Edit extends Component
{
    public ConsumoCombustible $consumo;
    
    #[Rule('required|exists:unidad_transportes,id')]
    public $unidad_transporte_id;
    
    #[Rule('required|exists:users,id')]
    public $conductor_id;
    
    #[Rule('nullable|exists:despacho_combustibles,id')]
    public $despacho_combustible_id;
    
    #[Rule('required|date')]
    public $fecha_registro;
    
    #[Rule('required|numeric|min:0.001|max:9999.999')]
    public $litros_cargados;
    
    #[Rule('required|in:Completa,Parcial,Emergencia')]
    public $tipo_carga = 'Completa';
    
    #[Rule('required|string|min:3|max:255')]
    public $lugar_carga;
    
    #[Rule('nullable|string|max:100')]
    public $numero_ticket;
    
    #[Rule('required|numeric|min:0|max:9999999')]
    public $kilometraje_inicial;
    
    #[Rule('required|numeric|min:0|max:9999999|gt:kilometraje_inicial')]
    public $kilometraje_fin;
    
    #[Rule('nullable|string|max:1000')]
    public $observaciones;

    // Propiedades calculadas
    public $kilometros_recorridos = 0;
    public $rendimiento = 0;

    public function mount(ConsumoCombustible $consumo)
    {
        $this->consumo = $consumo;
        
        // Cargar los datos actuales del consumo
        $this->unidad_transporte_id = $consumo->unidad_transporte_id;
        $this->conductor_id = $consumo->conductor_id;
        $this->despacho_combustible_id = $consumo->despacho_combustible_id;
        $this->fecha_registro = $consumo->fecha_registro->format('Y-m-d');
        $this->litros_cargados = $consumo->litros_cargados;
        $this->tipo_carga = $consumo->tipo_carga;
        $this->lugar_carga = $consumo->lugar_carga;
        $this->numero_ticket = $consumo->numero_ticket;
        $this->kilometraje_inicial = $consumo->kilometraje_inicial;
        $this->kilometraje_fin = $consumo->kilometraje_fin;
        $this->observaciones = $consumo->observaciones;
        
        $this->calcularKilometrajeYRendimiento();
    }
    
    public function updated($property)
    {
        if (in_array($property, ['kilometraje_inicial', 'kilometraje_fin', 'litros_cargados'])) {
            $this->calcularKilometrajeYRendimiento();
        }
    }
    
    public function calcularKilometrajeYRendimiento()
    {
        if ($this->kilometraje_inicial && $this->kilometraje_fin && $this->kilometraje_fin > $this->kilometraje_inicial) {
            $this->kilometros_recorridos = $this->kilometraje_fin - $this->kilometraje_inicial;
            
            if ($this->litros_cargados > 0) {
                $this->rendimiento = round($this->kilometros_recorridos / $this->litros_cargados, 2);
            }
        } else {
            $this->kilometros_recorridos = 0;
            $this->rendimiento = 0;
        }
    }
    
    #[On('despacho-selected')]
    public function onDespachoSelected($despachoId)
    {
        if ($despachoId) {
            $despacho = DespachoCombustible::find($despachoId);
            if ($despacho) {
                $this->litros_cargados = $despacho->litros_despachados;
                $this->lugar_carga = $despacho->lugar_despacho ?: $this->lugar_carga;
                $this->calcularKilometrajeYRendimiento();
            }
        }
    }
    
    public function save()
    {
        $this->validate();
        
        try {
            $this->consumo->update([
                'unidad_transporte_id' => $this->unidad_transporte_id,
                'conductor_id' => $this->conductor_id,
                'despacho_combustible_id' => $this->despacho_combustible_id,
                'fecha_registro' => $this->fecha_registro,
                'litros_cargados' => $this->litros_cargados,
                'tipo_carga' => $this->tipo_carga,
                'lugar_carga' => $this->lugar_carga,
                'numero_ticket' => $this->numero_ticket,
                'kilometraje_inicial' => $this->kilometraje_inicial,
                'kilometraje_fin' => $this->kilometraje_fin,
                'kilometros_recorridos' => $this->kilometros_recorridos,
                'rendimiento' => $this->rendimiento,
                'observaciones' => $this->observaciones,
            ]);
            
            session()->flash('message', 'Consumo de combustible actualizado exitosamente.');
            
            return redirect()->route('consumos.show', $this->consumo);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el consumo: ' . $e->getMessage());
        }
    }
    
    public function cancel()
    {
        return redirect()->route('consumos.show', $this->consumo);
    }

    public function render()
    {
        $unidadesTransporte = UnidadTransporte::with(['tipoVehiculo', 'tipoCombustible'])->get();
        $conductores = User::whereHas('roles', function($query) {
            $query->where('name', 'conductor');
        })->orWhereHas('permissions', function($query) {
            $query->where('name', 'like', '%conductor%');
        })->get();
        
        $despachos = DespachoCombustible::where('validado', true)
            ->whereDoesntHave('consumos')
            ->orWhere('id', $this->despacho_combustible_id)
            ->with(['proveedor', 'unidadTransporte'])
            ->get();
        
        return view('livewire.consumo-combustible.edit', [
            'unidadesTransporte' => $unidadesTransporte,
            'conductores' => $conductores,
            'despachos' => $despachos,
        ]);
    }
}
