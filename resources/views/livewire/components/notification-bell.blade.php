<div class="relative" 
     x-data="{ open: @entangle('mostrarDropdown') }"
     x-init="
         // Polling automático cada 30 segundos
         setInterval(() => {
             if (!open) {
                 $wire.polling();
             }
         }, 30000);
     ">
    <!-- Botón de notificaciones -->
    <button @click="$wire.toggleDropdown()" 
            class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700">
        
        <!-- Icono de campana -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        <!-- Badge de contador de notificaciones -->
        @if($totalNotificaciones > 0)
            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                {{ $totalNotificaciones > 99 ? '99+' : $totalNotificaciones }}
            </span>
        @endif
    </button>

    <!-- Dropdown de notificaciones -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50 dark:bg-gray-800 dark:border-gray-700">
        
        <!-- Header del dropdown -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    📢 Notificaciones
                </h3>
                @if($totalNotificaciones > 0)
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                        {{ $totalNotificaciones }} nueva{{ $totalNotificaciones > 1 ? 's' : '' }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Contenido de notificaciones -->
        <div class="max-h-96 overflow-y-auto">
            @if($totalNotificaciones > 0)
                
                <!-- Solicitudes de Combustible -->
                @if($solicitudesCombustiblePendientes > 0)
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                ⛽ Solicitudes de Combustible
                                <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $solicitudesCombustiblePendientes }}
                                </span>
                            </h4>
                            <button wire:click="irASolicitudesCombustible" 
                                    class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Ver todas
                            </button>
                        </div>
                        <div class="space-y-2">
                            @foreach($solicitudesCombustible as $solicitud)
                                <div wire:click="verSolicitudCombustible({{ $solicitud->id }})" 
                                     class="p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors dark:hover:bg-gray-700">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center dark:bg-blue-900">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $solicitud->numero_solicitud }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $solicitud->solicitante->name }} - {{ $solicitud->cantidad_litros_solicitados }}L
                                            </p>
                                            @if($solicitud->urgente)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    🚨 URGENTE
                                                </span>
                                            @endif
                                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ $solicitud->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Solicitudes de Aprobación de Usuario -->
                @if($solicitudesAprobacionPendientes > 0)
                    <div class="px-4 py-3">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                👤 Aprobaciones de Usuario
                                <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {{ $solicitudesAprobacionPendientes }}
                                </span>
                            </h4>
                            <button wire:click="irASolicitudesAprobacion" 
                                    class="text-xs text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                Ver todas
                            </button>
                        </div>
                        <div class="space-y-2">
                            @foreach($solicitudesAprobacion as $solicitud)
                                <div wire:click="verSolicitudAprobacion({{ $solicitud->id }})" 
                                     class="p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors dark:hover:bg-gray-700">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center dark:bg-green-900">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $solicitud->usuario->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ ucfirst(str_replace('_', ' ', $solicitud->tipo_solicitud)) }} - {{ $solicitud->rol_solicitado }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ $solicitud->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @else
                <!-- Sin notificaciones -->
                <div class="px-4 py-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center dark:bg-gray-700">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        🎉 ¡Todo al día!
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        No hay notificaciones pendientes
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer del dropdown -->
        @if($totalNotificaciones > 0)
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 rounded-b-lg dark:bg-gray-700 dark:border-gray-600">
                <button wire:click="actualizarNotificaciones" 
                        class="w-full text-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Actualizar</span>
                </button>
            </div>
        @endif
    </div>
</div>