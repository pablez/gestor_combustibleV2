<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Presupuesto') }}
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
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Columna Izquierda: Información Básica -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                                
                                <!-- Unidad Organizacional -->
                                <div class="mb-4">
                                    <label for="id_unidad_organizacional" class="block text-sm font-medium text-gray-700 mb-1">
                                        Unidad Organizacional <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="id_unidad_organizacional" id="id_unidad_organizacional" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccionar unidad</option>
                                        @foreach($unidades as $unidad)
                                            <option value="{{ $unidad->id }}">{{ $unidad->nombre_unidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_unidad_organizacional')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Categoría Programática -->
                                <div class="mb-4">
                                    <label for="id_cat_programatica" class="block text-sm font-medium text-gray-700 mb-1">
                                        Categoría Programática <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="id_cat_programatica" id="id_cat_programatica" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_cat_programatica')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fuente de Organismo Financiero -->
                                <div class="mb-4">
                                    <label for="id_fuente_org_fin" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fuente de Organismo Financiero <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="id_fuente_org_fin" id="id_fuente_org_fin" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Seleccionar fuente</option>
                                        @foreach($fuentes as $fuente)
                                            <option value="{{ $fuente->id }}">{{ $fuente->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_fuente_org_fin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Año Fiscal y Trimestre -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="anio_fiscal" class="block text-sm font-medium text-gray-700 mb-1">
                                            Año Fiscal <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" min="2020" max="2050" wire:model.live="anio_fiscal" id="anio_fiscal" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('anio_fiscal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="trimestre" class="block text-sm font-medium text-gray-700 mb-1">
                                            Trimestre <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="trimestre" id="trimestre" 
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="1">Q1 (Enero-Marzo)</option>
                                            <option value="2">Q2 (Abril-Junio)</option>
                                            <option value="3">Q3 (Julio-Septiembre)</option>
                                            <option value="4">Q4 (Octubre-Diciembre)</option>
                                        </select>
                                        @error('trimestre')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Documentación -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-1">
                                            Número de Documento
                                        </label>
                                        <input type="text" wire:model="num_documento" id="num_documento" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               placeholder="DOC-2024-001">
                                        @error('num_documento')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="numero_comprobante" class="block text-sm font-medium text-gray-700 mb-1">
                                            Número de Comprobante
                                        </label>
                                        <input type="text" wire:model="numero_comprobante" id="numero_comprobante" 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               placeholder="COMP-2024-001">
                                        @error('numero_comprobante')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Fecha de Aprobación -->
                                <div class="mb-4">
                                    <label for="fecha_aprobacion" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha de Aprobación
                                    </label>
                                    <input type="date" wire:model="fecha_aprobacion" id="fecha_aprobacion" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('fecha_aprobacion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Columna Derecha: Información Financiera -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Financiera</h3>
                                
                                <!-- Presupuesto Inicial -->
                                <div class="mb-4">
                                    <label for="presupuesto_inicial" class="block text-sm font-medium text-gray-700 mb-1">
                                        Presupuesto Inicial <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0.01" wire:model.live="presupuesto_inicial" id="presupuesto_inicial" 
                                               class="mt-1 block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    @error('presupuesto_inicial')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Presupuesto Actual -->
                                <div class="mb-4">
                                    <label for="presupuesto_actual" class="block text-sm font-medium text-gray-700 mb-1">
                                        Presupuesto Actual <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" wire:model.live="presupuesto_actual" id="presupuesto_actual" 
                                               class="mt-1 block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    @error('presupuesto_actual')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Montos Ejecutados -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="total_gastado" class="block text-sm font-medium text-gray-700 mb-1">
                                            Total Gastado
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" wire:model.live="total_gastado" id="total_gastado" 
                                                   class="mt-1 block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        @error('total_gastado')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="total_comprometido" class="block text-sm font-medium text-gray-700 mb-1">
                                            Total Comprometido
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" wire:model.live="total_comprometido" id="total_comprometido" 
                                                   class="mt-1 block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        @error('total_comprometido')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Métricas Calculadas -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Métricas Calculadas</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Saldo Disponible</dt>
                                            <dd class="text-lg font-bold {{ $saldo_disponible >= 0 ? 'text-green-900' : 'text-red-900' }}">
                                                ${{ number_format($saldo_disponible, 2) }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">% Ejecutado</dt>
                                            <dd class="text-lg font-bold {{ $porcentaje_ejecutado >= $alerta_porcentaje ? 'text-red-900' : 'text-blue-900' }}">
                                                {{ $porcentaje_ejecutado }}%
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configuración de Alertas -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="porcentaje_preventivo" class="block text-sm font-medium text-gray-700 mb-1">
                                            % Preventivo <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="0" max="100" wire:model="porcentaje_preventivo" id="porcentaje_preventivo" 
                                                   class="mt-1 block w-full pr-7 pl-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                        </div>
                                        @error('porcentaje_preventivo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="alerta_porcentaje" class="block text-sm font-medium text-gray-700 mb-1">
                                            % Alerta <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="0.01" min="0" max="100" wire:model="alerta_porcentaje" id="alerta_porcentaje" 
                                                   class="mt-1 block w-full pr-7 pl-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                        </div>
                                        @error('alerta_porcentaje')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Estado Activo -->
                                <div class="mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="activo" id="activo" 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="activo" class="ml-2 block text-sm text-gray-900">
                                            Presupuesto activo
                                        </label>
                                    </div>
                                    @error('activo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-6">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                                Observaciones
                            </label>
                            <textarea wire:model="observaciones" id="observaciones" rows="3" 
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Observaciones adicionales sobre el presupuesto"></textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <button type="button" wire:click="cancel" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>

                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Crear Presupuesto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
