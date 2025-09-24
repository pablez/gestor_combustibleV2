<div class="space-y-4">
    @if(session()->has('message'))
        <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700">Cantidad (litros)</label>
            <input type="number" step="0.001" wire:model.defer="cantidad_litros_solicitados" class="mt-1 block w-full rounded border-gray-300" />
            @error('cantidad_litros_solicitados') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Urgente</label>
            <input type="checkbox" wire:model="urgente" class="mt-2" />
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Motivo</label>
        <textarea wire:model.defer="motivo" class="mt-1 block w-full rounded border-gray-300" rows="3"></textarea>
        @error('motivo') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
    </div>

    <div>
        <button wire:click="submit" class="inline-flex items-center px-3 py-2 rounded bg-indigo-600 text-white">Crear Solicitud</button>
    </div>
</div>
