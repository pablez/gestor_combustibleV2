<div class="fixed top-16 right-4 z-50 w-96 max-w-sm" x-data="{ mostrarPanel: @entangle('mostrarAlertas') }">
    {{-- Botón toggle para mostrar/ocultar alertas --}}
    <button 
        @click="mostrarPanel = !mostrarPanel"
        class="mb-2 w-full flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
        <div class="flex items-center space-x-2">
            <div class="relative">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5 5-5h-5m-6 10v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
                @if(count($alertasActivas) > 0)
                    <div class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                        {{ count($alertasActivas) > 9 ? '9+' : count($alertasActivas) }}
                    </div>
                @endif
            </div>
            <span class="text-sm font-medium text-gray-700">
                Alertas del Sistema
                @if(count($alertasActivas) > 0)
                    <span class="text-red-600">({{ count($alertasActivas) }})</span>
                @endif
            </span>
        </div>
        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200" 
             :class="{ 'rotate-180': mostrarPanel }" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Panel de alertas --}}
    <div x-show="mostrarPanel" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="bg-white border border-gray-200 rounded-lg shadow-2xl max-h-96 overflow-hidden">
        
        {{-- Header del panel --}}
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">🚨 Alertas Activas</h3>
                <button wire:click="cargarAlertas" 
                        class="text-xs text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Actualizar</span>
                </button>
            </div>
        </div>

        {{-- Lista de alertas --}}
        <div class="max-h-80 overflow-y-auto">
            @if(count($alertasActivas) === 0)
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-600 font-medium">¡Todo en orden!</p>
                    <p class="text-xs text-gray-500 mt-1">No hay alertas activas en el sistema</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($alertasActivas as $alerta)
                    <div class="p-4 hover:bg-gray-50 transition-colors relative group">
                        {{-- Botón cerrar alerta --}}
                        <button wire:click="cerrarAlerta('{{ $alerta['id'] }}')"
                                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="flex items-start space-x-3">
                            {{-- Indicador de tipo --}}
                            <div class="flex-shrink-0 mt-1">
                                @if($alerta['tipo'] === 'critica')
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                @elseif($alerta['tipo'] === 'importante')
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                @else
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                @endif
                            </div>

                            {{-- Contenido de la alerta --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $alerta['titulo'] }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($alerta['tipo'] === 'critica') bg-red-100 text-red-800 @endif
                                        @if($alerta['tipo'] === 'importante') bg-yellow-100 text-yellow-800 @endif
                                        @if($alerta['tipo'] === 'informativa') bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($alerta['tipo']) }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-700 mb-1">{{ $alerta['mensaje'] }}</p>
                                <p class="text-xs text-gray-500 mb-2">{{ $alerta['detalle'] }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-400">{{ $alerta['tiempo'] }}</span>
                                    <button class="text-xs font-medium 
                                        @if($alerta['tipo'] === 'critica') text-red-600 hover:text-red-800 @endif
                                        @if($alerta['tipo'] === 'importante') text-yellow-600 hover:text-yellow-800 @endif
                                        @if($alerta['tipo'] === 'informativa') text-blue-600 hover:text-blue-800 @endif
                                        transition-colors">
                                        {{ $alerta['accion'] }} →
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer del panel --}}
        @if(count($alertasActivas) > 0)
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between text-xs text-gray-600">
                <span>{{ count($alertasActivas) }} alerta(s) activa(s)</span>
                <span>Actualizado: {{ now()->format('H:i') }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Script para auto-actualización cada 5 minutos --}}
<script>
    setInterval(function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('refreshAlertas');
        }
    }, 300000); // 5 minutos = 300000ms
</script>