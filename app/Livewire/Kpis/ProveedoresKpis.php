<?php

namespace App\Livewire\Kpis;

use Livewire\Component;
use App\Models\Proveedor;
use App\Models\TipoServicioProveedor;
use Illuminate\Support\Facades\DB;

class ProveedoresKpis extends Component
{
    public $totalProveedores;
    public $proveedoresActivos;
    public $proveedoresInactivos;
    public $proveedoresPorCalificacion;
    public $tiposServicioMasUsados;
    public $promedioCalificacion;
    public $proveedoresRecientes;
    public $porcentajeActivos;

    public function mount()
    {
        $this->loadKpis();
    }

    private function loadKpis()
    {
        // Total de proveedores
        $this->totalProveedores = Proveedor::count();

        // Proveedores activos e inactivos
        $this->proveedoresActivos = Proveedor::where('activo', true)->count();
        $this->proveedoresInactivos = Proveedor::where('activo', false)->count();

        // Porcentaje de proveedores activos
        $this->porcentajeActivos = $this->totalProveedores > 0 
            ? round(($this->proveedoresActivos / $this->totalProveedores) * 100, 1) 
            : 0;

        // Distribución por calificación
        $this->proveedoresPorCalificacion = Proveedor::select('calificacion', DB::raw('count(*) as total'))
            ->groupBy('calificacion')
            ->orderBy('calificacion', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->calificacion => $item->total];
            });

        // Promedio de calificación
        $calificaciones = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1];
        $totalPuntos = 0;
        $totalProveedores = 0;
        
        foreach ($this->proveedoresPorCalificacion as $calificacion => $cantidad) {
            if (isset($calificaciones[$calificacion])) {
                $totalPuntos += $calificaciones[$calificacion] * $cantidad;
                $totalProveedores += $cantidad;
            }
        }
        
        $this->promedioCalificacion = $totalProveedores > 0 
            ? round($totalPuntos / $totalProveedores, 1) 
            : 0;

        // Tipos de servicio más utilizados
        $this->tiposServicioMasUsados = TipoServicioProveedor::select('tipo_servicio_proveedors.nombre', DB::raw('count(proveedors.id) as total'))
            ->leftJoin('proveedors', 'tipo_servicio_proveedors.id', '=', 'proveedors.id_tipo_servicio_proveedor')
            ->groupBy('tipo_servicio_proveedors.id', 'tipo_servicio_proveedors.nombre')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Proveedores agregados recientemente (últimos 30 días)
        $this->proveedoresRecientes = Proveedor::where('created_at', '>=', now()->subDays(30))->count();
    }

    public function getCalificacionTexto()
    {
        if ($this->promedioCalificacion >= 3.5) {
            return 'Excelente';
        } elseif ($this->promedioCalificacion >= 2.5) {
            return 'Bueno';
        } elseif ($this->promedioCalificacion >= 1.5) {
            return 'Regular';
        } else {
            return 'Deficiente';
        }
    }

    public function render()
    {
        return view('livewire.kpis.proveedores-kpis');
    }
}