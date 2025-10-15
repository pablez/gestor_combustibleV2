<?php

namespace App\Exports;

use App\Models\Presupuesto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresupuestoExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $anio;
    protected $unidadId;
    protected $estado;

    public function __construct($anio = null, $unidadId = null, $estado = null)
    {
        $this->anio = $anio;
        $this->unidadId = $unidadId;
        $this->estado = $estado;
    }

    public function collection()
    {
        $query = Presupuesto::with([
            'unidadOrganizacional', 
            'categoriaProgramatica', 
            'fuenteOrganismoFinanciero'
        ])->orderBy('anio', 'desc');

        if ($this->anio) {
            $query->where('anio', $this->anio);
        }

        if ($this->unidadId) {
            $query->where('unidad_organizacional_id', $this->unidadId);
        }

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Año',
            'Unidad Organizacional',
            'Categoría Programática',
            'Fuente Financiera',
            'Monto Asignado',
            'Monto Ejecutado',
            'Monto Disponible',
            'Porcentaje Ejecutado',
            'Estado',
            'Fecha Aprobación',
            'Observaciones'
        ];
    }

    public function map($presupuesto): array
    {
        $porcentajeEjecutado = $presupuesto->monto_asignado > 0 
            ? ($presupuesto->monto_ejecutado / $presupuesto->monto_asignado) * 100 
            : 0;

        $montoDisponible = $presupuesto->monto_asignado - $presupuesto->monto_ejecutado;

        $estado = '';
        if ($porcentajeEjecutado >= 90) {
            $estado = 'Crítico (>90%)';
        } elseif ($porcentajeEjecutado >= 70) {
            $estado = 'Alerta (70-90%)';
        } else {
            $estado = 'Normal (<70%)';
        }

        return [
            $presupuesto->anio,
            $presupuesto->unidadOrganizacional->nombre_unidad ?? 'N/A',
            $presupuesto->categoriaProgramatica->nombre ?? 'N/A',
            $presupuesto->fuenteOrganismoFinanciero->nombre ?? 'N/A',
            number_format($presupuesto->monto_asignado, 2),
            number_format($presupuesto->monto_ejecutado, 2),
            number_format($montoDisponible, 2),
            number_format($porcentajeEjecutado, 1) . '%',
            $estado,
            $presupuesto->fecha_aprobacion ? $presupuesto->fecha_aprobacion->format('d/m/Y') : 'N/A',
            $presupuesto->observaciones ?? ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para encabezados
            1 => ['font' => ['bold' => true, 'size' => 12]],
            // Auto ajustar columnas
            'A:K' => ['width' => 'auto'],
        ];
    }
}