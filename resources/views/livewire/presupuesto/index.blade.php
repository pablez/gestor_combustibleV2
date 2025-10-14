<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Presupuestos') }}
            </h2>
            @can('presupuestos.crear')
            <a href="{{ route('presupuestos.create') }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Presupuesto
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alertas -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Filtros -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Búsqueda -->
                        <div>
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                   placeholder="Buscar por documento, comprobante..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Filtro Unidad -->
                        <div>
                            <select wire:model.live="unidadFilter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todas las unidades</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->nombre_unidad }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Categoría -->
                        <div>
                            <select wire:model.live="categoriaFilter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Estado -->
                        <div>
                            <select wire:model.live="estadoFilter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="todos">Todos los estados</option>
                                <option value="activos">Activos</option>
                                <option value="inactivos">Inactivos</option>
                                <option value="alerta">En alerta</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Filtro Fuente -->
                        <div>
                            <select wire:model.live="fuenteFilter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todas las fuentes</option>
                                @foreach($fuentes as $fuente)
                                    <option value="{{ $fuente->id }}">{{ $fuente->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Año -->
                        <div>
                            <select wire:model.live="anioFilter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todos los años</option>
                                @foreach($anios as $anio)
                                    <option value="{{ $anio }}">{{ $anio }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro Trimestre -->
                        <div>
                            <select wire:model.live="trimestreFilter" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todos los trimestres</option>
                                <option value="1">Q1</option>
                                <option value="2">Q2</option>
                                <option value="3">Q3</option>
                                <option value="4">Q4</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <button wire:click="clearFilters" 
                                class="text-sm text-gray-500 hover:text-gray-700">
                            Limpiar filtros
                        </button>
                        <div class="text-sm text-gray-500">
                            {{ $presupuestos->total() }} presupuesto(s) encontrado(s)
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortBy('anio_fiscal')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Año/Trimestre
                                        @if($sortBy === 'anio_fiscal')
                                            <svg class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unidad/Categoría
                                </th>
                                <th wire:click="sortBy('presupuesto_inicial')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center">
                                        Presupuesto
                                        @if($sortBy === 'presupuesto_inicial')
                                            <svg class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ejecución
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($presupuestos as $presupuesto)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $presupuesto->anio_fiscal }}</div>
                                        <div class="text-sm text-gray-500">T{{ $presupuesto->trimestre }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $presupuesto->unidadOrganizacional?->nombre_unidad ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ Str::limit($presupuesto->categoriaProgramatica?->descripcion ?? 'N/A', 30) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            ${{ number_format($presupuesto->presupuesto_inicial, 2) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Actual: ${{ number_format($presupuesto->presupuesto_actual, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            ${{ number_format($presupuesto->total_gastado, 2) }}
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-{{ $presupuesto->esta_cerca_limite ? 'red' : 'blue' }}-600 h-2 rounded-full" 
                                                 style="width: {{ min($presupuesto->porcentaje_ejecutado, 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $presupuesto->porcentaje_ejecutado }}%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $presupuesto->saldo_disponible >= 0 ? 'text-green-900' : 'text-red-900' }}">
                                            ${{ number_format($presupuesto->saldo_disponible, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($presupuesto->activo)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Activo
                                                </span>
                                                @if($presupuesto->esta_cerca_limite)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Alerta
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactivo
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @can('presupuestos.ver')
                                            <a href="{{ route('presupuestos.show', $presupuesto) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                            @endcan
                                            
                                            @can('presupuestos.editar')
                                            <a href="{{ route('presupuestos.edit', $presupuesto) }}" 
                                               class="text-blue-600 hover:text-blue-900">Editar</a>
                                            @endcan
                                            
                                            @can('presupuestos.editar')
                                            <button wire:click="toggleActivo({{ $presupuesto->id }})" 
                                                    class="text-{{ $presupuesto->activo ? 'red' : 'green' }}-600 hover:text-{{ $presupuesto->activo ? 'red' : 'green' }}-900">
                                                {{ $presupuesto->activo ? 'Desactivar' : 'Activar' }}
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron presupuestos con los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($presupuestos->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">
                        {{ $presupuestos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
