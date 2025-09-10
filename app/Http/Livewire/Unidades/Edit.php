<?php

namespace App\Http\Livewire\Unidades;

use Livewire\Component;
use App\Models\UnidadOrganizacional;

class Edit extends Component
{
    public $id_unidad;
    public $codigo_unidad;
    public $nombre_unidad;
    public $tipo_unidad;
    public $id_unidad_padre;
    public $nivel_jerarquico;
    public $responsable_unidad;
    public $telefono;
    public $direccion;
    public $presupuesto_asignado;
    public $descripcion;
    public $activa;

    protected $rules = [
        'codigo_unidad' => 'required|string|max:20',
        'nombre_unidad' => 'required|string|max:100',
        'tipo_unidad' => 'required|in:Superior,Ejecutiva,Operativa',
        'nivel_jerarquico' => 'nullable|integer|min:1',
        'presupuesto_asignado' => 'nullable|numeric|min:0',
        'activa' => 'boolean',
    ];

    public function mount($id)
    {
        if (! auth()->check() || ! auth()->user()->can('usuarios.gestionar')) {
            abort(403);
        }

        $u = UnidadOrganizacional::findOrFail($id);
        $this->id_unidad = $u->id_unidad_organizacional;
        $this->codigo_unidad = $u->codigo_unidad;
        $this->nombre_unidad = $u->nombre_unidad;
        $this->tipo_unidad = $u->tipo_unidad;
        $this->id_unidad_padre = $u->id_unidad_padre;
        $this->nivel_jerarquico = $u->nivel_jerarquico;
        $this->responsable_unidad = $u->responsable_unidad;
        $this->telefono = $u->telefono;
        $this->direccion = $u->direccion;
        $this->presupuesto_asignado = $u->presupuesto_asignado;
        $this->descripcion = $u->descripcion;
        $this->activa = $u->activa;
    }

    public function save()
    {
        $data = $this->validate();
        $u = UnidadOrganizacional::findOrFail($this->id_unidad);
        $u->update(array_merge($data, [
            'id_unidad_padre' => $this->id_unidad_padre,
            'responsable_unidad' => $this->responsable_unidad,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'descripcion' => $this->descripcion,
            'activa' => $this->activa,
        ]));

        session()->flash('success', 'Unidad actualizada');
        return redirect()->route('unidades.index');
    }

    public function render()
    {
        $parents = UnidadOrganizacional::where('id_unidad_organizacional', '!=', $this->id_unidad)->orderBy('nombre_unidad')->get();
        return view('livewire.unidades.edit', ['parents' => $parents]);
    }
}
