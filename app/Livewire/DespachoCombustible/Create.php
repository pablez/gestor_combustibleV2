<?php

namespace App\Livewire\DespachoCombustible;

use Livewire\Component;
use App\Models\DespachoCombustible;
use App\Models\SolicitudCombustible;
use App\Models\Proveedor;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Crear Despacho de Combustible')]
class Create extends Component
{
    #[Validate('required|exists:solicitud_combustibles,id')]
    public $id_solicitud = '';
    
    #[Validate('required|exists:proveedors,id')]
    public $id_proveedor = '';
    
    #[Validate('required|date')]
    public $fecha_despacho = '';
    
    #[Validate('required|numeric|min:0.1|max:9999')]
    public $litros_despachados = '';
    
    #[Validate('required|numeric|min:1')]
    public $precio_por_litro = '';
    
    public $costo_total = 0;
    
    #[Validate('nullable|string|max:100')]
    public $numero_vale = '';
    
    #[Validate('nullable|string|max:100')]
    public $numero_factura = '';
    
    #[Validate('nullable|string|max:255')]
    public $ubicacion_despacho = '';
    
    #[Validate('nullable|string|max:1000')]
    public $observaciones = '';

    public function mount()
    {
        $this->fecha_despacho = now()->format('Y-m-d');
    }

    public function updatedLitrosDespachados()
    {
        $this->calcularCostoTotal();
    }

    public function updatedPrecioPorLitro()
    {
        $this->calcularCostoTotal();
    }

    private function calcularCostoTotal()
    {
        if ($this->litros_despachados && $this->precio_por_litro) {
            $this->costo_total = round($this->litros_despachados * $this->precio_por_litro, 0);
        } else {
            $this->costo_total = 0;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Verificar que la solicitud esté aprobada y no tenga despacho
            $solicitud = SolicitudCombustible::findOrFail($this->id_solicitud);
            
            if ($solicitud->estado !== 'aprobada') {
                session()->flash('error', 'Solo se pueden despachar solicitudes aprobadas.');
                return;
            }

            // Verificar si ya tiene despacho
            $despachoExistente = DespachoCombustible::where('id_solicitud', $this->id_solicitud)->first();
            if ($despachoExistente) {
                session()->flash('error', 'Esta solicitud ya tiene un despacho registrado.');
                return;
            }

            // Calcular costo total final
            $this->calcularCostoTotal();

            DespachoCombustible::create([
                'id_solicitud' => $this->id_solicitud,
                'id_proveedor' => $this->id_proveedor,
                'fecha_despacho' => $this->fecha_despacho,
                'litros_despachados' => $this->litros_despachados,
                'precio_por_litro' => $this->precio_por_litro,
                'costo_total' => $this->costo_total,
                'numero_vale' => $this->numero_vale ?: null,
                'numero_factura' => $this->numero_factura ?: null,
                'id_usuario_despachador' => auth()->id(),
                'ubicacion_despacho' => $this->ubicacion_despacho ?: null,
                'observaciones' => $this->observaciones ?: null,
                'validado' => false,
            ]);

            // Actualizar estado de la solicitud
            $solicitud->update(['estado' => 'despachada']);

            session()->flash('message', 'Despacho creado correctamente.');
            return redirect()->route('despachos.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el despacho: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $solicitudesAprobadas = SolicitudCombustible::with(['unidadTransporte', 'solicitante'])
            ->where('estado_solicitud', 'Aprobada')
            ->whereDoesntHave('despachos')
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        $proveedores = Proveedor::where('activo', true)
            ->orderBy('nombre_comercial')
            ->orderBy('nombre_proveedor')
            ->get();

        return view('livewire.despacho-combustible.create', [
            'solicitudesAprobadas' => $solicitudesAprobadas,
            'proveedores' => $proveedores,
        ]);
    }
}
