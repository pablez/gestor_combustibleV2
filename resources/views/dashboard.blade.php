<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">Gestor de Combustible — Panel de Control</h1>
                <h3 class="text-sm text-gray-600 dark:text-gray-400 mt-1">Resumen operativo y KPIs en tiempo real — {{ now()->format('d/m/Y') }} @if(auth()->user()->unidad) · {{ auth()->user()->unidad->nombre_unidad }} @endif</h3>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Bienvenido, {{ auth()->user()->full_name }}
                <span class="ml-2 px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-xs">
                    {{ auth()->user()->primary_role ? str_replace('_', ' ', auth()->user()->primary_role) : 'Sin rol' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="w-full">
        {{-- Mensaje de bienvenida contextual --}}
        <div class="mb-8 bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold mb-2">
                    @if(auth()->user()->hasRole('Admin_General'))
                        Panel de Administración General
                    @elseif(auth()->user()->hasRole('Admin_Secretaria'))
                        Panel de Administración - {{ auth()->user()->unidad?->nombre_unidad ?? 'Sin unidad asignada' }}
                    @elseif(auth()->user()->hasRole('Supervisor'))
                        Panel de Supervisión - {{ auth()->user()->unidad?->nombre_unidad ?? 'Sin unidad asignada' }}
                    @elseif(auth()->user()->hasRole('Conductor'))
                        Panel de Conductor
                    @else
                        Bienvenido al Sistema
                    @endif
                </h3>
                <p class="text-indigo-100">
                    @if(auth()->user()->hasRole('Admin_General'))
                        Tienes acceso completo a todo el sistema. Gestiona usuarios, unidades y todas las operaciones.
                    @elseif(auth()->user()->hasRole('Admin_Secretaria'))
                        Gestiona usuarios y operaciones de tu unidad organizacional.
                    @elseif(auth()->user()->hasRole('Supervisor'))
                        Supervisa y gestiona a los conductores bajo tu cargo.
                    @elseif(auth()->user()->hasRole('Conductor'))
                        Accede a tus solicitudes y despachos de combustible.
                    @else
                        Explora las funcionalidades disponibles según tus permisos.
                    @endif
                </p>
            </div>

            {{-- Dashboard Ejecutivo para Administradores --}}
            @if(auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']))
                @livewire('kpis.dashboard-ejecutivo')
                
                {{-- Accesos Rápidos para Administradores --}}
                <div class="mt-8">
                    @livewire('kpis.accesos-rapidos')
                </div>

                {{-- Alertas en Tiempo Real --}}
                @livewire('kpis.alertas-en-tiempo-real')

                {{-- Generador de Códigos de Registro --}}
                @if(auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']))
                    <div class="mt-8">
                        @livewire('kpis.codigo-registro-generator')
                    </div>
                @endif
            @else
                {{-- Dashboard Tradicional para otros roles --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <main class="lg:col-span-2">
                        {{-- KPIs de Usuarios (contenido principal) --}}
                        @livewire('kpis.users-kpis')
                    </main>

                    <aside class="lg:col-span-1">
                        {{-- KPIs de Unidades --}}
                        @livewire('kpis.unidades-kpis')
                    </aside>
                </div>

                {{-- Nueva sección para KPIs de Vehículos --}}
                <div class="mt-8">
                    @livewire('kpis.vehiculos-kpis')
                </div>

                {{-- KPIs de imágenes de vehículos --}}
                <div class="mt-6">
                    @livewire('kpis.imagenes-vehiculos-kpis')
                </div>
            @endif

    </div>
</x-app-layout>