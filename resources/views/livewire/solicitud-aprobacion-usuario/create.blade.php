<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                                Nueva Solicitud de Aprobación
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Crear una solicitud de aprobación para un usuario
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button wire:click="cancelar" type="button"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancelar
                            </button>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form wire:submit.prevent="guardar">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Usuario -->
                            <div>
                                <label for="id_usuario" class="block text-sm font-medium text-gray-700">
                                    Usuario *
                                </label>
                                <select wire:model="id_usuario" id="id_usuario" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Seleccionar usuario</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                                    @endforeach
                                </select>
                                @error('id_usuario')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Solicitud -->
                            <div>
                                <label for="tipo_solicitud" class="block text-sm font-medium text-gray-700">
                                    Tipo de Solicitud *
                                </label>
                                <select wire:model="tipo_solicitud" id="tipo_solicitud" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($tiposDisponibles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_solicitud')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Rol Solicitado -->
                            <div>
                                <label for="rol_solicitado" class="block text-sm font-medium text-gray-700">
                                    Rol Solicitado *
                                </label>
                                <select wire:model="rol_solicitado" id="rol_solicitado" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Seleccionar rol</option>
                                    @foreach($rolesDisponibles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('rol_solicitado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supervisor Asignado -->
                            <div>
                                <label for="id_supervisor_asignado" class="block text-sm font-medium text-gray-700">
                                    Supervisor Asignado *
                                </label>
                                <select wire:model="id_supervisor_asignado" id="id_supervisor_asignado" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Seleccionar supervisor</option>
                                    @foreach($supervisores as $supervisor)
                                        <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                    @endforeach
                                </select>
                                @error('id_supervisor_asignado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Justificación -->
                        <div class="mt-6">
                            <label for="justificacion" class="block text-sm font-medium text-gray-700">
                                Justificación *
                            </label>
                            <textarea wire:model="justificacion" id="justificacion" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="Explique las razones de esta solicitud..."></textarea>
                            @error('justificacion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres</p>
                        </div>

                        <!-- Botones -->
                        <div class="mt-8 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                            <button wire:click="cancelar" type="button"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </button>
                            
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Crear Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
