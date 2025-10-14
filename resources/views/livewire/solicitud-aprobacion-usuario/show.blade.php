<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                                Solicitud de Aprobación #{{ $solicitud->id }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Creada el {{ $solicitud->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        
                        <!-- Estado Badge -->
                        <div class="mt-4 sm:mt-0">
                            @if($solicitud->estado_solicitud === 'pendiente')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Pendiente
                                </span>
                            @elseif($solicitud->estado_solicitud === 'aprobada')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Aprobada
                                </span>
                            @elseif($solicitud->estado_solicitud === 'rechazada')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Rechazada
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Información Principal -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Información del Usuario -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información del Usuario
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Nombre:</span>
                                    <p class="text-sm text-gray-900">{{ $solicitud->usuario->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Email:</span>
                                    <p class="text-sm text-gray-900">{{ $solicitud->usuario->email }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Rol Actual:</span>
                                    <p class="text-sm text-gray-900">
                                        @if($solicitud->usuario->roles->isNotEmpty())
                                            {{ $solicitud->usuario->roles->pluck('name')->join(', ') }}
                                        @else
                                            Sin rol asignado
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles de la Solicitud -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Detalles de la Solicitud
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Tipo:</span>
                                    <p class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $solicitud->tipo_solicitud) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Rol Solicitado:</span>
                                    <p class="text-sm text-gray-900">{{ $solicitud->rol_solicitado }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Supervisor Asignado:</span>
                                    <p class="text-sm text-gray-900">{{ $solicitud->supervisorAsignado->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Justificación -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.013 8.013 0 01-2.2-.305c-1.798.87-3.908 1.305-6.8 1.305L4 21l1.535-1.535C3.85 18.15 3 15.636 3 12a8 8 0 018-8s0 0 0 0 8 3.582 8 8z"></path>
                            </svg>
                            Justificación
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $solicitud->justificacion }}</p>
                        </div>
                    </div>

                    <!-- Información de Aprobación/Rechazo -->
                    @if($solicitud->estado_solicitud !== 'pendiente')
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($solicitud->estado_solicitud === 'aprobada')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @endif
                                </svg>
                                {{ $solicitud->estado_solicitud === 'aprobada' ? 'Información de Aprobación' : 'Información de Rechazo' }}
                            </h3>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">
                                            {{ $solicitud->estado_solicitud === 'aprobada' ? 'Aprobada por:' : 'Rechazada por:' }}
                                        </span>
                                        <p class="text-sm text-gray-900">
                                            {{ $solicitud->procesadoPor ? $solicitud->procesadoPor->name : 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Fecha:</span>
                                        <p class="text-sm text-gray-900">
                                            {{ $solicitud->fecha_proceso ? $solicitud->fecha_proceso->format('d/m/Y H:i') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @if($solicitud->observaciones_aprobacion)
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Observaciones:</span>
                                        <p class="text-sm text-gray-900 mt-1 whitespace-pre-wrap">{{ $solicitud->observaciones_aprobacion }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Historial de Cambios -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Historial
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    <span class="text-gray-600">Solicitud creada el</span>
                                    <strong class="ml-1">{{ $solicitud->created_at->format('d/m/Y H:i') }}</strong>
                                </div>
                                @if($solicitud->updated_at != $solicitud->created_at)
                                    <div class="flex items-center text-sm">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                                        <span class="text-gray-600">Última actualización el</span>
                                        <strong class="ml-1">{{ $solicitud->updated_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                @endif
                                @if($solicitud->fecha_proceso)
                                    <div class="flex items-center text-sm">
                                        <span class="w-2 h-2 bg-{{ $solicitud->estado_solicitud === 'aprobada' ? 'green' : 'red' }}-500 rounded-full mr-3"></span>
                                        <span class="text-gray-600">{{ $solicitud->estado_solicitud === 'aprobada' ? 'Aprobada' : 'Rechazada' }} el</span>
                                        <strong class="ml-1">{{ $solicitud->fecha_proceso->format('d/m/Y H:i') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                        <button wire:click="volver" type="button"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver al Listado
                        </button>

                        @if($solicitud->estado_solicitud === 'pendiente' && auth()->user()->can('solicitudes_aprobacion.procesar'))
                            <div class="flex space-x-3">
                                <button wire:click="rechazar" type="button"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Rechazar
                                </button>
                                
                                <button wire:click="aprobar" type="button"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Aprobar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
