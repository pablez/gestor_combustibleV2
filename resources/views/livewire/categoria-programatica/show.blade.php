<div>
    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-t-lg shadow-lg">
        <div class="px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📋 {{ $categoria->descripcion }}</h1>
                    <p class="text-indigo-100 text-lg">Detalles de la categoría programática</p>
                </div>
                <div class="flex items-center space-x-3">
                    @can(App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR)
                        <a href="{{ route('categorias-programaticas.edit', $categoria) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                    @endcan
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

    {{-- Contenido Principal --}}
    <div class="bg-white rounded-b-lg shadow-lg">
        <div class="p-6">
            {{-- Acciones Rápidas --}}
            @canany([App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR, App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_ELIMINAR])
                <div class="mb-6 flex items-center justify-end space-x-3">
                    @can(App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR)
                        <button wire:click="toggleEstado" 
                                class="inline-flex items-center px-4 py-2 {{ $categoria->activo ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors">
                            @if($categoria->activo)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Desactivar
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Activar
                            @endif
                        </button>
                    @endcan

                    @can(App\Constants\Permissions::CATEGORIAS_PROGRAMATICAS_ELIMINAR)
                        <button wire:click="delete" 
                                wire:confirm="¿Estás seguro de que deseas eliminar esta categoría programática?"
                                class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    @endcan
                </div>
            @endcanany

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Información General --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Información General</h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Código</dt>
                            <dd class="text-sm text-gray-900 font-mono">{{ $categoria->codigo }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="text-sm text-gray-900">{{ $categoria->descripcion }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Categoría</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $categoria->tipo_badge }}">
                                    {{ $categoria->tipo_categoria }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="text-sm text-gray-900">
                                @if($categoria->activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 mr-1 bg-green-400 rounded-full"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-2 h-2 mr-1 bg-red-400 rounded-full"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>

                {{-- Información de Jerarquía --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Jerarquía y Fechas</h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nivel Jerárquico</dt>
                            <dd class="text-sm text-gray-900">
                                Nivel {{ $categoria->nivel }}
                                @if($categoria->nivel == 1)
                                    <span class="text-blue-600">(Superior)</span>
                                @elseif($categoria->nivel == 2)
                                    <span class="text-green-600">(Intermedio)</span>
                                @elseif($categoria->nivel == 3)
                                    <span class="text-yellow-600">(Detalle)</span>
                                @endif
                            </dd>
                        </div>
                        @if($categoria->categoriaPadre)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Categoría Padre</dt>
                                <dd class="text-sm text-gray-900">
                                    <a href="{{ route('categorias-programaticas.show', $categoria->categoriaPadre) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $categoria->categoriaPadre->descripcion }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($categoria->fecha_inicio)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                                <dd class="text-sm text-gray-900">{{ $categoria->fecha_inicio->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                        @if($categoria->fecha_fin)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                                <dd class="text-sm text-gray-900">{{ $categoria->fecha_fin->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Creada</dt>
                            <dd class="text-sm text-gray-900">{{ $categoria->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($categoria->updated_at != $categoria->created_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="text-sm text-gray-900">{{ $categoria->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Subcategorías --}}
            @if($categoria->categoriasHijas->count() > 0)
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Subcategorías ({{ $categoria->categoriasHijas->count() }})</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categoria->categoriasHijas as $hija)
                            <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $hija->descripcion }}</h5>
                                        <p class="text-sm text-gray-500">{{ $hija->codigo }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $hija->tipo_badge }}">
                                            {{ $hija->tipo_categoria }}
                                        </span>
                                    </div>
                                    <a href="{{ route('categorias-programaticas.show', $hija) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Relaciones --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Presupuestos Relacionados --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        Presupuestos Relacionados 
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $categoria->presupuestos->count() }}
                        </span>
                    </h4>
                    @if($categoria->presupuestos->count() > 0)
                        <div class="space-y-2">
                            @foreach($categoria->presupuestos->take(5) as $presupuesto)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $presupuesto->anio_fiscal }} - T{{ $presupuesto->trimestre }}</p>
                                        <p class="text-sm text-gray-500">${{ number_format($presupuesto->presupuesto_inicial, 2) }}</p>
                                    </div>
                                    <a href="{{ route('presupuestos.show', $presupuesto) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                            @if($categoria->presupuestos->count() > 5)
                                <p class="text-sm text-gray-500 text-center">Y {{ $categoria->presupuestos->count() - 5 }} más...</p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No hay presupuestos relacionados</p>
                    @endif
                </div>

                {{-- Solicitudes Relacionadas --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">
                        Solicitudes de Combustible 
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $categoria->solicitudesCombustible->count() }}
                        </span>
                    </h4>
                    @if($categoria->solicitudesCombustible->count() > 0)
                        <div class="space-y-2">
                            @foreach($categoria->solicitudesCombustible->take(5) as $solicitud)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $solicitud->numero_solicitud }}</p>
                                        <p class="text-sm text-gray-500">{{ $solicitud->cantidad_litros_solicitados }}L</p>
                                    </div>
                                    <a href="{{ route('solicitudes.show', $solicitud) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                            @if($categoria->solicitudesCombustible->count() > 5)
                                <p class="text-sm text-gray-500 text-center">Y {{ $categoria->solicitudesCombustible->count() - 5 }} más...</p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No hay solicitudes relacionadas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>