<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Mobile Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                 class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out md:hidden flex flex-col">
                
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-indigo-600 dark:bg-indigo-700 flex-shrink-0">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                        <x-application-logo class="h-8 w-auto fill-current text-white" />
                        <span class="ml-2 text-xl font-semibold text-white">Sistema</span>
                    </a>
                </div>

                <!-- Navigation Content -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <livewire:layout.navigation />
                </div>
            </div>

            <!-- Desktop Sidebar - Fixed -->
            <div class="hidden md:flex fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-lg flex-col z-40">
                
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-indigo-600 dark:bg-indigo-700 flex-shrink-0">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                        <x-application-logo class="h-8 w-auto fill-current text-white" />
                        <span class="ml-2 text-xl font-semibold text-white">Sistema</span>
                    </a>
                </div>

                <!-- Navigation Content -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <livewire:layout.navigation />
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="md:ml-64">
                <!-- Mobile Header -->
                <header class="bg-white dark:bg-gray-800 shadow-sm md:hidden">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Sistema</h1>
                        <!-- Notificaciones móvil -->
                        <livewire:components.notification-bell />
                    </div>
                </header>

                <!-- Desktop Header -->
                <header class="hidden md:block bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                @if (isset($pageTitle))
                                    {{ $pageTitle }}
                                @else
                                    Sistema de Gestión de Combustible
                                @endif
                            </h1>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notificaciones desktop -->
                            <livewire:components.notification-bell />
                            
                            <!-- Información del usuario -->
                            @php
                                $user = auth()->user();
                                $avatarUrl = $user->profile_photo_url ?? null;
                                $initials = trim(collect(explode(' ', $user->name))->map(fn($p) => $p[0] ?? '')->take(2)->join('')) ?: strtoupper(substr($user->email ?? '', 0, 1));
                                $displayName = $user->full_name ?? $user->name;
                                $role = $user->primary_role ? str_replace('_', ' ', $user->primary_role) : 'Sin rol';
                            @endphp
                            
                            <div class="flex items-center space-x-3">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-8 w-8 rounded-full object-cover" />
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-medium text-sm">{{ strtoupper($initials) }}</div>
                                @endif
                                <div class="hidden lg:block">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $displayName }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $role }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white dark:bg-gray-800 shadow-sm px-6 py-4">
                            {{ $header }}
                        </header>
                    @endif

                    <!-- Main Content -->
                    <main class="p-6">
                        {{ $slot }}
                    </main>
                </div>
            </div>

            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-black bg-opacity-25 md:hidden">
            </div>
        </div>
    </body>
</html>
