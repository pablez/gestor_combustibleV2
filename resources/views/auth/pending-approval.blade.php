<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Cuenta Pendiente</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-green-50">
            <!-- Header Institucional -->
            <div class="w-full max-w-md mb-6">
                <div class="bg-gradient-to-r from-blue-800 to-blue-900 rounded-t-lg p-4 text-center">
                    <div class="flex items-center justify-center space-x-3 mb-2">
                        <div class="bg-white p-2 rounded-full">
                            <svg class="w-8 h-8 text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">Gobernación de Cochabamba</h1>
                            <p class="text-blue-100 text-sm">Sistema de Gestión de Combustible</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="w-full max-w-md px-6 py-8 bg-white shadow-lg overflow-hidden rounded-b-lg border-t-4 border-amber-500">
                <!-- Icono de Reloj -->
                <div class="flex justify-center mb-6">
                    <div class="bg-amber-100 p-4 rounded-full">
                        <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Título -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cuenta Pendiente de Aprobación</h2>
                    <p class="text-gray-600">Su registro ha sido exitoso y está siendo revisado</p>
                </div>

                <!-- Información -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-amber-800">
                            <p class="font-medium mb-1">Su solicitud está en proceso</p>
                            <p>Un administrador del sistema revisará y aprobará su cuenta. Recibirá una notificación por correo electrónico cuando sea aprobada.</p>
                        </div>
                    </div>
                </div>

                <!-- Pasos siguientes -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">¿Qué pasa ahora?</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 rounded-full p-1 mt-0.5">
                                <span class="text-blue-600 text-xs font-bold">1</span>
                            </div>
                            <p class="text-sm text-gray-700">Su solicitud será revisada por un administrador</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 rounded-full p-1 mt-0.5">
                                <span class="text-blue-600 text-xs font-bold">2</span>
                            </div>
                            <p class="text-sm text-gray-700">Recibirá un correo de confirmación cuando sea aprobada</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 rounded-full p-1 mt-0.5">
                                <span class="text-blue-600 text-xs font-bold">3</span>
                            </div>
                            <p class="text-sm text-gray-700">Podrá acceder al sistema con sus credenciales</p>
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-blue-900 mb-2">¿Necesita ayuda?</h4>
                    <p class="text-sm text-blue-800">Si tiene urgencia o preguntas sobre su solicitud, contacte al administrador del sistema.</p>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('login') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Login
                    </a>
                    
                    <a href="{{ route('register') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        ¿Otra cuenta?
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>© 2025 Gobernación de Cochabamba - Sistema de Gestión de Combustible</p>
            </div>
        </div>
    </body>
</html>