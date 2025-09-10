<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
                <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-white dark:bg-gray-900 rounded shadow">
                            <div class="text-sm text-gray-500">Usuarios</div>
                            <div class="text-2xl font-bold">
                                {{ \App\Models\User::count() }}
                            </div>
                            @can('usuarios.gestionar')
                                <a href="{{ route('profile') }}" class="text-sm text-blue-600">Ver usuarios</a>
                            @endcan
                        </div>

                        <div class="p-4 bg-white dark:bg-gray-900 rounded shadow">
                            <div class="text-sm text-gray-500">Unidades organizacionales</div>
                            <div class="text-2xl font-bold">
                                {{ \App\Models\UnidadOrganizacional::count() }}
                            </div>
                            @if(auth()->check() && (auth()->user()->can('usuarios.ver') || auth()->user()->can('usuarios.gestionar')))
                                <a href="{{ route('unidades.index') }}" class="text-sm text-blue-600">Ir a Unidades</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
