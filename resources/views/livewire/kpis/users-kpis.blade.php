<div class="space-y-6">
    {{-- Tarjetas principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-4 rounded-xl shadow bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Usuarios</p>
                    <p class="text-2xl font-bold leading-none">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-xl shadow bg-gradient-to-r from-green-500 to-green-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Activos</p>
                    <p class="text-2xl font-bold leading-none">{{ number_format($activeUsers) }}</p>
                    @if($totalUsers > 0)
                        <p class="text-xs opacity-80 mt-1">{{ round(($activeUsers / $totalUsers) * 100,1) }}% del total</p>
                    @endif
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-xl shadow bg-gradient-to-r from-red-500 to-red-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Inactivos</p>
                    <p class="text-2xl font-bold leading-none">{{ number_format($inactiveUsers) }}</p>
                    @if($totalUsers > 0)
                        <p class="text-xs opacity-80 mt-1">{{ round(($inactiveUsers / $totalUsers) * 100,1) }}% del total</p>
                    @endif
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <line x1="5.22" y1="5.22" x2="18.78" y2="18.78" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-xl shadow bg-gradient-to-r from-purple-500 to-purple-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Supervisados</p>
                    <p class="text-2xl font-bold leading-none">{{ number_format($supervisedUsers) }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Distribución por roles con barras --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <h4 class="text-lg font-semibold mb-4">Distribución por Roles</h4>
            @if(count($usersByRole) > 0)
                @foreach($usersByRole as $role => $count)
                    @php
                        $pct = $totalUsers > 0 ? round(($count / $totalUsers) * 100,1) : 0;
                        $color = match(trim(strtolower($role))) {
                            'admin general' => 'bg-red-500',
                            'admin secretaria' => 'bg-indigo-500',
                            'supervisor' => 'bg-yellow-400',
                            'conductor' => 'bg-green-500',
                            default => 'bg-gray-400'
                        };
                    @endphp
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-1">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $role }}</div>
                            <div class="text-sm font-semibold">{{ $count }} <span class="text-xs text-gray-500">({{ $pct }}%)</span></div>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                            <div class="{{ $color }} h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-sm text-gray-500">No hay datos por roles</div>
            @endif
        </div>

        {{-- Usuarios recientes --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
            <h4 class="text-lg font-semibold mb-4">Usuarios Recientes</h4>
            @if(count($recentUsers) > 0)
                <ul class="space-y-3">
                    @foreach($recentUsers as $u)
                        <li class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-medium">{{ strtoupper(substr($u['name'] ?? '',0,1)) }}</div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 dark:text-gray-100">{{ $u['name'] }} {{ $u['apellido_paterno'] }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($u['created_at'])->format('d/m/Y') }} — {{ $u['email'] }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-500">No hay usuarios recientes</div>
            @endif
        </div>
    </div>
</div>
