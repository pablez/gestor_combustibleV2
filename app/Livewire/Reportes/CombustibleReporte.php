<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use App\Models\UnidadTransporte;

class CombustibleReporte extends Component
{
    public $fechaInicio;
    public $fechaFin;
    public $unidadId;
    public $tipo = 'consumos';

    public function mount()
    {
        // Inicializar fechas por defecto
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->format('Y-m-d');
    }

    public function generarPDF()
    {
        $params = [
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'unidad_id' => $this->unidadId,
            'tipo' => $this->tipo,
            'formato' => 'pdf'
        ];

        return redirect()->route('reportes.combustible.generar', $params);
    }

    public function generarExcel()
    {
        $params = [
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'unidad_id' => $this->unidadId,
            'tipo' => $this->tipo,
            'formato' => 'excel'
        ];

        return redirect()->route('reportes.combustible.generar', $params);
    }

    public function render()
    {
        $unidades = UnidadTransporte::where('activo', true)
            ->orderBy('placa')
            ->get();
            
        return view('livewire.reportes.combustible-reporte', [
            'unidades' => $unidades
        ]);
    }
}