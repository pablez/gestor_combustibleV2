<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\UnidadTransporte;
use Illuminate\Pagination\LengthAwarePaginator;

class ImagenesIndex extends Component
{
    public $search = '';
    public $perPage = 10;

    public function render()
    {
        $query = UnidadTransporte::query();

        if (!empty($this->search)) {
            $query->where('placa', 'like', "%{$this->search}%")
                  ->orWhere('modelo', 'like', "%{$this->search}%")
                  ->orWhere('tipo', 'like', "%{$this->search}%");
        }

        $vehiculos = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin.imagenes-index', [
            'vehiculos' => $vehiculos
        ]);
    }
}
