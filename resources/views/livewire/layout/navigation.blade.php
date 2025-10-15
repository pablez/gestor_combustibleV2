
<div class="flex flex-col h-full">
    <!-- Navigation Links -->
    <nav class="mt-4 px-4 space-y-1 flex-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" wire:navigate
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z"></path>
                </svg>
                Dashboard
            </a>
            
            {{-- Gestión de Vehículos --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.ver') || auth()->user()->hasRole('Admin_General')))
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = ! open" 
                            class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Vehículos
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('tipos-vehiculo.index') }}" wire:navigate
                           class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('tipos-vehiculo*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                            Tipos de Vehículos
                        </a>
                        <a href="{{ route('unidades-transporte.index') }}" wire:navigate
                           class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('unidades-transporte*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                            Unidades de Transporte
                        </a>
                        <a href="{{ route('vehiculos.frontend.imagenes', ['vehiculo' => 26]) }}" wire:navigate
                           class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('vehiculos.frontend.imagenes') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                            Imágenes de Vehículos
                        </a>
                    </div>
                </div>
            @endif

            {{-- Unidades Organizacionales --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.ver') || auth()->user()->hasRole('Admin_General')))
                <a href="{{ route('unidades.index') }}" wire:navigate
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('unidades*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Unidades
                </a>
            @endif
            
            {{-- Solicitudes --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('solicitudes.ver') || auth()->user()->hasRole('Admin_General')))
                <a href="{{ route('solicitudes.index') }}" wire:navigate
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('solicitudes*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Solicitudes
                </a>
            @endif
                    
            {{-- Gestión de Combustible --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo(\App\Constants\Permissions::DESPACHOS_VER) || auth()->user()->hasPermissionTo(\App\Constants\Permissions::CONSUMOS_VER) || auth()->user()->hasRole('Admin_General')))
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = ! open" 
                            class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Combustible
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 space-y-1">
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::DESPACHOS_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('despachos.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('despachos*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Despachos
                            </a>
                        @endif
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::CONSUMOS_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('consumos.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('consumos*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Consumos
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Gestión de Presupuestos --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo(\App\Constants\Permissions::PRESUPUESTOS_VER) || auth()->user()->hasRole('Admin_General')))
                <a href="{{ route('presupuestos.index') }}" wire:navigate
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('presupuestos*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Presupuestos
                </a>
            @endif

            {{-- Administración del Sistema --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo(\App\Constants\Permissions::PRESUPUESTOS_VER) || auth()->user()->can(\App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_VER) || auth()->user()->hasPermissionTo(\App\Constants\Permissions::SOLICITUDES_APROBACION_VER) || auth()->user()->hasPermissionTo(\App\Constants\Permissions::CODIGOS_REGISTRO_VER) || auth()->user()->hasRole('Admin_General')))
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = ! open" 
                            class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Administración
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 space-y-1">
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::PRESUPUESTOS_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('presupuestos.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('presupuestos*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Presupuestos
                            </a>
                        @endif
                        @if(auth()->user()->can(\App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('categorias-programaticas.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('categorias-programaticas*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Categorías Programáticas
                            </a>
                        @endif
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::SOLICITUDES_APROBACION_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('solicitudes-aprobacion.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('solicitudes-aprobacion*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Solicitudes de Aprobación
                            </a>
                        @endif
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::CODIGOS_REGISTRO_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('codigos-registro.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('codigos-registro*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Códigos de Registro
                            </a>
                        @endif
                    </div>
                </div>
            @endif
                    
            {{-- Gestión de Proveedores --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo(\App\Constants\Permissions::PROVEEDORES_VER) || auth()->user()->hasRole('Admin_General')))
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = ! open" 
                            class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m7-10h0m-5-5h1m-1 8h1"></path>
                            </svg>
                            Proveedores
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 space-y-1">
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::TIPOS_SERVICIO_PROVEEDOR_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('tipos-servicio-proveedor.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('tipos-servicio-proveedor*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Tipos de Servicio
                            </a>
                        @endif
                        @if(auth()->user()->hasPermissionTo(\App\Constants\Permissions::PROVEEDORES_VER) || auth()->user()->hasRole('Admin_General'))
                            <a href="{{ route('proveedores.index') }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 {{ request()->routeIs('proveedores*') ? 'bg-gray-100 text-indigo-700 dark:bg-gray-700 dark:text-indigo-300' : '' }}">
                                Proveedores
                            </a>
                        @endif
                    </div>
                </div>
            @endif
                    
            {{-- Reportes --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo(\App\Constants\Permissions::REPORTES_VER) || auth()->user()->hasRole('Admin_General')))
                <a href="{{ route('reportes.index') }}" wire:navigate
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('reportes*') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Reportes
                </a>
            @endif
                    
            {{-- Gestión de Usuarios --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('usuarios.ver') || auth()->user()->hasRole('Admin_General')))
                <a href="{{ route('users.dashboard') }}" wire:navigate
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('users.*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Usuarios
                </a>
            @endif
    </nav>

    <!-- User Profile Section -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
        @php
            $user = auth()->user();
            $avatarUrl = $user->profile_photo_url ?? null;
            $initials = trim(collect(explode(' ', $user->name))->map(fn($p) => $p[0] ?? '')->take(2)->join('')) ?: strtoupper(substr($user->email ?? '', 0, 1));
            $displayName = $user->full_name ?? $user->name;
            $role = $user->primary_role ? str_replace('_', ' ', $user->primary_role) : 'Sin rol';
        @endphp
        <div class="flex items-center mb-3">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover" />
            @else
                <div class="h-10 w-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-medium text-sm">{{ strtoupper($initials) }}</div>
            @endif
            <div class="ml-3 flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $displayName }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $role }}</div>
            </div>
        </div>
        <div class="space-y-1">
            <a href="{{ route('profile') }}" wire:navigate
               class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Perfil
            </a>
            <button wire:click="logout" 
                    class="flex items-center w-full px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Cerrar Sesión
            </button>
        </div>
    </div>
</div>