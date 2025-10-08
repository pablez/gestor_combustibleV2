<div class="py-12">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tipos de Vehículos</h2>
                <p class="text-gray-600 dark:text-gray-400">Gestiona los tipos de vehículos del sistema</p>
            </div>
            
            {{-- Botón Nuevo Tipo en el header --}}
            <div class="flex items-center gap-3">
                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'tipo-vehiculo-create' }))" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Tipo
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Tarjetas de estadísticas rápidas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tipos</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $estadisticas['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Activos</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $estadisticas['activos'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6H5a1 1 0 110-2h2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Categorías</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $estadisticas['categorias'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En Uso</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $estadisticas['enUso'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filtros mejorados --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Filtros de Búsqueda</h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    {{-- Búsqueda --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input wire:model.live="search" 
                                   type="text" 
                                   placeholder="Buscar por nombre o descripción..."
                                   class="pl-10 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    {{-- Filtro por categoría --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
                        <select wire:model.live="categoria" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Elementos por página --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Por página</label>
                        <select wire:model.live="perPage" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    {{-- Mostrar inactivos --}}
                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input wire:model.live="mostrarInactivos" 
                                   type="checkbox" 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mostrar inactivos</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Tabla mejorada --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Lista de Tipos de Vehículos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $tiposVehiculo->count() }} de {{ $tiposVehiculo->total() }} tipos mostrados</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th wire:click="sortBy('nombre')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <div class="flex items-center gap-1">
                                        Nombre
                                        @if($sortBy === 'nombre')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                @else
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('categoria')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <div class="flex items-center gap-1">
                                        Categoría
                                        @if($sortBy === 'categoria')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                @else
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Consumo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Capacidades</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tiposVehiculo as $tipo)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br 
                                                    @if($tipo->categoria === 'Liviano') from-green-400 to-green-600
                                                    @elseif($tipo->categoria === 'Pesado') from-red-400 to-red-600
                                                    @elseif($tipo->categoria === 'Motocicleta') from-blue-400 to-blue-600
                                                    @elseif($tipo->categoria === 'Especializado') from-purple-400 to-purple-600
                                                    @else from-gray-400 to-gray-600 @endif 
                                                    flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($tipo->categoria === 'Liviano')
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        @elseif($tipo->categoria === 'Pesado')
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                        @elseif($tipo->categoria === 'Motocicleta')
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                        @endif
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $tipo->nombre }}</div>
                                                @if($tipo->descripcion)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($tipo->descripcion, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($tipo->categoria === 'Liviano') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($tipo->categoria === 'Pesado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($tipo->categoria === 'Motocicleta') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($tipo->categoria === 'Especializado') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            {{ $tipo->categoria }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            @if($tipo->consumo_promedio_ciudad || $tipo->consumo_promedio_carretera)
                                                <div class="space-y-1">
                                                    @if($tipo->consumo_promedio_ciudad)
                                                        <div class="flex items-center text-xs">
                                                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                            Ciudad: {{ number_format($tipo->consumo_promedio_ciudad, 1) }}L
                                                        </div>
                                                    @endif
                                                    @if($tipo->consumo_promedio_carretera)
                                                        <div class="flex items-center text-xs">
                                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                            Carretera: {{ number_format($tipo->consumo_promedio_carretera, 1) }}L
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">Sin datos</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white space-y-1">
                                            @if($tipo->capacidad_carga_kg)
                                                <div class="flex items-center text-xs">
                                                    <svg class="w-3 h-3 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    {{ number_format($tipo->capacidad_carga_kg) }} kg
                                                </div>
                                            @endif
                                            @if($tipo->numero_pasajeros)
                                                <div class="flex items-center text-xs">
                                                    <svg class="w-3 h-3 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                                    </svg>
                                                    {{ $tipo->numero_pasajeros }} personas
                                                </div>
                                            @endif
                                            @if(!$tipo->capacidad_carga_kg && !$tipo->numero_pasajeros)
                                                <span class="text-gray-400">Sin especificar</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button wire:click="toggleActivo({{ $tipo->id }})" 
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105
                                                @if($tipo->activo) bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 shadow-green-500/25
                                                @else bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 shadow-red-500/25 @endif shadow-lg">
                                            @if($tipo->activo)
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Activo
                                            @else
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Inactivo
                                            @endif
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="editarTipo({{ $tipo->id }})"
                                                    class="inline-flex items-center p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all duration-200 transform hover:scale-110"
                                                    title="Editar tipo de vehículo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button wire:click="delete({{ $tipo->id }})" 
                                                    wire:confirm="¿Estás seguro de eliminar este tipo de vehículo?"
                                                    class="inline-flex items-center p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200 transform hover:scale-110"
                                                    title="Eliminar tipo de vehículo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No hay tipos de vehículos</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Comienza creando tu primer tipo de vehículo.</p>
                                            <button wire:click="$dispatch('openModal', { component: 'tipo-vehiculo.create' })" 
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                                Crear Primer Tipo
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación mejorada --}}
                @if($tiposVehiculo->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Mostrando {{ $tiposVehiculo->firstItem() }} a {{ $tiposVehiculo->lastItem() }} de {{ $tiposVehiculo->total() }} resultados
                            </div>
                            <div>
                                {{ $tiposVehiculo->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modales flotantes --}}
    {{-- Modal para crear tipo de vehículo --}}
    <x-livewire-modal name="tipo-vehiculo-create" max-width="4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="px-8 py-6">
                @livewire('tipo-vehiculo.create', key('create-modal'))
            </div>
        </div>
    </x-livewire-modal>

    {{-- Modal para editar tipo de vehículo --}}
    <x-livewire-modal name="tipo-vehiculo-edit" max-width="4xl">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="px-8 py-6">
                @if($editingTipo)
                    @livewire('tipo-vehiculo.edit', ['tipoVehiculo' => $editingTipo], key('edit-modal-' . $editingTipo))
                @endif
            </div>
        </div>
    </x-livewire-modal>

    {{-- Scripts para manejo de modales --}}
    @script
    <script>
        // Función para editar tipo
        window.editarTipo = function(tipoId) {
            console.log('Editando tipo ID:', tipoId);
            $wire.set('editingTipo', tipoId);
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'tipo-vehiculo-edit'
                }));
            }, 100);
        };

        // Escuchar eventos de Livewire para abrir modales
        $wire.on('openModal', (event) => {
            console.log('Evento openModal recibido:', event);
            
            if (event.component === 'tipo-vehiculo.create') {
                console.log('Abriendo modal de creación');
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'tipo-vehiculo-create'
                }));
            } else if (event.component === 'tipo-vehiculo.edit') {
                console.log('Abriendo modal de edición');
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'tipo-vehiculo-edit'
                }));
            }
        });

        // Escuchar eventos para cerrar modales
        $wire.on('closeModal', () => {
            console.log('Cerrando modales');
            window.dispatchEvent(new CustomEvent('close-modal', {
                detail: 'tipo-vehiculo-create'
            }));
            window.dispatchEvent(new CustomEvent('close-modal', {
                detail: 'tipo-vehiculo-edit'
            }));
        });

        // Escuchar cuando se guarda un tipo de vehículo
        $wire.on('tipoVehiculoSaved', () => {
            console.log('Tipo de vehículo guardado');
            $wire.$refresh();
        });

        // Escuchar cuando se actualiza un tipo de vehículo
        $wire.on('tipoVehiculoUpdated', () => {
            console.log('Tipo de vehículo actualizado');
            $wire.$refresh();
        });
    </script>
    @endscript
</div>
