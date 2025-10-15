<div class="space-y-6">
    {{-- Estadísticas Rápidas --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $estadisticasRapidas['solicitudes_hoy'] }}</p>
            <p class="text-sm text-gray-600">Solicitudes Hoy</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $estadisticasRapidas['despachos_hoy'] }}</p>
            <p class="text-sm text-gray-600">Despachos Hoy</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
            <p class="text-2xl font-bold text-purple-600">{{ number_format($estadisticasRapidas['litros_hoy'], 0) }}L</p>
            <p class="text-sm text-gray-600">Litros Hoy</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $estadisticasRapidas['vehiculos_operativos'] }}</p>
            <p class="text-sm text-gray-600">Vehículos Operativos</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $estadisticasRapidas['usuarios_activos_hoy'] }}</p>
            <p class="text-sm text-gray-600">Usuarios Activos</p>
        </div>
    </div>

    {{-- Notificaciones Pendientes --}}
    @if(count($notificacionesPendientes) > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🔔 Notificaciones Pendientes</h3>
        </div>
        <div class="p-4">
            <div class="space-y-3">
                @foreach($notificacionesPendientes as $notificacion)
                <div class="flex items-center justify-between p-3 rounded-lg border-l-4 
                    @if($notificacion['tipo'] === 'urgente') border-red-500 bg-red-50 @endif
                    @if($notificacion['tipo'] === 'mantenimiento') border-yellow-500 bg-yellow-50 @endif
                    @if($notificacion['tipo'] === 'validacion') border-blue-500 bg-blue-50 @endif">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $notificacion['mensaje'] }}</p>
                    </div>
                    <a href="{{ $notificacion['ruta'] }}" 
                       class="ml-4 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white 
                              @if($notificacion['tipo'] === 'urgente') bg-red-600 hover:bg-red-700 @endif
                              @if($notificacion['tipo'] === 'mantenimiento') bg-yellow-600 hover:bg-yellow-700 @endif
                              @if($notificacion['tipo'] === 'validacion') bg-blue-600 hover:bg-blue-700 @endif">
                        {{ $notificacion['accion'] }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Accesos Rápidos para Administradores --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">⚡ Accesos Rápidos</h3>
            <p class="text-sm text-gray-600">Acciones más utilizadas para administradores</p>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($accesosAdministrador as $acceso)
                <a href="{{ $acceso['ruta'] }}" 
                   class="relative group block p-4 border border-gray-200 rounded-lg hover:border-{{ $acceso['color'] }}-300 hover:shadow-md transition-all duration-200">
                    
                    {{-- Badge de contador si existe --}}
                    @if($acceso['contador'] !== null && $acceso['contador'] > 0)
                    <div class="absolute -top-2 -right-2 bg-{{ $acceso['color'] }}-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center 
                         @if($acceso['urgente']) animate-pulse @endif">
                        {{ $acceso['contador'] > 99 ? '99+' : $acceso['contador'] }}
                    </div>
                    @endif

                    <div class="flex items-start space-x-3">
                        {{-- Icono --}}
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-{{ $acceso['color'] }}-100 rounded-lg flex items-center justify-center group-hover:bg-{{ $acceso['color'] }}-200 transition-colors">
                                @if($acceso['icono'] === 'clipboard-list')
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                @elseif($acceso['icono'] === 'chart-bar')
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                @elseif($acceso['icono'] === 'truck')
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                @elseif($acceso['icono'] === 'users')
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                @elseif($acceso['icono'] === 'check-circle')
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @elseif($acceso['icono'] === 'monitor')
                                    <svg class="w-6 h-6 text-{{ $acceso['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-{{ $acceso['color'] }}-600 transition-colors">
                                {{ $acceso['titulo'] }}
                            </h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $acceso['descripcion'] }}</p>
                        </div>
                    </div>

                    {{-- Indicador de acción --}}
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-xs text-{{ $acceso['color'] }}-600 font-medium group-hover:text-{{ $acceso['color'] }}-700">
                            Acceder →
                        </span>
                        @if($acceso['urgente'] && $acceso['contador'] > 0)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Urgente
                            </span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>