<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                    <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                        {{-- Avatar --}}
                        @if($user->profile_photo_url)
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->full_name }}" 
                                 class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="h-16 w-16 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xl font-semibold">
                                {{ $user->initials }}
                            </div>
                        @endif
                        
                        {{-- User Info --}}
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->full_name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">@ {{ $user->username }}</p>
                            <div class="flex items-center space-x-2 mt-1">
                                @if($user->activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Inactivo
                                    </span>
                                @endif
                                
                                {{-- Primary Role --}}
                                @if($user->primary_role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ str_replace('_', ' ', $user->primary_role) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-2">
                        @can('users.edit')
                            <a href="{{ route('users.edit', $user->id) }}" 
                               wire:navigate
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                        @endcan

                        @if(auth()->user()->hasRole('Admin_General'))
                            <button wire:click="toggleActive" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                @if($user->activo)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12" />
                                    </svg>
                                    Desactivar
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Activar
                                @endif
                            </button>
                        @endif

                        @can('users.delete')
                            @if($user->id !== auth()->id())
                                <button wire:click="confirmDelete" 
                                        class="inline-flex items-center px-3 py-2 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar
                                </button>
                            @endif
                        @endcan

                        <a href="{{ route('users.index') }}" 
                           wire:navigate
                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Main Information --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Personal Information --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Información Personal</h2>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->username }}</dd>
                            </div>
                            {{-- 'Nombre' moved to 'name' (Nombre Completo) — avoid duplicate column reference --}}
                            {{-- If you need a separate given name field, consider adding it explicitly later. --}}
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Apellido Paterno</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->apellido_paterno }}</dd>
                            </div>
                            @if($user->apellido_materno)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Apellido Materno</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->apellido_materno }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cédula de Identidad</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->ci }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <a href="mailto:{{ $user->email }}" class="text-indigo-600 hover:text-indigo-500">{{ $user->email }}</a>
                                </dd>
                            </div>
                            @if($user->telefono)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <a href="tel:{{ $user->telefono }}" class="text-indigo-600 hover:text-indigo-500">{{ $user->telefono }}</a>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Organizational Information --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Información Organizacional</h2>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unidad Organizacional</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($user->unidad)
                                        <div>
                                            {{ $user->unidad->nombre_unidad }}
                                            @if($user->unidad->codigo_unidad)
                                                <span class="text-gray-500">({{ $user->unidad->codigo_unidad }})</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $user->unidad->tipo_unidad }} - Nivel {{ $user->unidad->nivel_jerarquico }}
                                        </div>
                                    @else
                                        <span class="text-gray-500">Sin asignar</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supervisor</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($user->supervisor)
                                        <a href="{{ route('users.show', $user->supervisor->id) }}" 
                                           wire:navigate
                                           class="text-indigo-600 hover:text-indigo-500">
                                            {{ $user->supervisor->full_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Sin supervisor</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Supervised Users --}}
                @if($user->supervisados->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Usuarios Supervisados ({{ $user->supervisados->count() }})
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($user->supervisados as $supervisado)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    @if($supervisado->profile_photo_url)
                                        <img src="{{ $supervisado->profile_photo_url }}" alt="{{ $supervisado->full_name }}" 
                                             class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs font-medium">
                                            {{ $supervisado->initials }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('users.show', $supervisado->id) }}" 
                                           wire:navigate
                                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 truncate">
                                            {{ $supervisado->full_name }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $supervisado->unidad?->codigo_unidad ?? 'Sin unidad' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                
                {{-- Roles --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Roles</h2>
                        @if($user->roles->count() > 0)
                            <div class="space-y-2">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ str_replace('_', ' ', $role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">Sin roles asignados</p>
                        @endif
                    </div>
                </div>

                {{-- Account Information --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Información de Cuenta</h2>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</dt>
                                <dd class="mt-1">
                                    @if($user->activo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ✗ Inactivo
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Registro</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última Modificación</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->updated_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                            @if($user->fecha_ultimo_acceso)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Último Acceso</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->fecha_ultimo_acceso->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
                <div x-data="{ 
        show: false, 
        init() {
            // Listen to browser event dispatched from Livewire
            window.addEventListener('confirm-delete', (e) => {
                // Optional: you can check e.detail.id if needed
                this.show = true;
            });
        }
    }"
         x-show="show"
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Confirmar eliminación</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro de que deseas eliminar al usuario <strong>{{ $user->full_name }}</strong>? Esta acción no se puede deshacer.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.deleteUser(); show = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button @click="show = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
