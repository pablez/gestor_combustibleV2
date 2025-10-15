<div class="space-y-6">
    {{-- Header con título y botón de crear --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 rounded-xl shadow-lg">
        <div>
            <h1 class="text-2xl font-bold mb-2">📋 Gestión de Solicitudes</h1>
            <p class="text-blue-100">Administra y supervisa todas las solicitudes de combustible</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <livewire:solicitud.create />
        </div>
    </div>

    {{-- Filtros mejorados --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900">Filtros de Búsqueda</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </label>
                <input type="text" 
                       wire:model.live="buscar" 
                       id="buscar"
                       placeholder="Número, motivo o solicitante..."
                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            </div>
            
            <div>
                <label for="filtroEstado" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Estado
                </label>
                <select wire:model.live="filtroEstado" 
                        id="filtroEstado"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="">🔍 Todos los estados</option>
                    <option value="Pendiente">⏳ Pendiente</option>
                    <option value="Aprobada">✅ Aprobada</option>
                    <option value="Rechazada">❌ Rechazada</option>
                    <option value="En_Proceso">🔄 En Proceso</option>
                    <option value="Completada">✔️ Completada</option>
                </select>
            </div>
            
            <div>
                <label for="filtroUrgente" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    Prioridad
                </label>
                <select wire:model.live="filtroUrgente" 
                        id="filtroUrgente"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="">📋 Todas las prioridades</option>
                    <option value="1">🚨 Solo urgentes</option>
                    <option value="0">📝 No urgentes</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button wire:click="limpiarFiltros" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar
                </button>
            </div>
        </div>
        
        @if($filtroUrgente == 1)
            <div class="mt-4 p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            🚨 Mostrando solo solicitudes <strong>urgentes</strong> - Requieren atención inmediata
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Tabla de solicitudes mejorada --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Solicitudes de Combustible
                </h3>
                <div class="text-sm text-gray-500">
                    Total: <span class="font-semibold text-gray-700">{{ $solicitudes->total() }}</span> solicitudes
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1">#</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1">Número</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0a9 9 0 119 9m-9-9a9 9 0 119 9m-9-9v6m9-3v6"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1">Solicitante</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-1">Litros</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prioridad</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($solicitudes as $s)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 {{ $s->urgente ? 'bg-red-50 border-l-4 border-red-400' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">
                                    {{ str_pad($s->id, 3, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-blue-600">{{ $s->numero_solicitud }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">
                                                {{ substr($s->solicitante?->name ?? 'N', 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $s->solicitante?->name ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8zM3 5a2 2 0 012-2h1a3 3 0 003 3h2a3 3 0 003-3h1a2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L13.414 13H18v3a2 2 0 01-2 2H4a2 2 0 01-2-2V5zM11 2a1 1 0 100 2h2a1 1 0 100-2h-2z"/>
                                    </svg>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($s->cantidad_litros_solicitados, 2) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">L</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($s->estado_solicitud === 'Pendiente') bg-yellow-100 text-yellow-800 ring-1 ring-yellow-300
                                    @elseif($s->estado_solicitud === 'Aprobada') bg-green-100 text-green-800 ring-1 ring-green-300
                                    @elseif($s->estado_solicitud === 'Rechazada') bg-red-100 text-red-800 ring-1 ring-red-300
                                    @elseif($s->estado_solicitud === 'En_Proceso') bg-blue-100 text-blue-800 ring-1 ring-blue-300
                                    @elseif($s->estado_solicitud === 'Completada') bg-gray-100 text-gray-800 ring-1 ring-gray-300
                                    @else bg-gray-100 text-gray-800 ring-1 ring-gray-300
                                    @endif">
                                    @if($s->estado_solicitud === 'Pendiente') ⏳
                                    @elseif($s->estado_solicitud === 'Aprobada') ✅
                                    @elseif($s->estado_solicitud === 'Rechazada') ❌
                                    @elseif($s->estado_solicitud === 'En_Proceso') 🔄
                                    @elseif($s->estado_solicitud === 'Completada') ✔️
                                    @else 📋
                                    @endif
                                    {{ str_replace('_', ' ', $s->estado_solicitud) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($s->urgente)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 ring-1 ring-red-300 animate-pulse">
                                        🚨 Urgente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        📝 Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $s->created_at->format('d/m/Y') }}</span>
                                    <span class="text-xs text-gray-400">{{ $s->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('solicitudes.show', $s->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['Admin_General', 'Admin_Secretaria']) || $s->id_usuario_solicitante === auth()->id())
                                        @if($s->estado_solicitud === 'Pendiente')
                                            <a href="{{ route('solicitudes.edit', $s->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-300 text-gray-500 text-xs font-medium rounded-lg">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Bloqueado
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 0a9 9 0 119 9m-9-9a9 9 0 119 9m-9-9v6m9-3v6"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron solicitudes</h3>
                                    <p class="text-gray-500 mb-4">No hay solicitudes que coincidan con los filtros aplicados</p>
                                    @if(!empty($buscar) || !empty($filtroEstado) || $filtroUrgente !== null)
                                        <button wire:click="limpiarFiltros" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Limpiar filtros y ver todas las solicitudes
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($solicitudes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $solicitudes->links() }}
            </div>
        @endif
    </div>
</div>
