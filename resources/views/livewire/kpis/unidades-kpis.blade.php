<div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h4 class="text-lg font-semibold">Unidades</h4>
            <p class="text-sm text-gray-500 mt-1">Total visibles: <strong>{{ $totalUnidades }}</strong></p>
        </div>
        <div class="text-indigo-600 font-bold text-xl">{{ $totalUnidades }}</div>
    </div>

    <div class="space-y-3">
        @if(count($usersByUnidad) > 0)
            @foreach($usersByUnidad as $u)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                    <div>
                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ $u['codigo_unidad'] }} — {{ $u['nombre_unidad'] }}</div>
                        <div class="text-xs text-gray-500">Usuarios: {{ $u['count'] }}</div>
                    </div>
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $u['count'] }}</div>
                </div>
            @endforeach
        @else
            <div class="text-sm text-gray-500">No hay unidades visibles</div>
        @endif
    </div>
</div>
