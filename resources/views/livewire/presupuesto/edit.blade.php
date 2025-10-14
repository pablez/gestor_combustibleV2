<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Presupuesto') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('presupuestos.show', $presupuesto) }}" 
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

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit="save">
                        <!-- Header del formulario -->
                        <div class="mb-6 pb-4 border-b">
                            <h3 class="text-xl font-semibold text-gray-900">Modificar Datos del Presupuesto</h3>
                            <p class="text-sm text-gray-600 mt-1">Actualiza la información financiera y de control del presupuesto</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Columna Izquierda - Información General -->
                            <div class="space-y-6">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-blue-900 mb-4">Información General</h4>
                                    
                                    <!-- Año Fiscal y Trimestre -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="anio_fiscal" class="block text-sm font-medium text-gray-700 mb-1">
                                                Año Fiscal <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   wire:model.live="anio_fiscal" 
                                                   id="anio_fiscal"
                                                   min="2020" 
                                                   max="2050"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('anio_fiscal') border-red-500 @enderror">
                                            @error('anio_fiscal')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="trimestre" class="block text-sm font-medium text-gray-700 mb-1">
                                                Trimestre <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model.live="trimestre" 
                                                    id="trimestre"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('trimestre') border-red-500 @enderror">
                                                <option value="">Seleccionar...</option>
                                                <option value="1">Trimestre 1 (Ene-Mar)</option>
                                                <option value="2">Trimestre 2 (Abr-Jun)</option>
                                                <option value="3">Trimestre 3 (Jul-Sep)</option>
                                                <option value="4">Trimestre 4 (Oct-Dic)</option>
                                            </select>
                                            @error('trimestre')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Unidad Organizacional -->
                                    <div class="mb-4">
                                        <label for="id_unidad_organizacional" class="block text-sm font-medium text-gray-700 mb-1">
                                            Unidad Organizacional <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model.live="id_unidad_organizacional" 
                                                id="id_unidad_organizacional"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_unidad_organizacional') border-red-500 @enderror">
                                            <option value="">Seleccionar unidad...</option>
                                            @foreach($unidadesOrganizacionales as $unidad)
                                                <option value="{{ $unidad->id }}">{{ $unidad->nombre_unidad }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_unidad_organizacional')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Categoría Programática -->
                                    <div class="mb-4">
                                        <label for="id_cat_programatica" class="block text-sm font-medium text-gray-700 mb-1">
                                            Categoría Programática <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="id_cat_programatica" 
                                                id="id_cat_programatica"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_cat_programatica') border-red-500 @enderror">
                                            <option value="">Seleccionar categoría...</option>
                                            @foreach($categoriasProgramaticas as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_cat_programatica')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Fuente de Financiamiento -->
                                    <div>
                                        <label for="id_fuente_org_fin" class="block text-sm font-medium text-gray-700 mb-1">
                                            Fuente de Financiamiento <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="id_fuente_org_fin" 
                                                id="id_fuente_org_fin"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_fuente_org_fin') border-red-500 @enderror">
                                            <option value="">Seleccionar fuente...</option>
                                            @foreach($fuentesFinanciamiento as $fuente)
                                                <option value="{{ $fuente->id }}">{{ $fuente->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_fuente_org_fin')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Información Adicional -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h4>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-1">
                                                Número de Documento
                                            </label>
                                            <input type="text" 
                                                   wire:model="num_documento" 
                                                   id="num_documento"
                                                   maxlength="20"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('num_documento') border-red-500 @enderror">
                                            @error('num_documento')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="numero_comprobante" class="block text-sm font-medium text-gray-700 mb-1">
                                                Número de Comprobante
                                            </label>
                                            <input type="text" 
                                                   wire:model="numero_comprobante" 
                                                   id="numero_comprobante"
                                                   maxlength="20"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_comprobante') border-red-500 @enderror">
                                            @error('numero_comprobante')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="fecha_aprobacion" class="block text-sm font-medium text-gray-700 mb-1">
                                            Fecha de Aprobación
                                        </label>
                                        <input type="date" 
                                               wire:model="fecha_aprobacion" 
                                               id="fecha_aprobacion"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fecha_aprobacion') border-red-500 @enderror">
                                        @error('fecha_aprobacion')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                                            Observaciones
                                        </label>
                                        <textarea wire:model="observaciones" 
                                                  id="observaciones"
                                                  rows="3"
                                                  maxlength="500"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observaciones') border-red-500 @enderror"
                                                  placeholder="Observaciones o comentarios adicionales..."></textarea>
                                        @error('observaciones')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   wire:model="activo" 
                                                   class="form-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">Presupuesto activo</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha - Información Financiera -->
                            <div class="space-y-6">
                                <div class="bg-green-50 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-green-900 mb-4">Información Financiera</h4>
                                    
                                    <!-- Presupuestos -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="presupuesto_inicial" class="block text-sm font-medium text-gray-700 mb-1">
                                                Presupuesto Inicial <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                                <input type="number" 
                                                       wire:model.live="presupuesto_inicial" 
                                                       id="presupuesto_inicial"
                                                       step="0.01" 
                                                       min="0.01"
                                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('presupuesto_inicial') border-red-500 @enderror">
                                            </div>
                                            @error('presupuesto_inicial')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="presupuesto_actual" class="block text-sm font-medium text-gray-700 mb-1">
                                                Presupuesto Actual <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                                <input type="number" 
                                                       wire:model.live="presupuesto_actual" 
                                                       id="presupuesto_actual"
                                                       step="0.01" 
                                                       min="0.01"
                                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('presupuesto_actual') border-red-500 @enderror">
                                            </div>
                                            @error('presupuesto_actual')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Gastos y Compromisos -->
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="total_gastado" class="block text-sm font-medium text-gray-700 mb-1">
                                                Total Gastado
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                                <input type="number" 
                                                       wire:model.live="total_gastado" 
                                                       id="total_gastado"
                                                       step="0.01" 
                                                       min="0"
                                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('total_gastado') border-red-500 @enderror">
                                            </div>
                                            @error('total_gastado')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="total_comprometido" class="block text-sm font-medium text-gray-700 mb-1">
                                                Total Comprometido
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                                <input type="number" 
                                                       wire:model.live="total_comprometido" 
                                                       id="total_comprometido"
                                                       step="0.01" 
                                                       min="0"
                                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('total_comprometido') border-red-500 @enderror">
                                            </div>
                                            @error('total_comprometido')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Controles de Porcentaje -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="porcentaje_preventivo" class="block text-sm font-medium text-gray-700 mb-1">
                                                Límite Preventivo (%) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   wire:model="porcentaje_preventivo" 
                                                   id="porcentaje_preventivo"
                                                   min="50" 
                                                   max="100"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('porcentaje_preventivo') border-red-500 @enderror">
                                            @error('porcentaje_preventivo')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="alerta_porcentaje" class="block text-sm font-medium text-gray-700 mb-1">
                                                Alerta de Sobregiro (%) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   wire:model="alerta_porcentaje" 
                                                   id="alerta_porcentaje"
                                                   min="50" 
                                                   max="100"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('alerta_porcentaje') border-red-500 @enderror">
                                            @error('alerta_porcentaje')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Panel de Cálculos Automáticos -->
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-blue-900 mb-4">Información Calculada</h4>
                                    
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center p-3 bg-white rounded border">
                                            <span class="text-sm font-medium text-gray-700">Saldo Disponible:</span>
                                            <span class="text-lg font-bold {{ ($saldo_disponible ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                ${{ number_format($saldo_disponible ?? 0, 2) }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center p-3 bg-white rounded border">
                                            <span class="text-sm font-medium text-gray-700">Porcentaje Ejecutado:</span>
                                            <span class="text-lg font-bold {{ ($porcentaje_ejecutado ?? 0) > 90 ? 'text-red-600' : 'text-blue-600' }}">
                                                {{ number_format($porcentaje_ejecutado ?? 0, 1) }}%
                                            </span>
                                        </div>

                                        @if(($porcentaje_ejecutado ?? 0) >= ($porcentaje_preventivo ?? 80))
                                            <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded">
                                                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm text-yellow-800">Presupuesto en control preventivo</span>
                                            </div>
                                        @endif

                                        @if(($porcentaje_ejecutado ?? 0) >= ($alerta_porcentaje ?? 90))
                                            <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded">
                                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm text-red-800">¡Alerta de sobregiro!</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                            <a href="{{ route('presupuestos.show', $presupuesto) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Actualizar Presupuesto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
