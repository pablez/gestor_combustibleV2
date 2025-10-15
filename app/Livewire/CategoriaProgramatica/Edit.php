<?php

namespace App\Livewire\CategoriaProgramatica;

use App\Models\CategoriaProgramatica;
use App\Constants\Permissions;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    public CategoriaProgramatica $categoria;

    #[Validate('required|string|max:30')]
    public $codigo;

    #[Validate('required|string|max:200')]
    public $descripcion;

    #[Validate('required|in:Programa,Proyecto,Actividad')]
    public $tipo_categoria;

    #[Validate('nullable|exists:categoria_programaticas,id')]
    public $id_categoria_padre;

    #[Validate('required|integer|min:1|max:5')]
    public $nivel;

    #[Validate('boolean')]
    public $activo;

    #[Validate('nullable|date')]
    public $fecha_inicio;

    #[Validate('nullable|date|after_or_equal:fecha_inicio')]
    public $fecha_fin;

    public function mount(CategoriaProgramatica $categoria)
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR);
        
        $this->categoria = $categoria;
        $this->codigo = $categoria->codigo;
        $this->descripcion = $categoria->descripcion;
        $this->tipo_categoria = $categoria->tipo_categoria;
        $this->id_categoria_padre = $categoria->id_categoria_padre;
        $this->nivel = $categoria->nivel;
        $this->activo = $categoria->activo;
        $this->fecha_inicio = $categoria->fecha_inicio?->format('Y-m-d');
        $this->fecha_fin = $categoria->fecha_fin?->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'codigo' => 'required|string|max:30|unique:categoria_programaticas,codigo,' . $this->categoria->id,
            'descripcion' => 'required|string|max:200',
            'tipo_categoria' => 'required|in:Programa,Proyecto,Actividad',
            'id_categoria_padre' => [
                'nullable',
                'exists:categoria_programaticas,id',
                function ($attribute, $value, $fail) {
                    // No puede ser padre de sí misma
                    if ($value == $this->categoria->id) {
                        $fail('Una categoría no puede ser padre de sí misma.');
                    }
                    
                    // No puede seleccionar una de sus propias hijas como padre
                    if ($value && $this->categoria->categoriasHijas()->where('id', $value)->exists()) {
                        $fail('No se puede seleccionar una subcategoría como padre.');
                    }
                }
            ],
            'nivel' => 'required|integer|min:1|max:5',
            'activo' => 'boolean',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ];
    }

    public function updatedIdCategoriaPadre()
    {
        if ($this->id_categoria_padre) {
            $padre = CategoriaProgramatica::find($this->id_categoria_padre);
            if ($padre) {
                $this->nivel = $padre->nivel + 1;
            }
        } else {
            $this->nivel = 1;
        }
    }

    public function update()
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR);
        
        $this->validate();

        try {
            // Validación adicional: no permitir más de 3 niveles
            if ($this->nivel > 3) {
                session()->flash('error', 'No se permite crear categorías con más de 3 niveles de jerarquía.');
                return;
            }

            $this->categoria->update([
                'codigo' => $this->codigo,
                'descripcion' => $this->descripcion,
                'tipo_categoria' => $this->tipo_categoria,
                'id_categoria_padre' => $this->id_categoria_padre,
                'nivel' => $this->nivel,
                'activo' => $this->activo,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]);

            session()->flash('success', 'Categoría programática actualizada exitosamente.');
            return redirect()->route('categorias-programaticas.show', $this->categoria);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categoriasPadres = CategoriaProgramatica::whereNull('id_categoria_padre')
            ->where('activo', true)
            ->where('id', '!=', $this->categoria->id) // Excluir la categoría actual
            ->orderBy('descripcion')
            ->get();

        return view('livewire.categoria-programatica.edit', [
            'categoriasPadres' => $categoriasPadres,
        ]);
    }
}