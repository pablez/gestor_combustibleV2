<div class="space-y-8">
    {{-- Métricas Principales --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Flota Total --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Flota Total</p>
                    <p class="text-3xl font-bold">{{ number_format($metricasPrincipales['flota_total']['valor']) }}</p>
                    <p class="text-blue-200 text-sm">
                        {{ $metricasPrincipales['flota_total']['activos'] }} operativos 
                        ({{ $metricasPrincipales['flota_total']['porcentaje_operativo'] }}%)
                    </p>
                </div>
                <div class="p-4 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Combustible Hoy --}}
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Combustible Hoy</p>
                    <p class="text-3xl font-bold">{{ number_format($metricasPrincipales['combustible_hoy']['litros'], 0) }}L</p>
                    <p class="text-emerald-200 text-sm">
                        Bs. {{ number_format($metricasPrincipales['combustible_hoy']['costo'], 0) }} 
                        ({{ $metricasPrincipales['combustible_hoy']['despachos'] }} despachos)
                    </p>
                </div>
                <div class="p-4 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Combustible Mes --}}
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Combustible Este Mes</p>
                    <p class="text-3xl font-bold">{{ number_format($metricasPrincipales['combustible_mes']['litros'], 0) }}L</p>
                    <p class="text-purple-200 text-sm">
                        Bs. {{ number_format($metricasPrincipales['combustible_mes']['costo'], 0) }}
                    </p>
                </div>
                <div class="p-4 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Solicitudes Pendientes --}}
        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Solicitudes Pendientes</p>
                    <p class="text-3xl font-bold">{{ $metricasPrincipales['solicitudes_pendientes']['total'] }}</p>
                    <p class="text-orange-200 text-sm">
                        {{ $metricasPrincipales['solicitudes_pendientes']['urgentes'] }} urgentes
                    </p>
                </div>
                <div class="p-4 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas Críticas --}}
    @if(count($alertas) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.992-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">🚨 Alertas y Notificaciones</h3>
                <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                    {{ count($alertas) }}
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($alertas as $alerta)
                <div class="flex items-start p-4 rounded-lg border-l-4 
                    @if($alerta['tipo'] === 'danger') border-red-500 bg-red-50 @endif
                    @if($alerta['tipo'] === 'warning') border-yellow-500 bg-yellow-50 @endif
                    @if($alerta['tipo'] === 'info') border-blue-500 bg-blue-50 @endif">
                    <div class="flex-shrink-0 mr-3">
                        @if($alerta['urgencia'] === 'alta')
                            <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                        @elseif($alerta['urgencia'] === 'media')
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        @else
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $alerta['categoria'] }}</p>
                        <p class="text-sm text-gray-600">{{ $alerta['mensaje'] }}</p>
                        @if(isset($alerta['detalle']) && is_array($alerta['detalle']))
                            <p class="text-xs text-gray-500 mt-1">
                                Vehículos: {{ implode(', ', array_slice($alerta['detalle'], 0, 3)) }}
                                @if(count($alerta['detalle']) > 3)
                                    y {{ count($alerta['detalle']) - 3 }} más
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Eficiencia Operativa y Análisis Financiero --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Eficiencia Operativa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">📈 Eficiencia Operativa</h3>
                <p class="text-sm text-gray-600">Rendimiento promedio: {{ number_format($eficienciaOperativa['promedio_general'], 1) }} km/L</p>
            </div>
            <div class="p-6">
                {{-- Top Vehículos por Rendimiento --}}
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">🏆 Mejores Rendimientos (últimos 30 días)</h4>
                    <div class="space-y-2">
                        @foreach($eficienciaOperativa['rendimiento_vehiculos']->take(5) as $vehiculo)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $vehiculo->placa }}</p>
                                <p class="text-sm text-gray-600">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
                                <p class="text-xs text-gray-500">{{ $vehiculo->nombre_unidad }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-600">{{ $vehiculo->rendimiento_km_litro }} km/L</p>
                                <p class="text-xs text-gray-500">{{ number_format($vehiculo->litros_consumidos, 0) }}L consumidos</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Alerta de Vehículos con Bajo Rendimiento --}}
                @if($eficienciaOperativa['vehiculos_bajo_rendimiento'] > 0)
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.992-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800">
                            {{ $eficienciaOperativa['vehiculos_bajo_rendimiento'] }} vehículo(s) con rendimiento bajo (&lt;6 km/L)
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Análisis Financiero --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">💰 Análisis Financiero</h3>
                <div class="flex items-center mt-2">
                    @if($analisisFinanciero['costos_comparativos']['variacion_porcentual'] > 0)
                        <span class="text-red-600 text-sm font-medium">
                            ↗️ +{{ $analisisFinanciero['costos_comparativos']['variacion_porcentual'] }}% vs mes anterior
                        </span>
                    @elseif($analisisFinanciero['costos_comparativos']['variacion_porcentual'] < 0)
                        <span class="text-green-600 text-sm font-medium">
                            ↘️ {{ $analisisFinanciero['costos_comparativos']['variacion_porcentual'] }}% vs mes anterior
                        </span>
                    @else
                        <span class="text-gray-600 text-sm">Sin variación vs mes anterior</span>
                    @endif
                </div>
            </div>
            <div class="p-6">
                {{-- Comparativo de Costos --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Mes Actual</p>
                        <p class="text-2xl font-bold text-blue-600">Bs. {{ number_format($analisisFinanciero['costos_comparativos']['mes_actual'], 0) }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Mes Anterior</p>
                        <p class="text-2xl font-bold text-gray-600">Bs. {{ number_format($analisisFinanciero['costos_comparativos']['mes_anterior'], 0) }}</p>
                    </div>
                </div>

                {{-- Top Proveedores --}}
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">🏪 Gastos por Proveedor (este mes)</h4>
                    <div class="space-y-2">
                        @foreach($analisisFinanciero['gastos_por_proveedor']->take(3) as $proveedor)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $proveedor->nombre_proveedor }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($proveedor->litros_total, 0) }}L | {{ $proveedor->total_despachos }} despachos</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">Bs. {{ number_format($proveedor->costo_total, 0) }}</p>
                                <p class="text-xs text-gray-500">Bs. {{ $proveedor->precio_promedio }}/L</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Comparativas por Unidades (solo para Admin General) --}}
    @if(auth()->user()->hasRole('Admin_General') && count($comparativasUnidades) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🏢 Comparativas por Unidad Organizacional</h3>
            <p class="text-sm text-gray-600">Rendimiento y costos por unidad (últimos 30 días)</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Combustible</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eficiencia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($comparativasUnidades->take(10) as $unidad)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $unidad->nombre_unidad }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $unidad->vehiculos_operativos }}/{{ $unidad->total_vehiculos }}</div>
                                <div class="text-xs text-gray-500">operativos</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($unidad->litros_consumidos, 0) }}L</div>
                                <div class="text-xs text-gray-500">{{ number_format($unidad->km_recorridos, 0) }} km</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Bs. {{ number_format($unidad->costo_total, 0) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $unidad->eficiencia }} km/L</div>
                                    @if($unidad->eficiencia >= 10)
                                        <span class="ml-2 text-green-500">🏆</span>
                                    @elseif($unidad->eficiencia >= 8)
                                        <span class="ml-2 text-yellow-500">⚡</span>
                                    @elseif($unidad->eficiencia > 0)
                                        <span class="ml-2 text-red-500">⚠️</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Tendencias y Proyecciones --}}
    @if(count($tendencias['consumo_mensual']) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">📊 Tendencias y Proyecciones</h3>
            <p class="text-sm text-gray-600">Evolución del consumo últimos 6 meses</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Gráfico de Tendencias --}}
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Consumo Mensual</h4>
                    <div class="space-y-2">
                        @foreach($tendencias['consumo_mensual'] as $mes)
                        <div class="flex items-center justify-between p-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">{{ $mes->anio }}/{{ str_pad($mes->mes, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="text-right">
                                <span class="text-sm font-medium">{{ number_format($mes->litros, 0) }}L</span>
                                <span class="text-xs text-gray-500 ml-2">Bs. {{ number_format($mes->costo, 0) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Proyección --}}
                @if($tendencias['proyeccion_mes_siguiente'])
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Proyección Próximo Mes</h4>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($tendencias['proyeccion_mes_siguiente']['litros_estimados'], 0) }}L</p>
                            <p class="text-sm text-gray-600">Estimado</p>
                            <p class="text-lg font-semibold text-gray-800 mt-2">Bs. {{ number_format($tendencias['proyeccion_mes_siguiente']['costo_estimado'], 0) }}</p>
                        </div>
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                @if($tendencias['tendencia_litros'] === 'ascendente') bg-red-100 text-red-800 @endif
                                @if($tendencias['tendencia_litros'] === 'descendente') bg-green-100 text-green-800 @endif
                                @if($tendencias['tendencia_litros'] === 'estable') bg-gray-100 text-gray-800 @endif">
                                Tendencia: {{ ucfirst($tendencias['tendencia_litros']) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>