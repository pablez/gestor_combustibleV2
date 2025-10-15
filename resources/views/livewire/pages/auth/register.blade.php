<?php

use App\Models\User;
use App\Models\CodigoRegistro;
use App\Models\SolicitudAprobacionUsuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;
use function Livewire\Volt\mount;

layout('layouts.guest');

state([
    'codigo_registro' => '',
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
    'rol' => '',
    'id_unidad_organizacional' => null,
    'id_supervisor' => null,
    // Campos calculados del código
    'codigo_data' => null,
    'codigo_valido' => false,
]);

rules([
    'codigo_registro' => ['required', 'string'],
    'username' => ['required', 'string', 'max:50', 'unique:'.User::class],
    'nombre' => ['required', 'string', 'max:100'],
    'apellido_paterno' => ['required', 'string', 'max:50'],
    'apellido_materno' => ['nullable', 'string', 'max:50'],
    'ci' => ['required', 'string', 'max:15', 'unique:'.User::class],
    'telefono' => ['nullable', 'string', 'max:15'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

mount(function () {
    // Obtener código desde URL si existe
    $this->codigo_registro = request()->get('codigo', '');
    if ($this->codigo_registro) {
        $this->validarCodigo();
    }
});

$validarCodigo = function () {
    if (empty($this->codigo_registro)) {
        $this->codigo_valido = false;
        $this->codigo_data = null;
        $this->reset(['rol', 'id_unidad_organizacional', 'id_supervisor']);
        return;
    }

    try {
        $codigo = CodigoRegistro::validarParaRegistro($this->codigo_registro);
        $this->codigo_data = $codigo;
        $this->codigo_valido = true;

        // Aplicar datos de personalización del código SIEMPRE
        $datos = $codigo->getDatosPersonalizacion();
        
        // Los códigos DEBEN tener personalización para ser válidos
        if (!$codigo->tienePersonalizacion()) {
            throw new \Exception('Este código no tiene configuración válida. Contacte al administrador.');
        }
        
        $this->rol = $datos['rol'];
        $this->id_unidad_organizacional = $datos['id_unidad_organizacional'];
        $this->id_supervisor = $datos['id_supervisor'];

        session()->flash('success', 'Código válido. Configuración automática aplicada.');

    } catch (\Exception $e) {
        $this->codigo_valido = false;
        $this->codigo_data = null;
        $this->reset(['rol', 'id_unidad_organizacional', 'id_supervisor']);
        session()->flash('error', $e->getMessage());
    }
};

$register = function () {
    // Validar código primero
    if (!$this->codigo_valido || !$this->codigo_data) {
        session()->flash('error', 'Debe proporcionar un código de registro válido.');
        return;
    }

    $validated = $this->validate();

    DB::beginTransaction();
    try {
        $validated['password'] = Hash::make($validated['password']);

        // Map validated fields to User model fillable
        $data = [
            'username' => $validated['username'],
            'apellido_paterno' => $validated['apellido_paterno'],
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'ci' => $validated['ci'],
            'telefono' => $validated['telefono'] ?? null,
            'name' => ($validated['nombre'] ?? $validated['username']) . ' ' . ($validated['apellido_paterno'] ?? ''),
            'email' => $validated['email'],
            'password' => $validated['password'],
            'id_unidad_organizacional' => $this->id_unidad_organizacional,
            'id_supervisor' => $this->id_supervisor,
            'activo' => false, // Usuario inactivo hasta aprobación
        ];

        event(new Registered($user = User::create($data)));

        // Assign Spatie role
        if (!empty($this->rol)) {
            $user->assignRole($this->rol);
        }

        // Marcar código como usado
        $this->codigo_data->usar($user->id);

        // Crear solicitud de aprobación automáticamente
        SolicitudAprobacionUsuario::create([
            'id_usuario' => $user->id,
            'id_creador' => $this->codigo_data->id_usuario_generador,
            'id_supervisor_asignado' => $this->id_supervisor,
            'tipo_solicitud' => 'nuevo_usuario',
            'estado_solicitud' => 'pendiente',
            'rol_solicitado' => $this->rol,
            'justificacion' => 'Registro automático mediante código personalizado: ' . $this->codigo_registro,
        ]);

        DB::commit();

        // NO hacer login automático - el usuario debe esperar aprobación
        
        session()->flash('success', 'Registro completado exitosamente. Su solicitud está pendiente de aprobación por parte del administrador. Recibirá un correo cuando sea aprobada.');
        $this->redirect(route('login', absolute: false), navigate: true);

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Error en el registro: ' . $e->getMessage());
    }
};

?>

<div class="w-full max-w-4xl mx-auto">
    <!-- Header Institucional Compacto -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-900 -mx-6 -mt-4 mb-6 px-6 py-3 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-white p-1.5 rounded-lg shadow-md">
                    <svg class="w-5 h-5 text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white">Gobernación de Cochabamba</h1>
                    <p class="text-blue-100 text-xs">Sistema de Gestión de Combustible</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center space-x-2 bg-green-600 px-2 py-1 rounded-lg">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="text-white text-xs font-medium">Registro</span>
            </div>
        </div>
    </div>

    <!-- Formulario Principal -->
    <form wire:submit="register" class="space-y-4">
        <!-- Código de Registro -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-3">
            <div class="flex items-center space-x-2 mb-2">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                </svg>
                <h3 class="text-sm font-semibold text-amber-800">Código de Acceso</h3>
                @if($codigo_valido && $codigo_data)
                    <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">✓ Válido</span>
                @else
                    <span class="ml-auto bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full">Pendiente</span>
                @endif
            </div>
            
            <x-input-label for="codigo_registro" :value="__('Código de Registro *')" class="text-amber-700 font-medium text-xs mb-1" />
            <div class="flex space-x-2">
                <x-text-input wire:model="codigo_registro" 
                              id="codigo_registro" 
                              class="flex-1 border-amber-300 focus:border-amber-500 focus:ring-amber-500 font-mono text-sm" 
                              type="text" 
                              name="codigo_registro" 
                              required 
                              autofocus 
                              placeholder="Ingrese su código" />
                <button type="button" 
                        wire:click="validarCodigo" 
                        class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg whitespace-nowrap text-xs">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                    </svg>
                    Validar
                </button>
            </div>
            <x-input-error :messages="$errors->get('codigo_registro')" class="mt-1" />
        </div>

        <!-- Datos Personales -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center space-x-2 mb-3">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h3 class="text-sm font-semibold text-blue-800">Información Personal</h3>
            </div>

            <!-- Fila 1: Identificación -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 mb-3">
                <div>
                    <x-input-label for="username" :value="__('Usuario *')" class="text-blue-700 font-medium text-xs mb-1" />
                    <x-text-input wire:model="username" 
                                  id="username" 
                                  class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                  type="text" 
                                  name="username" 
                                  required 
                                  placeholder="juan.perez" />
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="ci" :value="__('CI *')" class="text-blue-700 font-medium text-xs mb-1" />
                    <x-text-input wire:model="ci" 
                                  id="ci" 
                                  class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                  type="text" 
                                  name="ci" 
                                  required
                                  placeholder="12345678" />
                    <x-input-error :messages="$errors->get('ci')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="telefono" :value="__('Teléfono')" class="text-blue-700 font-medium text-xs mb-1" />
                    <x-text-input wire:model="telefono" 
                                  id="telefono" 
                                  class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                  type="tel" 
                                  name="telefono" 
                                  placeholder="70123456" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
                </div>
            </div>

            <!-- Fila 2: Nombres -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 mb-3">
                <div>
                    <x-input-label for="nombre" :value="__('Nombres *')" class="text-blue-700 font-medium text-xs mb-1" />
                    <x-text-input wire:model="nombre" 
                                  id="nombre" 
                                  class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                  type="text" 
                                  name="nombre" 
                                  required 
                                  placeholder="Juan Carlos" />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="apellido_paterno" :value="__('Ap. Paterno *')" class="text-blue-700 font-medium text-xs mb-1" />
                    <x-text-input wire:model="apellido_paterno" 
                                  id="apellido_paterno" 
                                  class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                  type="text" 
                                  name="apellido_paterno" 
                                  required 
                                  placeholder="Pérez" />
                    <x-input-error :messages="$errors->get('apellido_paterno')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="apellido_materno" :value="__('Ap. Materno')" class="text-blue-700 font-medium text-xs mb-1" />
                    <x-text-input wire:model="apellido_materno" 
                                  id="apellido_materno" 
                                  class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                                  type="text" 
                                  name="apellido_materno" 
                                  placeholder="López" />
                    <x-input-error :messages="$errors->get('apellido_materno')" class="mt-1" />
                </div>
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico *')" class="text-blue-700 font-medium text-xs mb-1" />
                <x-text-input wire:model="email" 
                              id="email" 
                              class="w-full border-blue-300 focus:border-blue-500 focus:ring-blue-500 text-sm" 
                              type="email" 
                              name="email" 
                              required 
                              placeholder="juan.perez@gobernacion.bo" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
        </div>

        <!-- Configuración y Seguridad -->
        <div class="grid grid-cols-1 @if($codigo_valido && $codigo_data) lg:grid-cols-2 @endif gap-3">
            <!-- Configuración Organizacional -->
            @if($codigo_valido && $codigo_data)
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h3 class="text-sm font-semibold text-green-800">Configuración Organizacional</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="bg-white p-2 rounded-lg border border-green-200">
                            <p class="text-xs text-green-600 font-medium mb-0.5">ROL ASIGNADO</p>
                            <p class="text-xs font-bold text-green-900">{{ $codigo_data->rol_asignado }}</p>
                        </div>

                        <div class="bg-white p-2 rounded-lg border border-green-200">
                            <p class="text-xs text-blue-600 font-medium mb-0.5">UNIDAD ORGANIZACIONAL</p>
                            <p class="text-xs font-bold text-blue-900">{{ $codigo_data->unidadAsignada?->nombre_unidad ?? 'No asignada' }}</p>
                        </div>

                        <div class="bg-white p-2 rounded-lg border border-green-200">
                            <p class="text-xs text-purple-600 font-medium mb-0.5">SUPERVISOR ASIGNADO</p>
                            <p class="text-xs font-bold text-purple-900">{{ $codigo_data->supervisorAsignado?->name ?? 'No asignado' }}</p>
                        </div>

                        @if($codigo_data->observaciones)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2">
                                <p class="text-xs text-yellow-700 font-medium mb-0.5">OBSERVACIONES</p>
                                <p class="text-xs text-yellow-800">{{ $codigo_data->observaciones }}</p>
                            </div>
                        @endif

                        <div class="bg-green-100 rounded-lg p-1.5 text-center">
                            <p class="text-xs text-green-800 font-medium">✅ Configuración Verificada</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Configuración de Seguridad -->
            <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-lg p-3">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-red-800">Seguridad</h3>
                </div>

                <div class="space-y-2">
                    <div>
                        <x-input-label for="password" :value="__('Contraseña *')" class="text-red-700 font-medium text-xs mb-1" />
                        <x-text-input wire:model="password" 
                                      id="password" 
                                      class="w-full border-red-300 focus:border-red-500 focus:ring-red-500 text-sm"
                                      type="password"
                                      name="password"
                                      required 
                                      placeholder="Mínimo 8 caracteres" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar *')" class="text-red-700 font-medium text-xs mb-1" />
                        <x-text-input wire:model="password_confirmation" 
                                      id="password_confirmation" 
                                      class="w-full border-red-300 focus:border-red-500 focus:ring-red-500 text-sm"
                                      type="password"
                                      name="password_confirmation" 
                                      required 
                                      placeholder="Repita la contraseña" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Política Compacta -->
                    <div class="bg-white border border-red-200 rounded-lg p-2">
                        <p class="text-xs font-semibold text-red-800 mb-1">Política de Seguridad</p>
                        <div class="grid grid-cols-2 gap-1 text-xs text-red-700">
                            <span>• Mín. 8 caracteres</span>
                            <span>• Mayús/minús</span>
                            <span>• Números</span>
                            <span>• Símbolos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex flex-col sm:flex-row items-center justify-between pt-3 space-y-2 sm:space-y-0">
            <a class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900 transition-colors" 
               href="{{ route('login') }}" 
               wire:navigate>
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                ¿Ya tiene cuenta? Ingresar
            </a>

            <x-primary-button class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 text-sm" 
                              :disabled="!$codigo_valido">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                {{ __('Completar Registro') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Footer Institucional -->
    <div class="mt-4 text-center text-xs text-gray-500">
        <div class="flex items-center justify-center space-x-3 mb-1">
            <span class="flex items-center space-x-1">
                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                </svg>
                <span>Seguro</span>
            </span>
            <span class="flex items-center space-x-1">
                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Protegido</span>
            </span>
        </div>
        <p>© 2025 Gobernación de Cochabamba - Sistema de Gestión de Combustible</p>
    </div>
</div>