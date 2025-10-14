<?php

namespace App\Livewire\DespachoCombustible;

use Livewire\Component;
use App\Models\DespachoCombustible;
use App\Models\Proveedor;
use App\Models\SolicitudCombustible;
use Livewire\Attributes\Title;

#[Title('Editar Despacho de Combustible')]
class Edit extends Component
{
    public DespachoCombustible $despacho;
    
    // Campos del formulario
    public $id_solicitud;
    public $id_proveedor;
    public $fecha_despacho;
    public $litros_despachados;
    public $precio_por_litro;
    public $costo_total;
    public $numero_vale;
    public $numero_factura;
    public $ubicacion_despacho;
    public $observaciones;

    protected $rules = [
        'id_solicitud' => 'required|exists:solicitud_combustibles,id',
        'id_proveedor' => 'required|exists:proveedores,id',
        'fecha_despacho' => 'required|date|before_or_equal:today',
        'litros_despachados' => 'required|numeric|min:0.1|max:9999.99',
        'precio_por_litro' => 'required|integer|min:1',
        'numero_vale' => 'required|string|max:100',
        'numero_factura' => 'nullable|string|max:100',
        'ubicacion_despacho' => 'required|string|max:255',
        'observaciones' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'id_solicitud.required' => 'Debe seleccionar una solicitud.',
        'id_solicitud.exists' => 'La solicitud seleccionada no es válida.',
        'id_proveedor.required' => 'Debe seleccionar un proveedor.',
        'id_proveedor.exists' => 'El proveedor seleccionado no es válido.',
        'fecha_despacho.required' => 'La fecha de despacho es obligatoria.',
        'fecha_despacho.date' => 'La fecha de despacho debe ser una fecha válida.',
        'fecha_despacho.before_or_equal' => 'La fecha de despacho no puede ser posterior a hoy.',
        'litros_despachados.required' => 'Los litros despachados son obligatorios.',
        'litros_despachados.numeric' => 'Los litros despachados deben ser un número.',
        'litros_despachados.min' => 'Los litros despachados deben ser mayor a 0.',
        'precio_por_litro.required' => 'El precio por litro es obligatorio.',
        'precio_por_litro.integer' => 'El precio por litro debe ser un número entero.',
        'numero_vale.required' => 'El número de vale es obligatorio.',
        'ubicacion_despacho.required' => 'La ubicación de despacho es obligatoria.',
    ];

    public function mount(DespachoCombustible $despacho)
    {
        $this->despacho = $despacho->load(['solicitud', 'proveedor']);
        
        // Cargar datos del despacho en las propiedades
        $this->id_solicitud = $despacho->id_solicitud;
        $this->id_proveedor = $despacho->id_proveedor;
        $this->fecha_despacho = $despacho->fecha_despacho->format('Y-m-d');
        $this->litros_despachados = $despacho->litros_despachados;
        $this->precio_por_litro = $despacho->precio_por_litro;
        $this->costo_total = $despacho->costo_total;
        $this->numero_vale = $despacho->numero_vale;
        $this->numero_factura = $despacho->numero_factura;
        $this->ubicacion_despacho = $despacho->ubicacion_despacho;
        $this->observaciones = $despacho->observaciones;
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
            $this->costo_total = floatval($this->litros_despachados) * intval($this->precio_por_litro);
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $this->despacho->update([
                'id_solicitud' => $this->id_solicitud,
                'id_proveedor' => $this->id_proveedor,
                'fecha_despacho' => $this->fecha_despacho,
                'litros_despachados' => $this->litros_despachados,
                'precio_por_litro' => $this->precio_por_litro,
                'costo_total' => $this->costo_total,
                'numero_vale' => $this->numero_vale,
                'numero_factura' => $this->numero_factura ?: null,
                'ubicacion_despacho' => $this->ubicacion_despacho,
                'observaciones' => $this->observaciones ?: null,
            ]);

            session()->flash('message', 'Despacho actualizado correctamente.');
            return redirect()->route('despachos.show', $this->despacho);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el despacho: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $solicitudesAprobadas = SolicitudCombustible::with(['unidadTransporte', 'solicitante'])
            ->where('estado_solicitud', 'Aprobada')
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        $proveedores = Proveedor::where('activo', true)
            ->orderBy('nombre_comercial')
            ->orderBy('nombre_proveedor')
            ->get();

        return view('livewire.despacho-combustible.edit', [
            'solicitudesAprobadas' => $solicitudesAprobadas,
            'proveedores' => $proveedores,
        ]);
    }
}
