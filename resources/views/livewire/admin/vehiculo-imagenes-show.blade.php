<div class="max-w-4xl mx-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4">
    <!-- Encabezado con icono dentro del borde -->
    <div class="mb-4 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-md flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-600 text-white rounded-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10-6v4a1 1 0 01-1 1h-3M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-100">Gestión de Imágenes — {{ $vehiculo->placa }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $vehiculo->modelo }} • {{ $vehiculo->unidad }}</p>
            </div>
        </div>
        <div>
            <a href="/admin/vehiculos/imagenes" class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-gray-700 rounded hover:bg-gray-50">← Volver al listado</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow-sm">
        {{-- Reutilizamos el componente existente que maneja carga/galería/acciones --}}
        @livewire('vehiculo-imagenes', ['vehiculo' => $vehiculo])
    </div>
</div>
