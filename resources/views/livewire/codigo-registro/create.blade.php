<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                                Generar Códigos de Registro
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Crea códigos únicos para el registro de nuevos usuarios
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button wire:click="volver" type="button"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    @if(!$mostrarCodigos)
                        <!-- Formulario de Generación -->
                        <div class="max-w-md mx-auto">
                            <form wire:submit.prevent="generarCodigos">
                                <div class="space-y-6">
                                    <!-- Cantidad -->
                                    <div>
                                        <label for="cantidad" class="block text-sm font-medium text-gray-700">
                                            Cantidad de Códigos
                                        </label>
                                        <div class="mt-1">
                                            <input type="number" wire:model="cantidad" id="cantidad" min="1" max="50"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="1">
                                        </div>
                                        @error('cantidad')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Máximo 50 códigos por generación</p>
                                    </div>

                                    <!-- Días de Vigencia -->
                                    <div>
                                        <label for="diasVigencia" class="block text-sm font-medium text-gray-700">
                                            Días de Vigencia
                                        </label>
                                        <div class="mt-1">
                                            <input type="number" wire:model="diasVigencia" id="diasVigencia" min="1" max="365"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="7">
                                        </div>
                                        @error('diasVigencia')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Los códigos vencerán después de este período</p>
                                    </div>
                                </div>

                                <!-- Botón de Generación -->
                                <div class="mt-8 flex justify-center">
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Generar Códigos
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Códigos Generados -->
                        <div class="max-w-4xl mx-auto">
                            <div class="text-center mb-6">
                                <div class="inline-flex items-center px-4 py-2 bg-green-100 border border-green-200 rounded-md">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-green-800 font-medium">
                                        Se generaron {{ count($codigosGenerados) }} código(s) exitosamente
                                    </span>
                                </div>
                            </div>

                            <!-- Lista de Códigos -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Códigos Generados</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($codigosGenerados as $codigo)
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                            <div class="font-mono text-xl font-bold text-indigo-600 mb-2">
                                                {{ $codigo->codigo }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Vigente hasta: {{ \Carbon\Carbon::parse($codigo->vigente_hasta)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Información Importante -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Información Importante</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Guarda estos códigos en un lugar seguro</li>
                                                <li>Cada código solo puede ser usado una vez</li>
                                                <li>Los códigos vencerán automáticamente después de {{ $diasVigencia }} día(s)</li>
                                                <li>Comparte estos códigos solo con usuarios autorizados</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col sm:flex-row sm:justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                                <button wire:click="nuevaGeneracion" type="button"
                                        class="inline-flex items-center justify-center px-4 py-2 border border-indigo-300 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Generar Más Códigos
                                </button>
                                
                                <button wire:click="volver" type="button"
                                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Ir a Lista de Códigos
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
