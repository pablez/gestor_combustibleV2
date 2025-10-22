<div>
    {{-- Estilos personalizados para mejorar visibilidad de selectores --}}
    <style>
        /* Mejorar la visibilidad de los select elements */
        select.form-select-custom {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            font-weight: 500;
            text-shadow: none !important;
            line-height: 1.5;
        }
        
        select.form-select-custom option {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            padding: 10px 16px !important;
            font-weight: 500;
            line-height: 1.6;
            border: none !important;
            text-shadow: none !important;
        }
        
        select.form-select-custom option:hover {
            background-color: #f8fafc !important;
            color: #1f2937 !important;
        }
        
        select.form-select-custom option:checked,
        select.form-select-custom option:selected {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        
        /* Placeholder text style */
        select.form-select-custom option[value=""] {
            color: #6b7280 !important;
            font-style: italic;
            font-weight: 400;
        }
        
        /* For Webkit browsers */
        select.form-select-custom::-webkit-option {
            background-color: #ffffff !important;
            color: #1f2937 !important;
        }
        
        /* Focus state */
        select.form-select-custom:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        /* Dark mode prevention */
        @media (prefers-color-scheme: dark) {
            select.form-select-custom,
            select.form-select-custom option {
                background-color: #ffffff !important;
                color: #1f2937 !important;
            }
        }
    </style>

    {{-- Botón para abrir el formulario --}}
    @if(!$mostrarFormulario)
        <button wire:click="toggleFormulario" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Solicitud
        </button>
    @endif

    {{-- Modal/Formulario --}}
    @if($mostrarFormulario)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="toggleFormulario"></div>

                {{-- Modal Content --}}
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white flex items-center" id="modal-title">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    {{-- Formulario --}}
                    <form wire:submit.prevent="crear" class="p-6">
                        <div class="space-y-8">
                            {{-- Sección 1: Selección del Vehículo --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center mb-6">
                                    <div class="bg-blue-500 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">Selección del Vehículo</h4>
                                </div>
                                
                                <div>
                                    <label for="id_unidad_transporte" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Vehículo
                                            <span class="text-red-500 ml-1">*</span>
                                        </div>
                                    </label>
                                    <select wire:model.live="id_unidad_transporte" id="id_unidad_transporte" 
                                            class="form-select-custom w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors @error('id_unidad_transporte') border-red-500 @enderror">
                                        <option value="" class="text-gray-500 font-normal">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Selecciona un vehículo
                                        </option>
                                        @foreach($unidadesTransporte as $unidad)
                                            <option value="{{ $unidad->id }}" class="text-gray-900 font-medium py-2">
                                                <svg class="w-4 h-4 inline text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $unidad->placa }} 
                                                @if($unidad->marca && $unidad->modelo)
                                                    | {{ $unidad->marca }} {{ $unidad->modelo }}
                                                    @if($unidad->anio)
                                                        ({{ $unidad->anio }})
                                                    @endif
                                                @endif
                                                @if(isset($unidad->tipoVehiculo) && $unidad->tipoVehiculo)
                                                    | {{ $unidad->tipoVehiculo->nombre }}
                                                @endif
                                                @if(isset($unidad->capacidad_tanque) && $unidad->capacidad_tanque)
                                                    | 
                                                    <svg class="w-4 h-4 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    {{ number_format($unidad->capacidad_tanque, 1) }}L
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
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-blue-900">{{ $unidadSeleccionada->placa }}</div>
                                                <div class="text-sm text-blue-700">{{ $unidadSeleccionada->marca }} {{ $unidadSeleccionada->modelo }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-green-700">{{ number_format($capacidadTanque, 1) }}L</div>
                                                <div class="text-sm text-gray-600">Capacidad</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-orange-700">{{ number_format($unidadSeleccionada->kilometraje_actual) }}</div>
                                                <div class="text-sm text-gray-600">Kilometraje</div>
                                            </div>
                                            @if($rendimientoPromedio > 0)
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-purple-700">{{ $rendimientoPromedio }}</div>
                                                <div class="text-sm text-gray-600">km/L</div>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        {{-- Recomendaciones compactas --}}
                                        @if($capacidadTanque > 0)
                                            <div class="mt-3 bg-green-50 border border-green-200 rounded p-3">
                                                <div class="flex items-center text-sm text-green-800">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                                    </svg>
                                                    <strong>Recomendado:</strong> {{ number_format($capacidadTanque * 0.8, 1) }}L
                                                    @if($rendimientoPromedio > 0)
                                                        | <strong>Autonomía:</strong> {{ number_format(($capacidadTanque * 0.8) * $rendimientoPromedio) }}km
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Sección 2: Cantidad y Motivo --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center mb-6">
                                    <div class="bg-green-500 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">Solicitud de Combustible</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="cantidad_litros_solicitados" class="block text-sm font-semibold text-gray-700 mb-3">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                Cantidad (Litros)
                                                <span class="text-red-500 ml-1">*</span>
                                            </div>
                                        </label>
                                        <input type="number" step="0.01" min="0.01" wire:model.live="cantidad_litros_solicitados" id="cantidad_litros_solicitados"
                                               placeholder="Ej: 50.00"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('cantidad_litros_solicitados') border-red-500 @enderror">
                                        @error('cantidad_litros_solicitados')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        
                                        {{-- Mostrar consumo estimado si se calcula --}}
                                        @if($consumoEstimado > 0)
                                            <div class="mt-2 bg-blue-50 border border-blue-200 rounded p-2">
                                                <div class="flex items-center text-sm text-blue-800">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                                    </svg>
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
                                            Motivo de la Solicitud
                                            <span class="text-red-500 ml-1">*</span>
                                        </div>
                                    </label>
                                    <textarea wire:model="motivo" id="motivo" rows="4"
                                              placeholder="Describe el motivo de la solicitud de combustible..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('motivo') border-red-500 @enderror"></textarea>
                                    @error('motivo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Alertas en tiempo real --}}
                                @if(count($alertas) > 0)
                                    <div class="mt-6 space-y-3">
                                        @foreach($alertas as $alerta)
                                            <div class="p-4 rounded-lg border-l-4 {{ $alerta['nivel'] === 'error' ? 'bg-red-50 border-red-400 text-red-800' : 'bg-yellow-50 border-yellow-400 text-yellow-800' }}">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        @if($alerta['nivel'] === 'error')
                                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
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
                                                <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
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
                                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
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

                            {{-- Sección 3: Información Técnica --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center mb-6">
                                    <div class="bg-purple-500 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">Información Técnica</h4>
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

                                <div>
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
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                                <strong>Histórico:</strong> {{ $rendimientoPromedio }} km/L
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Sección 4: Información Presupuestaria --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center mb-6">
                                    <div class="bg-emerald-500 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">Información Presupuestaria</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                           placeholder="Combustible restante en el tanque"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('saldo_actual_combustible') border-red-500 @enderror">
                                    @error('saldo_actual_combustible')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
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
                                            <option value="" class="text-gray-500 font-normal">Selecciona categoría (opcional)</option>
                                            @foreach($categoriasProgramaticas as $categoria)
                                                <option value="{{ $categoria->id }}" class="text-gray-900 font-medium py-2">
                                                    {{ $categoria->codigo }}
                                                    @if($categoria->descripcion)
                                                        | {{ $categoria->descripcion }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($presupuestoDisponible > 0)
                                            <div class="mt-2 bg-green-50 border border-green-200 rounded p-2">
                                                <div class="flex items-center text-sm text-green-800">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                    </svg>
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
                                            <option value="" class="text-gray-500 font-normal">Selecciona fuente (opcional)</option>
                                            @foreach($fuentesOrganismo as $fuente)
                                                <option value="{{ $fuente->id }}" class="text-gray-900 font-medium py-2">
                                                    {{ $fuente->codigo }}
                                                    @if($fuente->descripcion)
                                                        | {{ $fuente->descripcion }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Información resumida de categoría y fuente seleccionadas --}}
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
                        </div>

                                {{-- Información de la categoría programática seleccionada --}}
                                @if($categoriaSeleccionada)
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-5 shadow-sm">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-purple-500 rounded-full p-2 mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                            <h5 class="font-bold text-purple-900 text-lg">📊 Categoría Programática Seleccionada</h5>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-white rounded-lg p-4 border border-purple-100">
                                                <div class="flex items-center">
                                                    <span class="text-3xl mr-3">🔢</span>
                                                    <div>
                                                        <p class="text-xs text-purple-600 font-medium uppercase">Código Presupuestario</p>
                                                        <p class="text-purple-900 font-bold text-xl">{{ $categoriaSeleccionada->codigo }}</p>
                                                        <p class="text-purple-700 text-sm">Identificador único</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-white rounded-lg p-4 border border-purple-100">
                                                <div class="flex items-center">
                                                    <span class="text-3xl mr-3">📋</span>
                                                    <div>
                                                        <p class="text-xs text-purple-600 font-medium uppercase">Descripción</p>
                                                        <p class="text-purple-900 font-bold text-base leading-tight">{{ $categoriaSeleccionada->descripcion }}</p>
                                                        <p class="text-purple-700 text-sm">Programa presupuestario</p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($categoriaSeleccionada->tipo_categoria)
                                                <div class="bg-white rounded-lg p-4 border border-purple-100">
                                                    <div class="flex items-center">
                                                        <span class="text-3xl mr-3">🎯</span>
                                                        <div>
                                                            <p class="text-xs text-purple-600 font-medium uppercase">Tipo de Categoría</p>
                                                            <p class="text-purple-900 font-bold text-lg">{{ $categoriaSeleccionada->tipo_categoria }}</p>
                                                            <p class="text-purple-700 text-sm">Clasificación</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($categoriaSeleccionada->nivel)
                                                <div class="bg-white rounded-lg p-4 border border-purple-100">
                                                    <div class="flex items-center">
                                                        <span class="text-3xl mr-3">�</span>
                                                        <div>
                                                            <p class="text-xs text-purple-600 font-medium uppercase">Nivel Jerárquico</p>
                                                            <p class="text-purple-900 font-bold text-lg">Nivel {{ $categoriaSeleccionada->nivel }}</p>
                                                            <p class="text-purple-700 text-sm">Estructura organizacional</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Información de presupuesto en tiempo real --}}
                                        @if($presupuestoDisponible > 0 && $costoEstimado > 0)
                                            <div class="mt-4 bg-white rounded-lg p-4 border border-purple-100">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-purple-700 font-medium text-sm">💳 Análisis Presupuestario</span>
                                                    @php
                                                        $porcentajeCosto = ($costoEstimado / $presupuestoDisponible) * 100;
                                                    @endphp
                                                    <span class="text-xs px-2 py-1 rounded-full {{ $porcentajeCosto > 50 ? 'bg-red-100 text-red-800' : ($porcentajeCosto > 25 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                        {{ number_format($porcentajeCosto, 1) }}% del disponible
                                                    </span>
                                                </div>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-purple-700">Presupuesto Disponible:</span>
                                                        <span class="font-bold text-purple-900">Bs. {{ number_format($presupuestoDisponible, 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-purple-700">Costo Estimado:</span>
                                                        <span class="font-bold text-purple-900">Bs. {{ number_format($costoEstimado, 2) }}</span>
                                                    </div>
                                                    <div class="flex justify-between border-t border-purple-200 pt-2">
                                                        <span class="text-purple-700 font-medium">Saldo Restante:</span>
                                                        <span class="font-bold {{ ($presupuestoDisponible - $costoEstimado) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                            Bs. {{ number_format($presupuestoDisponible - $costoEstimado, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Fechas de vigencia si aplica --}}
                                        @if($categoriaSeleccionada->fecha_inicio || $categoriaSeleccionada->fecha_fin)
                                            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <div class="flex items-start">
                                                    <span class="text-blue-500 text-xl mr-2">📅</span>
                                                    <div>
                                                        <p class="text-blue-800 font-medium text-sm">Vigencia de la Categoría:</p>
                                                        <p class="text-blue-700 text-xs mt-1">
                                                            @if($categoriaSeleccionada->fecha_inicio && $categoriaSeleccionada->fecha_fin)
                                                                Vigente desde {{ $categoriaSeleccionada->fecha_inicio->format('d/m/Y') }} hasta {{ $categoriaSeleccionada->fecha_fin->format('d/m/Y') }}
                                                            @elseif($categoriaSeleccionada->fecha_inicio)
                                                                Vigente desde {{ $categoriaSeleccionada->fecha_inicio->format('d/m/Y') }}
                                                            @elseif($categoriaSeleccionada->fecha_fin)
                                                                Vigente hasta {{ $categoriaSeleccionada->fecha_fin->format('d/m/Y') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Estado de la categoría --}}
                                        <div class="mt-4 bg-{{ $categoriaSeleccionada->activo ? 'green' : 'red' }}-50 border border-{{ $categoriaSeleccionada->activo ? 'green' : 'red' }}-200 rounded-lg p-3">
                                            <div class="flex items-start">
                                                <span class="text-{{ $categoriaSeleccionada->activo ? 'green' : 'red' }}-500 text-xl mr-2">{{ $categoriaSeleccionada->activo ? '✅' : '❌' }}</span>
                                                <div>
                                                    <p class="text-{{ $categoriaSeleccionada->activo ? 'green' : 'red' }}-800 font-medium text-sm">Estado: {{ $categoriaSeleccionada->activo ? 'Activa' : 'Inactiva' }}</p>
                                                    <p class="text-{{ $categoriaSeleccionada->activo ? 'green' : 'red' }}-700 text-xs mt-1">
                                                        {{ $categoriaSeleccionada->activo ? 'Esta categoría está disponible para usar en solicitudes.' : 'Esta categoría no está disponible actualmente.' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label for="id_fuente_org_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                        💰 Fuente de Financiamiento
                                    </label>
                                    <select wire:model.live="id_fuente_org_fin" id="id_fuente_org_fin" 
                                            class="form-select-custom w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-gray-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 transition-colors">
                                        <option value="" class="text-gray-500 font-normal">💳 Selecciona una fuente (opcional)</option>
                                        @foreach($fuentesOrganismo as $fuente)
                                            <option value="{{ $fuente->id }}" class="text-gray-900 font-medium py-2">
                                                🏦 {{ $fuente->codigo }}
                                                @if($fuente->descripcion)
                                                    | {{ $fuente->descripcion }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Información de la fuente financiera seleccionada --}}
                                @if($fuenteSeleccionada)
                                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-xl p-5 shadow-sm">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-emerald-500 rounded-full p-2 mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                            </div>
                                            <h5 class="font-bold text-emerald-900 text-lg">💰 Fuente de Financiamiento Seleccionada</h5>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-white rounded-lg p-4 border border-emerald-100">
                                                <div class="flex items-center">
                                                    <span class="text-3xl mr-3">🏷️</span>
                                                    <div>
                                                        <p class="text-xs text-emerald-600 font-medium uppercase">Código de Fuente</p>
                                                        <p class="text-emerald-900 font-bold text-xl">{{ $fuenteSeleccionada->codigo }}</p>
                                                        <p class="text-emerald-700 text-sm">Identificador financiero</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-white rounded-lg p-4 border border-emerald-100">
                                                <div class="flex items-center">
                                                    <span class="text-3xl mr-3">🏦</span>
                                                    <div>
                                                        <p class="text-xs text-emerald-600 font-medium uppercase">Descripción</p>
                                                        <p class="text-emerald-900 font-bold text-base leading-tight">{{ $fuenteSeleccionada->descripcion }}</p>
                                                        <p class="text-emerald-700 text-sm">Organismo financiero</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Información adicional basada en campos disponibles --}}
                                        @if($fuenteSeleccionada->activo !== null)
                                            <div class="mt-4 bg-{{ $fuenteSeleccionada->activo ? 'green' : 'red' }}-50 border border-{{ $fuenteSeleccionada->activo ? 'green' : 'red' }}-200 rounded-lg p-3">
                                                <div class="flex items-start">
                                                    <span class="text-{{ $fuenteSeleccionada->activo ? 'green' : 'red' }}-500 text-xl mr-2">{{ $fuenteSeleccionada->activo ? '✅' : '❌' }}</span>
                                                    <div>
                                                        <p class="text-{{ $fuenteSeleccionada->activo ? 'green' : 'red' }}-800 font-medium text-sm">Estado: {{ $fuenteSeleccionada->activo ? 'Activa' : 'Inactiva' }}</p>
                                                        <p class="text-{{ $fuenteSeleccionada->activo ? 'green' : 'red' }}-700 text-xs mt-1">
                                                            {{ $fuenteSeleccionada->activo ? 'Esta fuente está disponible para financiamiento.' : 'Esta fuente no está disponible actualmente.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Información de presupuesto relacionado --}}
                                        @if($presupuestoDisponible > 0)
                                            <div class="mt-4 bg-white rounded-lg p-4 border border-emerald-100">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-emerald-700 font-medium text-sm">💳 Información Presupuestaria</span>
                                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-800">
                                                        Disponible
                                                    </span>
                                                </div>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-emerald-700">Presupuesto Disponible:</span>
                                                        <span class="font-bold text-emerald-900">Bs. {{ number_format($presupuestoDisponible, 2) }}</span>
                                                    </div>
                                                    @if($costoEstimado > 0)
                                                        <div class="flex justify-between">
                                                            <span class="text-emerald-700">Costo Estimado:</span>
                                                            <span class="font-bold text-emerald-900">Bs. {{ number_format($costoEstimado, 2) }}</span>
                                                        </div>
                                                        <div class="flex justify-between border-t border-emerald-200 pt-2">
                                                            <span class="text-emerald-700 font-medium">Saldo Después:</span>
                                                            <span class="font-bold {{ ($presupuestoDisponible - $costoEstimado) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                                Bs. {{ number_format($presupuestoDisponible - $costoEstimado, 2) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Características operativas --}}
                                        <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                            <div class="flex items-start">
                                                <span class="text-emerald-500 text-xl mr-2">�</span>
                                                <div>
                                                    <p class="text-emerald-800 font-medium text-sm">Características de la Fuente:</p>
                                                    <div class="text-emerald-700 text-xs mt-1 space-y-1">
                                                        <p>• Código de identificación: {{ $fuenteSeleccionada->codigo }}</p>
                                                        <p>• Permite solicitudes de combustible</p>
                                                        <p>• Requiere justificación y aprobación</p>
                                                        <p>• Se registra en el sistema contable</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="mt-8 flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                            <button type="button" wire:click="toggleFormulario" 
                                    class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
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
