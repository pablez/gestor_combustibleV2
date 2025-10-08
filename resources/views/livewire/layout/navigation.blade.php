<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    {{-- KPIs removed per request --}}
                    
                    {{-- Gestión de Vehículos --}}
                    @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.ver') || auth()->user()->hasRole('Admin_General')))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                <span>Vehículos</span>
                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('tipos-vehiculo.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" wire:navigate>Tipos de Vehículos</a>
                                    <a href="{{ route('unidades-transporte.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" wire:navigate>Unidades de Transporte</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Unidades Organizacionales --}}
                    @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.ver') || auth()->user()->hasRole('Admin_General')))
                        <x-nav-link :href="route('unidades.index')" :active="request()->routeIs('unidades*')" wire:navigate>
                            Unidades
                        </x-nav-link>
                    @endif
                    
                    {{-- Solicitudes --}}
                    @if(auth()->user() && (auth()->user()->hasPermissionTo('solicitudes.ver') || auth()->user()->hasRole('Admin_General')))
                        <x-nav-link :href="route('solicitudes.index')" :active="request()->routeIs('solicitudes*')" wire:navigate>
                            Solicitudes
                        </x-nav-link>
                    @endif
                    
                    {{-- Gestión de Usuarios --}}
                    @if(auth()->user() && (auth()->user()->hasPermissionTo('usuarios.ver') || auth()->user()->hasRole('Admin_General')))
                        <x-nav-link :href="route('users.dashboard')" :active="request()->routeIs('users.*')" wire:navigate>
                            Usuarios
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @php
                            $user = auth()->user();
                            $avatarUrl = $user->profile_photo_url ?? null;
                            $initials = trim(collect(explode(' ', $user->name))->map(fn($p) => $p[0] ?? '')->take(2)->join('')) ?: strtoupper(substr($user->email ?? '', 0, 1));
                        @endphp
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-3">
                                {{-- Avatar or initials --}}
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-8 w-8 rounded-full object-cover" />
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-medium text-sm">{{ strtoupper($initials) }}</div>
                                @endif
                                <div class="flex flex-col items-start text-left">
                                    @php
                                        $displayName = $user->full_name ?? $user->name;
                                        $role = $user->primary_role ? str_replace('_', ' ', $user->primary_role) : 'Sin rol';
                                        $unidadNombre = optional($user->unidad)->nombre_unidad ?? 'Sin unidad asignada';
                                    @endphp
                                    <div x-data="{{ json_encode(['name' => $displayName]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name" class="font-medium text-sm text-gray-800 dark:text-gray-100">{{ $displayName }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $role }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $unidadNombre }}</div>
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-900">
        <div class="pt-4 pb-3 px-4">
                <div class="flex items-center gap-3">
                @php
                    $user = auth()->user();
                    $avatarUrl = $user->profile_photo_url ?? null;
                    $initials = trim(collect(explode(' ', $user->name))->map(fn($p) => $p[0] ?? '')->take(2)->join('')) ?: strtoupper(substr($user->email ?? '', 0, 1));
                @endphp
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover" />
                @else
                    <div class="h-10 w-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-medium">{{ strtoupper($initials) }}</div>
                @endif
                <div class="flex-1">
                    @php
                        $displayName = $user->full_name ?? $user->name;
                        $role = $user->primary_role ? str_replace('_', ' ', $user->primary_role) : 'Sin rol';
                        $unidadNombre = optional($user->unidad)->nombre_unidad ?? 'Sin unidad asignada';
                    @endphp
                    <div class="font-medium text-base text-gray-800 dark:text-gray-100">{{ $displayName }}</div>
                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $role }}</div>
                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $unidadNombre }}</div>
                </div>
            </div>
        </div>

        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- KPIs (mobile) removed per request --}}

            {{-- Gestión de Vehículos Mobile --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.ver') || auth()->user()->hasRole('Admin_General')))
                <div class="px-4 py-2 mt-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vehículos</div>
                </div>
                <x-responsive-nav-link :href="route('tipos-vehiculo.index')" :active="request()->routeIs('tipos-vehiculo*')" wire:navigate>
                    Tipos de Vehículos
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('unidades-transporte.index')" :active="request()->routeIs('unidades-transporte*')" wire:navigate>
                    Unidades de Transporte
                </x-responsive-nav-link>
            @endif

            {{-- Unidades Organizacionales Mobile --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.ver') || auth()->user()->hasRole('Admin_General')))
                <x-responsive-nav-link :href="route('unidades.index')" :active="request()->routeIs('unidades*')" wire:navigate>
                    Unidades Organizacionales
                </x-responsive-nav-link>
            @endif

            {{-- Solicitudes Mobile --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('solicitudes.ver') || auth()->user()->hasRole('Admin_General')))
                <x-responsive-nav-link :href="route('solicitudes.index')" :active="request()->routeIs('solicitudes*')" wire:navigate>
                    Solicitudes
                </x-responsive-nav-link>
            @endif

            {{-- Gestión de Usuarios Mobile --}}
            @if(auth()->user() && (auth()->user()->hasPermissionTo('usuarios.ver') || auth()->user()->hasRole('Admin_General')))
                <x-responsive-nav-link :href="route('users.dashboard')" :active="request()->routeIs('users.*')" wire:navigate>
                    Gestión de Usuarios
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('profile')" wire:navigate>
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <button wire:click="logout" class="w-full text-start block px-4 py-2 text-sm text-red-600">Cerrar sesión</button>
        </div>
</nav>
