<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                {{-- Header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <a href="{{ url()->previous() }}" 
                           onclick="event.preventDefault(); history.back();"
                           class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition mb-3">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 5L4 10l5 5" />
                                <path d="M4 10h12" />
                            </svg>
                            <span class="text-sm font-medium">Volver</span>
                        </a>

                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Gestión de Usuarios</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Administra los usuarios del sistema</p>
                    </div>
                    @can('usuarios.crear')
                        @php
                            $currentUser = auth()->user();
                            $canCreate = $currentUser->hasRole('Admin_General') || $currentUser->hasRole('Admin_Secretaria');
                        @endphp
                        
                        @if($canCreate)
                            <a href="{{ route('users.create') }}"
                               wire:navigate
                               role="button"
                               class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white text-sm font-semibold shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition transform hover:-translate-y-0.5">
                                <!-- Modern SVG: user + plus -->
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M15 14c2.761 0 5 2.239 5 5v1H4v-1c0-2.761 2.239-5 5-5h6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="9.5" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M19 8v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M21 10h-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Crear usuario</span>
                            </a>
                        @endif
                    @endcan
                </div>

                {{-- Filters and Search --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search"
                                   type="text" 
                                   placeholder="Buscar usuarios..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Unidad Filter --}}
                    <div>
                        <select wire:model.live="selectedUnidad" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Todas las unidades</option>
                            @foreach($unidades as $unidad)
                                <option value="{{ $unidad->id_unidad_organizacional }}">{{ $unidad->nombre_unidad }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Role Filter --}}
                    <div>
                        <select wire:model.live="selectedRole" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ str_replace('_', ' ', $role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Mostrando {{ $users->count() }} de {{ $users->total() }} usuarios
                    </p>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th wire:click="sortBy('name')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <div class="flex items-center">
                                        Nombre
                                        @if($sortBy === 'name')
                                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Unidad
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Rol
                                </th>
                                <th wire:click="sortBy('activo')" 
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($user->profile_photo_url)
                                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->full_name }}" 
                                                     class="h-8 w-8 rounded-full object-cover mr-3">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-sm font-medium mr-3">
                                                    {{ $user->initials }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->full_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->unidad?->nombre_unidad ?? 'Sin asignar' }}
                                        </div>
                                        @if($user->unidad?->codigo_unidad)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->unidad->codigo_unidad }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->primary_role)
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                {{ str_replace('_', ' ', $user->primary_role) }}
                                            </div>
                                            @if($user->roles->count() > 1)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">+{{ $user->roles->count() - 1 }}</div>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Sin rol</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->activo)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('users.show', $user->id) }}" 
                                               wire:navigate
                                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                               title="Ver usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            
                                            @can('usuarios.editar')
                                                @php
                                                    $currentUser = auth()->user();
                                                    $canManage = $currentUser->hasRole('Admin_General') || 
                                                               ($currentUser->hasRole('Admin_Secretaria') && $user->id_unidad_organizacional === $currentUser->id_unidad_organizacional) ||
                                                               ($currentUser->hasRole('Supervisor') && $user->id_supervisor === $currentUser->id && $user->hasRole('Conductor') && $user->id_unidad_organizacional === $currentUser->id_unidad_organizacional);
                                                @endphp
                                                
                                                @if($canManage)
                                                    <a href="{{ route('users.edit', $user->id) }}" 
                                                       wire:navigate
                                                       class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300"
                                                       title="Editar usuario">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('usuarios.eliminar')
                                                @if($canManage)
                                                    <button wire:click="confirmDelete({{ $user->id }})"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                            title="Eliminar usuario">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-lg font-medium">No se encontraron usuarios</p>
                                            <p class="mt-1">Intenta ajustar los filtros o crea un nuevo usuario</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ 
        show: false, 
        userId: null,
        init() {
            $wire.on('confirm-delete', (data) => {
                this.userId = data.userId;
                this.show = true;
            });
        }
    }"
         x-show="show"
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Confirmar eliminación</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="$wire.deleteUser(userId); show = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button @click="show = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
