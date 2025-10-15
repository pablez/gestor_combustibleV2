<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-medium text-gray-900">
                📊 Reportes de Combustible
            </h1>
            <p class="mt-2 text-gray-500 text-sm leading-relaxed">
                Genere reportes detallados de consumos y despachos de combustible en formato PDF o Excel.
            </p>
        </div>

        <div class="bg-gray-50 p-6 lg:p-8">
            <form wire:submit.prevent="generarPDF" class="space-y-6">
                <!-- Filtros de Fecha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fechaInicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                        <input type="date" wire:model="fechaInicio" id="fechaInicio" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="fechaFin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                        <input type="date" wire:model="fechaFin" id="fechaFin" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Filtro de Unidad -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="unidadId" class="block text-sm font-medium text-gray-700">Unidad de Transporte</label>
                        <select wire:model="unidadId" id="unidadId" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todas las unidades</option>
                            @foreach($unidades as $unidad)
                                <option value="{{ $unidad->id }}">
                                    {{ $unidad->placa }} - {{ $unidad->marca }} {{ $unidad->modelo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Reporte</label>
                        <select wire:model="tipo" id="tipo" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="consumos">Consumos de Combustible</option>
                            <option value="despachos">Despachos de Combustible</option>
                        </select>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex space-x-4">
                    <button type="button" wire:click="generarPDF" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Generar PDF
                    </button>

                    <button type="button" wire:click="generarExcel" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Generar Excel
                    </button>
                </div>

                <!-- Información Adicional -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Información del Reporte</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li><strong>PDF:</strong> Reporte completo con gráficos y estadísticas detalladas</li>
                                    <li><strong>Excel:</strong> Datos en formato de hoja de cálculo para análisis posterior</li>
                                    <li><strong>Consumos:</strong> Incluye datos de carga, rendimiento y validaciones</li>
                                    <li><strong>Despachos:</strong> Información de proveedores, costos y volúmenes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
