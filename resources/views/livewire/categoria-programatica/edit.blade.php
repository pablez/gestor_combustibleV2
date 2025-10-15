<div>
    {{-- Header --}}
    <div class="bg-gradient-to-r from-amber-600 to-orange-700 rounded-t-lg shadow-lg">
        <div class="px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">✏️ Editar Categoría Programática</h1>
                    <p class="text-amber-100 text-lg">Modificar información de: {{ $categoria->descripcion }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('categorias-programaticas.show', $categoria) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </a>
                    <a href="{{ route('categorias-programaticas.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Formulario --}}
    <div class="bg-white rounded-b-lg shadow-lg">
        <form wire:submit.prevent="update" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Información Básica --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                        📋 Información Básica
                    </h3>

                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                            Código <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="codigo" id="codigo" maxlength="30"
                               placeholder="Ej: PROG-001, PROJ-002, ACT-003"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('codigo') border-red-500 @enderror">
                        @error('codigo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Código único de identificación (máximo 30 caracteres)</p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="descripcion" id="descripcion" rows="4" maxlength="200"
                                  placeholder="Descripción detallada de la categoría programática..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('descripcion') border-red-500 @enderror"></textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Máximo 200 caracteres</p>
                    </div>

                    <!-- Tipo de Categoría -->
                    <div>
                        <label for="tipo_categoria" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Categoría <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="tipo_categoria" id="tipo_categoria" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('tipo_categoria') border-red-500 @enderror">
                            <option value="">Seleccionar tipo...</option>
                            <option value="Programa">📊 Programa</option>
                            <option value="Proyecto">🎯 Proyecto</option>
                            <option value="Actividad">⚡ Actividad</option>
                        </select>
                        @error('tipo_categoria')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="activo" 
                                   class="rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-300 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                            <span class="ml-3">
                                <span class="font-medium text-gray-900">Categoría Activa</span>
                                <span class="block text-sm text-gray-600">La categoría estará disponible para asignación</span>
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Información de Jerarquía --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                        🔗 Jerarquía y Fechas
                    </h3>

                    <!-- Categoría Padre -->
                    <div>
                        <label for="id_categoria_padre" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoría Padre (Opcional)
                        </label>
                        <select wire:model="id_categoria_padre" id="id_categoria_padre" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('id_categoria_padre') border-red-500 @enderror">
                            <option value="">Sin categoría padre (Nivel 1)</option>
                            @foreach($categoriasPadres as $padre)
                                <option value="{{ $padre->id }}">{{ $padre->descripcion }} ({{ $padre->tipo_categoria }})</option>
                            @endforeach
                        </select>
                        @error('id_categoria_padre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Selecciona una categoría padre si esta es una subcategoría</p>
                    </div>

                    <!-- Nivel -->
                    <div>
                        <label for="nivel" class="block text-sm font-medium text-gray-700 mb-2">
                            Nivel Jerárquico
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="number" wire:model="nivel" id="nivel" min="1" max="3" readonly
                                   class="w-20 px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            <span class="text-sm text-gray-600">
                                @if($nivel == 1)
                                    🏢 Nivel Superior
                                @elseif($nivel == 2)
                                    📁 Nivel Intermedio
                                @elseif($nivel == 3)
                                    📄 Nivel Detalle
                                @endif
                            </span>
                        </div>
                        @error('nivel')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Se calcula automáticamente según la categoría padre</p>
                    </div>

                    <!-- Fecha de Inicio -->
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Inicio (Opcional)
                        </label>
                        <input type="date" wire:model="fecha_inicio" id="fecha_inicio" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('fecha_inicio') border-red-500 @enderror">
                        @error('fecha_inicio')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Fin -->
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Fin (Opcional)
                        </label>
                        <input type="date" wire:model="fecha_fin" id="fecha_fin" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('fecha_fin') border-red-500 @enderror">
                        @error('fecha_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Advertencias sobre cambios --}}
                    @if($categoria->categoriasHijas->count() > 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-yellow-800">Advertencia</h4>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        Esta categoría tiene {{ $categoria->categoriasHijas->count() }} subcategoría(s). 
                                        Los cambios de jerarquía pueden afectar los niveles de las subcategorías.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Información sobre relaciones --}}
                    @if($categoria->presupuestos->count() > 0 || $categoria->solicitudesCombustible->count() > 0)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Relaciones Existentes</h4>
                                    <div class="mt-1 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @if($categoria->presupuestos->count() > 0)
                                                <li>{{ $categoria->presupuestos->count() }} presupuesto(s) relacionado(s)</li>
                                            @endif
                                            @if($categoria->solicitudesCombustible->count() > 0)
                                                <li>{{ $categoria->solicitudesCombustible->count() }} solicitud(es) de combustible</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-8 flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('categorias-programaticas.show', $categoria) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Actualizar Categoría
                </button>
            </div>
        </form>
    </div>
</div>