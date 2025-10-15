<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use App\Exports\PresupuestoExport;
use App\Models\Presupuesto;
use App\Models\UnidadOrganizacional;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PresupuestoReporte extends Component
{
    public function generarReporte()
    {
        $anio = request('anio');
        $unidadId = request('unidad_id');
        $estado = request('estado');
        $formato = request('formato', 'pdf');

        if ($formato === 'excel') {
            return $this->generarExcel($anio, $unidadId, $estado);
        } else {
            return $this->generarPDF($anio, $unidadId, $estado);
        }
    }

    private function generarExcel($anio, $unidadId, $estado)
    {
        $nombreArchivo = 'reporte_presupuesto_' . ($anio ?: 'todos') . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new PresupuestoExport($anio, $unidadId, $estado),
            $nombreArchivo
        );
    }

    private function generarPDF($anio, $unidadId, $estado)
    {
        $query = Presupuesto::with([
            'unidadOrganizacional', 
            'categoriaProgramatica', 
            'fuenteOrganismoFinanciero'
        ])->orderBy('anio', 'desc');

        if ($anio) {
            $query->where('anio', $anio);
        }

        if ($unidadId) {
            $query->where('unidad_organizacional_id', $unidadId);
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $presupuestos = $query->get();
        
        // Estadísticas globales
        $totalAsignado = $presupuestos->sum('monto_asignado');
        $totalEjecutado = $presupuestos->sum('monto_ejecutado');
        $totalDisponible = $totalAsignado - $totalEjecutado;
        $porcentajeEjecutadoGlobal = $totalAsignado > 0 ? ($totalEjecutado / $totalAsignado) * 100 : 0;

        // Estadísticas por estado
        $presupuestosNormales = $presupuestos->filter(function($p) {
            $porcentaje = $p->monto_asignado > 0 ? ($p->monto_ejecutado / $p->monto_asignado) * 100 : 0;
            return $porcentaje < 70;
        });

        $presupuestosAlerta = $presupuestos->filter(function($p) {
            $porcentaje = $p->monto_asignado > 0 ? ($p->monto_ejecutado / $p->monto_asignado) * 100 : 0;
            return $porcentaje >= 70 && $porcentaje < 90;
        });

        $presupuestosCriticos = $presupuestos->filter(function($p) {
            $porcentaje = $p->monto_asignado > 0 ? ($p->monto_ejecutado / $p->monto_asignado) * 100 : 0;
            return $porcentaje >= 90;
        });

        $unidadNombre = $unidadId ? UnidadOrganizacional::find($unidadId)?->nombre_unidad : 'Todas las unidades';

        $data = [
            'titulo' => 'Reporte de Estado Presupuestario',
            'anio' => $anio ?: 'Todos los años',
            'unidad' => $unidadNombre,
            'estado' => $estado ? ucfirst($estado) : 'Todos los estados',
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'presupuestos' => $presupuestos,
            'estadisticas' => [
                'total_asignado' => $totalAsignado,
                'total_ejecutado' => $totalEjecutado,
                'total_disponible' => $totalDisponible,
                'porcentaje_ejecutado_global' => $porcentajeEjecutadoGlobal,
                'total_presupuestos' => $presupuestos->count(),
                'presupuestos_normales' => $presupuestosNormales->count(),
                'presupuestos_alerta' => $presupuestosAlerta->count(),
                'presupuestos_criticos' => $presupuestosCriticos->count()
            ]
        ];

        $pdf = Pdf::loadView('reportes.presupuesto-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('reporte_presupuesto_' . ($anio ?: 'todos') . '_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    public function render()
    {
        return $this->generarReporte();
    }
}
