<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use App\Exports\CombustibleExport;
use App\Models\ConsumoCombustible;
use App\Models\DespachoCombustible;
use App\Models\UnidadTransporte;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CombustibleReporte extends Component
{
    public function generarReporte()
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');
        $unidadId = request('unidad_id');
        $tipo = request('tipo', 'consumos');
        $formato = request('formato', 'pdf');

        if ($formato === 'excel') {
            return $this->generarExcel($fechaInicio, $fechaFin, $unidadId, $tipo);
        } else {
            return $this->generarPDF($fechaInicio, $fechaFin, $unidadId, $tipo);
        }
    }

    private function generarExcel($fechaInicio, $fechaFin, $unidadId, $tipo)
    {
        $nombreArchivo = $tipo === 'consumos' 
            ? 'reporte_consumos_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
            : 'reporte_despachos_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new CombustibleExport($fechaInicio, $fechaFin, $unidadId, $tipo),
            $nombreArchivo
        );
    }

    private function generarPDF($fechaInicio, $fechaFin, $unidadId, $tipo)
    {
        if ($tipo === 'consumos') {
            return $this->generarPDFConsumos($fechaInicio, $fechaFin, $unidadId);
        } else {
            return $this->generarPDFDespachos($fechaInicio, $fechaFin, $unidadId);
        }
    }

    private function generarPDFConsumos($fechaInicio, $fechaFin, $unidadId)
    {
        $query = ConsumoCombustible::with(['unidadTransporte', 'conductor', 'despacho.proveedor'])
            ->orderBy('fecha_registro', 'desc');

        if ($fechaInicio) {
            $query->where('fecha_registro', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_registro', '<=', $fechaFin);
        }

        if ($unidadId) {
            $query->where('unidad_transporte_id', $unidadId);
        }

        $consumos = $query->get();
        
        // Estadísticas
        $totalLitros = $consumos->sum('litros_cargados');
        $totalKilometros = $consumos->sum('kilometros_recorridos');
        $rendimientoPromedio = $totalLitros > 0 ? $totalKilometros / $totalLitros : 0;
        $totalConsumos = $consumos->count();

        $unidadNombre = $unidadId ? UnidadTransporte::find($unidadId)?->codigo : 'Todas las unidades';

        $data = [
            'titulo' => 'Reporte de Consumos de Combustible',
            'fechaInicio' => $fechaInicio ? Carbon::parse($fechaInicio)->format('d/m/Y') : 'N/A',
            'fechaFin' => $fechaFin ? Carbon::parse($fechaFin)->format('d/m/Y') : 'N/A',
            'unidad' => $unidadNombre,
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'consumos' => $consumos,
            'estadisticas' => [
                'total_litros' => $totalLitros,
                'total_kilometros' => $totalKilometros,
                'rendimiento_promedio' => $rendimientoPromedio,
                'total_consumos' => $totalConsumos
            ]
        ];

        $pdf = Pdf::loadView('reportes.combustible-consumos-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('reporte_consumos_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    private function generarPDFDespachos($fechaInicio, $fechaFin, $unidadId)
    {
        $query = DespachoCombustible::with(['proveedor', 'solicitud.unidadTransporte', 'solicitud.conductor'])
            ->orderBy('fecha_despacho', 'desc');

        if ($fechaInicio) {
            $query->where('fecha_despacho', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_despacho', '<=', $fechaFin);
        }

        $despachos = $query->get();
        
        // Estadísticas
        $totalLitros = $despachos->sum('litros_despachados');
        $totalImporte = $despachos->sum(function($despacho) {
            return $despacho->litros_despachados * $despacho->precio_por_litro;
        });
        $totalDespachos = $despachos->count();
        $precioPromedio = $totalLitros > 0 ? $totalImporte / $totalLitros : 0;

        $data = [
            'titulo' => 'Reporte de Despachos de Combustible',
            'fechaInicio' => $fechaInicio ? Carbon::parse($fechaInicio)->format('d/m/Y') : 'N/A',
            'fechaFin' => $fechaFin ? Carbon::parse($fechaFin)->format('d/m/Y') : 'N/A',
            'fechaGeneracion' => now()->format('d/m/Y H:i:s'),
            'despachos' => $despachos,
            'estadisticas' => [
                'total_litros' => $totalLitros,
                'total_importe' => $totalImporte,
                'total_despachos' => $totalDespachos,
                'precio_promedio' => $precioPromedio
            ]
        ];

        $pdf = Pdf::loadView('reportes.combustible-despachos-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('reporte_despachos_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    public function mount()
    {
        // Si hay parámetros de reporte en la URL, generar inmediatamente
        $formato = request('formato');
        if ($formato) {
            return $this->generarReporte();
        }
    }

    public function render()
    {
        // Si hay parámetros de formato, generar reporte y retornar respuesta
        $formato = request('formato');
        if ($formato) {
            return $this->generarReporte();
        }
        
        // De lo contrario, mostrar la vista normal del componente
        $unidades = UnidadTransporte::where('activo', true)
            ->orderBy('codigo')
            ->get();
            
        return view('livewire.reportes.combustible-reporte', [
            'unidades' => $unidades
        ]);
    }
}
