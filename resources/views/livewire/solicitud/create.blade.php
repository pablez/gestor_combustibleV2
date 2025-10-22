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

                            {{-- Información detallada del vehículo seleccionado --}}
                            @if($unidadSeleccionada)
                                <div class="mt-6 bg-gradient-to-r from-blue-50 to-cyan-100 border-l-4 border-blue-500 rounded-lg p-5">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-blue-500 rounded-full p-2">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h5 class="text-lg font-bold text-blue-900 mb-3">🚗 Unidad de Transporte Seleccionada</h5>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                {{-- Información básica --}}
                                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                                    <div class="text-xs text-blue-600 font-medium uppercase mb-1">Placa</div>
                                                    <div class="text-blue-900 font-bold text-lg">{{ $unidadSeleccionada->placa }}</div>
                                                </div>
                                                
                                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                                    <div class="text-xs text-blue-600 font-medium uppercase mb-1">Marca y Modelo</div>
                                                    <div class="text-blue-900 font-semibold">{{ $unidadSeleccionada->marca }} {{ $unidadSeleccionada->modelo ?? '' }}</div>
                                                </div>
                                                
                                                <div class="bg-white rounded-lg p-3 border border-blue-200">
                                                    <div class="text-xs text-blue-600 font-medium uppercase mb-1">Kilometraje Actual</div>
                                                    <div class="text-blue-900 font-semibold">{{ number_format($unidadSeleccionada->kilometraje_actual) }} km</div>
                                                </div>
                                            </div>
                                            
                                            {{-- Información de combustible y rendimiento --}}
                                            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
                                                <div class="text-xs text-amber-600 font-medium uppercase mb-2">⛽ Información de Combustible</div>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div class="text-center">
                                                        <div class="text-2xl font-bold text-amber-900">{{ number_format($capacidadTanque, 1) }}</div>
                                                        <div class="text-xs text-amber-700">Litros</div>
                                                        <div class="text-xs text-amber-600">Capacidad del tanque</div>
                                                    </div>
                                                    
                                                    @if($rendimientoPromedio > 0)
                                                    <div class="text-center">
                                                        <div class="text-2xl font-bold text-amber-900">{{ $rendimientoPromedio }}</div>
                                                        <div class="text-xs text-amber-700">km/L</div>
                                                        <div class="text-xs text-amber-600">Rendimiento promedio</div>
                                                    </div>
                                                    
                                                    <div class="text-center">
                                                        <div class="text-2xl font-bold text-amber-900">{{ number_format(($capacidadTanque * 0.8) * $rendimientoPromedio, 0) }}</div>
                                                        <div class="text-xs text-amber-700">km aprox.</div>
                                                        <div class="text-xs text-amber-600">Autonomía estimada</div>
                                                    </div>
                                                    @else
                                                    <div class="text-center col-span-2">
                                                        <div class="text-lg text-amber-700">Sin datos de rendimiento</div>
                                                        <div class="text-xs text-amber-600">Se calculará con los consumos registrados</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            {{-- Recomendaciones de combustible --}}
                                            @if($capacidadTanque > 0)
                                                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                                                    <div class="text-xs text-green-600 font-medium uppercase mb-2">💡 Recomendaciones de Carga</div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="bg-white rounded-lg p-3 border border-green-100">
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-green-700 font-medium">Carga recomendada:</span>
                                                                <span class="text-green-900 font-bold">{{ number_format($capacidadTanque * 0.8, 1) }}L</span>
                                                            </div>
                                                            <div class="text-xs text-green-600 mt-1">80% de la capacidad total</div>
                                                        </div>
                                                        
                                                        @if($rendimientoPromedio > 0)
                                                        <div class="bg-white rounded-lg p-3 border border-green-100">
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-green-700 font-medium">Autonomía esperada:</span>
                                                                <span class="text-green-900 font-bold">{{ number_format(($capacidadTanque * 0.8) * $rendimientoPromedio, 0) }} km</span>
                                                            </div>
                                                            <div class="text-xs text-green-600 mt-1">Con rendimiento promedio</div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="mt-3 flex items-center text-sm text-green-800">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>Se recomienda no llenar el tanque completamente para evitar expansión del combustible</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- Información del conductor asignado --}}
                                            @if(isset($unidadSeleccionada->conductorAsignado))
                                                <div class="mt-4 bg-purple-50 border border-purple-200 rounded-lg p-4">
                                                    <div class="flex items-center">
                                                        <div class="bg-purple-500 rounded-full p-2 mr-3">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-purple-700">👨‍💼 Conductor Asignado</div>
                                                            <div class="text-purple-900 font-semibold">{{ $unidadSeleccionada->conductorAsignado->full_name }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- Estado operativo y alertas --}}
                                            @if(isset($unidadSeleccionada->estado_operativo))
                                                <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                    <div class="text-xs text-gray-600 font-medium uppercase mb-2">📊 Estado Operativo</div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="bg-white rounded-lg p-3 border border-gray-100">
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-gray-700 font-medium">Estado:</span>
                                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                                    @if($unidadSeleccionada->estado_operativo === 'Operativo') bg-green-100 text-green-800 
                                                                    @elseif($unidadSeleccionada->estado_operativo === 'Mantenimiento') bg-yellow-100 text-yellow-800 
                                                                    @else bg-red-100 text-red-800 @endif">
                                                                    {{ $unidadSeleccionada->estado_operativo }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        @if(isset($unidadSeleccionada->unidadOrganizacional))
                                                            <div class="bg-white rounded-lg p-3 border border-gray-100">
                                                                <div class="text-xs text-gray-600 font-medium mb-1">Unidad Organizacional</div>
                                                                <div class="text-gray-900 font-semibold text-sm">{{ $unidadSeleccionada->unidadOrganizacional->nombre }}</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
                                            <div class="flex items-center justify-between text-sm text-blue-800">
                                                <div class="flex items-center">
                                                    <span class="mr-2">📊</span>
                                                    <strong>Estimado:</strong> {{ $consumoEstimado }}L para {{ $km_proyectado }}km
                                                </div>
                                                <button type="button" 
                                                        wire:click="$set('cantidad_litros_solicitados', {{ $consumoEstimado }})"
                                                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded transition-colors">
                                                    Usar Estimado
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    {{-- Acciones rápidas de cantidad --}}
                                    @if($capacidadTanque > 0)
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <button type="button" 
                                                    wire:click="$set('cantidad_litros_solicitados', {{ round($capacidadTanque * 0.5, 2) }})"
                                                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border">
                                                50% ({{ round($capacidadTanque * 0.5, 1) }}L)
                                            </button>
                                            <button type="button" 
                                                    wire:click="$set('cantidad_litros_solicitados', {{ round($capacidadTanque * 0.75, 2) }})"
                                                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border">
                                                75% ({{ round($capacidadTanque * 0.75, 1) }}L)
                                            </button>
                                            <button type="button" 
                                                    wire:click="$set('cantidad_litros_solicitados', {{ round($capacidadTanque * 0.8, 2) }})"
                                                    class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded border">
                                                80% ({{ round($capacidadTanque * 0.8, 1) }}L) 👍
                                            </button>
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

                            {{-- Alertas mejoradas --}}
                            @if(count($alertas) > 0)
                                <div class="mt-6 space-y-3">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <h6 class="text-lg font-bold text-gray-900">⚠️ Alertas y Validaciones</h6>
                                    </div>
                                    
                                    @foreach($alertas as $alerta)
                                        <div class="p-4 rounded-lg border-l-4 animate-pulse {{ $alerta['nivel'] === 'error' ? 'bg-red-50 border-red-400 text-red-800' : 'bg-yellow-50 border-yellow-400 text-yellow-800' }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    @if($alerta['nivel'] === 'error')
                                                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 9.414l1.707-1.707a1 1 0 011.414 1.414L11.414 10l1.707 1.707a1 1 0 01-1.414 1.414L10 11.414l-1.707 1.707a1 1 0 01-1.414-1.414L9.586 10 7.879 8.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="text-sm font-medium mb-1">
                                                        @if(isset($alerta['tipo']))
                                                            @switch($alerta['tipo'])
                                                                @case('capacidad')
                                                                    🚗 Capacidad del Tanque
                                                                    @break
                                                                @case('consumo')
                                                                    📊 Consumo Estimado
                                                                    @break
                                                                @case('presupuesto')
                                                                    💰 Presupuesto
                                                                    @break
                                                                @case('estado_vehiculo')
                                                                    🔧 Estado del Vehículo
                                                                    @break
                                                                @case('seguro_vencido')
                                                                    📄 Seguro Vencido
                                                                    @break
                                                                @case('revision_vencida')
                                                                    🔍 Revisión Técnica
                                                                    @break
                                                                @case('mantenimiento_requerido')
                                                                    🛠️ Mantenimiento
                                                                    @break
                                                                @default
                                                                    ⚠️ Información
                                                            @endswitch
                                                        @else
                                                            ⚠️ Información
                                                        @endif
                                                    </div>
                                                    <div class="text-sm">{{ $alerta['mensaje'] }}</div>
                                                    
                                                    {{-- Acciones sugeridas --}}
                                                    @if(isset($alerta['tipo']))
                                                        @if($alerta['tipo'] === 'capacidad')
                                                            <div class="mt-2 text-xs opacity-75">
                                                                💡 Sugerencia: Ajustar la cantidad a máximo {{ number_format($capacidadTanque, 1) }}L
                                                            </div>
                                                        @elseif($alerta['tipo'] === 'consumo')
                                                            <div class="mt-2 text-xs opacity-75">
                                                                💡 Sugerencia: Revisar el cálculo de kilometraje y rendimiento
                                                            </div>
                                                        @elseif($alerta['tipo'] === 'presupuesto')
                                                            <div class="mt-2 text-xs opacity-75">
                                                                💡 Sugerencia: Verificar el presupuesto disponible o reducir la cantidad
                                                            </div>
                                                        @elseif($alerta['nivel'] === 'error')
                                                            <div class="mt-2 text-xs opacity-75">
                                                                ⚠️ Acción requerida: Este problema debe resolverse antes de continuar
                                                            </div>
                                                        @endif
                                                    @endif
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
                                        <div class="flex items-center justify-between text-sm text-blue-800">
                                            <div class="flex items-center">
                                                <span class="mr-2">📈</span>
                                                <strong>Histórico:</strong> {{ $rendimientoPromedio }} km/L
                                            </div>
                                            <button type="button" 
                                                    wire:click="$set('rendimiento_estimado', {{ $rendimientoPromedio }})"
                                                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded transition-colors">
                                                Usar Histórico
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- Acciones rápidas para rendimiento común --}}
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div class="text-xs text-gray-600 w-full mb-1">Rendimientos típicos:</div>
                                    <button type="button" 
                                            wire:click="$set('rendimiento_estimado', 8)"
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border">
                                        🚛 Camión (8 km/L)
                                    </button>
                                    <button type="button" 
                                            wire:click="$set('rendimiento_estimado', 12)"
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border">
                                        🚗 Auto (12 km/L)
                                    </button>
                                    <button type="button" 
                                            wire:click="$set('rendimiento_estimado', 15)"
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border">
                                        🚙 SUV (15 km/L)
                                    </button>
                                    <button type="button" 
                                            wire:click="$set('rendimiento_estimado', 20)"
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded border">
                                        🏎️ Compacto (20 km/L)
                                    </button>
                                </div>
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

                            {{-- Información detallada de selecciones --}}
                            @if($categoriaSeleccionada)
                                <div class="mt-6 bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-500 rounded-lg p-5">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-purple-500 rounded-full p-2">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h5 class="text-lg font-bold text-purple-900 mb-3">📊 Categoría Programática Seleccionada</h5>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                                    <div class="text-xs text-purple-600 font-medium uppercase mb-1">Código</div>
                                                    <div class="text-purple-900 font-bold text-lg">{{ $categoriaSeleccionada->codigo }}</div>
                                                </div>
                                                
                                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                                    <div class="text-xs text-purple-600 font-medium uppercase mb-1">Tipo</div>
                                                    <div class="text-purple-900 font-semibold">{{ $categoriaSeleccionada->tipo_categoria ?? 'No especificado' }}</div>
                                                </div>
                                                
                                                <div class="bg-white rounded-lg p-3 border border-purple-200 md:col-span-2">
                                                    <div class="text-xs text-purple-600 font-medium uppercase mb-1">Descripción</div>
                                                    <div class="text-purple-900 font-semibold">{{ $categoriaSeleccionada->descripcion }}</div>
                                                </div>
                                                
                                                @if($categoriaSeleccionada->nivel)
                                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                                    <div class="text-xs text-purple-600 font-medium uppercase mb-1">Nivel Jerárquico</div>
                                                    <div class="text-purple-900 font-semibold">Nivel {{ $categoriaSeleccionada->nivel }}</div>
                                                </div>
                                                @endif
                                                
                                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                                    <div class="text-xs text-purple-600 font-medium uppercase mb-1">Estado</div>
                                                    <div class="flex items-center">
                                                        @if($categoriaSeleccionada->activo)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Activa
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 9.414l1.707-1.707a1 1 0 011.414 1.414L11.414 10l1.707 1.707a1 1 0 01-1.414 1.414L10 11.414l-1.707 1.707a1 1 0 01-1.414-1.414L9.586 10 7.879 8.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Inactiva
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Fechas de vigencia --}}
                                            @if($categoriaSeleccionada->fecha_inicio || $categoriaSeleccionada->fecha_fin)
                                                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                    <div class="text-xs text-blue-600 font-medium uppercase mb-1">Vigencia</div>
                                                    <div class="text-blue-900 text-sm">
                                                        @if($categoriaSeleccionada->fecha_inicio && $categoriaSeleccionada->fecha_fin)
                                                            📅 Desde {{ $categoriaSeleccionada->fecha_inicio->format('d/m/Y') }} hasta {{ $categoriaSeleccionada->fecha_fin->format('d/m/Y') }}
                                                        @elseif($categoriaSeleccionada->fecha_inicio)
                                                            📅 Vigente desde {{ $categoriaSeleccionada->fecha_inicio->format('d/m/Y') }}
                                                        @elseif($categoriaSeleccionada->fecha_fin)
                                                            📅 Vigente hasta {{ $categoriaSeleccionada->fecha_fin->format('d/m/Y') }}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- Información presupuestaria --}}
                                            @if($presupuestoDisponible > 0)
                                                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
                                                    <div class="text-xs text-green-600 font-medium uppercase mb-2">Información Presupuestaria</div>
                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <span class="text-green-700">💰 Disponible:</span>
                                                            <span class="font-bold text-green-900">Bs. {{ number_format($presupuestoDisponible, 2) }}</span>
                                                        </div>
                                                        @if($costoEstimado > 0)
                                                            <div>
                                                                <span class="text-green-700">🧮 Costo estimado:</span>
                                                                <span class="font-bold text-green-900">Bs. {{ number_format($costoEstimado, 2) }}</span>
                                                            </div>
                                                            @php
                                                                $porcentajeUso = ($costoEstimado / $presupuestoDisponible) * 100;
                                                                $saldoRestante = $presupuestoDisponible - $costoEstimado;
                                                            @endphp
                                                            <div class="col-span-2 mt-2">
                                                                <div class="flex items-center justify-between text-xs mb-1">
                                                                    <span class="text-green-700">Uso del presupuesto</span>
                                                                    <span class="font-medium {{ $porcentajeUso > 80 ? 'text-red-600' : ($porcentajeUso > 50 ? 'text-yellow-600' : 'text-green-600') }}">
                                                                        {{ number_format($porcentajeUso, 1) }}%
                                                                    </span>
                                                                </div>
                                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                                    <div class="h-2 rounded-full {{ $porcentajeUso > 80 ? 'bg-red-500' : ($porcentajeUso > 50 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                                                         style="width: {{ min($porcentajeUso, 100) }}%"></div>
                                                                </div>
                                                                <div class="text-xs text-green-700 mt-1">
                                                                    Saldo restante: <span class="font-bold {{ $saldoRestante < 0 ? 'text-red-600' : 'text-green-900' }}">Bs. {{ number_format($saldoRestante, 2) }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($fuenteSeleccionada)
                                <div class="mt-6 bg-gradient-to-r from-emerald-50 to-emerald-100 border-l-4 border-emerald-500 rounded-lg p-5">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-emerald-500 rounded-full p-2">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h5 class="text-lg font-bold text-emerald-900 mb-3">🏦 Fuente de Financiamiento Seleccionada</h5>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                                                    <div class="text-xs text-emerald-600 font-medium uppercase mb-1">Código de Fuente</div>
                                                    <div class="text-emerald-900 font-bold text-lg">{{ $fuenteSeleccionada->codigo }}</div>
                                                </div>
                                                
                                                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                                                    <div class="text-xs text-emerald-600 font-medium uppercase mb-1">Estado</div>
                                                    <div class="flex items-center">
                                                        @if($fuenteSeleccionada->activo ?? true)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Activa
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 9.414l1.707-1.707a1 1 0 011.414 1.414L11.414 10l1.707 1.707a1 1 0 01-1.414 1.414L10 11.414l-1.707 1.707a1 1 0 01-1.414-1.414L9.586 10 7.879 8.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Inactiva
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="bg-white rounded-lg p-3 border border-emerald-200 md:col-span-2">
                                                    <div class="text-xs text-emerald-600 font-medium uppercase mb-1">Descripción</div>
                                                    <div class="text-emerald-900 font-semibold">{{ $fuenteSeleccionada->descripcion }}</div>
                                                </div>
                                            </div>
                                            
                                            {{-- Información adicional sobre el financiamiento --}}
                                            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <div class="text-xs text-blue-600 font-medium uppercase mb-1">Características del Financiamiento</div>
                                                <div class="text-blue-900 text-sm space-y-1">
                                                    <div class="flex items-center">
                                                        <span class="mr-2">✅</span>
                                                        <span>Permite solicitudes de combustible</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">📋</span>
                                                        <span>Requiere justificación y aprobación</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">💼</span>
                                                        <span>Se registra en el sistema contable</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">🔍</span>
                                                        <span>Sujeto a auditoría y control</span>
                                                    </div>
                                                    @if($fuenteSeleccionada->tipo_fuente ?? false)
                                                    <div class="flex items-center">
                                                        <span class="mr-2">🏛️</span>
                                                        <span>Tipo: <strong>{{ $fuenteSeleccionada->tipo_fuente }}</strong></span>
                                                    </div>
                                                    @endif
                                                    @if($fuenteSeleccionada->organismo_financiador ?? false)
                                                    <div class="flex items-center">
                                                        <span class="mr-2">🏢</span>
                                                        <span>Organismo: <strong>{{ $fuenteSeleccionada->organismo_financiador }}</strong></span>
                                                    </div>
                                                    @endif
                                                    @if($fuenteSeleccionada->requiere_contrapartida ?? false)
                                                    <div class="flex items-center">
                                                        <span class="mr-2">💰</span>
                                                        <span>Requiere contrapartida: <strong>{{ $fuenteSeleccionada->porcentaje_contrapartida }}%</strong></span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            {{-- Información del presupuesto asociado --}}
                                            @if($presupuestoInfo)
                                                <div class="mt-4 bg-gradient-to-r from-amber-50 to-orange-100 border border-amber-200 rounded-lg p-4">
                                                    <div class="text-xs text-amber-600 font-medium uppercase mb-2">💰 Información Presupuestaria</div>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div class="bg-white rounded-lg p-3 border border-amber-100">
                                                            <div class="text-xs text-amber-600 font-medium uppercase mb-1">Presupuesto Inicial</div>
                                                            <div class="text-amber-900 font-bold text-lg">Bs. {{ number_format($presupuestoInfo->presupuesto_inicial, 2) }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-amber-100">
                                                            <div class="text-xs text-amber-600 font-medium uppercase mb-1">Presupuesto Actual</div>
                                                            <div class="text-amber-900 font-bold text-lg">Bs. {{ number_format($presupuestoInfo->presupuesto_actual, 2) }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-amber-100">
                                                            <div class="text-xs text-amber-600 font-medium uppercase mb-1">Saldo Disponible</div>
                                                            <div class="text-amber-900 font-bold text-lg">Bs. {{ number_format($presupuestoInfo->saldo_disponible, 2) }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-amber-100">
                                                            <div class="text-xs text-amber-600 font-medium uppercase mb-1">Total Gastado</div>
                                                            <div class="text-amber-900 font-semibold">Bs. {{ number_format($presupuestoInfo->total_gastado, 2) }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-amber-100">
                                                            <div class="text-xs text-amber-600 font-medium uppercase mb-1">Total Comprometido</div>
                                                            <div class="text-amber-900 font-semibold">Bs. {{ number_format($presupuestoInfo->total_comprometido, 2) }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-amber-100">
                                                            <div class="text-xs text-amber-600 font-medium uppercase mb-1">% Ejecutado</div>
                                                            <div class="text-amber-900 font-semibold">{{ $presupuestoInfo->porcentaje_ejecutado }}%</div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Barra de progreso del presupuesto --}}
                                                    <div class="mt-4">
                                                        <div class="flex items-center justify-between text-xs mb-1">
                                                            <span class="text-amber-700">Ejecución Presupuestaria</span>
                                                            <span class="font-medium {{ $presupuestoInfo->porcentaje_ejecutado > 80 ? 'text-red-600' : ($presupuestoInfo->porcentaje_ejecutado > 60 ? 'text-yellow-600' : 'text-green-600') }}">
                                                                {{ $presupuestoInfo->porcentaje_ejecutado }}%
                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                                            <div class="h-3 rounded-full {{ $presupuestoInfo->porcentaje_ejecutado > 80 ? 'bg-red-500' : ($presupuestoInfo->porcentaje_ejecutado > 60 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                                                 style="width: {{ min($presupuestoInfo->porcentaje_ejecutado, 100) }}%"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Alertas presupuestarias --}}
                                                    @if($presupuestoInfo->esta_cerca_limite)
                                                        <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3">
                                                            <div class="flex items-center">
                                                                <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                </svg>
                                                                <span class="text-sm font-medium text-red-800">
                                                                    ⚠️ Presupuesto cerca del límite ({{ $presupuestoInfo->alerta_porcentaje }}%)
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Información adicional del presupuesto --}}
                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                        <div>
                                                            <span class="text-amber-700">📄 Documento:</span>
                                                            <span class="font-semibold text-amber-900">{{ $presupuestoInfo->num_documento }}</span>
                                                        </div>
                                                        @if($presupuestoInfo->numero_comprobante)
                                                        <div>
                                                            <span class="text-amber-700">🧾 Comprobante:</span>
                                                            <span class="font-semibold text-amber-900">{{ $presupuestoInfo->numero_comprobante }}</span>
                                                        </div>
                                                        @endif
                                                        <div>
                                                            <span class="text-amber-700">📅 Año Fiscal:</span>
                                                            <span class="font-semibold text-amber-900">{{ $presupuestoInfo->anio_fiscal }}</span>
                                                        </div>
                                                        @if($presupuestoInfo->fecha_aprobacion)
                                                        <div>
                                                            <span class="text-amber-700">✅ Aprobado:</span>
                                                            <span class="font-semibold text-amber-900">{{ $presupuestoInfo->fecha_aprobacion->format('d/m/Y') }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- Información de la unidad organizacional --}}
                                            @if($unidadOrganizacionalInfo)
                                                <div class="mt-4 bg-gradient-to-r from-indigo-50 to-blue-100 border border-indigo-200 rounded-lg p-4">
                                                    <div class="text-xs text-indigo-600 font-medium uppercase mb-2">🏢 Unidad Organizacional</div>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="bg-white rounded-lg p-3 border border-indigo-100">
                                                            <div class="text-xs text-indigo-600 font-medium uppercase mb-1">Código</div>
                                                            <div class="text-indigo-900 font-bold text-lg">{{ $unidadOrganizacionalInfo->codigo_unidad }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-indigo-100">
                                                            <div class="text-xs text-indigo-600 font-medium uppercase mb-1">Tipo</div>
                                                            <div class="text-indigo-900 font-semibold">{{ $unidadOrganizacionalInfo->tipo_unidad }}</div>
                                                        </div>
                                                        
                                                        <div class="bg-white rounded-lg p-3 border border-indigo-100 md:col-span-2">
                                                            <div class="text-xs text-indigo-600 font-medium uppercase mb-1">Nombre de la Unidad</div>
                                                            <div class="text-indigo-900 font-semibold">{{ $unidadOrganizacionalInfo->nombre_unidad }}</div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Información adicional de la unidad --}}
                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                                        @if($unidadOrganizacionalInfo->responsable_unidad)
                                                        <div>
                                                            <span class="text-indigo-700">👤 Responsable:</span>
                                                            <span class="font-semibold text-indigo-900">{{ $unidadOrganizacionalInfo->responsable_unidad }}</span>
                                                        </div>
                                                        @endif
                                                        
                                                        @if($unidadOrganizacionalInfo->telefono)
                                                        <div>
                                                            <span class="text-indigo-700">📞 Teléfono:</span>
                                                            <span class="font-semibold text-indigo-900">{{ $unidadOrganizacionalInfo->telefono }}</span>
                                                        </div>
                                                        @endif
                                                        
                                                        @if($unidadOrganizacionalInfo->nivel_jerarquico)
                                                        <div>
                                                            <span class="text-indigo-700">📊 Nivel Jerárquico:</span>
                                                            <span class="font-semibold text-indigo-900">Nivel {{ $unidadOrganizacionalInfo->nivel_jerarquico }}</span>
                                                        </div>
                                                        @endif
                                                        
                                                        @if($unidadOrganizacionalInfo->presupuesto_asignado > 0)
                                                        <div>
                                                            <span class="text-indigo-700">💰 Presupuesto Asignado:</span>
                                                            <span class="font-semibold text-indigo-900">Bs. {{ number_format($unidadOrganizacionalInfo->presupuesto_asignado, 2) }}</span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($unidadOrganizacionalInfo->direccion)
                                                    <div class="mt-3 bg-white border border-indigo-100 rounded-lg p-3">
                                                        <div class="text-xs text-indigo-600 font-medium uppercase mb-1">📍 Dirección</div>
                                                        <div class="text-indigo-900 text-sm">{{ $unidadOrganizacionalInfo->direccion }}</div>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($unidadOrganizacionalInfo->descripcion)
                                                    <div class="mt-3 bg-white border border-indigo-100 rounded-lg p-3">
                                                        <div class="text-xs text-indigo-600 font-medium uppercase mb-1">📝 Descripción</div>
                                                        <div class="text-indigo-900 text-sm">{{ $unidadOrganizacionalInfo->descripcion }}</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- 5. Resumen de la Solicitud --}}
                        @if($id_unidad_transporte && $cantidad_litros_solicitados > 0)
                            <div class="section-card">
                                <div class="section-header">
                                    <div class="section-icon bg-indigo-500 text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c0 .621-.504-1.125-1.125-1.125H18a2.25 2.25 0 01-2.25-2.25M8.25 8.25V6.108"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900">📋 Resumen de la Solicitud</h4>
                                </div>

                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-lg p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        {{-- Información del vehículo --}}
                                        @if($unidadSeleccionada)
                                            <div class="info-card">
                                                <div class="info-value text-blue-600">{{ $unidadSeleccionada->placa }}</div>
                                                <div class="info-label">Vehículo</div>
                                            </div>
                                        @endif

                                        {{-- Cantidad solicitada --}}
                                        <div class="info-card">
                                            <div class="info-value text-green-600">{{ number_format($cantidad_litros_solicitados, 2) }}L</div>
                                            <div class="info-label">Combustible</div>
                                        </div>

                                        {{-- Costo estimado --}}
                                        @if($costoEstimado > 0)
                                            <div class="info-card">
                                                <div class="info-value text-yellow-600">Bs. {{ number_format($costoEstimado, 2) }}</div>
                                                <div class="info-label">Costo Estimado</div>
                                            </div>
                                        @endif

                                        {{-- Autonomía estimada --}}
                                        @if($consumoEstimado > 0 && $rendimiento_estimado > 0)
                                            <div class="info-card">
                                                <div class="info-value text-purple-600">{{ number_format($cantidad_litros_solicitados * $rendimiento_estimado, 0) }} km</div>
                                                <div class="info-label">Autonomía Estimada</div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Estado de validación --}}
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">Estado de validación:</span>
                                            @if(count($alertas) === 0)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    ✅ Todo correcto
                                                </span>
                                            @else
                                                @php
                                                    $errores = collect($alertas)->where('nivel', 'error')->count();
                                                    $advertencias = collect($alertas)->where('nivel', 'warning')->count();
                                                @endphp
                                                @if($errores > 0)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 9.414l1.707-1.707a1 1 0 011.414 1.414L11.414 10l1.707 1.707a1 1 0 01-1.414 1.414L10 11.414l-1.707 1.707a1 1 0 01-1.414-1.414L9.586 10 7.879 8.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                        ❌ {{ $errores }} error(es)
                                                    </span>
                                                @elseif($advertencias > 0)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        ⚠️ {{ $advertencias }} advertencia(s)
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Botones optimizados --}}
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <div class="flex items-center space-x-3">
                                {{-- Botón para limpiar formulario --}}
                                <button type="button" wire:click="limpiarFormulario" 
                                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Limpiar Formulario
                                </button>
                                
                                {{-- Indicador de campos requeridos --}}
                                <div class="text-sm text-gray-600 flex items-center">
                                    <span class="text-red-500 mr-1">*</span>
                                    Campos obligatorios
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>