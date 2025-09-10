<div>
    <h2 class="text-xl font-bold mb-4">Unidad: {{ $unidad->nombre_unidad }}</h2>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <strong>Código</strong>
            <div>{{ $unidad->codigo_unidad }}</div>
        </div>
        <div>
            <strong>Tipo</strong>
            <div>{{ $unidad->tipo_unidad }}</div>
        </div>
        <div>
            <strong>Responsable</strong>
            <div>{{ $unidad->responsable_unidad }}</div>
        </div>
        <div>
            <strong>Teléfono</strong>
            <div>{{ $unidad->telefono }}</div>
        </div>
        <div>
            <strong>Dirección</strong>
            <div>{{ $unidad->direccion }}</div>
        </div>
        <div>
            <strong>Presupuesto asignado</strong>
            <div>{{ number_format($unidad->presupuesto_asignado, 2) }}</div>
        </div>
        <div class="col-span-2">
            <strong>Descripción</strong>
            <div>{{ $unidad->descripcion }}</div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('unidades.index') }}" class="text-gray-600 mr-2">Volver</a>
        @can('usuarios.gestionar')
            <a href="{{ route('unidades.edit', $unidad->id_unidad_organizacional) }}" class="text-green-600 mr-2">Editar</a>
        @endcan
    </div>
</div>
