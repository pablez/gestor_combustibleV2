<div class="space-y-6">
    
    {{-- KPIs Principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Total Usuarios --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Usuarios</p>
                    <p class="text-3xl font-bold">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Usuarios Activos --}}
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Usuarios Activos</p>
                    <p class="text-3xl font-bold">{{ number_format($activeUsers) }}</p>
                    @if($totalUsers > 0)
                        <p class="text-green-200 text-xs">{{ round(($activeUsers / $totalUsers) * 100, 1) }}% del total</p>
                    @endif
                </div>
                <div class="p-3 bg-green-400 bg-opacity-30 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Usuarios Inactivos --}}
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Usuarios Inactivos</p>
                    <p class="text-3xl font-bold">{{ number_format($inactiveUsers) }}</p>
                    @if($totalUsers > 0)
                        <p class="text-red-200 text-xs">{{ round(($inactiveUsers / $totalUsers) * 100, 1) }}% del total</p>
                    @endif
                </div>
                <div class="p-3 bg-red-400 bg-opacity-30 rounded-full">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="5.22" y1="5.22" x2="18.78" y2="18.78" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Usuarios Supervisados --}}
        @if($supervisedUsers > 0)
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">
                        @if(auth()->user()->hasRole('Supervisor'))
                            Supervisados por mí
                        @else
                            Con Supervisión
                        @endif
                    </p>
                    <p class="text-3xl font-bold">{{ number_format($supervisedUsers) }}</p>
                </div>
                <div class="p-3 bg-purple-400 bg-opacity-30 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
        @endif

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Usuarios por Rol --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Distribución por Roles</h3>
            
            @if(count($usersByRole) > 0)
                <div class="space-y-3">
                    @foreach($usersByRole as $role => $count)
                        @php
                            $percentage = $totalUsers > 0 ? round(($count / $totalUsers) * 100, 1) : 0;
                            $colorClass = match($role) {
                                'Admin General' => 'bg-red-500',
                                'Admin Secretaria' => 'bg-blue-500',
                                'Supervisor' => 'bg-yellow-500',
                                'Conductor' => 'bg-green-500',
                                default => 'bg-gray-500'
                            };
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 {{ $colorClass }} rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $role }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $count }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $percentage }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="{{ $colorClass }} h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No hay usuarios disponibles</p>
                </div>
            @endif
        </div>

        {{-- Usuarios por Unidad --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Distribución por Unidad</h3>
            
            @if(count($usersByUnidad) > 0)
                <div class="space-y-4">
                    @foreach($usersByUnidad as $unidad)
                        @php
                            $percentage = $totalUsers > 0 ? round(($unidad['count'] / $totalUsers) * 100, 1) : 0;
                        @endphp
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-3 last:border-b-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $unidad['codigo_unidad'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $unidad['nombre_unidad'] }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $unidad['count'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">({{ $percentage }}%)</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">No hay unidades disponibles</p>
                </div>
            @endif
        </div>

    </div>

    {{-- Usuarios Recientes --}}
    @if(count($recentUsers) > 0)
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
            Usuarios Recientes (últimos 7 días)
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Registrado
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($recentUsers as $user)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $user['name'] }} {{ $user['apellido_paterno'] }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user['email'] }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Acceso Rápido --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Acceso Rápido</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            
            @can('usuarios.ver')
                <a href="{{ route('users.index') }}" 
                   wire:navigate
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                    <div class="p-2 bg-blue-100 dark:bg-blue-800 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Gestionar Usuarios</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ver listado completo</p>
                    </div>
                </a>
            @endcan

            @can('usuarios.crear')
                @php
                    $currentUser = auth()->user();
                    $canCreate = $currentUser->hasRole('Admin_General') || $currentUser->hasRole('Admin_Secretaria');
                @endphp
                
                @if($canCreate)
                    <a href="{{ route('users.create') }}" 
                       wire:navigate
                       class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                        <div class="p-2 bg-green-100 dark:bg-green-800 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Crear Usuario</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Agregar nuevo usuario</p>
                        </div>
                    </a>
                @endif
            @endcan

            @can('unidades.ver')
                <a href="#" 
                   class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors group">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-800 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Unidades</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Gestionar unidades</p>
                    </div>
                </a>
            @endcan

        </div>
    </div>

</div>