<?php

namespace App\Exports;

use App\Models\ConsumoCombustible;
use App\Models\DespachoCombustible;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CombustibleExport implements FromCollection, WithHeadings, WithStyles
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $unidadId;
    protected $tipo;

    public function __construct($fechaInicio = null, $fechaFin = null, $unidadId = null, $tipo = 'consumos')
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->unidadId = $unidadId;
        $this->tipo = $tipo;
    }

    public function collection()
    {
        if ($this->tipo === 'despachos') {
            return $this->getDespachos()->map(function ($despacho) {
                return [
                    $despacho->fecha_despacho ? date('d/m/Y', strtotime($despacho->fecha_despacho)) : '',
                    $despacho->numero_vale ?? '',
                    $despacho->proveedor->nombre_proveedor ?? '',
                    $despacho->litros_despachados ?? 0,
                    $despacho->precio_por_litro ?? 0,
                    $despacho->costo_total ?? 0,
                    $despacho->validado ? 'Sí' : 'No',
                    $despacho->solicitud->unidadTransporte->placa ?? '',
                    $despacho->solicitud->solicitante->name ?? '',
                ];
            });
        }
        
        return $this->getConsumos()->map(function ($consumo) {
            return [
                $consumo->fecha_registro ? date('d/m/Y', strtotime($consumo->fecha_registro)) : '',
                $consumo->unidadTransporte->marca . ' ' . $consumo->unidadTransporte->modelo ?? '',
                $consumo->unidadTransporte->placa ?? '',
                $consumo->conductor->name ?? '',
                $consumo->kilometraje_inicial ?? 0,
                $consumo->kilometraje_fin ?? 0,
                $consumo->kilometros_recorridos ?? 0,
                $consumo->litros_cargados ?? 0,
                $consumo->rendimiento ?? 0,
                $consumo->lugar_carga ?? '',
                ucfirst($consumo->tipo_carga ?? ''),
                $consumo->validado ? 'Sí' : 'No',
                $consumo->despacho->proveedor->nombre_proveedor ?? '',
                $consumo->numero_ticket ?? '',
                $consumo->observaciones ?? '',
            ];
        });
    }

    protected function getConsumos()
    {
        $query = ConsumoCombustible::with(['unidadTransporte', 'conductor', 'despacho.proveedor'])
            ->orderBy('fecha_registro', 'desc');

        if ($this->fechaInicio) {
            $query->where('fecha_registro', '>=', $this->fechaInicio);
        }

        if ($this->fechaFin) {
            $query->where('fecha_registro', '<=', $this->fechaFin);
        }

        if ($this->unidadId) {
            $query->where('id_unidad_transporte', $this->unidadId);
        }

        return $query->get();
    }

    protected function getDespachos()
    {
        $query = DespachoCombustible::with(['proveedor', 'solicitud.unidadTransporte', 'solicitud.solicitante'])
            ->orderBy('fecha_despacho', 'desc');

        if ($this->fechaInicio) {
            $query->where('fecha_despacho', '>=', $this->fechaInicio);
        }

        if ($this->fechaFin) {
            $query->where('fecha_despacho', '<=', $this->fechaFin);
        }

        return $query->get();
    }

    public function headings(): array
    {
        if ($this->tipo === 'despachos') {
            return [
                'Fecha Despacho',
                'Número Vale',
                'Proveedor',
                'Litros Despachados',
                'Precio por Litro',
                'Total',
                'Validado',
                'Vehículo',
                'Conductor'
            ];
        }

        return [
            'Fecha Registro',
            'Vehículo',
            'Placa',
            'Conductor',
            'Km Inicial',
            'Km Final',
            'Km Recorridos',
            'Litros Cargados',
            'Rendimiento (Km/L)',
            'Lugar Carga',
            'Tipo Carga',
            'Validado',
            'Proveedor',
            'Número Ticket',
            'Observaciones'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para encabezados
            1 => ['font' => ['bold' => true, 'size' => 12]],
            // Auto ajustar columnas
            'A:M' => ['width' => 'auto'],
        ];
    }
}