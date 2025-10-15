<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Editar Solicitud de Combustible</h2>
                    <p class="text-gray-600">{{ $solicitud->numero_solicitud }}</p>
                </div>
                <a href="{{ route('solicitudes.show', $solicitud->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                    ← Cancelar
                </a>
            </div>

            {{-- Alertas --}}
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Formulario --}}
            <form wire:submit.prevent="actualizar">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Información Básica --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Información Básica</h3>
                        
                        <div>
                            <label for="id_unidad_transporte" class="block text-sm font-medium text-gray-700 mb-1">
                                Unidad de Transporte <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="id_unidad_transporte" id="id_unidad_transporte" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_unidad_transporte') border-red-500 @enderror">
                                <option value="">Selecciona una unidad</option>
                                @foreach($unidadesTransporte as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->placa }} - {{ $unidad->marca }} {{ $unidad->modelo }}</option>
                                @endforeach
                            </select>
                            @error('id_unidad_transporte')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cantidad_litros_solicitados" class="block text-sm font-medium text-gray-700 mb-1">
                                Cantidad de Litros <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" max="9999.99" 
                                   wire:model="cantidad_litros_solicitados" id="cantidad_litros_solicitados"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cantidad_litros_solicitados') border-red-500 @enderror">
                            @error('cantidad_litros_solicitados')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">
                                Motivo <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="motivo" id="motivo" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('motivo') border-red-500 @enderror"></textarea>
                            @error('motivo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="urgente" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Marcar como urgente</span>
                            </label>
                        </div>

                        @if($urgente)
                            <div>
                                <label for="justificacion_urgencia" class="block text-sm font-medium text-gray-700 mb-1">
                                    Justificación de Urgencia
                                </label>
                                <textarea wire:model="justificacion_urgencia" id="justificacion_urgencia" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('justificacion_urgencia') border-red-500 @enderror"></textarea>
                                @error('justificacion_urgencia')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    {{-- Información Adicional --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Información Adicional (Opcional)</h3>
                        
                        <div>
                            <label for="km_actual" class="block text-sm font-medium text-gray-700 mb-1">
                                Kilometraje Actual
                            </label>
                            <input type="number" min="0" wire:model="km_actual" id="km_actual"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('km_actual') border-red-500 @enderror">
                            @error('km_actual')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="km_proyectado" class="block text-sm font-medium text-gray-700 mb-1">
                                Kilometraje Proyectado
                            </label>
                            <input type="number" min="0" wire:model="km_proyectado" id="km_proyectado"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('km_proyectado') border-red-500 @enderror">
                            @error('km_proyectado')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rendimiento_estimado" class="block text-sm font-medium text-gray-700 mb-1">
                                Rendimiento Estimado (km/L)
                            </label>
                            <input type="number" step="0.01" min="0" wire:model="rendimiento_estimado" id="rendimiento_estimado"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rendimiento_estimado') border-red-500 @enderror">
                            @error('rendimiento_estimado')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="saldo_actual_combustible" class="block text-sm font-medium text-gray-700 mb-1">
                                Saldo Actual de Combustible (L)
                            </label>
                            <input type="number" step="0.01" min="0" wire:model="saldo_actual_combustible" id="saldo_actual_combustible"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('saldo_actual_combustible') border-red-500 @enderror">
                            @error('saldo_actual_combustible')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('solicitudes.show', $solicitud->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        Actualizar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
