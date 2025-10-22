<div class="space-y-6">
    {{-- Header con información principal --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 rounded-xl shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl">📋</span>
                        <h1 class="text-2xl font-bold">Solicitud de Combustible</h1>
                    </div>
                    @if($solicitud->urgente)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white animate-pulse">
                            🚨 URGENTE
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-4 text-blue-100">
                    <span class="font-mono text-lg font-semibold">{{ $solicitud->numero_solicitud }}</span>
                    <span class="text-sm">•</span>
                    <span class="text-sm">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                {{-- Estado Badge --}}
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $this->estadoBadgeColor }}">
                    {{ $this->estadoIcon }} {{ str_replace('_', ' ', $solicitud->estado_solicitud) }}
                </span>
                
                {{-- Botón Volver --}}
                <a href="{{ route('solicitudes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-all duration-200 backdrop-blur-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al listado
                </a>
            </div>
        </div>
    </div>

    {{-- Alertas de notificación --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Grid principal de información --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Información Principal --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Datos de la Solicitud --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Datos de la Solicitud
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Solicitante</dt>
                            <dd class="text-sm text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $solicitud->solicitante?->name ?? 'No disponible' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Fecha de Solicitud</dt>
                            <dd class="text-sm text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Cantidad Solicitada</dt>
                            <dd class="text-lg font-bold text-blue-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                {{ number_format($solicitud->cantidad_litros_solicitados, 2) }} L
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Prioridad</dt>
                            <dd class="text-sm">
                                @if($solicitud->urgente)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 ring-1 ring-red-300">
                                        🚨 Urgente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        📝 Normal
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Información del Vehículo --}}
            @if($solicitud->unidadTransporte)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Unidad de Transporte
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Placa</dt>
                            <dd class="text-lg font-bold text-green-600 font-mono">{{ $solicitud->unidadTransporte->placa }}</dd>
                        </div>
                        
                        @if($solicitud->km_actual)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">KM Actual</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ number_format($solicitud->km_actual) }} km</dd>
                        </div>
                        @endif
                        
                        @if($solicitud->km_proyectado)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">KM Proyectado</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ number_format($solicitud->km_proyectado) }} km</dd>
                        </div>
                        @endif
                    </dl>
                    
                    @if($solicitud->rendimiento_estimado)
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <span class="font-medium">Rendimiento estimado:</span> {{ $solicitud->rendimiento_estimado }} km/L
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Motivo y Justificación --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Motivo de la Solicitud
                    </h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed">{{ $solicitud->motivo }}</p>
                    </div>
                    
                    @if($solicitud->urgente && $solicitud->justificacion_urgencia)
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h4 class="text-sm font-medium text-red-800 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Justificación de Urgencia
                        </h4>
                        <p class="text-red-700 text-sm">{{ $solicitud->justificacion_urgencia }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel lateral de estado y acciones --}}
        <div class="space-y-6">
            {{-- Estado y Aprobación --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-indigo-100 border-b border-indigo-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Estado de Aprobación
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($solicitud->aprobador)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Aprobado por</dt>
                            <dd class="text-sm text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $solicitud->aprobador->name }}
                            </dd>
                        </div>
                    @endif
                    
                    @if($solicitud->fecha_aprobacion)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Fecha de Decisión</dt>
                            <dd class="text-sm text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $solicitud->fecha_aprobacion->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    @endif
                    
                    @if($solicitud->observaciones_aprobacion)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Observaciones</dt>
                            <dd class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border">
                                {{ $solicitud->observaciones_aprobacion }}
                            </dd>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Acciones para administradores --}}
            @can('update', $solicitud)
                @if($solicitud->estado_solicitud === 'Pendiente')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-orange-100 border-b border-orange-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            Acciones de Administración
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button wire:click="mostrarModalAprobacion" 
                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Aprobar Solicitud
                        </button>
                        
                        <button wire:click="mostrarModalRechazo" 
                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Rechazar Solicitud
                        </button>
                    </div>
                </div>
                @endif
            @endcan

            {{-- Información Presupuestaria --}}
            @if($solicitud->categoriaProgramatica || $solicitud->fuenteOrganismoFinanciero)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border-b border-yellow-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        Información Presupuestaria
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($solicitud->categoriaProgramatica)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Categoría Programática</dt>
                            <dd class="text-sm text-gray-900">{{ $solicitud->categoriaProgramatica->codigo ?? 'No disponible' }}</dd>
                        </div>
                    @endif
                    
                    @if($solicitud->fuenteOrganismoFinanciero)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Fuente de Financiamiento</dt>
                            <dd class="text-sm text-gray-900">{{ $solicitud->fuenteOrganismoFinanciero->descripcion ?? 'No disponible' }}</dd>
                        </div>
                    @endif
                    
                    @if($solicitud->saldo_actual_combustible)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Saldo Actual</dt>
                            <dd class="text-sm font-semibold text-green-600">{{ number_format($solicitud->saldo_actual_combustible, 2) }} L</dd>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal de Aprobación --}}
    <div x-data="{ show: @entangle('showApprovalModal') }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="cerrarModales"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Aprobar Solicitud
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500 mb-4">
                                    ¿Está seguro que desea aprobar esta solicitud de combustible?
                                </p>
                                
                                <div>
                                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                        Observaciones (opcionales)
                                    </label>
                                    <textarea wire:model="observaciones" 
                                              id="observaciones"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                              rows="3"
                                              placeholder="Ingrese observaciones adicionales..."></textarea>
                                    @error('observaciones') 
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="aprobar" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Aprobar
                    </button>
                    <button wire:click="cerrarModales" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Rechazo --}}
    <div x-data="{ show: @entangle('showRejectionModal') }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="cerrarModales"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Rechazar Solicitud
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500 mb-4">
                                    ¿Está seguro que desea rechazar esta solicitud de combustible?
                                </p>
                                
                                <div>
                                    <label for="observaciones_rechazo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Motivo del rechazo <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model="observaciones" 
                                              id="observaciones_rechazo"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                              rows="3"
                                              placeholder="Explique el motivo del rechazo..."></textarea>
                                    @error('observaciones') 
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="rechazar" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Rechazar
                    </button>
                    <button wire:click="cerrarModales" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
