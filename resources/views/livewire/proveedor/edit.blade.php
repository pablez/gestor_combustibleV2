<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Proveedor: ') . ($proveedor->nombre_comercial ?: $proveedor->nombre_proveedor) }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('proveedores.show', $proveedor) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Cancelar
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
                <div class="p-6">
                    <form wire:submit="save">
                        <!-- Información General -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Información General</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tipo de Servicio -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Servicio *</label>
                                    <select
                                        wire:model="id_tipo_servicio_proveedor"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_tipo_servicio_proveedor') border-red-500 @enderror"
                                    >
                                        <option value="">Seleccione un tipo de servicio</option>
                                        @foreach($tiposServicio as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_tipo_servicio_proveedor')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIT/RUT -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NIT/RUT *</label>
                                    <input
                                        type="text"
                                        wire:model="nit"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nit') border-red-500 @enderror"
                                        placeholder="Ej: 12.345.678-9"
                                        maxlength="20"
                                    >
                                    @error('nit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre del Proveedor -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Proveedor *</label>
                                    <input
                                        type="text"
                                        wire:model="nombre_proveedor"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre_proveedor') border-red-500 @enderror"
                                        maxlength="100"
                                    >
                                    @error('nombre_proveedor')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre Comercial -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Comercial</label>
                                    <input
                                        type="text"
                                        wire:model="nombre_comercial"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre_comercial') border-red-500 @enderror"
                                        maxlength="100"
                                    >
                                    @error('nombre_comercial')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input
                                        type="email"
                                        wire:model="email"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                        maxlength="100"
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                    <input
                                        type="text"
                                        wire:model="telefono"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telefono') border-red-500 @enderror"
                                        maxlength="15"
                                    >
                                    @error('telefono')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de Ubicación -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Información de Ubicación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                    <input
                                        type="text"
                                        wire:model="direccion"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('direccion') border-red-500 @enderror"
                                        maxlength="200"
                                    >
                                    @error('direccion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Información Adicional</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contacto Principal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contacto Principal</label>
                                    <input
                                        type="text"
                                        wire:model="contacto_principal"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contacto_principal') border-red-500 @enderror"
                                        maxlength="100"
                                    >
                                    @error('contacto_principal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Calificación -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Calificación *</label>
                                    <select
                                        wire:model="calificacion"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('calificacion') border-red-500 @enderror"
                                    >
                                        <option value="A">A - Excelente</option>
                                        <option value="B">B - Bueno</option>
                                        <option value="C">C - Regular</option>
                                        <option value="D">D - Deficiente</option>
                                    </select>
                                    @error('calificacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Observaciones -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                                    <textarea
                                        wire:model="observaciones"
                                        rows="4"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observaciones') border-red-500 @enderror"
                                    ></textarea>
                                    @error('observaciones')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado Activo -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            wire:model="activo"
                                            id="activo"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label for="activo" class="ml-2 block text-sm text-gray-700">
                                            Proveedor activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <button
                                type="button"
                                wire:click="cancel"
                                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Actualizar Proveedor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
