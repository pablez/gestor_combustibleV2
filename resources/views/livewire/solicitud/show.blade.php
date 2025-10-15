<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Solicitud de Combustible</h2>
                    <p class="text-gray-600">{{ $solicitud->numero_solicitud }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($solicitud->estado_solicitud === 'Pendiente') bg-yellow-100 text-yellow-800
                        @elseif($solicitud->estado_solicitud === 'Aprobada') bg-green-100 text-green-800
                        @elseif($solicitud->estado_solicitud === 'Rechazada') bg-red-100 text-red-800
                        @elseif($solicitud->estado_solicitud === 'En_Proceso') bg-blue-100 text-blue-800
                        @elseif($solicitud->estado_solicitud === 'Completada') bg-gray-100 text-gray-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ str_replace('_', ' ', $solicitud->estado_solicitud) }}
                    </span>
                    @if($solicitud->urgente)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                            🚨 Urgente
                        </span>
                    @endif
                    <a href="{{ route('solicitudes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                        ← Volver al listado
                    </a>
                </div>
            </div>

            {{-- Alertas --}}
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Información Principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Solicitud</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Número de Solicitud</dt>
                            <dd class="text-sm text-gray-900 font-mono">{{ $solicitud->numero_solicitud }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Solicitante</dt>
                            <dd class="text-sm text-gray-900">{{ $solicitud->solicitante?->name ?? 'No disponible' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unidad de Transporte</dt>
                            <dd class="text-sm text-gray-900">{{ $solicitud->unidadTransporte?->placa ?? 'No asignada' }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Solicitud</dt>
                            <dd class="text-sm text-gray-900">{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cantidad Solicitada</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ number_format($solicitud->cantidad_litros_solicitados, 2) }} litros</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estado y Aprobación</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado Actual</dt>
                            <dd class="text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($solicitud->estado_solicitud === 'Pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($solicitud->estado_solicitud === 'Aprobada') bg-green-100 text-green-800
                                    @elseif($solicitud->estado_solicitud === 'Rechazada') bg-red-100 text-red-800
                                    @elseif($solicitud->estado_solicitud === 'En_Proceso') bg-blue-100 text-blue-800
                                    @elseif($solicitud->estado_solicitud === 'Completada') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', $solicitud->estado_solicitud) }}
                                </span>
                            </dd>
                        </div>
                        
                        @if($solicitud->aprobador)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Aprobado por</dt>
                                <dd class="text-sm text-gray-900">{{ $solicitud->aprobador->name }}</dd>
                            </div>
                        @endif
                        
                        @if($solicitud->fecha_aprobacion)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Aprobación</dt>
                                <dd class="text-sm text-gray-900">{{ $solicitud->fecha_aprobacion->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                        
                        @if($solicitud->observaciones_aprobacion)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Observaciones</dt>
                                <dd class="text-sm text-gray-900">{{ $solicitud->observaciones_aprobacion }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Detalles Adicionales --}}
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detalles</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Motivo de la Solicitud</h4>
                        <p class="text-sm text-gray-900 bg-white p-3 rounded border">{{ $solicitud->motivo }}</p>
                        
                        @if($solicitud->urgente && $solicitud->justificacion_urgencia)
                            <h4 class="text-md font-medium text-red-700 mb-3 mt-4">Justificación de Urgencia</h4>
                            <p class="text-sm text-red-900 bg-red-50 p-3 rounded border border-red-200">{{ $solicitud->justificacion_urgencia }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Información Técnica</h4>
                        <dl class="space-y-2">
                            @if($solicitud->km_actual)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Kilometraje Actual:</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($solicitud->km_actual) }} km</dd>
                                </div>
                            @endif
                            
                            @if($solicitud->km_proyectado)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Kilometraje Proyectado:</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($solicitud->km_proyectado) }} km</dd>
                                </div>
                            @endif
                            
                            @if($solicitud->rendimiento_estimado)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Rendimiento Estimado:</dt>
                                    <dd class="text-sm text-gray-900">{{ $solicitud->rendimiento_estimado }} km/L</dd>
                                </div>
                            @endif
                            
                            @if($solicitud->saldo_actual_combustible)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Saldo Actual:</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($solicitud->saldo_actual_combustible, 2) }} L</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            @if($solicitud->estado_solicitud === 'Pendiente' && auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']))
                <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div>
                        <h4 class="text-md font-medium text-yellow-800">Solicitud Pendiente de Aprobación</h4>
                        <p class="text-sm text-yellow-700">Esta solicitud requiere aprobación para continuar con el proceso.</p>
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="aprobar" 
                                onclick="return confirm('¿Estás seguro de aprobar esta solicitud?')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                            ✓ Aprobar
                        </button>
                        <button wire:click="rechazar" 
                                onclick="return confirm('¿Estás seguro de rechazar esta solicitud?')"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                            ✗ Rechazar
                        </button>
                    </div>
                </div>
            @endif

            {{-- Botón de Edición --}}
            @if($solicitud->estado_solicitud === 'Pendiente' && (auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']) || $solicitud->id_usuario_solicitante === auth()->id()))
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('solicitudes.edit', $solicitud->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors">
                        ✏️ Editar Solicitud
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
