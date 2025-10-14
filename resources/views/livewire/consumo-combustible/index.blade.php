<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Consumos de Combustible') }}
            </h2>
            @can('consumos.crear')
            <a href="{{ route('consumos.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Consumo
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

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Filtros -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Búsqueda -->
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search" 
                                   id="search"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   placeholder="Ticket, lugar, placa, conductor...">
                        </div>

                        <!-- Filtro por Unidad -->
                        <div>
                            <label for="unidadFilter" class="block text-sm font-medium text-gray-700 mb-1">Unidad</label>
                            <select wire:model.live="unidadFilter" 
                                    id="unidadFilter"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todas</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->placa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro por Estado de Validación -->
                        <div>
                            <label for="validadoFilter" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select wire:model.live="validadoFilter" 
                                    id="validadoFilter"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="1">Validados</option>
                                <option value="0">Pendientes</option>
                            </select>
                        </div>

                        <!-- Filtro por Tipo de Carga -->
                        <div>
                            <label for="tipoCargaFilter" class="block text-sm font-medium text-gray-700 mb-1">Tipo Carga</label>
                            <select wire:model.live="tipoCargaFilter" 
                                    id="tipoCargaFilter"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach($tiposCarga as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha Desde -->
                        <div>
                            <label for="fechaDesde" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                            <input type="date" 
                                   wire:model.live="fechaDesde" 
                                   id="fechaDesde"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Fecha Hasta -->
                        <div>
                            <label for="fechaHasta" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                            <input type="date" 
                                   wire:model.live="fechaHasta" 
                                   id="fechaHasta"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Botones de filtro -->
                    <div class="flex justify-between items-center mt-4">
                        <button wire:click="limpiarFiltros" 
                                class="text-sm text-gray-600 hover:text-gray-900">
                            Limpiar filtros
                        </button>
                        <div class="flex items-center space-x-2">
                            <label for="perPage" class="text-sm text-gray-700">Mostrar:</label>
                            <select wire:model.live="perPage" 
                                    id="perPage"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha/Ticket
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unidad/Conductor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Consumo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kilometraje
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
                            @forelse($consumos as $consumo)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $consumo->fecha_registro ? $consumo->fecha_registro->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Ticket: {{ $consumo->numero_ticket ?: 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $consumo->lugar_carga }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $consumo->unidadTransporte?->placa ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $consumo->conductor?->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($consumo->litros_cargados, 1) }} L
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $consumo->tipo_carga }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($consumo->kilometraje_inicial, 0) }} - {{ number_format($consumo->kilometraje_fin, 0) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ number_format($consumo->kilometros_recorridos, 0) }} km
                                            @if($consumo->rendimiento > 0)
                                                • {{ $consumo->rendimiento }} km/L
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($consumo->validado)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Validado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @can('consumos.ver')
                                        <a href="{{ route('consumos.show', $consumo) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                        @endcan

                                        @can('consumos.editar')
                                        <a href="{{ route('consumos.edit', $consumo) }}" 
                                           class="text-blue-600 hover:text-blue-900">Editar</a>
                                        @endcan

                                        @if(!$consumo->validado)
                                            @can('consumos.validar')
                                            <button wire:click="validar({{ $consumo->id }})" 
                                                    class="text-green-600 hover:text-green-900"
                                                    onclick="return confirm('¿Validar este consumo?')">
                                                Validar
                                            </button>
                                            @endcan
                                        @else
                                            @can('consumos.validar')
                                            <button wire:click="invalidar({{ $consumo->id }})" 
                                                    class="text-orange-600 hover:text-orange-900"
                                                    onclick="return confirm('¿Invalidar este consumo?')">
                                                Invalidar
                                            </button>
                                            @endcan
                                        @endif

                                        @can('consumos.eliminar')
                                        <button wire:click="delete({{ $consumo->id }})" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('¿Estás seguro de eliminar este consumo?')">
                                            Eliminar
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron consumos de combustible.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($consumos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $consumos->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
