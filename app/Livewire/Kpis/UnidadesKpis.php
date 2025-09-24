<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\UnidadOrganizacional;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UnidadesKpis extends Component
{
    public $totalUnidades = 0;
    public $usersByUnidad = [];

    public function mount()
    {
        $this->load();
    }

    public function load()
    {
        $currentUser = auth()->user();

        // Admin General ve todas las unidades
        // Admin Secretaria ve solo su unidad
        $query = UnidadOrganizacional::query();

        if ($currentUser->hasRole('Admin_Secretaria') && $currentUser->id_unidad_organizacional) {
            $query->where('id_unidad_organizacional', $currentUser->id_unidad_organizacional);
        }

        $this->totalUnidades = $query->count();

        // Usuarios por unidad (solo unidades visibles)
        $unidadIds = $query->pluck('id_unidad_organizacional')->toArray();

        if (empty($unidadIds)) {
            $this->usersByUnidad = [];
            return;
        }

        $rows = DB::table('users')
                ->join('unidades_organizacionales', 'users.id_unidad_organizacional', '=', 'unidades_organizacionales.id_unidad_organizacional')
                ->whereIn('users.id_unidad_organizacional', $unidadIds)
                ->select('unidades_organizacionales.id_unidad_organizacional','unidades_organizacionales.codigo_unidad','unidades_organizacionales.nombre_unidad', DB::raw('COUNT(*) as count'))
                ->groupBy('unidades_organizacionales.id_unidad_organizacional','unidades_organizacionales.codigo_unidad','unidades_organizacionales.nombre_unidad')
                ->orderByDesc('count')
                ->get()
                ->map(function($r) {
                    return [
                        'id_unidad_organizacional' => $r->id_unidad_organizacional,
                        'codigo_unidad' => $r->codigo_unidad,
                        'nombre_unidad' => $r->nombre_unidad,
                        'count' => (int) $r->count,
                    ];
                })->toArray();

        $this->usersByUnidad = $rows;
    }

    public function render()
    {
        return view('livewire.kpis.unidades-kpis');
    }
}
