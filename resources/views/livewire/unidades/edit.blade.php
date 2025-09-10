<div>
    <h2 class="text-xl font-bold mb-4">Editar Unidad Organizacional</h2>

    <form wire:submit.prevent="save" class="space-y-3">
        <div>
            <label class="block">Código</label>
            <input wire:model.defer="codigo_unidad" class="border rounded px-2 py-1 w-full" />
            @error('codigo_unidad') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block">Nombre</label>
            <input wire:model.defer="nombre_unidad" class="border rounded px-2 py-1 w-full" />
            @error('nombre_unidad') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block">Tipo</label>
                <select wire:model.defer="tipo_unidad" class="border rounded px-2 py-1 w-full">
                    <option value="Superior">Superior</option>
                    <option value="Ejecutiva">Ejecutiva</option>
                    <option value="Operativa">Operativa</option>
                </select>
                @error('tipo_unidad') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block">Nivel jerárquico</label>
                <input type="number" wire:model.defer="nivel_jerarquico" class="border rounded px-2 py-1 w-full" />
            </div>
        </div>

        <div>
            <label class="block">Unidad padre (opcional)</label>
            <select wire:model.defer="id_unidad_padre" class="border rounded px-2 py-1 w-full">
                <option value="">-- Ninguna --</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id_unidad_organizacional }}">{{ $p->nombre_unidad }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block">Responsable</label>
            <input wire:model.defer="responsable_unidad" class="border rounded px-2 py-1 w-full" />
        </div>

        <div>
            <label class="block">Teléfono</label>
            <input wire:model.defer="telefono" class="border rounded px-2 py-1 w-full" />
        </div>

        <div>
            <label class="block">Dirección</label>
            <input wire:model.defer="direccion" class="border rounded px-2 py-1 w-full" />
        </div>

        <div>
            <label class="block">Presupuesto asignado</label>
            <input type="number" step="0.01" wire:model.defer="presupuesto_asignado" class="border rounded px-2 py-1 w-full" />
        </div>

        <div>
            <label class="block">Descripción</label>
            <textarea wire:model.defer="descripcion" class="border rounded px-2 py-1 w-full"></textarea>
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model.defer="activa" class="mr-2" /> Activa
            </label>
        </div>

        <div>
            <button class="bg-blue-600 text-white px-3 py-1 rounded">Guardar</button>
            <a href="{{ route('unidades.index') }}" class="ml-2 text-gray-600">Cancelar</a>
        </div>
    </form>
</div>
