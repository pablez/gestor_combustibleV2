<?php

namespace App\Livewire\CategoriaProgramatica;

use App\Models\CategoriaProgramatica;
use App\Constants\Permissions;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Create extends Component
{
    #[Rule('required|string|max:30|unique:categoria_programaticas,codigo')]
    public $codigo = '';

    #[Rule('required|string|max:200')]
    public $descripcion = '';

    #[Rule('required|in:Programa,Proyecto,Actividad')]
    public $tipo_categoria = '';

    #[Rule('nullable|exists:categoria_programaticas,id')]
    public $id_categoria_padre = null;

    #[Rule('required|integer|min:1|max:5')]
    public $nivel = 1;

    #[Rule('boolean')]
    public $activo = true;

    #[Rule('nullable|date')]
    public $fecha_inicio = null;

    #[Rule('nullable|date|after_or_equal:fecha_inicio')]
    public $fecha_fin = null;

    public function mount()
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_CREAR);
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

    public function create()
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_CREAR);
        
        $this->validate();

        try {
            // Validación adicional: no permitir más de 3 niveles
            if ($this->nivel > 3) {
                session()->flash('error', 'No se permite crear categorías con más de 3 niveles de jerarquía.');
                return;
            }

            CategoriaProgramatica::create([
                'codigo' => $this->codigo,
                'descripcion' => $this->descripcion,
                'tipo_categoria' => $this->tipo_categoria,
                'id_categoria_padre' => $this->id_categoria_padre,
                'nivel' => $this->nivel,
                'activo' => $this->activo,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]);

            session()->flash('success', 'Categoría programática creada exitosamente.');
            return redirect()->route('categorias-programaticas.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categoriasPadres = CategoriaProgramatica::whereNull('id_categoria_padre')
            ->where('activo', true)
            ->orderBy('descripcion')
            ->get();

        return view('livewire.categoria-programatica.create', [
            'categoriasPadres' => $categoriasPadres,
        ]);
    }
}
