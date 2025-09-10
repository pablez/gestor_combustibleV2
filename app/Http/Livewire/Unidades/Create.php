<?php

namespace App\Http\Livewire\Unidades;

use Livewire\Component;
use App\Models\UnidadOrganizacional;

class Create extends Component
{
    public $codigo_unidad;
    public $nombre_unidad;
    public $tipo_unidad = 'Operativa';
    public $id_unidad_padre;
    public $nivel_jerarquico = 1;
    public $responsable_unidad;
    public $telefono;
    public $direccion;
    public $presupuesto_asignado = 0;
    public $descripcion;
    public $activa = true;

    protected $rules = [
        'codigo_unidad' => 'required|string|max:20|unique:unidades_organizacionales,codigo_unidad',
        'nombre_unidad' => 'required|string|max:100|unique:unidades_organizacionales,nombre_unidad',
        'tipo_unidad' => 'required|in:Superior,Ejecutiva,Operativa',
        'nivel_jerarquico' => 'nullable|integer|min:1',
        'presupuesto_asignado' => 'nullable|numeric|min:0',
        'activa' => 'boolean',
    ];

    public function mount()
    {
        if (! auth()->check() || ! auth()->user()->can('usuarios.gestionar')) {
            abort(403);
        }
    }

    public function save()
    {
        $data = $this->validate();
        $data = array_merge($data, [
            'id_unidad_padre' => $this->id_unidad_padre,
            'responsable_unidad' => $this->responsable_unidad,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'descripcion' => $this->descripcion,
            'activa' => $this->activa,
        ]);

        UnidadOrganizacional::create($data);

        session()->flash('success', 'Unidad creada');

        return redirect()->route('unidades.index');
    }

    public function render()
    {
        $parents = UnidadOrganizacional::orderBy('nombre_unidad')->get();
        return view('livewire.unidades.create', ['parents' => $parents]);
    }
}
