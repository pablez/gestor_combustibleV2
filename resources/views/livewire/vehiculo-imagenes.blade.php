<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header con estadísticas -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                📸 Gestión de Imágenes
            </h2>
            <p class="text-gray-600">Vehículo: <span class="font-semibold">{{ $vehiculo->placa }}</span></p>
        </div>
        
        <div class="flex space-x-4">
            <!-- Progreso de documentos -->
            <div class="bg-blue-50 rounded-lg p-4 min-w-[200px]">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-700">Documentos</span>
                    <span class="text-lg font-bold text-blue-600">
                        {{ $estadisticas['progreso']['completados'] }}/{{ $estadisticas['progreso']['total'] }}
                    </span>
                </div>
                <div class="w-full bg-blue-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $estadisticas['progreso']['porcentaje'] }}%"></div>
                </div>
                <span class="text-xs text-blue-600">{{ $estadisticas['progreso']['porcentaje'] }}% completo</span>
            </div>
            
            <!-- Total de fotos -->
            <div class="bg-green-50 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $estadisticas['total_fotos'] }}</div>
                    <div class="text-sm text-green-700">Total imágenes</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de estado -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Errores de validación -->
    @if (!empty($errores))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <h4 class="text-red-800 font-medium mb-2">Errores de validación:</h4>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errores as $campo => $error)
                    <li>• {{ is_array($error) ? implode(', ', $error) : $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Acciones rápidas -->
    <div class="mb-6 flex flex-wrap gap-3">
        <button wire:click="optimizarImagenes" 
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:opacity-50 transition-colors"
                wire:loading.attr="disabled">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Optimizar Imágenes
        </button>
        
        <button onclick="downloadReport()" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Descargar Reporte
        </button>
    </div>

    <!-- Grid de tipos de imágenes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($tiposImagenes as $tipo => $config)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <!-- Header del tipo -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <span class="text-2xl mr-2">{{ $config['icono'] ?? '📄' }}</span>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $config['nombre'] ?? $tipo }}</h3>
                            @if ($config['required'] ?? false)
                                <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Requerido</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Indicador de estado -->
                    @if (!empty($imagenes[$tipo]))
                        <span class="text-green-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    @else
                        <span class="text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                    @endif
                </div>

                <!-- Descripción -->
                @if (!empty($config['descripcion']))
                    <p class="text-sm text-gray-600 mb-3">{{ $config['descripcion'] }}</p>
                @endif

                <!-- Vista previa de imágenes -->
                <div class="mb-4">
                    @if (!empty($imagenes[$tipo]))
                        @if ($tipo === 'galeria_fotos')
                            <div class="grid grid-cols-3 gap-2">
                                @foreach (array_slice($imagenes[$tipo], 0, 6) as $index => $url)
                                    <div class="relative group">
                                        <img src="{{ $url }}" alt="Imagen {{ $index + 1 }}" 
                                             class="w-full h-16 object-cover rounded border cursor-pointer hover:opacity-75"
                                             onclick="openImageModal('{{ $url }}')">
                                        <button wire:click="eliminarImagen('{{ $tipo }}', {{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                                onclick="event.stopPropagation()">×</button>
                                    </div>
                                @endforeach
                                @if (count($imagenes[$tipo]) > 6)
                                    <div class="w-full h-16 bg-gray-100 rounded border flex items-center justify-center text-gray-500 text-sm">
                                        +{{ count($imagenes[$tipo]) - 6 }} más
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="relative group">
                                <img src="{{ $imagenes[$tipo][0] }}" alt="{{ $config['nombre'] }}" 
                                     class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-75"
                                     onclick="openImageModal('{{ $imagenes[$tipo][0] }}')">
                                <button wire:click="eliminarImagen('{{ $tipo }}')"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                        onclick="event.stopPropagation()">×</button>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-32 bg-gray-100 rounded border-2 border-dashed border-gray-300 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">Sin imagen</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Botón de acción -->
                <button wire:click="abrirModal('{{ $tipo }}')" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ !empty($imagenes[$tipo]) ? 'Actualizar' : 'Subir' }} 
                    {{ $config['multiple'] ?? false ? 'Imágenes' : 'Imagen' }}
                </button>

                <!-- Información técnica -->
                <div class="mt-3 text-xs text-gray-500 space-y-1">
                    @if (isset($config['max_size_kb']))
                        <div>📏 Máximo: {{ number_format($config['max_size_kb'] / 1024, 1) }}MB</div>
                    @endif
                    @if (isset($config['min_width']) || isset($config['min_height']))
                        <div>📐 Mínimo: {{ $config['min_width'] ?? 'auto' }}x{{ $config['min_height'] ?? 'auto' }}px</div>
                    @endif
                    @if ($config['multiple'] ?? false)
                        <div>🔢 Múltiples archivos permitidos</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal de subida -->
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="cerrarModal">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $tiposImagenes[$modalTipo]['icono'] ?? '📄' }}
                        Subir {{ $tiposImagenes[$modalTipo]['nombre'] ?? $modalTipo }}
                    </h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Información del tipo de imagen -->
                @if (!empty($tiposImagenes[$modalTipo]['descripcion']))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                        <p class="text-blue-800 text-sm">{{ $tiposImagenes[$modalTipo]['descripcion'] }}</p>
                    </div>
                @endif

                <!-- Zona de subida -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4">
                    <input type="file" 
                           wire:model="nuevasImagenes" 
                           {{ ($tiposImagenes[$modalTipo]['multiple'] ?? false) ? 'multiple' : '' }}
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           class="hidden" 
                           id="file-input-{{ $modalTipo }}">
                    
                    <label for="file-input-{{ $modalTipo }}" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-lg text-gray-600 mb-2">
                            Arrastra archivos aquí o 
                            <span class="text-blue-600 underline">haz clic para seleccionar</span>
                        </p>
                        <p class="text-sm text-gray-500">
                            Formatos: JPG, PNG, WEBP | 
                            Máximo: {{ number_format(($tiposImagenes[$modalTipo]['max_size_kb'] ?? 2048) / 1024, 1) }}MB
                            @if ($tiposImagenes[$modalTipo]['multiple'] ?? false)
                                | Múltiples archivos
                            @endif
                        </p>
                    </label>
                </div>

                <!-- Vista previa de archivos seleccionados -->
                @if (!empty($nuevasImagenes))
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-900 mb-2">Archivos seleccionados:</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($nuevasImagenes as $index => $archivo)
                                @if (is_object($archivo))
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $archivo->getClientOriginalName() }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($archivo->getSize() / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3">
                    <button wire:click="cerrarModal" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="subirImagen" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="subirImagen">Subir Imagen{{ ($tiposImagenes[$modalTipo]['multiple'] ?? false) ? 'es' : '' }}</span>
                        <span wire:loading wire:target="subirImagen">Subiendo...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de vista de imagen -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-60 hidden" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-[90vh] p-4">
            <img id="modalImage" src="" alt="Vista ampliada" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <!-- Indicador de carga global -->
    @if ($cargando)
        <div class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-40">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <svg class="animate-spin w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span class="text-gray-700 font-medium">Procesando...</span>
            </div>
        </div>
    @endif

    <!-- Scripts del componente -->
    <script>
        function openImageModal(url) {
            document.getElementById('modalImage').src = url;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function downloadReport() {
            // Generar y descargar reporte (implementar según necesidades)
            const vehiculoData = @json($vehiculo->placa);
            const reportData = @this.generarReporte();
            
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(reportData, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", `reporte-imagenes-${vehiculoData}-${new Date().toISOString().split('T')[0]}.json`);
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        }

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                @this.cerrarModal();
            }
        });
    </script>
</div>
