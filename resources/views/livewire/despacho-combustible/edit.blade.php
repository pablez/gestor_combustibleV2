<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Despacho de Combustible') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('despachos.show', $despacho) }}" 
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
                <form wire:submit="update" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Información actual del despacho -->
                        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Información Actual</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Vale:</span>
                                    <span class="font-medium">{{ $despacho->numero_vale }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Estado:</span>
                                    @if($despacho->validado)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Validado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-gray-600">Fecha Original:</span>
                                    <span class="font-medium">{{ $despacho->fecha_despacho->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Solicitud -->
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <label for="id_solicitud" class="block text-sm font-medium text-gray-700 mb-2">
                                    Solicitud de Combustible *
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
                            </div>
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

                        <!-- Fecha Despacho -->
                        <div>
                            <label for="fecha_despacho" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Despacho *
                            </label>
                            <input type="date" 
                                   wire:model="fecha_despacho" 
                                   id="fecha_despacho"
                                   max="{{ date('Y-m-d') }}"
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
                                   max="9999.99"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="0.0">
                            @error('litros_despachados') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Precio por Litro -->
                        <div>
                            <label for="precio_por_litro" class="block text-sm font-medium text-gray-700 mb-2">
                                Precio por Litro (CLP) *
                            </label>
                            <input type="number" 
                                   wire:model.live="precio_por_litro" 
                                   id="precio_por_litro"
                                   min="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="1250">
                            @error('precio_por_litro') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Costo Total (calculado automáticamente) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Costo Total (CLP)
                            </label>
                            <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 font-medium">
                                ${{ number_format($costo_total ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Número de Vale -->
                        <div>
                            <label for="numero_vale" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Vale *
                            </label>
                            <input type="text" 
                                   wire:model="numero_vale" 
                                   id="numero_vale"
                                   maxlength="100"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="V-001234">
                            @error('numero_vale') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Número de Factura -->
                        <div>
                            <label for="numero_factura" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Factura (Opcional)
                            </label>
                            <input type="text" 
                                   wire:model="numero_factura" 
                                   id="numero_factura"
                                   maxlength="100"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="F-2024-001">
                            @error('numero_factura') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Ubicación de Despacho -->
                        <div class="md:col-span-2">
                            <label for="ubicacion_despacho" class="block text-sm font-medium text-gray-700 mb-2">
                                Ubicación de Despacho *
                            </label>
                            <input type="text" 
                                   wire:model="ubicacion_despacho" 
                                   id="ubicacion_despacho"
                                   maxlength="255"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Estación Shell - Av. Providencia 1234">
                            @error('ubicacion_despacho') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Observaciones -->
                        <div class="md:col-span-2">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Observaciones (Opcional)
                            </label>
                            <textarea wire:model="observaciones" 
                                      id="observaciones"
                                      rows="3"
                                      maxlength="1000"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Cualquier información adicional sobre el despacho..."></textarea>
                            @error('observaciones') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            * Campos obligatorios
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('despachos.show', $despacho) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Despacho
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
