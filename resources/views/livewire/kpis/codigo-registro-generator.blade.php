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
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h4 class="text-md font-medium text-gray-900 mb-3">Nuevo Código de Registro</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="diasVigencia" class="block text-sm font-medium text-gray-700 mb-1">
                        Días de vigencia
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
                
                <div class="flex items-end">
                    <button wire:click="generarCodigo" 
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        Generar Código
                    </button>
                </div>
            </div>
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
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <code class="px-2 py-1 bg-white border border-gray-300 rounded font-mono text-sm">{{ $codigo->codigo }}</code>
                                @if(auth()->user()->hasRole('Admin_General'))
                                    <span class="text-xs text-gray-500">por {{ $codigo->generador->name }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Válido hasta: {{ $codigo->vigente_hasta->format('d/m/Y') }}
                                ({{ $codigo->vigente_hasta->diffForHumans() }})
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button wire:click="copiarCodigo('{{ $codigo->codigo }}')" 
                                    class="px-2 py-1 text-blue-600 hover:text-blue-800 text-xs">
                                Copiar
                            </button>
                            @if(auth()->user()->hasRole('Admin_General') || $codigo->id_usuario_generador === auth()->id())
                                <button wire:click="marcarComoUsado({{ $codigo->id }})" 
                                        class="px-2 py-1 text-red-600 hover:text-red-800 text-xs"
                                        onclick="return confirm('¿Marcar este código como usado?')">
                                    Marcar usado
                                </button>
                            @endif
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