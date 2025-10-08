<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Vehículos y Transporte</h3>
        <a href="/tipos-vehiculo" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-sm font-medium">
            Ver todos →
        </a>
    </div>

    {{-- KPIs principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Tipos de Vehículos --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tipos de Vehículos</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $tiposActivosVehiculo }}</p>
                        <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ {{ $totalTiposVehiculo }}</p>
                    </div>
                    <p class="text-xs text-green-600 dark:text-green-400">Activos / Total</p>
                </div>
            </div>
        </div>

        {{-- Unidades de Transporte --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Vehículos Activos</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $unidadesActivasTransporte }}</p>
                        <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ {{ $totalUnidadesTransporte }}</p>
                    </div>
                    <p class="text-xs text-blue-600 dark:text-blue-400">
                        {{ $totalUnidadesTransporte > 0 ? round(($unidadesActivasTransporte / $totalUnidadesTransporte) * 100, 1) : 0 }}% Operativos
                    </p>
                </div>
            </div>
        </div>

        {{-- Tipos de Combustible --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tipos Combustible</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $tiposActivosCombustible }}</p>
                        <p class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ {{ $totalTiposCombustible }}</p>
                    </div>
                    <p class="text-xs text-orange-600 dark:text-orange-400">Disponibles</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos y distribuciones --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Vehículos por Categoría --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Vehículos por Categoría</h4>
            @if(count($vehiculosPorCategoria) > 0)
                <div class="space-y-3">
                    @foreach($vehiculosPorCategoria as $categoria => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3 
                                    @if($categoria === 'Liviano') bg-green-500
                                    @elseif($categoria === 'Pesado') bg-red-500
                                    @elseif($categoria === 'Motocicleta') bg-blue-500
                                    @elseif($categoria === 'Especializado') bg-purple-500
                                    @else bg-gray-500 @endif">
                                </div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $categoria }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full 
                                @if($categoria === 'Liviano') bg-green-500
                                @elseif($categoria === 'Pesado') bg-red-500
                                @elseif($categoria === 'Motocicleta') bg-blue-500
                                @elseif($categoria === 'Especializado') bg-purple-500
                                @else bg-gray-500 @endif"
                                style="width: {{ array_sum($vehiculosPorCategoria) > 0 ? ($count / array_sum($vehiculosPorCategoria)) * 100 : 0 }}%">
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No hay datos disponibles</p>
            @endif
        </div>

        {{-- Estado de Vehículos --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Estado de Vehículos</h4>
            @if(count($vehiculosPorEstado) > 0)
                <div class="space-y-3">
                    @foreach($vehiculosPorEstado as $estado => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3 
                                    @if($estado === 'Operativo') bg-green-500
                                    @elseif($estado === 'Mantenimiento') bg-yellow-500
                                    @elseif($estado === 'Taller') bg-orange-500
                                    @elseif($estado === 'Baja') bg-red-500
                                    @elseif($estado === 'Reserva') bg-blue-500
                                    @else bg-gray-500 @endif">
                                </div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $estado }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full 
                                @if($estado === 'Operativo') bg-green-500
                                @elseif($estado === 'Mantenimiento') bg-yellow-500
                                @elseif($estado === 'Taller') bg-orange-500
                                @elseif($estado === 'Baja') bg-red-500
                                @elseif($estado === 'Reserva') bg-blue-500
                                @else bg-gray-500 @endif"
                                style="width: {{ array_sum($vehiculosPorEstado) > 0 ? ($count / array_sum($vehiculosPorEstado)) * 100 : 0 }}%">
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No hay datos disponibles</p>
            @endif
        </div>
    </div>

    {{-- Consumo promedio por categoría --}}
    @if(count($consumoPromedio) > 0)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Consumo Promedio por Categoría (L/100km)</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($consumoPromedio as $categoria => $consumo)
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">{{ $categoria }}</h5>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Ciudad: <span class="font-medium">{{ $consumo['ciudad'] ?? 'N/A' }}L</span>
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Carretera: <span class="font-medium">{{ $consumo['carretera'] ?? 'N/A' }}L</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Últimos vehículos registrados --}}
    @if(count($topUnidadesTransporte) > 0)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Últimos Vehículos Registrados</h4>
            <div class="space-y-3">
                @foreach($topUnidadesTransporte as $vehiculo)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 rounded-full 
                                    @if($vehiculo['estado'] === 'Operativo') bg-green-500
                                    @elseif($vehiculo['estado'] === 'Mantenimiento') bg-yellow-500
                                    @elseif($vehiculo['estado'] === 'Taller') bg-orange-500
                                    @elseif($vehiculo['estado'] === 'Baja') bg-red-500
                                    @elseif($vehiculo['estado'] === 'Reserva') bg-blue-500
                                    @else bg-gray-500 @endif">
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $vehiculo['placa'] }} - {{ $vehiculo['modelo'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $vehiculo['tipo'] }} | {{ $vehiculo['unidad'] }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($vehiculo['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
