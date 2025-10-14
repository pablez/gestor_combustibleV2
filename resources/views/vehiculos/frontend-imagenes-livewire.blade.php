<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" onclick="event.preventDefault(); (window.history.length > 1 ? window.history.back() : window.location.href='{{ url()->previous() }}');" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border">
                    <!-- Back icon -->
                    <svg class="h-4 w-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver
                </a>

                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Imágenes del Vehículo') }} - {{ $vehiculo->placa }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('vehiculo-imagenes-frontend', ['vehiculo' => $vehiculo])
        </div>
    </div>
</x-app-layout>