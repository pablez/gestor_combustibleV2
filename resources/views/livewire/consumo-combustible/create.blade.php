<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nuevo Consumo de Combustible') }}
            </h2>
            <div class="flex space-x-2">
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
                <form wire:submit="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Unidad de Transporte -->
                        <div>
                            <label for="id_unidad_transporte" class="block text-sm font-medium text-gray-700 mb-2">
                                Unidad de Transporte *
                            </label>
                            <select wire:model="id_unidad_transporte" 
                                    id="id_unidad_transporte"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione una unidad...</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}">
                                        {{ $unidad->placa }} - {{ $unidad->marca }} {{ $unidad->modelo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_unidad_transporte') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Conductor -->
                        <div>
                            <label for="id_usuario_conductor" class="block text-sm font-medium text-gray-700 mb-2">
                                Conductor *
                            </label>
                            <select wire:model="id_usuario_conductor" 
                                    id="id_usuario_conductor"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un conductor...</option>
                                @foreach($conductores as $conductor)
                                    <option value="{{ $conductor->id }}">
                                        {{ $conductor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_usuario_conductor') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Despacho Asociado (Opcional) -->
                        <div class="md:col-span-2">
                            <label for="id_despacho" class="block text-sm font-medium text-gray-700 mb-2">
                                Despacho Asociado (Opcional)
                            </label>
                            <select wire:model="id_despacho" 
                                    id="id_despacho"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Sin despacho asociado</option>
                                @foreach($despachos as $despacho)
                                    <option value="{{ $despacho->id }}">
                                        {{ $despacho->numero_vale }} - 
                                        {{ $despacho->fecha_despacho ? $despacho->fecha_despacho->format('d/m/Y') : 'N/A' }} - 
                                        {{ $despacho->proveedor?->nombre_comercial ?? $despacho->proveedor?->nombre_proveedor ?? 'N/A' }} -
                                        {{ number_format($despacho->litros_despachados, 1) }}L
                                    </option>
                                @endforeach
                            </select>
                            @error('id_despacho') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Fecha de Registro -->
                        <div>
                            <label for="fecha_registro" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Registro *
                            </label>
                            <input type="date" 
                                   wire:model="fecha_registro" 
                                   id="fecha_registro"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('fecha_registro') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Tipo de Carga -->
                        <div>
                            <label for="tipo_carga" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Carga *
                            </label>
                            <select wire:model="tipo_carga" 
                                    id="tipo_carga"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($tiposCarga as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                            @error('tipo_carga') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Kilometraje Inicial -->
                        <div>
                            <label for="kilometraje_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                                Kilometraje Inicial *
                            </label>
                            <input type="number" 
                                   wire:model.live="kilometraje_inicial" 
                                   id="kilometraje_inicial"
                                   min="0"
                                   step="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="50000">
                            @error('kilometraje_inicial') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Kilometraje Final -->
                        <div>
                            <label for="kilometraje_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Kilometraje Final *
                            </label>
                            <input type="number" 
                                   wire:model.live="kilometraje_fin" 
                                   id="kilometraje_fin"
                                   min="0"
                                   step="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="50150">
                            @error('kilometraje_fin') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Litros Cargados -->
                        <div>
                            <label for="litros_cargados" class="block text-sm font-medium text-gray-700 mb-2">
                                Litros Cargados *
                            </label>
                            <input type="number" 
                                   wire:model.live="litros_cargados" 
                                   id="litros_cargados"
                                   step="0.001"
                                   min="0.1"
                                   max="999.999"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="50.5">
                            @error('litros_cargados') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Cálculos automáticos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Km Recorridos / Rendimiento
                            </label>
                            <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700">
                                {{ number_format($kilometros_recorridos, 0) }} km
                                @if($rendimiento > 0)
                                    • {{ $rendimiento }} km/L
                                @endif
                            </div>
                        </div>

                        <!-- Lugar de Carga -->
                        <div class="md:col-span-2">
                            <label for="lugar_carga" class="block text-sm font-medium text-gray-700 mb-2">
                                Lugar de Carga *
                            </label>
                            <input type="text" 
                                   wire:model="lugar_carga" 
                                   id="lugar_carga"
                                   maxlength="255"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Estación Shell - Av. Providencia 1234">
                            @error('lugar_carga') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Número de Ticket -->
                        <div>
                            <label for="numero_ticket" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Ticket (Opcional)
                            </label>
                            <input type="text" 
                                   wire:model="numero_ticket" 
                                   id="numero_ticket"
                                   maxlength="100"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="T-001234">
                            @error('numero_ticket') 
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
                                      placeholder="Cualquier información adicional sobre el consumo..."></textarea>
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
                            <a href="{{ route('consumos.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Consumo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
