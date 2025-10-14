<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Imágenes del Vehículo - {{ $vehiculo->placa }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        .image-container {
            position: relative;
            height: 0;
            padding-bottom: 75%;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .image-container:hover img {
            transform: scale(1.05);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }
        .modal-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            Imágenes del Vehículo - {{ $vehiculo->placa }}
                        </h1>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Información del vehículo -->
            <div class="mb-6 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                🚗 {{ $vehiculo->marca }} {{ $vehiculo->modelo }} {{ $vehiculo->anio_fabricacion }}
                            </h2>
                            <p class="text-sm text-gray-600">
                                Placa: <span class="font-semibold">{{ $vehiculo->placa }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $vehiculo->estado_operativo === 'Operativo' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $vehiculo->estado_operativo }}
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Total imágenes: <span class="font-semibold">{{ $data['total_fotos'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagen Principal -->
            @if(!empty($data['data']['foto_principal']))
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Imagen Principal</h3>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="aspect-w-16 aspect-h-9 mb-2">
                        <img src="{{ $data['data']['foto_principal'][0] }}" 
                             alt="Imagen Principal {{ $vehiculo->placa }}" 
                             class="rounded-lg object-cover w-full h-full cursor-pointer"
                             onclick="openModal('{{ $data['data']['foto_principal'][0] }}')"
                             loading="lazy">
                    </div>
                </div>
            </div>
            @endif

            <!-- Galería de Fotos -->
            @if(!empty($data['data']['galeria_fotos']))
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Galería de Fotos</h3>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="image-gallery">
                        @foreach($data['data']['galeria_fotos'] as $imagen)
                        <div class="image-container">
                            <img src="{{ $imagen }}" 
                                 alt="Galería {{ $vehiculo->placa }}" 
                                 class="cursor-pointer"
                                 onclick="openModal('{{ $imagen }}')"
                                 loading="lazy">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Documentos -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Documentos del Vehículo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Tarjetón Propiedad -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="text-base font-medium text-gray-800 mb-2">Tarjetón de Propiedad</h4>
                        @if(!empty($data['data']['foto_tarjeton_propiedad']))
                        <div class="aspect-w-3 aspect-h-4 mb-2">
                            <img src="{{ $data['data']['foto_tarjeton_propiedad'][0] }}" 
                                 alt="Tarjetón de Propiedad" 
                                 class="rounded-lg object-cover w-full h-full cursor-pointer"
                                 onclick="openModal('{{ $data['data']['foto_tarjeton_propiedad'][0] }}')"
                                 loading="lazy">
                        </div>
                        @else
                        <div class="flex items-center justify-center h-40 bg-gray-100 rounded-lg">
                            <p class="text-gray-500 text-sm">No disponible</p>
                        </div>
                        @endif
                    </div>

                    <!-- Cédula de Identidad -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="text-base font-medium text-gray-800 mb-2">Cédula de Identidad</h4>
                        @if(!empty($data['data']['foto_cedula_identidad']))
                        <div class="aspect-w-3 aspect-h-4 mb-2">
                            <img src="{{ $data['data']['foto_cedula_identidad'][0] }}" 
                                 alt="Cédula de Identidad" 
                                 class="rounded-lg object-cover w-full h-full cursor-pointer"
                                 onclick="openModal('{{ $data['data']['foto_cedula_identidad'][0] }}')"
                                 loading="lazy">
                        </div>
                        @else
                        <div class="flex items-center justify-center h-40 bg-gray-100 rounded-lg">
                            <p class="text-gray-500 text-sm">No disponible</p>
                        </div>
                        @endif
                    </div>

                    <!-- Seguro -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="text-base font-medium text-gray-800 mb-2">Seguro</h4>
                        @if(!empty($data['data']['foto_seguro']))
                        <div class="aspect-w-3 aspect-h-4 mb-2">
                            <img src="{{ $data['data']['foto_seguro'][0] }}" 
                                 alt="Seguro" 
                                 class="rounded-lg object-cover w-full h-full cursor-pointer"
                                 onclick="openModal('{{ $data['data']['foto_seguro'][0] }}')"
                                 loading="lazy">
                        </div>
                        @else
                        <div class="flex items-center justify-center h-40 bg-gray-100 rounded-lg">
                            <p class="text-gray-500 text-sm">No disponible</p>
                        </div>
                        @endif
                    </div>

                    <!-- Revisión Técnica -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="text-base font-medium text-gray-800 mb-2">Revisión Técnica</h4>
                        @if(!empty($data['data']['foto_revision_tecnica']))
                        <div class="aspect-w-3 aspect-h-4 mb-2">
                            <img src="{{ $data['data']['foto_revision_tecnica'][0] }}" 
                                 alt="Revisión Técnica" 
                                 class="rounded-lg object-cover w-full h-full cursor-pointer"
                                 onclick="openModal('{{ $data['data']['foto_revision_tecnica'][0] }}')"
                                 loading="lazy">
                        </div>
                        @else
                        <div class="flex items-center justify-center h-40 bg-gray-100 rounded-lg">
                            <p class="text-gray-500 text-sm">No disponible</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estado de Documentación -->
            <div class="mb-6 bg-white overflow-hidden shadow rounded-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Estado de documentación</h3>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $documentosCompletadosPorcentaje }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ $documentosCompletadosPorcentaje }}%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($data['documentos_completos'])
                        ✅ Todos los documentos requeridos han sido cargados.
                        @else
                        ❌ Faltan documentos requeridos por cargar.
                        @endif
                    </p>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para visualizar imágenes -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close-modal">&times;</span>
        <div class="modal-content">
            <img id="modalImage" class="modal-img" src="" alt="Imagen ampliada">
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = "block";
            modalImg.src = imageSrc;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = "none";
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>