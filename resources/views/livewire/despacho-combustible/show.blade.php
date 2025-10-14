<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ver Despacho: ') . ($despacho->numero_vale ?: 'ID ' . $despacho->id) }}
            </h2>
            <div class="flex space-x-3">
                @can('despachos.editar')
                <a href="{{ route('despachos.edit', $despacho) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                @endcan
                <a href="{{ route('despachos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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
                                Despacho {{ $despacho->numero_vale ?: '#' . $despacho->id }}
                            </h3>
                            <p class="text-lg text-gray-600">{{ $despacho->fecha_despacho ? $despacho->fecha_despacho->format('d/m/Y') : 'N/A' }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                @if($despacho->validado)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Validado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pendiente Validación
                                    </span>
                                @endif
                                <div class="text-2xl font-bold text-green-600">
                                    ${{ number_format($despacho->costo_total, 0) }} CLP
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            @can('despachos.validar')
                            @if(!$despacho->validado)
                            <button
                                wire:click="validar"
                                wire:confirm="¿Confirmar validación de este despacho?"
                                class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Validar Despacho
                            </button>
                            @else
                            <button
                                wire:click="invalidar"
                                wire:confirm="¿Quitar validación de este despacho?"
                                class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                Invalidar
                            </button>
                            @endif
                            @endcan
                        </div>
                    </div>

                    <!-- Grid principal -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Información del Despacho -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Información del Despacho</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Despacho</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->fecha_despacho->format('l, j \\de F \\de Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Litros Despachados</dt>
                                    <dd class="text-sm text-gray-900 font-semibold">{{ number_format($despacho->litros_despachados, 1) }} L</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Precio por Litro</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($despacho->precio_por_litro, 0) }} CLP</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Costo Total</dt>
                                    <dd class="text-lg font-bold text-green-600">${{ number_format($despacho->costo_total, 0) }} CLP</dd>
                                </div>
                                @if($despacho->numero_vale)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número Vale</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->numero_vale }}</dd>
                                </div>
                                @endif
                                @if($despacho->numero_factura)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número Factura</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->numero_factura }}</dd>
                                </div>
                                @endif
                                @if($despacho->ubicacion_despacho)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->ubicacion_despacho }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Información del Vehículo y Solicitud -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Vehículo y Solicitud</h4>
                            <dl class="space-y-3">
                                @if($despacho->solicitud)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vehículo</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="font-semibold">{{ $despacho->solicitud->unidadTransporte?->placa ?? 'N/A' }}</span>
                                        @if($despacho->solicitud->unidadTransporte)
                                        <br>
                                        <span class="text-xs text-gray-500">
                                            {{ $despacho->solicitud->unidadTransporte->tipoVehiculo?->nombre ?? 'N/A' }} - 
                                            {{ $despacho->solicitud->unidadTransporte->tipoCombustible?->nombre ?? 'N/A' }}
                                        </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha Solicitud</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->solicitud->fecha_solicitud ? $despacho->solicitud->fecha_solicitud->format('d/m/Y') : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Litros Solicitados</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($despacho->solicitud->cantidad_litros_solicitados, 1) }} L</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Solicitante</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->solicitud->usuario?->nombre ?? 'N/A' }}</dd>
                                </div>
                                @if($despacho->solicitud->destino)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Destino</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->solicitud->destino }}</dd>
                                </div>
                                @endif
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Información del Proveedor -->
                    @if($despacho->proveedor)
                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Proveedor</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Razón Social</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->proveedor->nombre_proveedor }}</dd>
                                </div>
                                @if($despacho->proveedor->nombre_comercial)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre Comercial</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->proveedor->nombre_comercial }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">RUT/NIT</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->proveedor->nit }}</dd>
                                </div>
                            </dl>
                            <dl class="space-y-3">
                                @if($despacho->proveedor->telefono)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->proveedor->telefono }}</dd>
                                </div>
                                @endif
                                @if($despacho->proveedor->tipoServicioProveedor)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo de Servicio</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->proveedor->tipoServicioProveedor->nombre }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Calificación</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                            {{ $despacho->proveedor->calificacion === 'A' ? 'bg-green-100 text-green-800' : 
                                               ($despacho->proveedor->calificacion === 'B' ? 'bg-blue-100 text-blue-800' : 
                                                ($despacho->proveedor->calificacion === 'C' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ $despacho->proveedor->calificacion }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif

                    <!-- Personal -->
                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Personal</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Despachador</dt>
                                    <dd class="text-sm text-gray-900">{{ $despacho->despachador?->nombre ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                            <dl class="space-y-3">
                                @if($despacho->validado && $despacho->validador)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Validado por</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $despacho->validador->nombre }}
                                        <br>
                                        <span class="text-xs text-gray-500">
                                            {{ $despacho->fecha_validacion->format('d/m/Y H:i') }}
                                        </span>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($despacho->observaciones)
                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Observaciones</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $despacho->observaciones }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Información del Sistema -->
                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <strong>Creado:</strong> {{ $despacho->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <strong>Última actualización:</strong> {{ $despacho->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                        @can('despachos.eliminar')
                        <button
                            wire:click="delete"
                            wire:confirm="¿Está seguro de eliminar este despacho? Esta acción no se puede deshacer y revertirá el estado de la solicitud."
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            Eliminar Despacho
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
