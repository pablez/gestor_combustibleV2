<?php

namespace App\Livewire\SolicitudAprobacionUsuario;

use App\Models\SolicitudAprobacionUsuario;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $id_usuario;
    public $tipo_solicitud = 'nuevo_usuario';
    public $rol_solicitado = '';
    public $justificacion = '';
    public $id_supervisor_asignado;

    public $tiposDisponibles = [
        'nuevo_usuario' => 'Nuevo Usuario',
        'cambio_rol' => 'Cambio de Rol',
        'activacion' => 'Activación',
        'suspension' => 'Suspensión'
    ];

    public $rolesDisponibles = [
        'Admin_General' => 'Admin General',
        'Admin_Secretaria' => 'Admin Secretaría',
        'Conductor' => 'Conductor', 
        'Supervisor' => 'Supervisor'
    ];

    protected $rules = [
        'id_usuario' => 'required|exists:users,id',
        'tipo_solicitud' => 'required|in:nuevo_usuario,cambio_rol,activacion,suspension',
        'rol_solicitado' => 'required|string|max:255',
        'justificacion' => 'required|string|max:1000',
        'id_supervisor_asignado' => 'required|exists:users,id'
    ];

    public function mount()
    {
        $this->authorize('solicitudes_aprobacion.crear');
    }

    public function render()
    {
        $usuarios = User::select('id', 'name', 'email')->get();
        $supervisores = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin_General', 'Admin_Secretaria']);
        })->get(['id', 'name']);

        return view('livewire.solicitud-aprobacion-usuario.create', [
            'usuarios' => $usuarios,
            'supervisores' => $supervisores
        ]);
    }

    public function guardar()
    {
        $this->validate();

        try {
            SolicitudAprobacionUsuario::create([
                'id_usuario' => $this->id_usuario,
                'id_creador' => auth()->id(),
                'id_supervisor_asignado' => $this->id_supervisor_asignado,
                'tipo_solicitud' => $this->tipo_solicitud,
                'estado_solicitud' => 'pendiente',
                'rol_solicitado' => $this->rol_solicitado,
                'justificacion' => $this->justificacion
            ]);

            session()->flash('message', 'Solicitud de aprobación creada exitosamente.');
            return redirect()->route('solicitudes-aprobacion.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    public function cancelar()
    {
        return redirect()->route('solicitudes-aprobacion.index');
    }
}
