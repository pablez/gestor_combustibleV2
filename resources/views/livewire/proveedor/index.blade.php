<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proveedores') }}
        </h2>
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
                <div class="p-6">
                    <!-- Header con filtros y botón crear -->
                    <div class="mb-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Buscador -->
                            <div class="flex-1 max-w-md">
                                <input
                                    type="text"
                                    wire:model.live="search"
                                    placeholder="Buscar proveedores..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            
                            <!-- Filtros -->
                            <div class="flex flex-wrap gap-3">
                                <select wire:model.live="filterTipoServicio" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos los tipos</option>
                                    @foreach($tiposServicio as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                                
                                <select wire:model.live="filterActivo" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos los estados</option>
                                    <option value="1">Activos</option>
                                    <option value="0">Inactivos</option>
                                </select>
                                
                                @if($search || $filterTipoServicio || $filterActivo !== '')
                                    <button
                                        wire:click="clearFilters"
                                        class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                    >
                                        Limpiar
                                    </button>
                                @endif
                            </div>

                            <!-- Botón crear -->
                            <a
                                href="{{ route('proveedores.create') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center whitespace-nowrap"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nuevo Proveedor
                            </a>
                        </div>
                    </div>

                    <!-- Tabla para pantallas grandes -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th wire:click="sortBy('nombre_comercial')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        Nombre Comercial
                                        @if($sortBy === 'nombre_comercial')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('nombre_proveedor')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                        Nombre Proveedor
                                        @if($sortBy === 'nombre_proveedor')
                                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIT/RUT
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo Servicio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contacto
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($proveedores as $proveedor)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $proveedor->nombre_comercial }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $proveedor->nombre_proveedor }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $proveedor->nit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $proveedor->tipoServicioProveedor->nombre ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ $proveedor->telefono }}</div>
                                            <div class="text-gray-500">{{ $proveedor->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button
                                                wire:click="toggleStatus({{ $proveedor->id }})"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $proveedor->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                            >
                                                {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-2">
                                                <a
                                                    href="{{ route('proveedores.show', $proveedor) }}"
                                                    class="text-blue-600 hover:text-blue-900 font-medium"
                                                    title="Ver"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a
                                                    href="{{ route('proveedores.edit', $proveedor) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 font-medium"
                                                    title="Editar"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <button
                                                    wire:click="delete({{ $proveedor->id }})"
                                                    wire:confirm="¿Está seguro de eliminar este proveedor?"
                                                    class="text-red-600 hover:text-red-900 font-medium"
                                                    title="Eliminar"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No se encontraron proveedores.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista de tarjetas para móviles -->
                    <div class="lg:hidden">
                        <div class="grid gap-4">
                            @forelse($proveedores as $proveedor)
                                <div class="bg-gray-50 rounded-lg p-4 border">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900">{{ $proveedor->nombre_comercial ?: $proveedor->nombre_proveedor }}</h3>
                                            <p class="text-sm text-gray-600">{{ $proveedor->nombre_proveedor }}</p>
                                            <p class="text-sm text-gray-500">NIT: {{ $proveedor->nit }}</p>
                                        </div>
                                        <button
                                            wire:click="toggleStatus({{ $proveedor->id }})"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $proveedor->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                        >
                                            {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                                        </button>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $proveedor->tipoServicioProveedor->nombre ?? 'N/A' }}
                                        </span>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 mb-3">
                                        <div>{{ $proveedor->telefono }}</div>
                                        <div>{{ $proveedor->email }}</div>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-2">
                                        <a
                                            href="{{ route('proveedores.show', $proveedor) }}"
                                            class="text-blue-600 hover:text-blue-900 font-medium"
                                            title="Ver"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a
                                            href="{{ route('proveedores.edit', $proveedor) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium"
                                            title="Editar"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button
                                            wire:click="delete({{ $proveedor->id }})"
                                            wire:confirm="¿Está seguro de eliminar este proveedor?"
                                            class="text-red-600 hover:text-red-900 font-medium"
                                            title="Eliminar"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    No se encontraron proveedores.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $proveedores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
