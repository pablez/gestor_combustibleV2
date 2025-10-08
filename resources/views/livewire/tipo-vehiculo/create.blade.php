<div class="max-w-4xl mx-auto">
    {{-- Header del Modal con mejor espaciado --}}
    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-6 mb-8">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Tipo de Vehículo</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Completa la información del nuevo tipo de vehículo</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                </svg>
                Nuevo
            </span>
        </div>
    </div>

    <form wire:submit="save" class="space-y-8">
        {{-- Información básica con mejor espaciado --}}
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información Básica</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Tipo <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="nombre" 
                           type="text" 
                           id="nombre"
                           placeholder="Ej: Sedán, Camioneta, etc."
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('nombre') 
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Categoría --}}
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="categoria" 
                            id="categoria"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        <option value="">Seleccionar categoría</option>
                        <option value="Liviano">Liviano</option>
                        <option value="Pesado">Pesado</option>
                        <option value="Motocicleta">Motocicleta</option>
                        <option value="Especializado">Especializado</option>
                    </select>
                    @error('categoria') 
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            {{-- Descripción --}}
            <div class="mt-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Descripción
                </label>
                <textarea wire:model="descripcion" 
                          id="descripcion"
                          rows="3"
                          placeholder="Descripción opcional del tipo de vehículo..."
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors resize-none"></textarea>
                @error('descripcion') 
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>
        </div>

        {{-- Consumo de combustible con mejor diseño --}}
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Consumo de Combustible</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Especifica el consumo promedio en diferentes condiciones</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="consumo_ciudad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Consumo en Ciudad (L/100km)
                    </label>
                    <input wire:model="consumo_promedio_ciudad" 
                           type="number" 
                           step="0.1"
                           min="0"
                           max="999.99"
                           id="consumo_ciudad"
                           placeholder="8.5"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('consumo_promedio_ciudad') 
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="consumo_carretera" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Consumo en Carretera (L/100km)
                    </label>
                    <input wire:model="consumo_promedio_carretera" 
                           type="number" 
                           step="0.1"
                           min="0"
                           max="999.99"
                           id="consumo_carretera"
                           placeholder="6.8"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('consumo_promedio_carretera') 
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        {{-- Capacidades con mejor diseño --}}
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Capacidades del Vehículo</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Define las capacidades específicas del tipo de vehículo</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="capacidad_carga" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Capacidad de Carga (kg)
                    </label>
                    <input wire:model="capacidad_carga_kg" 
                           type="number" 
                           min="0"
                           max="999999"
                           id="capacidad_carga"
                           placeholder="500"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('capacidad_carga_kg') 
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="numero_pasajeros" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Número de Pasajeros
                    </label>
                    <input wire:model="numero_pasajeros" 
                           type="number" 
                           min="1"
                           max="100"
                           id="numero_pasajeros"
                           placeholder="5"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                    @error('numero_pasajeros') 
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        {{-- Estado con mejor diseño --}}
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configuración</h4>
            <div class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                <input wire:model="activo" 
                       type="checkbox" 
                       id="activo"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4">
                <label for="activo" class="ml-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de vehículo activo</span>
                    <p class="text-xs text-gray-500 dark:text-gray-400">El tipo estará disponible para asignar a vehículos</p>
                </label>
            </div>
        </div>

        {{-- Botones de acción mejorados --}}
        <div class="flex justify-end gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
            <button type="button" 
                    wire:click="$dispatch('closeModal')"
                    class="flex items-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </button>
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-8 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg disabled:transform-none shadow-lg">
                <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <svg wire:loading class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove>Crear Tipo de Vehículo</span>
                <span wire:loading>Creando...</span>
            </button>
        </div>
    </form>
</div>
