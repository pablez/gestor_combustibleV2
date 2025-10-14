<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Consumo de Combustible') }}
        </h2>
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
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Columna Izquierda: Información del Vehículo y Conductor -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Vehículo</h3>
                                
                                <!-- Unidad de Transporte -->
                                <div class="mb-4">
                                    <label for="unidad_transporte_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Unidad de Transporte <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="unidad_transporte_id" id="unidad_transporte_id" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccionar unidad</option>
                                        @foreach($unidadesTransporte as $unidad)
                                            <option value="{{ $unidad->id }}">
                                                {{ $unidad->placa }} - {{ $unidad->marca }} {{ $unidad->modelo }}
                                                ({{ $unidad->tipoVehiculo?->nombre ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unidad_transporte_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Conductor -->
                                <div class="mb-4">
                                    <label for="conductor_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Conductor <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="conductor_id" id="conductor_id" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccionar conductor</option>
                                        @foreach($conductores as $conductor)
                                            <option value="{{ $conductor->id }}">{{ $conductor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('conductor_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha de Registro -->
                                <div class="mb-4">
                                    <label for="fecha_registro" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha de Registro <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="fecha_registro" id="fecha_registro" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('fecha_registro')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Despacho Asociado -->
                                <div class="mb-4">
                                    <label for="despacho_combustible_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Despacho Asociado (Opcional)
                                    </label>
                                    <select wire:model="despacho_combustible_id" wire:change="$dispatch('despacho-selected', { despachoId: $event.target.value })" 
                                            id="despacho_combustible_id" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Sin despacho asociado</option>
                                        @foreach($despachos as $despacho)
                                            <option value="{{ $despacho->id }}">
                                                {{ $despacho->numero_vale }} - {{ $despacho->proveedor?->nombre_comercial ?? 'N/A' }}
                                                ({{ number_format($despacho->litros_despachados, 1) }} L)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('despacho_combustible_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Columna Derecha: Información del Consumo -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos del Consumo</h3>
                                
                                <!-- Litros Cargados -->
                                <div class="mb-4">
                                    <label for="litros_cargados" class="block text-sm font-medium text-gray-700 mb-1">
                                        Litros Cargados <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.001" min="0.001" max="9999.999" 
                                               wire:model.live="litros_cargados" id="litros_cargados" 
                                               class="mt-1 block w-full px-3 py-2 pr-12 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">L</span>
                                        </div>
                                    </div>
                                    @error('litros_cargados')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo de Carga -->
                                <div class="mb-4">
                                    <label for="tipo_carga" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tipo de Carga <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="tipo_carga" id="tipo_carga" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="Completa">Carga Completa</option>
                                        <option value="Parcial">Carga Parcial</option>
                                        <option value="Emergencia">Carga de Emergencia</option>
                                    </select>
                                    @error('tipo_carga')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Lugar de Carga -->
                                <div class="mb-4">
                                    <label for="lugar_carga" class="block text-sm font-medium text-gray-700 mb-1">
                                        Lugar de Carga <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="lugar_carga" id="lugar_carga" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="Ej: Estación de Servicio Central">
                                    @error('lugar_carga')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Número de Ticket -->
                                <div class="mb-4">
                                    <label for="numero_ticket" class="block text-sm font-medium text-gray-700 mb-1">
                                        Número de Ticket (Opcional)
                                    </label>
                                    <input type="text" wire:model="numero_ticket" id="numero_ticket" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="Número del ticket de combustible">
                                    @error('numero_ticket')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Kilometraje -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Kilometraje</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Kilometraje Inicial -->
                                <div>
                                    <label for="kilometraje_inicial" class="block text-sm font-medium text-gray-700 mb-1">
                                        Km Inicial <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" min="0" max="9999999" 
                                           wire:model.live="kilometraje_inicial" id="kilometraje_inicial" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('kilometraje_inicial')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kilometraje Final -->
                                <div>
                                    <label for="kilometraje_fin" class="block text-sm font-medium text-gray-700 mb-1">
                                        Km Final <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" min="0" max="9999999" 
                                           wire:model.live="kilometraje_fin" id="kilometraje_fin" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('kilometraje_fin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kilómetros Recorridos (Calculado) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Km Recorridos
                                    </label>
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm text-sm font-semibold text-green-700">
                                        {{ number_format($kilometros_recorridos, 0) }} km
                                    </div>
                                </div>

                                <!-- Rendimiento (Calculado) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Rendimiento
                                    </label>
                                    <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm text-sm font-semibold text-blue-700">
                                        {{ $rendimiento }} km/L
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-6">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                                Observaciones
                            </label>
                            <textarea wire:model="observaciones" id="observaciones" rows="3" 
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Observaciones adicionales sobre el consumo de combustible"></textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <button type="button" wire:click="cancel" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>

                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Consumo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
