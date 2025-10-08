<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Imágenes - {{ $vehiculo->placa }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            Sistema de Gestión de Imágenes
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            Usuario: {{ auth()->user()->name ?? 'Demo' }}
                        </span>
                        <a href="{{ route('dashboard') }}" 
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Información del vehículo -->
            <div class="mb-6 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                🚗 Vehículo: {{ $vehiculo->placa }}
                            </h2>
                            <p class="text-sm text-gray-600">
                                {{ $vehiculo->marca }} {{ $vehiculo->modelo }} 
                                @if($vehiculo->anio_fabricacion)
                                    ({{ $vehiculo->anio_fabricacion }})
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">
                                Tipo: {{ $vehiculo->tipoVehiculo->nombre ?? 'No especificado' }} | 
                                Estado: <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $vehiculo->estado_operativo === 'Operativo' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $vehiculo->estado_operativo }}
                                </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Unidad Organizacional</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $vehiculo->unidadOrganizacional->nombre ?? 'No asignada' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Componente de imágenes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4">
                    @livewire('vehiculo-imagenes', ['vehiculo' => $vehiculo])
                </div>
            </div>

            <!-- Panel de ayuda -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-800 mb-2">💡 Guía de Uso</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Haz clic en cualquier tarjeta para subir imágenes</li>
                    <li>• Puedes arrastrar y soltar archivos en el modal</li>
                    <li>• Los documentos marcados como "Requeridos" son obligatorios</li>
                    <li>• La galería permite múltiples imágenes</li>
                    <li>• Haz clic en una imagen para verla en tamaño completo</li>
                    <li>• Usa los botones de optimización para mejorar el rendimiento</li>
                </ul>
            </div>

            <!-- Comandos CLI disponibles -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-800 mb-2">🖥️ Comandos CLI Disponibles</h3>
                <div class="space-y-2 text-sm font-mono text-gray-600">
                    <div>
                        <code class="bg-gray-200 px-2 py-1 rounded">./vendor/bin/sail artisan vehiculos:imagenes estadisticas</code>
                        <span class="ml-2 text-gray-500">- Ver estadísticas del sistema</span>
                    </div>
                    <div>
                        <code class="bg-gray-200 px-2 py-1 rounded">./vendor/bin/sail artisan vehiculos:imagenes limpiar</code>
                        <span class="ml-2 text-gray-500">- Limpiar imágenes huérfanas</span>
                    </div>
                    <div>
                        <code class="bg-gray-200 px-2 py-1 rounded">./vendor/bin/sail artisan vehiculos:imagenes optimizar</code>
                        <span class="ml-2 text-gray-500">- Optimizar todas las imágenes</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>