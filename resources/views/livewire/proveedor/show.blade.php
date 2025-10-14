<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ver Proveedor: ') . ($proveedor->nombre_comercial ?: $proveedor->nombre_proveedor) }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('proveedores.edit', $proveedor) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('proveedores.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                    <!-- Header con estado -->
                    <div class="flex justify-between items-start mb-6 pb-4 border-b">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $proveedor->nombre_comercial ?: $proveedor->nombre_proveedor }}
                            </h3>
                            <p class="text-lg text-gray-600">{{ $proveedor->nombre_proveedor }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $proveedor->tipoServicioProveedor->nombre ?? 'Sin tipo' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <button
                                wire:click="toggleStatus"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $proveedor->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                            >
                                {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                            <div class="flex items-center space-x-1">
                                @php
                                    $calificacionNumerica = ['D' => 1, 'C' => 2, 'B' => 3, 'A' => 4][$proveedor->calificacion] ?? 1;
                                @endphp
                                @for($i = 1; $i <= 4; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $calificacionNumerica ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $proveedor->calificacion }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Información General -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Información Legal</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIT/RUT</dt>
                                    <dd class="text-sm text-gray-900">{{ $proveedor->nit }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Razón Social</dt>
                                    <dd class="text-sm text-gray-900">{{ $proveedor->nombre_proveedor }}</dd>
                                </div>
                                @if($proveedor->nombre_comercial)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre Comercial</dt>
                                    <dd class="text-sm text-gray-900">{{ $proveedor->nombre_comercial }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo de Servicio</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $proveedor->tipoServicioProveedor->nombre ?? 'No especificado' }}
                                        @if($proveedor->tipoServicioProveedor && $proveedor->tipoServicioProveedor->descripcion)
                                            <br><span class="text-xs text-gray-500">{{ $proveedor->tipoServicioProveedor->descripcion }}</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h4>
                            <dl class="space-y-3">
                                @if($proveedor->telefono)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="tel:{{ $proveedor->telefono }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $proveedor->telefono }}
                                        </a>
                                    </dd>
                                </div>
                                @endif
                                @if($proveedor->email)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="mailto:{{ $proveedor->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $proveedor->email }}
                                        </a>
                                    </dd>
                                </div>
                                @endif
                                @if($proveedor->direccion)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                                    <dd class="text-sm text-gray-900">{{ $proveedor->direccion }}</dd>
                                </div>
                                @endif
                                @if($proveedor->contacto_principal)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contacto Principal</dt>
                                    <dd class="text-sm text-gray-900">{{ $proveedor->contacto_principal }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($proveedor->observaciones)
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Observaciones</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $proveedor->observaciones }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Información de Auditoría -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <strong>Creado:</strong> {{ $proveedor->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <strong>Última actualización:</strong> {{ $proveedor->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                        <button
                            wire:click="delete"
                            wire:confirm="¿Está seguro de eliminar este proveedor? Esta acción no se puede deshacer."
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            Eliminar Proveedor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
