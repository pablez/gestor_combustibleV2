<div class="bg-white rounded-lg shadow-lg p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-6 6c-1.098 0-2.118-.062-3-.306m0 0A17.9 17.9 0 014 15c0-1.105-.07-2.191-.306-3M15 7a2 2 0 00-2 2m2-2a2 2 0 00-2-2m2 2c1.098 0 2.118.062 3 .306m-15 4c0 1.105.07 2.191.306 3M4 18c0-1.105.07-2.191.306-3m0 0a17.9 17.9 0 005.306 0M4.306 15a17.9 17.9 0 005.306 0m7.888 0A17.9 17.9 0 0022.694 15M4.306 15c.07-.676.274-1.323.694-1.888m0 0A17.9 17.9 0 014 12m18.306 3c-.274.675-.694 1.212-1.388 1.888M22.694 15c-.07-.676-.274-1.323-.694-1.888m0 0A17.9 17.9 0 0122 12m-18.306 3A17.9 17.9 0 0012 22.694m6.306-7.694A17.9 17.9 0 0012 22.694m-6.306-7.694A17.9 17.9 0 0012 1.306m6.306 7.694A17.9 17.9 0 0012 1.306"/>
                </svg>
                Códigos de Registro
            </h3>
            <p class="text-sm text-gray-600">Genera códigos para registro de nuevos usuarios</p>
        </div>
        
        @if(auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']))
            <button wire:click="toggleFormulario" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Generar Código
            </button>
        @endif
    </div>

    {{-- Mensajes --}}
    @if($mensaje)
        <div class="mb-4 p-4 rounded-lg {{ $tipoMensaje === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : '' }}
                                      {{ $tipoMensaje === 'error' ? 'bg-red-50 text-red-800 border border-red-200' : '' }}
                                      {{ $tipoMensaje === 'info' ? 'bg-blue-50 text-blue-800 border border-blue-200' : '' }}"
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center justify-between">
                <span class="text-sm">{{ $mensaje }}</span>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Formulario de generación --}}
    @if($mostrarFormulario)
        <div class="mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Nuevo Código de Registro Personalizado</h4>
            
            <form wire:submit.prevent="generarCodigo">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Días de vigencia -->
                    <div>
                        <label for="diasVigencia" class="block text-sm font-medium text-gray-700 mb-1">
                            Días de vigencia *
                        </label>
                        <input type="number" 
                               wire:model="diasVigencia" 
                               id="diasVigencia"
                               min="1" 
                               max="365"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('diasVigencia') border-red-500 @enderror">
                        @error('diasVigencia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rol asignado -->
                    <div>
                        <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">
                            Rol para el nuevo usuario
                        </label>
                        <select wire:model="rol" id="rol" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rol') border-red-500 @enderror">
                            <option value="">Seleccionar rol</option>
                            @foreach($roles as $rolOption)
                                <option value="{{ $rolOption }}">{{ $rolOption }}</option>
                            @endforeach
                        </select>
                        @error('rol')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unidad organizacional -->
                    <div>
                        <label for="id_unidad_organizacional" class="block text-sm font-medium text-gray-700 mb-1">
                            Unidad organizacional
                        </label>
                        <select wire:model="id_unidad_organizacional" id="id_unidad_organizacional" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_unidad_organizacional') border-red-500 @enderror">
                            <option value="">Seleccionar unidad</option>
                            @foreach($unidades as $unidad)
                                <option value="{{ $unidad->id_unidad_organizacional }}">{{ $unidad->nombre_unidad }}</option>
                            @endforeach
                        </select>
                        @error('id_unidad_organizacional')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supervisor -->
                    <div>
                        <label for="id_supervisor" class="block text-sm font-medium text-gray-700 mb-1">
                            Supervisor asignado
                        </label>
                        <select wire:model="id_supervisor" id="id_supervisor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_supervisor') border-red-500 @enderror">
                            <option value="">Seleccionar supervisor</option>
                            @foreach($supervisores as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }} ({{ $supervisor->roles->first()?->name }})</option>
                            @endforeach
                        </select>
                        @error('id_supervisor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mt-4">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">
                        Observaciones
                    </label>
                    <textarea wire:model="observaciones" 
                              id="observaciones"
                              rows="3"
                              maxlength="500"
                              placeholder="Información adicional sobre este código de registro..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observaciones') border-red-500 @enderror"></textarea>
                    @error('observaciones')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Máximo 500 caracteres</p>
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            wire:click="toggleFormulario"
                            class="px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-md transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Generar Código
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Código recién generado --}}
    @if($codigoGenerado)
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <h4 class="text-md font-medium text-green-900 mb-2">¡Código Generado!</h4>
            <div class="flex items-center space-x-2">
                <code class="flex-1 px-3 py-2 bg-white border border-green-300 rounded text-green-900 font-mono text-lg">{{ $codigoGenerado }}</code>
                <button wire:click="copiarCodigo('{{ $codigoGenerado }}')" 
                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors">
                    Copiar
                </button>
            </div>
            <p class="text-sm text-green-700 mt-2">
                Válido por {{ $diasVigencia }} día(s) desde hoy
            </p>
        </div>
    @endif

    {{-- Lista de códigos vigentes --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-md font-medium text-gray-900">Códigos Vigentes</h4>
            <button wire:click="cargarCodigosVigentes" 
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Actualizar
            </button>
        </div>

        @if($codigosVigentes->isEmpty())
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0a9 9 0 119 9m-9-9a9 9 0 119 9m-9-9v6m9-3v6"/>
                </svg>
                <p class="text-gray-500 text-sm">No hay códigos vigentes</p>
                @if(auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']))
                    <p class="text-gray-400 text-xs">Genera un nuevo código para comenzar</p>
                @endif
            </div>
        @else
            <div class="space-y-3">
                @foreach($codigosVigentes as $codigo)
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <code class="px-3 py-1 bg-white border border-gray-300 rounded font-mono text-sm font-semibold">{{ $codigo->codigo }}</code>
                                    @if(auth()->user()->hasRole('Admin_General'))
                                        <span class="text-xs text-gray-500">por {{ $codigo->generador->name }}</span>
                                    @endif
                                </div>
                                
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p><strong>Válido hasta:</strong> {{ $codigo->vigente_hasta->format('d/m/Y') }} ({{ $codigo->vigente_hasta->diffForHumans() }})</p>
                                    
                                    @if($codigo->tienePersonalizacion())
                                        <div class="mt-2 p-2 bg-blue-50 rounded border border-blue-200">
                                            <p class="text-blue-800 font-medium text-xs mb-1">Configuración personalizada:</p>
                                            @if($codigo->rol_asignado)
                                                <p><strong>Rol:</strong> {{ $codigo->rol_asignado }}</p>
                                            @endif
                                            @if($codigo->unidadAsignada)
                                                <p><strong>Unidad:</strong> {{ $codigo->unidadAsignada->nombre_unidad }}</p>
                                            @endif
                                            @if($codigo->supervisorAsignado)
                                                <p><strong>Supervisor:</strong> {{ $codigo->supervisorAsignado->name }}</p>
                                            @endif
                                            @if($codigo->observaciones)
                                                <p><strong>Observaciones:</strong> {{ $codigo->observaciones }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">Sin personalización - el usuario completará todos los datos</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                <button wire:click="copiarCodigo('{{ $codigo->codigo }}')" 
                                        class="px-3 py-1 text-blue-600 hover:text-blue-800 text-xs border border-blue-300 rounded hover:bg-blue-50 transition-colors">
                                    Copiar
                                </button>
                                @if(auth()->user()->hasRole('Admin_General') || $codigo->id_usuario_generador === auth()->id())
                                    <button wire:click="marcarComoUsado({{ $codigo->id }})" 
                                            class="px-3 py-1 text-red-600 hover:text-red-800 text-xs border border-red-300 rounded hover:bg-red-50 transition-colors"
                                            onclick="return confirm('¿Marcar este código como usado?')">
                                        Marcar usado
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Script para copiar al portapapeles --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('copiarTexto', (texto) => {
            navigator.clipboard.writeText(texto).then(() => {
                console.log('Texto copiado al portapapeles');
            }).catch(err => {
                console.error('Error al copiar texto: ', err);
            });
        });

        Livewire.on('limpiarMensaje', () => {
            setTimeout(() => {
                Livewire.dispatch('limpiarMensaje');
            }, 5000);
        });
    });
</script>