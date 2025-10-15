<div>
    {{-- Botón para abrir el formulario --}}
    @if(!$mostrarFormulario)
        <button wire:click="toggleFormulario" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Solicitud
        </button>
    @endif

    {{-- Modal/Formulario --}}
    @if($mostrarFormulario)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="toggleFormulario"></div>

                {{-- Modal Content --}}
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white" id="modal-title">
                                📋 Nueva Solicitud de Combustible
                            </h3>
                            <button wire:click="toggleFormulario" 
                                    class="text-white hover:text-gray-200 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Alertas --}}
                    @if(session()->has('success'))
                        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session()->has('error'))
                        <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Formulario --}}
                    <form wire:submit.prevent="crear" class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Información Básica --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                                    📝 Información Básica
                                </h4>
                                
                                <div>
                                    <label for="id_unidad_transporte" class="block text-sm font-medium text-gray-700 mb-2">
                                        🚗 Unidad de Transporte <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="id_unidad_transporte" id="id_unidad_transporte" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_unidad_transporte') border-red-500 @enderror">
                                        <option value="">Selecciona una unidad de transporte</option>
                                        @foreach($unidadesTransporte as $unidad)
                                            <option value="{{ $unidad->id }}">
                                                {{ $unidad->placa }} - {{ $unidad->marca }} {{ $unidad->modelo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_unidad_transporte')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="cantidad_litros_solicitados" class="block text-sm font-medium text-gray-700 mb-2">
                                        ⛽ Cantidad de Litros <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="0.01" min="0" max="9999.99" 
                                           wire:model="cantidad_litros_solicitados" id="cantidad_litros_solicitados"
                                           placeholder="Ejemplo: 50.00"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cantidad_litros_solicitados') border-red-500 @enderror">
                                    @error('cantidad_litros_solicitados')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                                        📋 Motivo de la Solicitud <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model="motivo" id="motivo" rows="4"
                                              placeholder="Describe el motivo de la solicitud de combustible..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('motivo') border-red-500 @enderror"></textarea>
                                    @error('motivo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="urgente" 
                                               class="rounded border-orange-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        <span class="ml-3 text-sm">
                                            <span class="font-medium text-orange-800">🚨 Marcar como URGENTE</span>
                                            <span class="block text-orange-600">Esta solicitud requiere atención inmediata</span>
                                        </span>
                                    </label>
                                </div>

                                @if($urgente)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <label for="justificacion_urgencia" class="block text-sm font-medium text-red-700 mb-2">
                                            ⚠️ Justificación de Urgencia
                                        </label>
                                        <textarea wire:model="justificacion_urgencia" id="justificacion_urgencia" rows="3"
                                                  placeholder="Explica por qué esta solicitud es urgente..."
                                                  class="w-full px-4 py-3 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('justificacion_urgencia') border-red-500 @enderror"></textarea>
                                        @error('justificacion_urgencia')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            {{-- Información Adicional --}}
                            <div class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                                    📊 Información Adicional (Opcional)
                                </h4>
                                
                                <div>
                                    <label for="km_actual" class="block text-sm font-medium text-gray-700 mb-2">
                                        📏 Kilometraje Actual
                                    </label>
                                    <input type="number" min="0" wire:model="km_actual" id="km_actual"
                                           placeholder="Kilometraje actual del vehículo"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('km_actual') border-red-500 @enderror">
                                    @error('km_actual')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="km_proyectado" class="block text-sm font-medium text-gray-700 mb-2">
                                        🎯 Kilometraje Proyectado
                                    </label>
                                    <input type="number" min="0" wire:model="km_proyectado" id="km_proyectado"
                                           placeholder="Kilometraje estimado a recorrer"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('km_proyectado') border-red-500 @enderror">
                                    @error('km_proyectado')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="rendimiento_estimado" class="block text-sm font-medium text-gray-700 mb-2">
                                        ⚡ Rendimiento Estimado (km/L)
                                    </label>
                                    <input type="number" step="0.01" min="0" wire:model="rendimiento_estimado" id="rendimiento_estimado"
                                           placeholder="Ejemplo: 12.5"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rendimiento_estimado') border-red-500 @enderror">
                                    @error('rendimiento_estimado')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="saldo_actual_combustible" class="block text-sm font-medium text-gray-700 mb-2">
                                        ⛽ Saldo Actual de Combustible (L)
                                    </label>
                                    <input type="number" step="0.01" min="0" wire:model="saldo_actual_combustible" id="saldo_actual_combustible"
                                           placeholder="Combustible restante en el tanque"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('saldo_actual_combustible') border-red-500 @enderror">
                                    @error('saldo_actual_combustible')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="id_cat_programatica" class="block text-sm font-medium text-gray-700 mb-2">
                                        🏷️ Categoría Programática
                                    </label>
                                    <select wire:model="id_cat_programatica" id="id_cat_programatica" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Selecciona una categoría (opcional)</option>
                                        @foreach($categoriasProgramaticas as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="id_fuente_org_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                        💰 Fuente de Financiamiento
                                    </label>
                                    <select wire:model="id_fuente_org_fin" id="id_fuente_org_fin" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Selecciona una fuente (opcional)</option>
                                        @foreach($fuentesOrganismo as $fuente)
                                            <option value="{{ $fuente->id }}">{{ $fuente->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="mt-8 flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                            <button type="button" wire:click="toggleFormulario" 
                                    class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Crear Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
