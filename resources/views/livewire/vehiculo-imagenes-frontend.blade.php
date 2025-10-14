<div x-data="imageModal()"
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
                            Total imágenes: <span class="font-semibold">{{ $totalFotos }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Imagen Principal -->
        @if(!empty($imagenes['foto_principal']))
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Imagen Principal</h3>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="aspect-w-16 aspect-h-9 mb-2">
                    <img src="{{ $imagenes['foto_principal'][0] }}" 
                         alt="Imagen Principal {{ $vehiculo->placa }}" 
                         class="rounded-lg object-cover w-full h-full cursor-pointer"
                         @click="openModal('{{ $imagenes['foto_principal'][0] }}')"
                         loading="lazy">
                </div>
            </div>
        </div>
        @endif

        <!-- Galería de Fotos -->
        @if(!empty($imagenes['galeria_fotos']))
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Galería de Fotos</h3>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($imagenes['galeria_fotos'] as $imagen)
                    <div class="relative aspect-w-4 aspect-h-3">
                        <img src="{{ $imagen }}" 
                             alt="Galería {{ $vehiculo->placa }}" 
                             class="rounded-lg object-cover w-full h-full cursor-pointer"
                             @click="openModal('{{ $imagen }}')"
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
                    @if(!empty($imagenes['foto_tarjeton_propiedad']))
                    <div class="aspect-w-3 aspect-h-4 mb-2">
                        <img src="{{ $imagenes['foto_tarjeton_propiedad'][0] }}" 
                             alt="Tarjetón de Propiedad" 
                             class="rounded-lg object-cover w-full h-full cursor-pointer"
                             @click="openModal('{{ $imagenes['foto_tarjeton_propiedad'][0] }}')"
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
                    @if(!empty($imagenes['foto_cedula_identidad']))
                    <div class="aspect-w-3 aspect-h-4 mb-2">
                        <img src="{{ $imagenes['foto_cedula_identidad'][0] }}" 
                             alt="Cédula de Identidad" 
                             class="rounded-lg object-cover w-full h-full cursor-pointer"
                             @click="openModal('{{ $imagenes['foto_cedula_identidad'][0] }}')"
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
                    @if(!empty($imagenes['foto_seguro']))
                    <div class="aspect-w-3 aspect-h-4 mb-2">
                        <img src="{{ $imagenes['foto_seguro'][0] }}" 
                             alt="Seguro" 
                             class="rounded-lg object-cover w-full h-full cursor-pointer"
                             @click="openModal('{{ $imagenes['foto_seguro'][0] }}')"
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
                    @if(!empty($imagenes['foto_revision_tecnica']))
                    <div class="aspect-w-3 aspect-h-4 mb-2">
                        <img src="{{ $imagenes['foto_revision_tecnica'][0] }}" 
                             alt="Revisión Técnica" 
                             class="rounded-lg object-cover w-full h-full cursor-pointer"
                             @click="openModal('{{ $imagenes['foto_revision_tecnica'][0] }}')"
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
                    @if($documentosCompletos)
                    ✅ Todos los documentos requeridos han sido cargados.
                    @else
                    ❌ Faltan documentos requeridos por cargar.
                    @endif
                </p>
            </div>
        </div>

        <!-- Modal para visualizar imágenes -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80"
             @click="closeModal()"
             style="display: none;">
            <div class="relative max-w-4xl max-h-full p-4">
                <img :src="modalImage" 
                     alt="Imagen ampliada" 
                     class="max-w-full max-h-full object-contain rounded-lg">
                <button @click="closeModal()" 
                        class="absolute top-2 right-2 text-white text-3xl font-bold hover:text-gray-300">
                    &times;
                </button>
            </div>
        </div>

    <script>
        function imageModal() {
            return {
                modalOpen: false,
                modalImage: '',
                
                openModal(imageSrc) {
                    this.modalImage = imageSrc;
                    this.modalOpen = true;
                },
                
                closeModal() {
                    this.modalOpen = false;
                    this.modalImage = '';
                }
            }
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Trigger Alpine.js close modal
                window.dispatchEvent(new CustomEvent('close-modal'));
            }
        });
    </script>
</div>
