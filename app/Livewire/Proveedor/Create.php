<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;
use App\Models\Proveedor;
use App\Models\TipoServicioProveedor;
use Livewire\Attributes\Title;

#[Title('Crear Proveedor')]
class Create extends Component
{
    // Propiedades del formulario
    public $id_tipo_servicio_proveedor = '';
    public $nit = '';
    public $nombre_proveedor = '';
    public $nombre_comercial = '';
    public $telefono = '';
    public $email = '';
    public $direccion = '';
    public $contacto_principal = '';
    public $calificacion = 'C';
    public $observaciones = '';
    public $activo = true;

    protected $rules = [
        'id_tipo_servicio_proveedor' => 'required|exists:tipo_servicio_proveedors,id',
        'nit' => 'required|string|max:20|unique:proveedors,nit',
        'nombre_proveedor' => 'required|string|max:100|unique:proveedors,nombre_proveedor',
        'nombre_comercial' => 'nullable|string|max:100',
        'telefono' => 'nullable|string|max:15',
        'email' => 'nullable|email|max:100',
        'direccion' => 'nullable|string|max:200',
        'contacto_principal' => 'nullable|string|max:100',
        'calificacion' => 'required|in:A,B,C,D',
        'observaciones' => 'nullable|string',
        'activo' => 'boolean',
    ];

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

    public function save()
    {
        $this->validate();

        try {
            Proveedor::create([
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

            session()->flash('message', 'Proveedor creado correctamente.');
            return redirect()->route('proveedores.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el proveedor: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('proveedores.index');
    }

    public function render()
    {
        $tiposServicio = TipoServicioProveedor::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('livewire.proveedor.create', [
            'tiposServicio' => $tiposServicio
        ]);
    }
}
