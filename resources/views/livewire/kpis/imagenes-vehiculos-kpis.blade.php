<div class="bg-white rounded-lg shadow-lg p-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">KPIs - Imágenes de Vehículos</h3>
        <small class="text-xs text-gray-500">Actualizado: {{ now()->format('d/m/Y H:i') }}</small>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Vehículos con foto principal --}}
        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex-shrink-0 p-2 bg-indigo-100 dark:bg-indigo-900 rounded-md">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10-6v4a1 1 0 01-1 1h-3M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-3 w-full">
                <div class="text-xs text-gray-500">Vehículos con foto principal</div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $vehiculosConFotoPrincipal ?? 0 }}</div>
                    <div class="text-xs text-gray-500">de <span class="font-medium">{{ $totalUnidadesTransporte ?? '—' }}</span></div>
                </div>
                <div class="mt-2">
                    <a href="/admin/vehiculos/imagenes" class="text-xs text-indigo-600 hover:text-indigo-800">Ver detalles</a>
                </div>
            </div>
        </div>

        {{-- Vehículos con galería --}}
        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex-shrink-0 p-2 bg-green-100 dark:bg-green-900 rounded-md">
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </div>
            <div class="ml-3 w-full">
                <div class="text-xs text-gray-500">Vehículos con galería</div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $vehiculosConGaleria ?? 0 }}</div>
                    <div class="text-xs text-gray-500">Galería activa</div>
                </div>
                <div class="mt-2">
                    <a href="/admin/vehiculos/imagenes" class="text-xs text-green-600 hover:text-green-800">Ver detalles</a>
                </div>
            </div>
        </div>

        {{-- Promedio imágenes por vehículo --}}
        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex-shrink-0 p-2 bg-yellow-100 dark:bg-yellow-900 rounded-md">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 4v-6a2 2 0 00-2-2H8a2 2 0 00-2 2v6m10 0H7"/>
                </svg>
            </div>
            <div class="ml-3 w-full">
                <div class="text-xs text-gray-500">Promedio imágenes/vehículo</div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $promedioFotosPorVehiculo ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500">Media</div>
                </div>
                <div class="mt-2">
                    <a href="/admin/vehiculos/imagenes" class="text-xs text-yellow-600 hover:text-yellow-800">Ver detalles</a>
                </div>
            </div>
        </div>

        {{-- % Vehículos con documentos completos --}}
        <div class="flex flex-col p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-2 bg-pink-100 dark:bg-pink-900 rounded-md">
                    <svg class="w-6 h-6 text-pink-600 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="text-xs text-gray-500">% Vehículos con documentos completos</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $porcentajeDocumentosCompletos ?? 0 }}%</div>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $porcentajeDocumentosCompletos }}">
                        <div class="h-2 rounded-full bg-pink-500" style="width: {{ max(0, min(100, $porcentajeDocumentosCompletos ?? 0)) }}%"></div>
                </div>
                    <div class="text-xs text-gray-500 mt-2">
                        <div class="flex items-center">
                            <span>Documentos completos de vehículos registrados</span>
                            <!-- Alpine tooltip -->
                            <div x-data="{open:false}" class="relative ml-2">
                                <button @mouseenter="open = true" @mouseleave="open = false" aria-label="Explicación cálculo" class="ml-2 text-xs text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                </button>
                                <div x-show="open" x-cloak class="absolute z-20 -top-16 left-0 w-64 p-2 text-xs bg-gray-800 text-white rounded shadow-lg">
                                    Se calcula como: (vehículos con todos los documentos obligatorios / total de vehículos registrados) * 100
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="/admin/vehiculos/imagenes" class="text-xs text-pink-600 hover:text-pink-800">Ver detalles</a>
                    </div>
            </div>
        </div>
    </div>
</div>
