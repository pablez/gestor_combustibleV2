<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                {{-- Header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Crear Usuario</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Completa la información del nuevo usuario</p>
                    </div>
                    <a href="{{ route('users.index') }}" 
                       wire:navigate
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver
                    </a>
                </div>

                {{-- Form --}}
                <form wire:submit="save" class="space-y-6">
                    
                    {{-- Profile Photo --}}
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Foto de Perfil (Opcional)
                        </label>
                        
                        <div class="flex items-center space-x-4">
                            {{-- Photo Preview --}}
                            <div class="flex-shrink-0">
                                @if ($profile_photo)
                                    <img src="{{ $profile_photo->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="h-20 w-20 rounded-full object-cover border-2 border-gray-300">
                                @else
                                    <div class="h-20 w-20 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Upload Input --}}
                            <div class="flex-1">
                                <input wire:model="profile_photo" 
                                       type="file" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Personal Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información Personal</h3>
                        </div>

                        {{-- Username --}}
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre de Usuario *
                            </label>
                            <input wire:model.blur="username" 
                                   type="text" 
                                   id="username"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('username')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre Completo *
                            </label>
                            <input wire:model="name" 
                                   type="text" 
                                   id="name"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Removed separate 'nombre' field: using 'name' (Nombre Completo) instead --}}

                        {{-- Apellido Paterno --}}
                        <div>
                            <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Apellido Paterno *
                            </label>
                            <input wire:model="apellido_paterno" 
                                   type="text" 
                                   id="apellido_paterno"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('apellido_paterno')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Apellido Materno --}}
                        <div>
                            <label for="apellido_materno" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Apellido Materno
                            </label>
                            <input wire:model="apellido_materno" 
                                   type="text" 
                                   id="apellido_materno"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('apellido_materno')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CI --}}
                        <div>
                            <label for="ci" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cédula de Identidad *
                            </label>
                            <input wire:model="ci" 
                                   type="text" 
                                   id="ci"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('ci')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Teléfono
                            </label>
                            <input wire:model="telefono" 
                                   type="text" 
                                   id="telefono"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email *
                            </label>
                            <input wire:model="email" 
                                   type="email" 
                                   id="email"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Credenciales</h3>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contraseña *
                            </label>
                            <input wire:model="password" 
                                   type="password" 
                                   id="password"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirmar Contraseña *
                            </label>
                            <input wire:model="password_confirmation" 
                                   type="password" 
                                   id="password_confirmation"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Organizational Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Información Organizacional</h3>
                        </div>

                        {{-- Unidad --}}
                        <div>
                            <label for="id_unidad_organizacional" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Unidad Organizacional
                            </label>
                            <select wire:model="id_unidad_organizacional" 
                                    id="id_unidad_organizacional"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar unidad...</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id_unidad_organizacional }}">{{ $unidad->nombre_unidad }}</option>
                                @endforeach
                            </select>
                            @error('id_unidad_organizacional')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Supervisor --}}
                        <div>
                            <label for="id_supervisor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Supervisor
                            </label>
                            <select wire:model="id_supervisor" 
                                    id="id_supervisor"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar supervisor...</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->full_name }}</option>
                                @endforeach
                            </select>
                            @error('id_supervisor')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Roles --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Roles
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($roles as $role)
                                    <label class="flex items-center">
                                        <input wire:model="selectedRoles" 
                                               type="checkbox" 
                                               value="{{ $role->name }}"
                                               class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ str_replace('_', ' ', $role->name) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedRoles')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input wire:model="activo" 
                                       type="checkbox" 
                                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Usuario activo
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('users.index') }}" 
                           wire:navigate
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                            <span wire:loading.remove>Crear Usuario</span>
                            <span wire:loading>Creando...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
