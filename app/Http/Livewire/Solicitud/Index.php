<?php

namespace App\Http\Livewire\Solicitud;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SolicitudCombustible;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;

    protected $listeners = ['solicitudCreated' => '$refresh'];

    public function render()
    {
        $solicitudes = SolicitudCombustible::orderBy('created_at', 'desc')->paginate($this->perPage);
        return view('livewire.solicitud.index', compact('solicitudes'));
    }
}
