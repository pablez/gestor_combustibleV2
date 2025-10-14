<?php

namespace App\Livewire\TipoServicioProveedor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoServicioProveedor;
use Livewire\Attributes\Title;

#[Title('Tipos de Servicio de Proveedor')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'nombre';
    public $sortDirection = 'asc';
    public $showModal = false;
    public $editMode = false;

    // Propiedades del formulario
    public $tipo_id;
    public $codigo = '';
    public $nombre = '';
    public $descripcion = '';
    public $requiere_autorizacion_especial = false;
    public $dias_credito_maximo = 0;
    public $activo = true;

    protected $rules = [
        'codigo' => 'required|string|max:10|unique:tipo_servicio_proveedors,codigo',
        'nombre' => 'required|string|max:100|unique:tipo_servicio_proveedors,nombre',
        'descripcion' => 'nullable|string|max:200',
        'requiere_autorizacion_especial' => 'boolean',
        'dias_credito_maximo' => 'integer|min:0|max:255',
        'activo' => 'boolean',
    ];

    protected $messages = [
        'codigo.required' => 'El código es requerido.',
        'codigo.unique' => 'Este código ya está en uso.',
        'codigo.max' => 'El código no puede tener más de 10 caracteres.',
        'nombre.required' => 'El nombre es requerido.',
        'nombre.unique' => 'Este nombre ya está en uso.',
        'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
        'descripcion.max' => 'La descripción no puede tener más de 200 caracteres.',
        'dias_credito_maximo.integer' => 'Los días de crédito deben ser un número entero.',
        'dias_credito_maximo.min' => 'Los días de crédito no pueden ser negativos.',
        'dias_credito_maximo.max' => 'Los días de crédito no pueden ser mayores a 255.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $tipo = TipoServicioProveedor::findOrFail($id);
        $this->tipo_id = $tipo->id;
        $this->codigo = $tipo->codigo;
        $this->nombre = $tipo->nombre;
        $this->descripcion = $tipo->descripcion;
        $this->requiere_autorizacion_especial = $tipo->requiere_autorizacion_especial;
        $this->dias_credito_maximo = $tipo->dias_credito_maximo;
        $this->activo = $tipo->activo;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['codigo'] = 'required|string|max:10|unique:tipo_servicio_proveedors,codigo,' . $this->tipo_id;
            $this->rules['nombre'] = 'required|string|max:100|unique:tipo_servicio_proveedors,nombre,' . $this->tipo_id;
        }

        $this->validate();

        try {
            if ($this->editMode) {
                $tipo = TipoServicioProveedor::findOrFail($this->tipo_id);
                $tipo->update([
                    'codigo' => $this->codigo,
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'requiere_autorizacion_especial' => $this->requiere_autorizacion_especial,
                    'dias_credito_maximo' => $this->dias_credito_maximo,
                    'activo' => $this->activo,
                ]);
                session()->flash('message', 'Tipo de servicio actualizado correctamente.');
            } else {
                TipoServicioProveedor::create([
                    'codigo' => $this->codigo,
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'requiere_autorizacion_especial' => $this->requiere_autorizacion_especial,
                    'dias_credito_maximo' => $this->dias_credito_maximo,
                    'activo' => $this->activo,
                ]);
                session()->flash('message', 'Tipo de servicio creado correctamente.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $tipo = TipoServicioProveedor::findOrFail($id);
            $tipo->delete();
            session()->flash('message', 'Tipo de servicio eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $tipo = TipoServicioProveedor::findOrFail($id);
            $tipo->update(['activo' => !$tipo->activo]);
            session()->flash('message', 'Estado actualizado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    private function resetForm()
    {
        $this->tipo_id = null;
        $this->codigo = '';
        $this->nombre = '';
        $this->descripcion = '';
        $this->requiere_autorizacion_especial = false;
        $this->dias_credito_maximo = 0;
        $this->activo = true;
    }

    public function render()
    {
        $tipos = TipoServicioProveedor::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.tipo-servicio-proveedor.index', [
            'tipos' => $tipos
        ]);
    }
}
