<div role="region" aria-labelledby="uploader-heading">
    <h2 id="uploader-heading" class="sr-only">Subir imagen de vehículo</h2>
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded" role="status" aria-live="polite">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded" role="alert" aria-live="assertive">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="subir" class="mt-4">
        <div class="mb-2">
            <label for="placa-input" class="block text-sm font-medium text-gray-700">Placa</label>
            <input id="placa-input" type="text" wire:model.defer="placa" class="mt-1 block w-full border rounded px-2 py-1" placeholder="Ej: ABC-123" aria-required="true" />
            @error('placa') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-2">
            <label for="tipo-select" class="block text-sm font-medium text-gray-700">Tipo de imagen</label>
            <select id="tipo-select" wire:model="tipo" class="mt-1 block w-full border rounded px-2 py-1" aria-required="true">
                <option value="">Seleccionar</option>
                @foreach($tipos as $tipoItem)
                    <option value="{{ $tipoItem }}">{{ $tipoItem }}</option>
                @endforeach
            </select>
            @error('tipo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="archivo-input" class="block text-sm font-medium text-gray-700">Archivo</label>
            <input id="archivo-input" type="file" wire:model="archivo" accept="image/*" class="mt-1" aria-describedby="archivo-help" />
            <p id="archivo-help" class="text-xs text-gray-500">Formatos: JPG/PNG. Tamaño máximo aproximado 5MB.</p>
            @error('archivo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            <div wire:loading wire:target="archivo" class="text-sm text-gray-500 mt-1" role="status" aria-live="polite">Subiendo...</div>

            {{-- Preview básico usando temporaryUrl si es posible --}}
            @if (is_object($archivo) && method_exists($archivo, 'isPreviewable') && $archivo->isPreviewable() && method_exists($archivo, 'temporaryUrl'))
                <div class="mt-2" aria-live="polite">
                    <img src="{{ $archivo->temporaryUrl() }}" alt="Preview" class="w-48 h-auto rounded border" />
                </div>
            @endif

            {{-- Barra de progreso con porcentaje real usando Alpine + eventos Livewire --}}
            <div x-data="{ uploading: false, progress: 0 }"
                 x-on:livewire-upload-start="uploading = true; progress = 0"
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                 x-on:livewire-upload-finish="uploading = false; progress = 100"
                 x-on:livewire-upload-error="uploading = false; progress = 0"
                 class="mt-2">

                <div x-show="uploading" class="w-full bg-gray-200 rounded h-2" role="progressbar" aria-valuemin="0" aria-valuemax="100" :aria-valuenow="progress">
                    <div class="bg-blue-600 h-2 rounded transition-all duration-200" :style="`width: ${progress}%`" aria-hidden="true"></div>
                </div>

                <div x-show="uploading" class="text-xs text-gray-600 mt-1">Cargando: <span x-text="progress"></span>%</div>
            </div>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" aria-label="Subir imagen">Subir</button>
            <button type="button" wire:click="cancelarUpload" class="ml-2 bg-gray-300 text-gray-800 px-3 py-2 rounded" aria-label="Cancelar subida">Cancelar</button>
        </div>
    </form>

    {{-- Mostrar errores de upload emitidos por el servidor --}}
    <div x-data="{ error: null }" x-init="
            window.addEventListener('upload:error', (e) => { error = e.detail.message })
            window.addEventListener('upload:cancelled', () => { error = null })
        " class="mt-2">
        <template x-if="error">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded mt-2" role="alert" aria-live="assertive"> 
                <strong>Error:</strong> <span x-text="error"></span>
            </div>
        </template>
    </div>
</div>
