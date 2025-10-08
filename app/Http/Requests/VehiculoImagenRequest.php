<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class VehiculoImagenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $config = config('vehiculos-imagenes.tipos');
        $tipoImagen = $this->route('tipo_imagen');
        $configTipo = $config[$tipoImagen] ?? [];

        $rules = [
            'vehiculo_id' => 'required|exists:unidad_transportes,id',
            'tipo_imagen' => 'required|in:' . implode(',', array_keys($config))
        ];

        if ($configTipo['multiple'] ?? false) {
            $rules['imagenes'] = 'required|array|max:' . ($configTipo['max_files'] ?? 5);
            $rules['imagenes.*'] = [
                'required',
                File::image()
                    ->max($configTipo['max_size_kb'] ?? 2048)
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth($configTipo['dimensions']['width'] ?? 300)
                            ->minHeight($configTipo['dimensions']['height'] ?? 200)
                    )
            ];
        } else {
            $rules['imagen'] = [
                'required',
                File::image()
                    ->max($configTipo['max_size_kb'] ?? 2048)
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth($configTipo['dimensions']['width'] ?? 300)
                            ->minHeight($configTipo['dimensions']['height'] ?? 200)
                    )
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo debe ser una imagen válida.',
            'imagen.max' => 'La imagen no puede ser mayor a :max KB.',
            'imagenes.*.image' => 'Todos los archivos deben ser imágenes válidas.',
            'imagenes.*.max' => 'Cada imagen no puede ser mayor a :max KB.',
            'vehiculo_id.exists' => 'El vehículo seleccionado no existe.',
            'tipo_imagen.in' => 'El tipo de imagen no es válido.'
        ];
    }
}