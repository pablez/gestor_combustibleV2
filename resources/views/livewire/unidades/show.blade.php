<div class="max-w-7xl mx-auto px-4 py-6">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalle de Unidad Organizacional
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <!-- Header con gradiente -->
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-white">{{ $unidad->nombre_unidad }}</h1>
                    <p class="text-indigo-100 text-sm mt-1">Código: <span class="font-medium">{{ $unidad->codigo_unidad }}</span></p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-sm">
                        ID {{ $unidad->id_unidad_organizacional }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información básica -->
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Información General
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Nombre</label>
                                <p class="text-base font-medium text-gray-800 dark:text-gray-100">{{ $unidad->nombre_unidad }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Código</label>
                                <p class="text-base font-medium text-gray-800 dark:text-gray-100">{{ $unidad->codigo_unidad }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Tipo</label>
                                <p class="text-base text-gray-700 dark:text-gray-200">{{ $unidad->tipo_unidad }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Información de Contacto
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Responsable</label>
                                <p class="text-base text-gray-700 dark:text-gray-200">{{ $unidad->responsable_unidad ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Teléfono</label>
                                <p class="text-base text-gray-700 dark:text-gray-200">{{ $unidad->telefono ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="space-y-4">
                    <!-- Dirección -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ubicación
                        </h3>
                        <div>
                            <label class="text-sm text-gray-500 dark:text-gray-300">Dirección</label>
                            <p class="text-base text-gray-700 dark:text-gray-200">{{ $unidad->direccion ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Jerarquía y presupuesto -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Información Administrativa
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Nivel Jerárquico</label>
                                <p class="text-base text-gray-700 dark:text-gray-200">{{ $unidad->nivel_jerarquico ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 dark:text-gray-300">Presupuesto Asignado</label>
                                <p class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($unidad->presupuesto_asignado ?? 0, 2, ',', '.') }} Bs.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    @if(isset($unidad->created_at))
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información del Sistema
                        </h3>
                        <div class="space-y-2">
                            <div class="text-sm text-gray-500 dark:text-gray-300">
                                Creada: <span class="font-medium">{{ \Carbon\Carbon::parse($unidad->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            @if(isset($unidad->updated_at))
                            <div class="text-sm text-gray-500 dark:text-gray-300">
                                Actualizada: <span class="font-medium">{{ \Carbon\Carbon::parse($unidad->updated_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Acciones -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <a href="{{ route('unidades.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al listado
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    @if(auth()->user() && auth()->user()->hasRole('Admin_General'))
                    <button type="button" wire:click="openEdit({{ $unidad->id_unidad_organizacional }})" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white px-4 py-2 rounded-lg shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Unidad
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Edit incluido como componente -->
        @livewire('unidades.edit')
    </div>
</div>
