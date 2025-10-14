<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-6">
        <!-- Header del KPI -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proveedores</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Gestión y análisis de proveedores</p>
                </div>
            </div>
            @can('proveedores.ver')
            <a href="{{ route('proveedores.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-purple-700 dark:text-purple-300 bg-purple-100 dark:bg-purple-900 hover:bg-purple-200 dark:hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7h10l-4-4M7 7l4 4 4-4"></path>
                </svg>
                Ver Todos
            </a>
            @endcan
        </div>

        <!-- Métricas principales -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Proveedores -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $totalProveedores }}</p>
                    </div>
                </div>
            </div>

            <!-- Proveedores Activos -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Activos</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $proveedoresActivos }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400">{{ $porcentajeActivos }}% del total</p>
                    </div>
                </div>
            </div>

            <!-- Promedio de Calificación -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Calificación</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $promedioCalificacion }}</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400">{{ $this->getCalificacionTexto() }}</p>
                    </div>
                </div>
            </div>

            <!-- Proveedores Recientes -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Nuevos</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $proveedoresRecientes }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400">Últimos 30 días</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y análisis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Distribución por Calificación -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Distribución por Calificación</h4>
                <div class="space-y-3">
                    @php
                        $coloresCalificacion = [
                            'A' => ['bg' => 'bg-green-500', 'text' => 'text-green-700', 'bg-light' => 'bg-green-100'],
                            'B' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'bg-light' => 'bg-blue-100'],
                            'C' => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-700', 'bg-light' => 'bg-yellow-100'],
                            'D' => ['bg' => 'bg-red-500', 'text' => 'text-red-700', 'bg-light' => 'bg-red-100']
                        ];
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $calificacion)
                        @php
                            $cantidad = $proveedoresPorCalificacion[$calificacion] ?? 0;
                            $porcentaje = $totalProveedores > 0 ? ($cantidad / $totalProveedores) * 100 : 0;
                            $color = $coloresCalificacion[$calificacion];
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 {{ $color['bg'] }} rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Calificación {{ $calificacion }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $cantidad }}</span>
                                <div class="w-16 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="{{ $color['bg'] }} h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 w-10 text-right">
                                    {{ number_format($porcentaje, 1) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tipos de Servicio Más Utilizados -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Tipos de Servicio Más Utilizados</h4>
                <div class="space-y-3">
                    @php
                        $coloresTipo = ['bg-indigo-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500', 'bg-cyan-500'];
                    @endphp
                    @foreach($tiposServicioMasUsados as $index => $tipo)
                        @php
                            $porcentaje = $totalProveedores > 0 ? ($tipo->total / $totalProveedores) * 100 : 0;
                            $colorClass = $coloresTipo[$index % count($coloresTipo)];
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 {{ $colorClass }} rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate" title="{{ $tipo->nombre }}">
                                    {{ Str::limit($tipo->nombre, 20) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $tipo->total }}</span>
                                <div class="w-16 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="{{ $colorClass }} h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 w-10 text-right">
                                    {{ number_format($porcentaje, 1) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Estado de salud general -->
        <div class="mt-6 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($porcentajeActivos >= 80)
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @elseif($porcentajeActivos >= 60)
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Estado de Proveedores</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @if($porcentajeActivos >= 80)
                                Excelente: La mayoría de proveedores están activos
                            @elseif($porcentajeActivos >= 60)
                                Bueno: Considera revisar proveedores inactivos
                            @else
                                Atención: Muchos proveedores inactivos necesitan revisión
                            @endif
                        </p>
                    </div>
                </div>
                @can('proveedores.crear')
                <a href="{{ route('proveedores.create') }}" 
                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Proveedor
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>