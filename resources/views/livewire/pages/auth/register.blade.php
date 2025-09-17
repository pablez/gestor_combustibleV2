<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state([
    'username' => '',
    'name' => '',
    'nombre' => '',
    'apellido_paterno' => '',
    'apellido_materno' => '',
    'ci' => '',
    'telefono' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
    'rol' => 'Conductor',
    'id_unidad_organizacional' => null,
]);

rules([
    'username' => ['required', 'string', 'max:50', 'unique:'.User::class],
    'nombre' => ['required', 'string', 'max:100'],
    'apellido_paterno' => ['required', 'string', 'max:50'],
    'apellido_materno' => ['nullable', 'string', 'max:50'],
    'ci' => ['required', 'string', 'max:15', 'unique:'.User::class],
    'telefono' => ['nullable', 'string', 'max:15'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    'rol' => ['required', 'in:Admin_General,Admin_Secretaria,Supervisor,Conductor'],
    'id_unidad_organizacional' => ['nullable', 'integer', 'exists:unidades_organizacionales,id_unidad_organizacional'],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    // Map validated fields to User model fillable
    $data = [
        'username' => $validated['username'],
        'nombre' => $validated['nombre'],
        'apellido_paterno' => $validated['apellido_paterno'],
        'apellido_materno' => $validated['apellido_materno'] ?? null,
        'ci' => $validated['ci'],
        'telefono' => $validated['telefono'] ?? null,
        'name' => ($validated['nombre'] ?? $validated['username']) . ' ' . ($validated['apellido_paterno'] ?? ''),
        'email' => $validated['email'],
        'password' => $validated['password'],
        // 'rol' field is managed via Spatie role assignment, not stored on users table
        'id_unidad_organizacional' => $validated['id_unidad_organizacional'] ?? null,
        'activo' => true,
    ];

    event(new Registered($user = User::create($data)));

    // Assign Spatie role based on the 'rol' field
    if (!empty($validated['rol'])) {
        $user->assignRole($validated['rol']);
    }

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <form wire:submit="register">
        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text" name="username" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Nombre -->
        <div class="mt-4">
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input wire:model="nombre" id="nombre" class="block mt-1 w-full" type="text" name="nombre" required autocomplete="given-name" />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        <!-- Apellido Paterno -->
        <div class="mt-4">
            <x-input-label for="apellido_paterno" :value="__('Apellido Paterno')" />
            <x-text-input wire:model="apellido_paterno" id="apellido_paterno" class="block mt-1 w-full" type="text" name="apellido_paterno" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('apellido_paterno')" class="mt-2" />
        </div>

        <!-- Apellido Materno -->
        <div class="mt-4">
            <x-input-label for="apellido_materno" :value="__('Apellido Materno')" />
            <x-text-input wire:model="apellido_materno" id="apellido_materno" class="block mt-1 w-full" type="text" name="apellido_materno" autocomplete="family-name" />
            <x-input-error :messages="$errors->get('apellido_materno')" class="mt-2" />
        </div>

        <!-- CI -->
        <div class="mt-4">
            <x-input-label for="ci" :value="__('Cédula de Identidad')" />
            <x-text-input wire:model="ci" id="ci" class="block mt-1 w-full" type="text" name="ci" required />
            <x-input-error :messages="$errors->get('ci')" class="mt-2" />
        </div>

        <!-- Teléfono -->
        <div class="mt-4">
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input wire:model="telefono" id="telefono" class="block mt-1 w-full" type="tel" name="telefono" autocomplete="tel" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Rol -->
        <div class="mt-4">
            <x-input-label for="rol" :value="__('Rol')" />
            <select wire:model="rol" id="rol" name="rol" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="">{{ __('Seleccionar Rol') }}</option>
                <option value="Conductor">{{ __('Conductor') }}</option>
                <option value="Supervisor">{{ __('Supervisor') }}</option>
                <option value="Admin_Secretaria">{{ __('Admin Secretaría') }}</option>
                <option value="Admin_General">{{ __('Admin General') }}</option>
            </select>
            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
        </div>

        <!-- Unidad Organizacional -->
        <div class="mt-4">
            <x-input-label for="id_unidad_organizacional" :value="__('Unidad Organizacional')" />
            <select wire:model="id_unidad_organizacional" id="id_unidad_organizacional" name="id_unidad_organizacional" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Seleccionar Unidad') }}</option>
                @foreach(\DB::table('unidades_organizacionales')->where('activa', true)->orderBy('nombre_unidad')->get() as $unidad)
                    <option value="{{ $unidad->id_unidad_organizacional }}">{{ $unidad->nombre_unidad }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('id_unidad_organizacional')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
