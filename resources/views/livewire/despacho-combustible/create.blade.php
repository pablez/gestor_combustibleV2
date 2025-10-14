<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Despacho de Combustible') }}
            </h2>
            <a href="{{ route('despachos.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                <form wire:submit="save">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Solicitud -->
                            <div class="md:col-span-2">
                                <label for="id_solicitud" class="block text-sm font-medium text-gray-700 mb-2">
                                    Solicitud a Despachar *
                                </label>
                                <select wire:model="id_solicitud" 
                                        id="id_solicitud"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una solicitud...</option>
                                    @foreach($solicitudesAprobadas as $solicitud)
                                        <option value="{{ $solicitud->id }}">
                                            {{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y') : 'N/A' }} - 
                                            {{ $solicitud->unidadTransporte?->placa ?? 'N/A' }} - 
                                            {{ $solicitud->cantidad_litros_solicitados }}L - 
                                            {{ $solicitud->solicitante?->name ?? 'N/A' }}
                                            @if($solicitud->motivo)
                                                ({{ $solicitud->motivo }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_solicitud') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                                
                                @if(count($solicitudesAprobadas) == 0)
                                    <p class="text-sm text-gray-500 mt-2">
                                        No hay solicitudes aprobadas pendientes de despacho.
                                    </p>
                                @endif
                            </div>

                            <!-- Proveedor -->
                            <div>
                                <label for="id_proveedor" class="block text-sm font-medium text-gray-700 mb-2">
                                    Proveedor *
                                </label>
                                <select wire:model="id_proveedor" 
                                        id="id_proveedor"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione un proveedor...</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}">
                                            {{ $proveedor->nombre_comercial ?: $proveedor->nombre_proveedor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_proveedor') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Fecha de Despacho -->
                            <div>
                                <label for="fecha_despacho" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Despacho *
                                </label>
                                <input type="date" 
                                       wire:model="fecha_despacho" 
                                       id="fecha_despacho"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('fecha_despacho') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Litros Despachados -->
                            <div>
                                <label for="litros_despachados" class="block text-sm font-medium text-gray-700 mb-2">
                                    Litros Despachados *
                                </label>
                                <input type="number" 
                                       wire:model.live="litros_despachados" 
                                       id="litros_despachados"
                                       step="0.1"
                                       min="0.1"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0.0">
                                @error('litros_despachados') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Precio por Litro -->
                            <div>
                                <label for="precio_por_litro" class="block text-sm font-medium text-gray-700 mb-2">
                                    Precio por Litro ($) *
                                </label>
                                <input type="number" 
                                       wire:model.live="precio_por_litro" 
                                       id="precio_por_litro"
                                       step="1"
                                       min="1"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0">
                                @error('precio_por_litro') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Costo Total (calculado automáticamente) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Costo Total
                                </label>
                                <div class="flex items-center space-x-2">
                                    <div class="w-full p-3 bg-gray-50 border border-gray-300 rounded-md">
                                        <span class="text-2xl font-bold text-green-600">
                                            ${{ number_format($costo_total, 0) }} CLP
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Cálculo automático: {{ $litros_despachados ?: '0' }} L × ${{ $precio_por_litro ?: '0' }}
                                </p>
                            </div>

                            <!-- Número de Vale -->
                            <div>
                                <label for="numero_vale" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Vale
                                </label>
                                <input type="text" 
                                       wire:model="numero_vale" 
                                       id="numero_vale"
                                       maxlength="100"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Ej: V-001234">
                                @error('numero_vale') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Número de Factura -->
                            <div>
                                <label for="numero_factura" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Factura
                                </label>
                                <input type="text" 
                                       wire:model="numero_factura" 
                                       id="numero_factura"
                                       maxlength="100"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Ej: F-001234">
                                @error('numero_factura') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Ubicación de Despacho -->
                            <div class="md:col-span-2">
                                <label for="ubicacion_despacho" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ubicación de Despacho
                                </label>
                                <input type="text" 
                                       wire:model="ubicacion_despacho" 
                                       id="ubicacion_despacho"
                                       maxlength="255"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Ej: Estación de Servicio Shell, Av. Principal 123">
                                @error('ubicacion_despacho') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Observaciones -->
                            <div class="md:col-span-2">
                                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observaciones
                                </label>
                                <textarea wire:model="observaciones" 
                                          id="observaciones"
                                          rows="3"
                                          maxlength="1000"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Observaciones adicionales sobre el despacho..."></textarea>
                                @error('observaciones') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('despachos.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Crear Despacho
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
