<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold">Gestión de Imágenes - Vehículos</h3>
            <p class="text-sm text-gray-500">Total vehículos: <span class="font-medium">{{ $vehiculos->total() ?? 0 }}</span></p>
        </div>

        <div class="flex items-center space-x-2">
            <input wire:model.defer="search" type="text" placeholder="Buscar por placa, modelo o tipo" class="px-3 py-2 border rounded w-56" />
            <button wire:click="$refresh" class="px-3 py-2 bg-blue-600 text-white rounded">Buscar</button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3">
        @forelse($vehiculos as $vehiculo)
            <div class="p-3 bg-white dark:bg-gray-800 rounded flex items-center justify-between border border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-8 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden flex items-center justify-center">
                        @if(!empty($vehiculo->foto_principal))
                            <img src="{{ asset('storage/' . $vehiculo->foto_principal) }}" alt="{{ $vehiculo->placa }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs text-gray-400">Sin foto</span>
                        @endif
                    </div>
                    <div>
                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $vehiculo->placa }} <span class="text-sm text-gray-500">— {{ $vehiculo->modelo }}</span></div>
                        <div class="text-xs text-gray-500">{{ $vehiculo->tipo }} • {{ $vehiculo->unidad }}</div>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="/vehiculos/{{ $vehiculo->id }}/imagenes" class="text-sm px-3 py-1 bg-white border rounded text-blue-600 hover:bg-blue-50">Galería</a>
                    <a href="/admin/vehiculos/imagenes/{{ $vehiculo->id }}" class="text-sm px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Detalle</a>
                </div>
            </div>
        @empty
            <div class="p-4 bg-white dark:bg-gray-800 rounded">No se encontraron vehículos.</div>
        @endforelse
    </div>

    <div class="mt-4 flex items-center justify-end">
        {{ $vehiculos->links() }}
    </div>
</div>
