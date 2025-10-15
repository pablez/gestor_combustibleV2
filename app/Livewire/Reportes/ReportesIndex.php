<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\UnidadOrganizacional;

class ReportesIndex extends Component
{
    public $reporteSeleccionado = '';
    public $fechaInicio = '';
    public $fechaFin = '';
    public $unidadId = '';
    public $anio = '';
    public $estado = '';

    public function mount()
    {
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
        $this->anio = now()->year;
    }

    public function generarReporte($tipo, $formato)
    {
        $parametros = [
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'unidad_id' => $this->unidadId,
            'anio' => $this->anio,
            'estado' => $this->estado,
            'formato' => $formato
        ];

        $queryString = http_build_query($parametros);

        if ($tipo === 'combustible_consumos') {
            $url = route('reportes.combustible') . '?' . $queryString . '&tipo=consumos';
            return redirect($url);
        } elseif ($tipo === 'combustible_despachos') {
            $url = route('reportes.combustible') . '?' . $queryString . '&tipo=despachos';
            return redirect($url);
        } elseif ($tipo === 'presupuesto') {
            $url = route('reportes.presupuesto') . '?' . $queryString;
            return redirect($url);
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.reportes.reportes-index', [
            'unidades' => UnidadOrganizacional::orderBy('nombre_unidad')->get(),
            'anios' => range(now()->year, now()->year - 5),
            'estados' => [
                'activo' => 'Activo',
                'inactivo' => 'Inactivo',
                'suspendido' => 'Suspendido'
            ]
        ]);
    }
}
