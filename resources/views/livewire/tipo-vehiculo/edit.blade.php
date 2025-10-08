<div class="space-y-6">
{{-- Header del Modal --}}
<div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    <div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Tipo de Vehículo</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Modifica la información del tipo de vehículo "{{ $tipoVehiculo->nombre }}"</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editando
        </span>
    </div>
</div>

    <form wire:submit="save" class="space-y-6">
        {{-- Información básica --}}
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
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                @error('nombre') 
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Categoría --}}
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select wire:model="categoria" 
                        id="categoria"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Seleccionar categoría</option>
                    <option value="Liviano">Liviano</option>
                    <option value="Pesado">Pesado</option>
                    <option value="Motocicleta">Motocicleta</option>
                    <option value="Especializado">Especializado</option>
                </select>
                @error('categoria') 
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                @enderror
            </div>
        </div>

        {{-- Descripción --}}
        <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Descripción
            </label>
            <textarea wire:model="descripcion" 
                      id="descripcion"
                      rows="3"
                      placeholder="Descripción detallada del tipo de vehículo..."
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
            @error('descripcion') 
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Consumo de combustible --}}
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Consumo de Combustible</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('consumo_promedio_ciudad') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
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
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('consumo_promedio_carretera') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        {{-- Capacidades --}}
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Capacidades</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('capacidad_carga_kg') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
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
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('numero_pasajeros') 
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        {{-- Estado --}}
        <div>
            <label class="flex items-center">
                <input wire:model="activo" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tipo de vehículo activo</span>
            </label>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button" 
                    wire:click="$dispatch('closeModal')"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </button>
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg disabled:transform-none">
                <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <svg wire:loading class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove>Actualizar Tipo de Vehículo</span>
                <span wire:loading>Actualizando...</span>
            </button>
        </div>
    </form>
</div>
