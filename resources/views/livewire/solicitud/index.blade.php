<div>
    <div class="mb-4">
        <livewire:solicitud.create />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Número</th>
                    <th class="px-4 py-2 text-left">Solicitante</th>
                    <th class="px-4 py-2 text-left">Litros</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Creado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $s->id }}</td>
                        <td class="px-4 py-2">{{ $s->numero_solicitud }}</td>
                        <td class="px-4 py-2">{{ $s->solicitante?->full_name ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $s->cantidad_litros_solicitados }}</td>
                        <td class="px-4 py-2">{{ $s->estado_solicitud }}</td>
                        <td class="px-4 py-2">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $solicitudes->links() }}
    </div>
</div>
