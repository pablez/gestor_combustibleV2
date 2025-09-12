<div>
    @if($show)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-xl p-6">
                <h3 class="text-lg font-semibold mb-4">Crear unidad</h3>
                <form wire:submit.prevent="save">
                    <div class="mb-3">
                        <label class="block text-sm text-gray-600">Código</label>
                        <div class="flex gap-2 items-center">
                            <input wire:model="codigo_unidad" class="flex-1 border rounded px-3 py-2" />
                            <button type="button" wire:click="regenerateCodigo" title="Generar" class="inline-flex items-center justify-center px-2 py-1 border rounded text-sm bg-gray-100 hover:bg-gray-200">
                                ⟳
                            </button>
                        </div>
                        @error('codigo_unidad') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-600">Nombre</label>
                        <input wire:model="nombre_unidad" class="w-full border rounded px-3 py-2" />
                        @error('nombre_unidad') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <!-- Siglas ahora se generan en el campo código -->

                    <div class="mb-3">
                        <label class="block text-sm text-gray-600">Tipo</label>
                        <select wire:model="tipo_unidad" class="w-full border rounded px-3 py-2">
                            <option value="Operativa">Operativa</option>
                            <option value="Ejecutiva">Ejecutiva</option>
                            <option value="Superior">Superior</option>
                        </select>
                        @error('tipo_unidad') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="close" class="px-4 py-2 border rounded">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
