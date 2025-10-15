<?php

namespace App\Livewire\CategoriaProgramatica;

use App\Models\CategoriaProgramatica;
use App\Constants\Permissions;
use Livewire\Component;

class Show extends Component
{
    public CategoriaProgramatica $categoria;

    public function mount(CategoriaProgramatica $categoria)
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_VER);
        $this->categoria = $categoria->load(['categoriaPadre', 'categoriasHijas', 'presupuestos', 'solicitudesCombustible']);
    }

    public function toggleEstado()
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_EDITAR);
        
        $this->categoria->update(['activo' => !$this->categoria->activo]);
        $this->categoria->refresh();
        
        session()->flash('success', 'Estado actualizado exitosamente.');
    }

    public function delete()
    {
        $this->authorize(Permissions::CATEGORIAS_PROGRAMATICAS_ELIMINAR);
        
        try {
            // Verificar si tiene categorías hijas
            if ($this->categoria->categoriasHijas()->count() > 0) {
                session()->flash('error', 'No se puede eliminar una categoría que tiene subcategorías.');
                return;
            }
            
            // Verificar si tiene presupuestos asociados
            if ($this->categoria->presupuestos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar una categoría que tiene presupuestos asociados.');
                return;
            }
            
            $this->categoria->delete();
            session()->flash('success', 'Categoría eliminada exitosamente.');
            return redirect()->route('categorias-programaticas.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.categoria-programatica.show');
    }
}