<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;
use App\Models\Proveedor;
use App\Models\TipoServicioProveedor;
use Livewire\Attributes\Title;

#[Title('Editar Proveedor')]
class Edit extends Component
{
    public Proveedor $proveedor;

    // Propiedades del formulario
    public $id_tipo_servicio_proveedor;
    public $nit;
    public $nombre_proveedor;
    public $nombre_comercial;
    public $telefono;
    public $email;
    public $direccion;
    public $contacto_principal;
    public $calificacion = 'C';
    public $observaciones;
    public $activo = true;

    protected function rules()
    {
        return [
            'id_tipo_servicio_proveedor' => 'required|exists:tipo_servicio_proveedors,id',
            'nit' => 'required|string|max:20|unique:proveedors,nit,' . $this->proveedor->id,
            'nombre_proveedor' => 'required|string|max:100|unique:proveedors,nombre_proveedor,' . $this->proveedor->id,
            'nombre_comercial' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:200',
            'contacto_principal' => 'nullable|string|max:100',
            'calificacion' => 'required|in:A,B,C,D',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean',
        ];
    }

    protected $messages = [
        'id_tipo_servicio_proveedor.required' => 'El tipo de servicio es requerido.',
        'id_tipo_servicio_proveedor.exists' => 'El tipo de servicio seleccionado no es válido.',
        'nit.required' => 'El NIT/RUT es requerido.',
        'nit.unique' => 'Este NIT/RUT ya está registrado.',
        'nit.max' => 'El NIT/RUT no puede tener más de 20 caracteres.',
        'nombre_proveedor.required' => 'El nombre del proveedor es requerido.',
        'nombre_proveedor.unique' => 'Este nombre de proveedor ya está registrado.',
        'nombre_proveedor.max' => 'El nombre del proveedor no puede tener más de 100 caracteres.',
        'nombre_comercial.max' => 'El nombre comercial no puede tener más de 100 caracteres.',
        'telefono.max' => 'El teléfono no puede tener más de 15 caracteres.',
        'email.email' => 'El email debe tener un formato válido.',
        'email.max' => 'El email no puede tener más de 100 caracteres.',
        'direccion.max' => 'La dirección no puede tener más de 200 caracteres.',
        'contacto_principal.max' => 'El contacto principal no puede tener más de 100 caracteres.',
        'calificacion.required' => 'La calificación es requerida.',
        'calificacion.in' => 'La calificación debe ser A, B, C o D.',
    ];

    public function mount(Proveedor $proveedor)
    {
        $this->proveedor = $proveedor;
        $this->id_tipo_servicio_proveedor = $proveedor->id_tipo_servicio_proveedor;
        $this->nit = $proveedor->nit;
        $this->nombre_proveedor = $proveedor->nombre_proveedor;
        $this->nombre_comercial = $proveedor->nombre_comercial;
        $this->telefono = $proveedor->telefono;
        $this->email = $proveedor->email;
        $this->direccion = $proveedor->direccion;
        $this->contacto_principal = $proveedor->contacto_principal;
        $this->calificacion = $proveedor->calificacion;
        $this->observaciones = $proveedor->observaciones;
        $this->activo = $proveedor->activo;
    }

    public function save()
    {
        $this->validate();

        try {
            $this->proveedor->update([
                'id_tipo_servicio_proveedor' => $this->id_tipo_servicio_proveedor,
                'nit' => $this->nit,
                'nombre_proveedor' => $this->nombre_proveedor,
                'nombre_comercial' => $this->nombre_comercial,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'direccion' => $this->direccion,
                'contacto_principal' => $this->contacto_principal,
                'calificacion' => $this->calificacion,
                'observaciones' => $this->observaciones,
                'activo' => $this->activo,
            ]);

            session()->flash('message', 'Proveedor actualizado correctamente.');
            return redirect()->route('proveedores.show', $this->proveedor);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('proveedores.show', $this->proveedor);
    }

    public function render()
    {
        $tiposServicio = TipoServicioProveedor::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('livewire.proveedor.edit', [
            'tiposServicio' => $tiposServicio
        ]);
    }
}
