<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <!-- Header del KPI -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Despachos de Combustible</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Análisis y control operacional</p>
                </div>
            </div>
            @can('despachos.ver')
            <a href="{{ route('despachos.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-orange-700 dark:text-orange-300 bg-orange-100 dark:bg-orange-900 hover:bg-orange-200 dark:hover:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                </svg>
                Ver Todos
            </a>
            @endcan
        </div>

        <!-- Métricas principales del día -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Despachos Hoy -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Hoy</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $totalDespachosHoy }}</p>
                        <p class="text-xs text-blue-500 dark:text-blue-300">despachos</p>
                    </div>
                </div>
            </div>

            <!-- Litros Hoy -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Litros Hoy</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($totalLitrosHoy, 1) }}</p>
                        <p class="text-xs text-green-500 dark:text-green-300">litros</p>
                    </div>
                </div>
            </div>

            <!-- Costo Hoy -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.51-1.31c-.562-.649-1.413-1.076-2.353-1.253V5z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Costo Hoy</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">${{ number_format($totalCostoHoy, 0) }}</p>
                        <p class="text-xs text-purple-500 dark:text-purple-300">CLP</p>
                    </div>
                </div>
            </div>

            <!-- Estado Validación -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Validados</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $porcentajeValidados }}%</p>
                        <p class="text-xs text-yellow-500 dark:text-yellow-300">{{ $despachosValidados }}/{{ $totalDespachos }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen mensual -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900 dark:to-blue-900 p-4 rounded-lg mb-6">
            <h4 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-3">Resumen del Mes</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($totalLitrosMes, 0) }}</p>
                    <p class="text-sm text-indigo-500 dark:text-indigo-300">Litros Totales</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($totalCostoMes, 0) }}</p>
                    <p class="text-sm text-indigo-500 dark:text-indigo-300">Costo Total</p>
                </div>
                <div class="text-center md:col-span-1 col-span-2">
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($promedioLitrosPorDespacho, 1) }}</p>
                    <p class="text-sm text-indigo-500 dark:text-indigo-300">Promedio L/Despacho</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Despachos Recientes -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Despachos Recientes</h4>
                <div class="space-y-3">
                    @forelse($despachosRecientes as $despacho)
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $despacho->numero_vale }}
                                    </span>
                                    @if($despacho->validado)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            ✓ Validado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                            ⏳ Pendiente
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $despacho->solicitud?->unidadTransporte?->placa ?? 'N/A' }} • 
                                    {{ number_format($despacho->litros_despachados, 1) }}L • 
                                    ${{ number_format($despacho->costo_total, 0) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $despacho->proveedor?->nombre_comercial ?? $despacho->proveedor?->nombre_proveedor ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $despacho->fecha_despacho->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay despachos recientes</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Proveedores más utilizados -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Proveedores Top (30 días)</h4>
                <div class="space-y-3">
                    @forelse($proveedoresMasUsados as $proveedor)
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $proveedor->proveedor?->nombre_comercial ?? $proveedor->proveedor?->nombre_proveedor ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $proveedor->total_despachos }} despachos • {{ number_format($proveedor->total_litros, 1) }} litros
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-800 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-orange-600 dark:text-orange-400">{{ $proveedor->total_despachos }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay datos de proveedores</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tendencia semanal -->
        @if(count($estadisticasSemanal) > 0)
        <div class="mt-6">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Tendencia Últimas 4 Semanas</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @foreach($estadisticasSemanal as $semana)
                <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900 dark:to-cyan-900 p-3 rounded-lg">
                    <div class="text-center">
                        <p class="text-xs font-medium text-teal-600 dark:text-teal-400 mb-1">{{ $semana['semana'] }}</p>
                        <p class="text-lg font-bold text-teal-900 dark:text-teal-100">{{ $semana['despachos'] }}</p>
                        <p class="text-xs text-teal-500 dark:text-teal-300">despachos</p>
                        <div class="mt-2 pt-2 border-t border-teal-200 dark:border-teal-700">
                            <p class="text-xs text-teal-600 dark:text-teal-400">{{ number_format($semana['litros'], 0) }}L</p>
                            <p class="text-xs text-teal-500 dark:text-teal-300">${{ number_format($semana['costo'], 0) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Indicadores de rendimiento adicionales -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $despachosPendientes }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Despachos Pendientes</p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $despachosUltimos7Dias }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Últimos 7 días</p>
            </div>
            
            <div class="text-center col-span-2 md:col-span-1">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($promedioCostoPorLitro, 0) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Precio Promedio/L</p>
            </div>
        </div>
    </div>
</div>