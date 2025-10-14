<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Despachos de Combustible') }}
            </h2>
            @can('despachos.crear')
            <a href="{{ route('despachos.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Despacho
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Búsqueda -->
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search" 
                                   id="search"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   placeholder="Vale, factura, ubicación, proveedor, placa...">
                        </div>

                        <!-- Filtro por Proveedor -->
                        <div>
                            <label for="proveedorFilter" class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                            <select wire:model.live="proveedorFilter" 
                                    id="proveedorFilter"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">
                                        {{ $proveedor->nombre_comercial ?: $proveedor->nombre_proveedor }}
                                    </option>
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
                                <option value="0">No Validados</option>
                            </select>
                        </div>

                        <!-- Botón limpiar filtros -->
                        <div class="flex items-end">
                            <button wire:click="limpiarFiltros" 
                                    class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Filtros de fecha -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="fechaDesde" class="block text-sm font-medium text-gray-700 mb-1">Fecha Desde</label>
                            <input type="date" 
                                   wire:model.live="fechaDesde" 
                                   id="fechaDesde"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="fechaHasta" class="block text-sm font-medium text-gray-700 mb-1">Fecha Hasta</label>
                            <input type="date" 
                                   wire:model.live="fechaHasta" 
                                   id="fechaHasta"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha / Vale
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vehículo / Proveedor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Combustible
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
                            @forelse($despachos as $despacho)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $despacho->fecha_despacho ? $despacho->fecha_despacho->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Vale: {{ $despacho->numero_vale ?: 'N/A' }}
                                        </div>
                                        @if($despacho->numero_factura)
                                        <div class="text-xs text-gray-400">
                                            Factura: {{ $despacho->numero_factura }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $despacho->solicitud?->unidadTransporte?->placa ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $despacho->proveedor?->nombre_comercial ?: $despacho->proveedor?->nombre_proveedor }}
                                        </div>
                                        @if($despacho->ubicacion_despacho)
                                        <div class="text-xs text-gray-400">
                                            {{ $despacho->ubicacion_despacho }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format($despacho->litros_despachados, 1) }} L
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ${{ number_format($despacho->precio_por_litro, 0) }}/L
                                        </div>
                                        <div class="text-sm font-medium text-green-600">
                                            ${{ number_format($despacho->costo_total, 0) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($despacho->validado)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Validado
                                            </span>
                                            @if($despacho->validador)
                                            <div class="text-xs text-gray-400 mt-1">
                                                por {{ $despacho->validador->nombre }}
                                            </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @can('despachos.ver')
                                            <a href="{{ route('despachos.show', $despacho) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" title="Ver">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @endcan

                                            @can('despachos.editar')
                                            <a href="{{ route('despachos.edit', $despacho) }}" 
                                               class="text-blue-600 hover:text-blue-900" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @endcan

                                            @can('despachos.validar')
                                            @if(!$despacho->validado)
                                            <button wire:click="validar({{ $despacho->id }})"
                                                    wire:confirm="¿Confirmar validación de este despacho?"
                                                    class="text-green-600 hover:text-green-900" title="Validar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                            @else
                                            <button wire:click="invalidar({{ $despacho->id }})"
                                                    wire:confirm="¿Quitar validación de este despacho?"
                                                    class="text-orange-600 hover:text-orange-900" title="Invalidar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                </svg>
                                            </button>
                                            @endif
                                            @endcan

                                            @can('despachos.eliminar')
                                            <button wire:click="delete({{ $despacho->id }})"
                                                    wire:confirm="¿Está seguro de eliminar este despacho? Esta acción no se puede deshacer."
                                                    class="text-red-600 hover:text-red-900" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        @if($search || $proveedorFilter || $validadoFilter !== '' || $fechaDesde || $fechaHasta)
                                            No se encontraron despachos que coincidan con los filtros aplicados.
                                        @else
                                            No hay despachos registrados.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($despachos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $despachos->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
