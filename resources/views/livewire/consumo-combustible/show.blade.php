<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Consumo') }}
            </h2>
            <div class="flex space-x-2">
                @can('consumos.editar')
                <a href="{{ route('consumos.edit', $consumo) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                @endcan

                <a href="{{ route('consumos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alertas -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Header con estado -->
                    <div class="flex justify-between items-start mb-6 pb-4 border-b">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Consumo {{ $consumo->numero_ticket ?: '#' . $consumo->id }}
                            </h3>
                            <p class="text-lg text-gray-600">{{ $consumo->fecha_registro ? $consumo->fecha_registro->format('d/m/Y') : 'N/A' }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                @if($consumo->validado)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Validado
                                    </span>
                                    @if($consumo->fecha_validacion)
                                        <span class="text-sm text-gray-500">
                                            el {{ $consumo->fecha_validacion->format('d/m/Y H:i') }}
                                            @if($consumo->validador)
                                                por {{ $consumo->validador->name }}
                                            @endif
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pendiente de Validación
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones de validación -->
                        <div class="flex space-x-2">
                            @if(!$consumo->validado)
                                @can('consumos.validar')
                                <button wire:click="validar" 
                                        onclick="return confirm('¿Validar este consumo de combustible?')"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Validar
                                </button>
                                @endcan
                            @else
                                @can('consumos.validar')
                                <button wire:click="invalidar" 
                                        onclick="return confirm('¿Invalidar este consumo de combustible?')"
                                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Invalidar
                                </button>
                                @endcan
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Información del Vehículo y Conductor -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Información del Vehículo</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Unidad de Transporte</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="font-semibold">{{ $consumo->unidadTransporte?->placa ?? 'N/A' }}</span>
                                        @if($consumo->unidadTransporte)
                                        <br>
                                        <span class="text-xs text-gray-500">
                                            {{ $consumo->unidadTransporte->marca }} {{ $consumo->unidadTransporte->modelo }} 
                                            ({{ $consumo->unidadTransporte->anio_fabricacion }})
                                        </span>
                                        <br>
                                        <span class="text-xs text-gray-500">
                                            {{ $consumo->unidadTransporte->tipoVehiculo?->nombre ?? 'N/A' }} - 
                                            {{ $consumo->unidadTransporte->tipoCombustible?->nombre ?? 'N/A' }}
                                        </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Conductor</dt>
                                    <dd class="text-sm text-gray-900">{{ $consumo->conductor?->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                                    <dd class="text-sm text-gray-900">{{ $consumo->fecha_registro->format('d/m/Y H:i') }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Consumo -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Datos del Consumo</h4>
                            <div class="bg-blue-50 rounded-lg p-4 space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Litros Cargados</dt>
                                    <dd class="text-lg font-bold text-blue-900">{{ number_format($consumo->litros_cargados, 3) }} L</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo de Carga</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($consumo->tipo_carga === 'Completa') bg-green-100 text-green-800
                                            @elseif($consumo->tipo_carga === 'Parcial') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $consumo->tipo_carga }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lugar de Carga</dt>
                                    <dd class="text-sm text-gray-900">{{ $consumo->lugar_carga }}</dd>
                                </div>
                                @if($consumo->numero_ticket)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número de Ticket</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $consumo->numero_ticket }}</dd>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información de Kilometraje -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Kilometraje</h4>
                            <div class="bg-green-50 rounded-lg p-4 space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Km Inicial</dt>
                                        <dd class="text-sm font-bold text-gray-900">{{ number_format($consumo->kilometraje_inicial, 0) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Km Final</dt>
                                        <dd class="text-sm font-bold text-gray-900">{{ number_format($consumo->kilometraje_fin, 0) }}</dd>
                                    </div>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kilómetros Recorridos</dt>
                                    <dd class="text-lg font-bold text-green-900">{{ number_format($consumo->kilometros_recorridos, 0) }} km</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Rendimiento</dt>
                                    <dd class="text-lg font-bold text-green-900">{{ $consumo->rendimiento }} km/L</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Despacho Asociado -->
                        @if($consumo->despacho)
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Despacho Asociado</h4>
                            <div class="bg-purple-50 rounded-lg p-4 space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número de Vale</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="{{ route('despachos.show', $consumo->despacho) }}" 
                                           class="text-purple-600 hover:text-purple-900 font-medium">
                                            {{ $consumo->despacho->numero_vale }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Despacho</dt>
                                    <dd class="text-sm text-gray-900">{{ $consumo->despacho->fecha_despacho->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Proveedor</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $consumo->despacho->proveedor?->nombre_comercial ?? $consumo->despacho->proveedor?->nombre_proveedor ?? 'N/A' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Litros Despachados</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($consumo->despacho->litros_despachados, 1) }} L</dd>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Observaciones -->
                    @if($consumo->observaciones)
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Observaciones</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $consumo->observaciones }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Información de auditoría -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-500">
                            <div>
                                <strong>Creado:</strong> {{ $consumo->created_at->format('d/m/Y H:i:s') }}
                            </div>
                            <div>
                                <strong>Última modificación:</strong> {{ $consumo->updated_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
