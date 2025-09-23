<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\UnidadOrganizacional;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserEdit extends Component
{
    use WithFileUploads;

    public User $user;

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
    public bool $remove_photo = false;
    
    // Role assignment
    public array $selectedRoles = [];

    public function mount($id)
    {
        $this->user = User::with(['unidad', 'roles'])->findOrFail($id);
        
        // Populate fields
        $this->username = $this->user->username;
        $this->name = $this->user->name;
        $this->apellido_paterno = $this->user->apellido_paterno;
        $this->apellido_materno = $this->user->apellido_materno ?? '';
        $this->ci = $this->user->ci;
        $this->telefono = $this->user->telefono ?? '';
        $this->email = $this->user->email;
        $this->id_unidad_organizacional = $this->user->id_unidad_organizacional;
        $this->id_supervisor = $this->user->id_supervisor;
        $this->activo = $this->user->activo;
        
        // Load current roles
        $this->selectedRoles = $this->user->roles->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($this->user->id)],
            'name' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:50'],
            'apellido_materno' => ['nullable', 'string', 'max:50'],
            'ci' => ['required', 'string', 'max:15', Rule::unique('users', 'ci')->ignore($this->user->id)],
            'telefono' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password'],
            'id_unidad_organizacional' => ['nullable', 'exists:unidades_organizacionales,id_unidad_organizacional'],
            'id_supervisor' => ['nullable', 'exists:users,id', Rule::notIn([$this->user->id])],
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
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'id_supervisor.not_in' => 'Un usuario no puede ser supervisor de sí mismo.',
        'profile_photo.image' => 'Debe ser una imagen válida.',
        'profile_photo.max' => 'La imagen no puede ser mayor a 1MB.',
    ];

    public function updatedProfilePhoto()
    {
        $this->validateOnly('profile_photo');
        $this->remove_photo = false; // Reset remove flag if new photo uploaded
    }

    public function removePhoto()
    {
        $this->remove_photo = true;
        $this->profile_photo = null;
    }

    public function update()
    {
        $this->validate();

        try {
            // Prepare update data
            $userData = [
                'username' => $this->username,
                'name' => $this->name,
                'apellido_paterno' => $this->apellido_paterno,
                'apellido_materno' => $this->apellido_materno,
                'ci' => $this->ci,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'id_unidad_organizacional' => $this->id_unidad_organizacional,
                'id_supervisor' => $this->id_supervisor,
                'activo' => $this->activo,
            ];

            // Update password if provided
            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            // Handle photo operations
            if ($this->remove_photo && $this->user->profile_photo_path) {
                // Delete old photo
                Storage::disk('public')->delete($this->user->profile_photo_path);
                $userData['profile_photo_path'] = null;
            } elseif ($this->profile_photo) {
                // Delete old photo if exists
                if ($this->user->profile_photo_path) {
                    Storage::disk('public')->delete($this->user->profile_photo_path);
                }
                // Store new photo
                $photoPath = $this->profile_photo->store('profile-photos', 'public');
                $userData['profile_photo_path'] = $photoPath;
            }

            $this->user->update($userData);

            // Sync roles
            $this->user->syncRoles($this->selectedRoles);

            $this->dispatch('userSaved');
            session()->flash('message', 'Usuario actualizado correctamente.');

            return $this->redirectRoute('users.index', navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $unidades = UnidadOrganizacional::orderBy('nombre_unidad')->get();
        $roles = Role::orderBy('name')->get();
        $supervisors = User::where('activo', true)
                          ->where('id', '!=', $this->user->id) // Exclude current user
                          ->whereHas('roles', function($q) {
                              $q->whereIn('name', ['Admin_General', 'Admin_Secretaria', 'Supervisor']);
                          })
                          ->orderBy('name')
                          ->get();

        return view('livewire.users.user-edit', compact('unidades', 'roles', 'supervisors'));
    }
}
