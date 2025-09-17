<div class="max-w-7xl mx-auto px-4 py-6">
    <x-slot name="header">
        <h2 class="text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            Unidades organizacionales
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <label class="text-sm text-gray-600 dark:text-gray-300 font-medium">Mostrar</label>
                <select wire:model="perPage" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <label class="text-sm text-gray-600 dark:text-gray-300 font-medium">Tipo</label>
                <select wire:model="tipoFilter" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="">Todos</option>
                    <option value="Operativa">Operativa</option>
                    <option value="Ejecutiva">Ejecutiva</option>
                    <option value="Superior">Superior</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.debounce.300ms="search" wire:keydown.enter="applySearch" type="text" placeholder="Buscar por código o nombre..." class="w-full border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" />
                    <div wire:loading wire:target="search" class="absolute right-3 top-2">
                        <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </div>
                </div>

                <button type="button" wire:click="applySearch" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar
                </button>

                <div class="ml-2">
                    <button type="button" wire:click="openCreate" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Unidad
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 text-sm bg-emerald-100 text-emerald-800 p-2 rounded">{{ session('success') }}</div>
        @endif

        <!-- Debug: mostrar tipos únicos en BD -->
        <div class="mb-4 text-xs text-gray-500">Tipos en BD: {{ implode(', ', $tiposUnicos) }}</div>

        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-separate border-spacing-0">
                <thead>
                    <tr class="text-sm text-gray-500 uppercase bg-gray-50 dark:bg-gray-700">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Código</th>
                        <th class="py-3 px-4">Nombre</th>
                        <th class="py-3 px-4">Tipo</th>
                        <th class="py-3 px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                @forelse($unidades as $index => $u)
                    <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-3 px-4">{{ ($unidades->currentPage()-1) * $unidades->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4 font-medium">{{ $u->codigo_unidad }}</td>
                        <td class="py-3 px-4">{{ $u->nombre_unidad }}</td>
                        <td class="py-3 px-4">{{ $u->tipo_unidad }}</td>
                        <td class="py-3 px-4 flex gap-2 items-center">
                            <a href="{{ route('unidades.show', ['id' => $u->id_unidad_organizacional]) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-800 rounded-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver
                            </a>
                            @if(auth()->user() && (auth()->user()->hasPermissionTo('unidades.editar') || auth()->user()->hasRole('Admin_General')))
                                <button type="button" wire:click.prevent="openEdit({{ $u->id_unidad_organizacional }})" class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 hover:text-amber-800 rounded-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                                </button>
                            @endif
                            <button onclick="if(!confirm('¿Eliminar unidad?')) return false;" wire:click="delete({{ $u->id_unidad_organizacional }})" class="inline-flex items-center gap-1 px-3 py-1 bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 rounded-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 px-4 text-center text-gray-500">No se encontraron unidades.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">Mostrando {{ $unidades->count() }} de {{ $unidades->total() }} resultados — Página: {{ $unidades->currentPage() }} — perPage: {{ $perPage }}</div>
            <div>{{ $unidades->links('pagination::tailwind', ['pageName' => 'unidades_page']) }}</div>
        </div>

        <!-- Modales Create / Edit dentro del mismo root para evitar conflictos Livewire -->
        @livewire('unidades.create')
        @livewire('unidades.edit')
    </div>
</div>
