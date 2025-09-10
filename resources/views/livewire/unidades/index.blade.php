<div>
    <div class="flex items-center justify-between mb-4">
        <div class="flex space-x-2">
            <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar..." class="border rounded px-2 py-1" />
            <select wire:model="perPage" class="border rounded px-2 py-1">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            @can('usuarios.gestionar')
                <a href="{{ route('unidades.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Crear unidad</a>
            @endcan
        </div>
    </div>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="text-left border-b">
                <th class="py-2">Código</th>
                <th class="py-2">Nombre</th>
                <th class="py-2">Tipo</th>
                <th class="py-2">Responsable</th>
                <th class="py-2">Activa</th>
                <th class="py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $u)
                <tr class="border-b">
                    <td class="py-2">{{ $u->codigo_unidad }}</td>
                    <td class="py-2">{{ $u->nombre_unidad }}</td>
                    <td class="py-2">{{ $u->tipo_unidad }}</td>
                    <td class="py-2">{{ $u->responsable_unidad }}</td>
                    <td class="py-2">{{ $u->activa ? 'Sí' : 'No' }}</td>
                    <td class="py-2">
                        <a href="{{ route('unidades.show', $u->id_unidad_organizacional) }}" class="text-blue-600 mr-2">Ver</a>

                        @can('usuarios.gestionar')
                            <a href="{{ route('unidades.edit', $u->id_unidad_organizacional) }}" class="text-green-600 mr-2">Editar</a>

                            <form action="{{ route('unidades.destroy', $u->id_unidad_organizacional) }}" method="POST" class="inline-block" onsubmit="return confirm('Eliminar unidad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Eliminar</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4">No se encontraron unidades.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
