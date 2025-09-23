<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\UnidadOrganizacional;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserCreate extends Component
{
    use WithFileUploads;

    // User fields
    public string $username = '';
    public string $name = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $ci = '';
    public string $telefono = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $id_unidad_organizacional = null;
    public $id_supervisor = null;
    public bool $activo = true;
    
    // Photo upload
    public $profile_photo = null;
    
    // Role assignment
    public array $selectedRoles = [];

    protected function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:50'],
            'apellido_materno' => ['nullable', 'string', 'max:50'],
            'ci' => ['required', 'string', 'max:15', 'unique:users,ci'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'id_unidad_organizacional' => ['nullable', 'exists:unidades_organizacionales,id_unidad_organizacional'],
            'id_supervisor' => ['nullable', 'exists:users,id'],
            'activo' => ['boolean'],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // 1MB max
            'selectedRoles' => ['array'],
            'selectedRoles.*' => ['exists:roles,name'],
        ];
    }

    protected $messages = [
        'username.required' => 'El nombre de usuario es obligatorio.',
        'username.unique' => 'Este nombre de usuario ya está en uso.',
        'name.required' => 'El nombre es obligatorio.',
    //'nombre' messages removed — use 'name' instead
        'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
        'ci.required' => 'La cédula de identidad es obligatoria.',
        'ci.unique' => 'Esta cédula de identidad ya está registrada.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'Debe ser un email válido.',
        'email.unique' => 'Este email ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'profile_photo.image' => 'Debe ser una imagen válida.',
        'profile_photo.max' => 'La imagen no puede ser mayor a 1MB.',
    ];

    public function updatedProfilePhoto()
    {
        $this->validateOnly('profile_photo');
    }

    public function updatedUsername()
    {
        // Auto-generate name if empty
        if (empty($this->name) && !empty($this->username)) {
            $this->name = ucwords(str_replace(['_', '-', '.'], ' ', $this->username));
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Normalize nullable foreign keys
            $unidad = $this->id_unidad_organizacional === '' ? null : $this->id_unidad_organizacional;
            $supervisor = $this->id_supervisor === '' ? null : $this->id_supervisor;

            DB::beginTransaction();

            // Create user
            $userData = [
                'username' => $this->username,
                'name' => $this->name,
                'apellido_paterno' => $this->apellido_paterno,
                'apellido_materno' => $this->apellido_materno,
                'ci' => $this->ci,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'id_unidad_organizacional' => $unidad,
                'id_supervisor' => $supervisor,
                'activo' => $this->activo,
            ];

            $user = User::create($userData);

            // Handle photo upload after create (so we can track it)
            if ($this->profile_photo) {
                $photoPath = $this->profile_photo->store('profile-photos', 'public');
                $user->profile_photo_path = $photoPath;
                $user->save();
            }

            // Assign roles (sync to ensure exact selection)
            if (!empty($this->selectedRoles)) {
                if (method_exists($user, 'syncRoles')) {
                    $user->syncRoles($this->selectedRoles);
                } else {
                    $user->assignRole($this->selectedRoles);
                }
            }

            DB::commit();

            $this->dispatch('userSaved');
            session()->flash('message', 'Usuario creado correctamente.');

            return $this->redirectRoute('users.index', navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $unidades = UnidadOrganizacional::orderBy('nombre_unidad')->get();
        $roles = Role::orderBy('name')->get();
        $supervisors = User::where('activo', true)
                          ->whereHas('roles', function($q) {
                              $q->whereIn('name', ['Admin_General', 'Admin_Secretaria', 'Supervisor']);
                          })
                          ->orderBy('name')
                          ->get();

        return view('livewire.users.user-create', compact('unidades', 'roles', 'supervisors'));
    }
}
