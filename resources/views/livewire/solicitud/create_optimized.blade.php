<div>
    {{-- Estilos optimizados --}}
    <style>
        .form-select-custom {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            font-weight: 500;
        }
        
        .form-select-custom option {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            padding: 10px 16px !important;
            font-weight: 500;
        }
        
        .form-select-custom option:hover {
            background-color: #f8fafc !important;
        }
        
        .form-select-custom option:checked {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        
        .section-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .section-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .info-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
        }
        
        .info-value {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
    </style>

    {{-- Botón para abrir formulario --}}
    @if(!$mostrarFormulario)
        <button wire:click="toggleFormulario" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Solicitud
        </button>
    @endif

    {{-- Modal optimizado --}}
    @if($mostrarFormulario)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="toggleFormulario"></div>

                {{-- Modal Content --}}
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                    {{-- Header profesional --}}
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center" id="modal-title">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c0 .621-.504-1.125-1.125-1.125H18a2.25 2.25 0 01-2.25-2.25M8.25 8.25V6.108"/>
                                </svg>
                                Nueva Solicitud de Combustible
                            </h3>
                            <button wire:click="toggleFormulario" 
                                    class="text-white hover:text-gray-200 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Alertas --}}
                    @if(session()->has('success'))
                        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session()->has('error'))
                        <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Formulario optimizado --}}
                    <form wire:submit.prevent="crear" class="p-6">
                        
                        {{-- 1. Selección del Vehículo --}}
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-icon bg-blue-500 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">🚗 Selección del Vehículo</h4>
                            </div>
                            
                            <div>
                                <label for="id_unidad_transporte" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Vehículo <span class="text-red-500 ml-1">*</span>
                                    </div>
                                </label>
                                <select wire:model.live="id_unidad_transporte" id="id_unidad_transporte" 
                                        class="form-select-custom w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors @error('id_unidad_transporte') border-red-500 @enderror">
                                    <option value="">Selecciona un vehículo</option>
                                    @foreach($unidadesTransporte as $unidad)
                                        <option value="{{ $unidad->id }}">
                                            🚙 {{ $unidad->placa }}
                                            @if($unidad->marca && $unidad->modelo)
                                                | {{ $unidad->marca }} {{ $unidad->modelo }}
                                                @if($unidad->anio) ({{ $unidad->anio }}) @endif
                                            @endif
                                            @if(isset($unidad->tipoVehiculo) && $unidad->tipoVehiculo)
                                                | {{ $unidad->tipoVehiculo->nombre }}
                                            @endif
                                            @if(isset($unidad->capacidad_tanque) && $unidad->capacidad_tanque)
                                                | ⛽ {{ number_format($unidad->capacidad_tanque, 1) }}L
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_unidad_transporte')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Información del vehículo seleccionado --}}
                            @if($unidadSeleccionada)
                                <div class="info-grid">
                                    <div class="info-card">
                                        <div class="info-value text-blue-700">{{ $unidadSeleccionada->placa }}</div>
                                        <div class="info-label">{{ $unidadSeleccionada->marca }} {{ $unidadSeleccionada->modelo }}</div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-value text-green-700">{{ number_format($capacidadTanque, 1) }}L</div>
                                        <div class="info-label">Capacidad del Tanque</div>
                                    </div>
                                    <div class="info-card">
                                        <div class="info-value text-orange-700">{{ number_format($unidadSeleccionada->kilometraje_actual) }}</div>
                                        <div class="info-label">Kilometraje Actual</div>
                                    </div>
                                    @if($rendimientoPromedio > 0)
                                    <div class="info-card">
                                        <div class="info-value text-purple-700">{{ $rendimientoPromedio }}</div>
                                        <div class="info-label">km/L Promedio</div>
                                    </div>
                                    @endif
                                </div>
                                
                                {{-- Recomendaciones --}}
                                @if($capacidadTanque > 0)
                                    <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex items-center text-sm text-green-800">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            <strong>💡 Recomendado:</strong> {{ number_format($capacidadTanque * 0.8, 1) }}L
                                            @if($rendimientoPromedio > 0)
                                                | <strong>Autonomía:</strong> {{ number_format(($capacidadTanque * 0.8) * $rendimientoPromedio) }}km
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- 2. Solicitud de Combustible --}}
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-icon bg-green-500 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">⛽ Solicitud de Combustible</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cantidad_litros_solicitados" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Cantidad (Litros) <span class="text-red-500 ml-1">*</span>
                                        </div>
                                    </label>
                                    <input type="number" step="0.01" min="0.01" wire:model.live="cantidad_litros_solicitados" id="cantidad_litros_solicitados"
                                           placeholder="Ej: 50.00"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('cantidad_litros_solicitados') border-red-500 @enderror">
                                    @error('cantidad_litros_solicitados')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($consumoEstimado > 0)
                                        <div class="mt-2 bg-blue-50 border border-blue-200 rounded p-2">
                                            <div class="flex items-center text-sm text-blue-800">
                                                <span class="mr-2">📊</span>
                                                <strong>Estimado:</strong> {{ $consumoEstimado }}L para {{ $km_proyectado }}km
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label for="saldo_actual_combustible" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Saldo Actual (L)
                                        </div>
                                    </label>
                                    <input type="number" step="0.01" min="0" wire:model="saldo_actual_combustible" id="saldo_actual_combustible"
                                           placeholder="Combustible en tanque"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('saldo_actual_combustible') border-red-500 @enderror">
                                    @error('saldo_actual_combustible')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="motivo" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c0 .621-.504-1.125-1.125-1.125H18a2.25 2.25 0 01-2.25-2.25M8.25 8.25V6.108"/>
                                        </svg>
                                        Motivo de la Solicitud <span class="text-red-500 ml-1">*</span>
                                    </div>
                                </label>
                                <textarea wire:model="motivo" id="motivo" rows="4"
                                          placeholder="Describe el motivo de la solicitud de combustible..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('motivo') border-red-500 @enderror"></textarea>
                                @error('motivo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alertas --}}
                            @if(count($alertas) > 0)
                                <div class="mt-6 space-y-3">
                                    @foreach($alertas as $alerta)
                                        <div class="p-4 rounded-lg border-l-4 {{ $alerta['nivel'] === 'error' ? 'bg-red-50 border-red-400 text-red-800' : 'bg-yellow-50 border-yellow-400 text-yellow-800' }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    @if($alerta['nivel'] === 'error')
                                                        <span class="text-xl">❌</span>
                                                    @else
                                                        <span class="text-xl">⚠️</span>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium">{{ $alerta['mensaje'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Urgencia --}}
                            <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="urgente" 
                                           class="rounded border-orange-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="text-xl mr-2">🚨</span>
                                            <span class="font-semibold text-orange-800">Marcar como URGENTE</span>
                                        </div>
                                        <span class="text-sm text-orange-600">Esta solicitud requiere atención inmediata</span>
                                    </div>
                                </label>
                            </div>

                            @if($urgente)
                                <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                                    <label for="justificacion_urgencia" class="block text-sm font-semibold text-red-700 mb-3">
                                        <div class="flex items-center">
                                            <span class="text-xl mr-2">⚠️</span>
                                            Justificación de Urgencia
                                        </div>
                                    </label>
                                    <textarea wire:model="justificacion_urgencia" id="justificacion_urgencia" rows="3"
                                              placeholder="Explica por qué esta solicitud es urgente..."
                                              class="w-full px-4 py-3 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('justificacion_urgencia') border-red-500 @enderror"></textarea>
                                    @error('justificacion_urgencia')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- 3. Información Técnica --}}
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-icon bg-purple-500 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">📊 Información Técnica</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="km_actual" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Kilometraje Actual
                                        </div>
                                    </label>
                                    <input type="number" min="0" wire:model="km_actual" id="km_actual"
                                           placeholder="Kilometraje del vehículo"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('km_actual') border-red-500 @enderror">
                                    @error('km_actual')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="km_proyectado" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 01-8 0V6a4 4 0 018 0v1zM8 2v4m0 0V2"/>
                                            </svg>
                                            Kilometraje Proyectado
                                        </div>
                                    </label>
                                    <input type="number" min="0" wire:model.live="km_proyectado" id="km_proyectado"
                                           placeholder="Kilómetros a recorrer"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('km_proyectado') border-red-500 @enderror">
                                    @error('km_proyectado')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="rendimiento_estimado" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Rendimiento Estimado (km/L)
                                    </div>
                                </label>
                                <input type="number" step="0.01" min="0" wire:model.live="rendimiento_estimado" id="rendimiento_estimado"
                                       placeholder="Ej: 12.5"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('rendimiento_estimado') border-red-500 @enderror">
                                @error('rendimiento_estimado')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @if($rendimientoPromedio > 0)
                                    <div class="mt-2 bg-blue-50 border border-blue-200 rounded p-2">
                                        <div class="flex items-center text-sm text-blue-800">
                                            <span class="mr-2">📈</span>
                                            <strong>Histórico:</strong> {{ $rendimientoPromedio }} km/L
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- 4. Información Presupuestaria --}}
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-icon bg-emerald-500 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900">💰 Información Presupuestaria</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="id_cat_programatica" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Categoría Programática
                                        </div>
                                    </label>
                                    <select wire:model.live="id_cat_programatica" id="id_cat_programatica" 
                                            class="form-select-custom w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400 transition-colors">
                                        <option value="">Selecciona categoría (opcional)</option>
                                        @foreach($categoriasProgramaticas as $categoria)
                                            <option value="{{ $categoria->id }}">
                                                📊 {{ $categoria->codigo }}
                                                @if($categoria->descripcion)
                                                    | {{ $categoria->descripcion }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($presupuestoDisponible > 0)
                                        <div class="mt-2 bg-green-50 border border-green-200 rounded p-2">
                                            <div class="flex items-center text-sm text-green-800">
                                                <span class="mr-2">💵</span>
                                                <strong>Disponible:</strong> Bs. {{ number_format($presupuestoDisponible, 2) }}
                                                @if($costoEstimado > 0)
                                                    | <strong>Costo:</strong> Bs. {{ number_format($costoEstimado, 2) }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label for="id_fuente_org_fin" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Fuente de Financiamiento
                                        </div>
                                    </label>
                                    <select wire:model.live="id_fuente_org_fin" id="id_fuente_org_fin" 
                                            class="form-select-custom w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 hover:border-gray-400 transition-colors">
                                        <option value="">Selecciona fuente (opcional)</option>
                                        @foreach($fuentesOrganismo as $fuente)
                                            <option value="{{ $fuente->id }}">
                                                🏦 {{ $fuente->codigo }}
                                                @if($fuente->descripcion)
                                                    | {{ $fuente->descripcion }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Información resumida de selecciones --}}
                            @if($categoriaSeleccionada || $fuenteSeleccionada)
                                <div class="mt-6 bg-gradient-to-r from-purple-50 to-emerald-50 border border-gray-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($categoriaSeleccionada)
                                            <div class="bg-white rounded-lg p-3 border border-purple-100">
                                                <div class="flex items-center">
                                                    <span class="text-2xl mr-3">📊</span>
                                                    <div>
                                                        <p class="text-sm font-semibold text-purple-900">{{ $categoriaSeleccionada->codigo }}</p>
                                                        <p class="text-xs text-purple-700">{{ $categoriaSeleccionada->descripcion }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($fuenteSeleccionada)
                                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                                <div class="flex items-center">
                                                    <span class="text-2xl mr-3">🏦</span>
                                                    <div>
                                                        <p class="text-sm font-semibold text-emerald-900">{{ $fuenteSeleccionada->codigo }}</p>
                                                        <p class="text-xs text-emerald-700">{{ $fuenteSeleccionada->descripcion }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Botones optimizados --}}
                        <div class="flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                            <button type="button" wire:click="toggleFormulario" 
                                    class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Crear Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>