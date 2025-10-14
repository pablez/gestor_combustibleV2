<?php

namespace App\Livewire\ConsumoCombustible;

use Livewire\Component;
use App\Models\ConsumoCombustible;
use App\Models\UnidadTransporte;
use App\Models\DespachoCombustible;
use App\Models\User;
use Livewire\Attributes\Title;

#[Title('Nuevo Consumo de Combustible')]
class Create extends Component
{
    // Campos del formulario
    public $id_unidad_transporte;
    public $id_despacho;
    public $id_usuario_conductor;
    public $fecha_registro;
    public $kilometraje_inicial;
    public $kilometraje_fin;
    public $litros_cargados;
    public $tipo_carga = 'Completa';
    public $lugar_carga;
    public $numero_ticket;
    public $observaciones;

    // Campos calculados
    public $kilometros_recorridos = 0;
    public $rendimiento = 0;

    protected $rules = [
        'id_unidad_transporte' => 'required|exists:unidad_transportes,id',
        'id_despacho' => 'nullable|exists:despacho_combustibles,id',
        'id_usuario_conductor' => 'required|exists:users,id',
        'fecha_registro' => 'required|date|before_or_equal:today',
        'kilometraje_inicial' => 'required|numeric|min:0',
        'kilometraje_fin' => 'required|numeric|gt:kilometraje_inicial',
        'litros_cargados' => 'required|numeric|min:0.1|max:999.999',
        'tipo_carga' => 'required|in:Completa,Parcial,Emergencia',
        'lugar_carga' => 'required|string|max:255',
        'numero_ticket' => 'nullable|string|max:100',
        'observaciones' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'id_unidad_transporte.required' => 'Debe seleccionar una unidad de transporte.',
        'id_unidad_transporte.exists' => 'La unidad seleccionada no es válida.',
        'id_usuario_conductor.required' => 'Debe seleccionar un conductor.',
        'id_usuario_conductor.exists' => 'El conductor seleccionado no es válido.',
        'fecha_registro.required' => 'La fecha de registro es obligatoria.',
        'fecha_registro.date' => 'La fecha de registro debe ser una fecha válida.',
        'fecha_registro.before_or_equal' => 'La fecha de registro no puede ser posterior a hoy.',
        'kilometraje_inicial.required' => 'El kilometraje inicial es obligatorio.',
        'kilometraje_inicial.numeric' => 'El kilometraje inicial debe ser un número.',
        'kilometraje_inicial.min' => 'El kilometraje inicial no puede ser negativo.',
        'kilometraje_fin.required' => 'El kilometraje final es obligatorio.',
        'kilometraje_fin.numeric' => 'El kilometraje final debe ser un número.',
        'kilometraje_fin.gt' => 'El kilometraje final debe ser mayor al inicial.',
        'litros_cargados.required' => 'Los litros cargados son obligatorios.',
        'litros_cargados.numeric' => 'Los litros cargados deben ser un número.',
        'litros_cargados.min' => 'Los litros cargados deben ser mayor a 0.',
        'lugar_carga.required' => 'El lugar de carga es obligatorio.',
        'tipo_carga.required' => 'El tipo de carga es obligatorio.',
        'tipo_carga.in' => 'El tipo de carga debe ser: Completa, Parcial o Emergencia.',
    ];

    public function mount()
    {
        $this->fecha_registro = date('Y-m-d');
    }

    public function updatedKilometrajeInicial()
    {
        $this->calcularKilometrosYRendimiento();
    }

    public function updatedKilometrajeFin()
    {
        $this->calcularKilometrosYRendimiento();
    }

    public function updatedLitrosCargados()
    {
        $this->calcularKilometrosYRendimiento();
    }

    private function calcularKilometrosYRendimiento()
    {
        if ($this->kilometraje_inicial && $this->kilometraje_fin && $this->kilometraje_fin > $this->kilometraje_inicial) {
            $this->kilometros_recorridos = $this->kilometraje_fin - $this->kilometraje_inicial;
            
            if ($this->litros_cargados && $this->litros_cargados > 0) {
                $this->rendimiento = round($this->kilometros_recorridos / $this->litros_cargados, 2);
            }
        } else {
            $this->kilometros_recorridos = 0;
            $this->rendimiento = 0;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            ConsumoCombustible::create([
                'id_unidad_transporte' => $this->id_unidad_transporte,
                'id_despacho' => $this->id_despacho ?: null,
                'id_usuario_conductor' => $this->id_usuario_conductor,
                'fecha_registro' => $this->fecha_registro,
                'kilometraje_inicial' => $this->kilometraje_inicial,
                'kilometraje_fin' => $this->kilometraje_fin,
                'litros_cargados' => $this->litros_cargados,
                'tipo_carga' => $this->tipo_carga,
                'lugar_carga' => $this->lugar_carga,
                'numero_ticket' => $this->numero_ticket ?: null,
                'observaciones' => $this->observaciones ?: null,
                'validado' => false,
            ]);

            session()->flash('message', 'Consumo de combustible registrado correctamente.');
            return redirect()->route('consumos.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar el consumo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $unidades = UnidadTransporte::where('activo', true)
            ->orderBy('placa')
            ->get();

        $despachos = DespachoCombustible::with(['proveedor', 'solicitud.unidadTransporte'])
            ->where('validado', true)
            ->orderBy('fecha_despacho', 'desc')
            ->limit(20)
            ->get();

        $conductores = User::whereHas('roles', function ($query) {
                $query->where('name', 'Conductor');
            })
            ->orderBy('name')
            ->get();

        $tiposCarga = ['Completa', 'Parcial', 'Emergencia'];

        return view('livewire.consumo-combustible.create', [
            'unidades' => $unidades,
            'despachos' => $despachos,
            'conductores' => $conductores,
            'tiposCarga' => $tiposCarga,
        ]);
    }
}
