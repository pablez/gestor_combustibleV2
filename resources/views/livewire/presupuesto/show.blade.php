<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Presupuesto') }}
            </h2>
            <div class="flex space-x-2">
                @can('presupuestos.editar')
                <a href="{{ route('presupuestos.edit', $presupuesto) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                @endcan

                <a href="{{ route('presupuestos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver
                </a>
            </div>
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
                <div class="p-6">
                    <!-- Header con estado -->
                    <div class="flex justify-between items-start mb-6 pb-4 border-b">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Presupuesto {{ $presupuesto->anio_fiscal }} - T{{ $presupuesto->trimestre }}
                            </h3>
                            <p class="text-lg text-gray-600">{{ $presupuesto->unidadOrganizacional?->nombre_unidad ?? 'N/A' }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                @if($presupuesto->activo)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Inactivo
                                    </span>
                                @endif
                                
                                @if($presupuesto->esta_cerca_limite)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        En Alerta
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones de estado -->
                        @can('presupuestos.editar')
                        <div class="flex space-x-2">
                            <button wire:click="toggleActivo" 
                                    onclick="return confirm('¿{{ $presupuesto->activo ? 'Desactivar' : 'Activar' }} este presupuesto?')"
                                    class="bg-{{ $presupuesto->activo ? 'red' : 'green' }}-500 hover:bg-{{ $presupuesto->activo ? 'red' : 'green' }}-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                @if($presupuesto->activo)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Desactivar
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Activar
                                @endif
                            </button>
                        </div>
                        @endcan
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Información General -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Información General</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Categoría Programática</dt>
                                    <dd class="text-sm text-gray-900">{{ $presupuesto->categoriaProgramatica?->descripcion ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fuente de Financiamiento</dt>
                                    <dd class="text-sm text-gray-900">{{ $presupuesto->fuenteOrganismoFinanciero?->descripcion ?? 'N/A' }}</dd>
                                </div>
                                @if($presupuesto->num_documento)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número de Documento</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $presupuesto->num_documento }}</dd>
                                </div>
                                @endif
                                @if($presupuesto->numero_comprobante)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número de Comprobante</dt>
                                    <dd class="text-sm text-gray-900 font-mono">{{ $presupuesto->numero_comprobante }}</dd>
                                </div>
                                @endif
                                @if($presupuesto->fecha_aprobacion)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Aprobación</dt>
                                    <dd class="text-sm text-gray-900">{{ $presupuesto->fecha_aprobacion->format('d/m/Y') }}</dd>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información Financiera -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Información Financiera</h4>
                            <div class="bg-blue-50 rounded-lg p-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Presupuesto Inicial</dt>
                                        <dd class="text-lg font-bold text-blue-900">${{ number_format($presupuesto->presupuesto_inicial, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Presupuesto Actual</dt>
                                        <dd class="text-lg font-bold text-blue-900">${{ number_format($presupuesto->presupuesto_actual, 2) }}</dd>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Total Gastado</dt>
                                        <dd class="text-lg font-bold text-red-900">${{ number_format($presupuesto->total_gastado, 2) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Total Comprometido</dt>
                                        <dd class="text-lg font-bold text-orange-900">${{ number_format($presupuesto->total_comprometido, 2) }}</dd>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-blue-200">
                                    <dt class="text-sm font-medium text-gray-500">Saldo Disponible</dt>
                                    <dd class="text-2xl font-bold {{ $presupuesto->saldo_disponible >= 0 ? 'text-green-900' : 'text-red-900' }}">
                                        ${{ number_format($presupuesto->saldo_disponible, 2) }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas de Ejecución -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Métricas de Ejecución</h4>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Porcentaje Ejecutado</dt>
                                    <dd class="text-3xl font-bold {{ $presupuesto->esta_cerca_limite ? 'text-red-900' : 'text-green-900' }}">
                                        {{ $presupuesto->porcentaje_ejecutado }}%
                                    </dd>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mt-2">
                                        <div class="bg-{{ $presupuesto->esta_cerca_limite ? 'red' : 'green' }}-600 h-3 rounded-full transition-all duration-300" 
                                             style="width: {{ min($presupuesto->porcentaje_ejecutado, 100) }}%"></div>
                                    </div>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Límite Preventivo</dt>
                                    <dd class="text-2xl font-bold text-orange-900">{{ $presupuesto->porcentaje_preventivo }}%</dd>
                                    <div class="text-xs text-gray-500 mt-1">Control interno</div>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Alerta de Sobregiro</dt>
                                    <dd class="text-2xl font-bold text-red-900">{{ $presupuesto->alerta_porcentaje }}%</dd>
                                    <div class="text-xs text-gray-500 mt-1">Límite máximo</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($presupuesto->observaciones)
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Observaciones</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $presupuesto->observaciones }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Información de auditoría -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-500">
                            <div>
                                <strong>Creado:</strong> {{ $presupuesto->created_at->format('d/m/Y H:i:s') }}
                            </div>
                            <div>
                                <strong>Última modificación:</strong> {{ $presupuesto->updated_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
