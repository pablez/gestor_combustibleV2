<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #059669;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1F2937;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 5px;
        }
        .info-section div {
            flex: 1;
        }
        .info-section strong {
            color: #374151;
        }
        .estadisticas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .estadisticas-financieras {
            background-color: #ECFDF5;
            padding: 15px;
            border-radius: 5px;
        }
        .estadisticas-estados {
            background-color: #FEF3C7;
            padding: 15px;
            border-radius: 5px;
        }
        .estadisticas-financieras h3 {
            color: #059669;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .estadisticas-estados h3 {
            color: #D97706;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .estadistica-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .estadistica-row .numero {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #059669;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        table td {
            padding: 6px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 9px;
        }
        table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .estado-normal {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .estado-alerta {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .estado-critico {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding: 10px;
            background-color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Sistema de Control Presupuestario</p>
    </div>

    <!-- Información del Reporte -->
    <div class="info-section">
        <div>
            <strong>Año:</strong><br>
            {{ $anio }}
        </div>
        <div>
            <strong>Unidad:</strong><br>
            {{ $unidad }}
        </div>
        <div>
            <strong>Estado:</strong><br>
            {{ $estado }}
        </div>
        <div>
            <strong>Generado:</strong><br>
            {{ $fechaGeneracion }}
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="estadisticas-grid">
        <div class="estadisticas-financieras">
            <h3>Estadísticas Financieras</h3>
            <div class="estadistica-row">
                <span>Total Asignado:</span>
                <span class="numero">${{ number_format($estadisticas['total_asignado'], 2) }}</span>
            </div>
            <div class="estadistica-row">
                <span>Total Ejecutado:</span>
                <span class="numero">${{ number_format($estadisticas['total_ejecutado'], 2) }}</span>
            </div>
            <div class="estadistica-row">
                <span>Total Disponible:</span>
                <span class="numero">${{ number_format($estadisticas['total_disponible'], 2) }}</span>
            </div>
            <div class="estadistica-row">
                <span>% Ejecución Global:</span>
                <span class="numero">{{ number_format($estadisticas['porcentaje_ejecutado_global'], 1) }}%</span>
            </div>
        </div>

        <div class="estadisticas-estados">
            <h3>Distribución por Estado</h3>
            <div class="estadistica-row">
                <span>Total Presupuestos:</span>
                <span class="numero">{{ $estadisticas['total_presupuestos'] }}</span>
            </div>
            <div class="estadistica-row">
                <span>Normal (&lt;70%):</span>
                <span class="numero">{{ $estadisticas['presupuestos_normales'] }}</span>
            </div>
            <div class="estadistica-row">
                <span>Alerta (70-90%):</span>
                <span class="numero">{{ $estadisticas['presupuestos_alerta'] }}</span>
            </div>
            <div class="estadistica-row">
                <span>Crítico (&gt;90%):</span>
                <span class="numero">{{ $estadisticas['presupuestos_criticos'] }}</span>
            </div>
        </div>
    </div>

    <!-- Tabla de Presupuestos -->
    <table>
        <thead>
            <tr>
                <th>Año</th>
                <th>Unidad Organizacional</th>
                <th>Categoría</th>
                <th>Fuente</th>
                <th class="text-right">Asignado</th>
                <th class="text-right">Ejecutado</th>
                <th class="text-right">Disponible</th>
                <th class="text-right">% Ejecución</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuestos as $presupuesto)
            @php
                $porcentajeEjecutado = $presupuesto->monto_asignado > 0 
                    ? ($presupuesto->monto_ejecutado / $presupuesto->monto_asignado) * 100 
                    : 0;
                $montoDisponible = $presupuesto->monto_asignado - $presupuesto->monto_ejecutado;
                
                if ($porcentajeEjecutado >= 90) {
                    $estadoClass = 'estado-critico';
                    $estadoTexto = 'Crítico';
                } elseif ($porcentajeEjecutado >= 70) {
                    $estadoClass = 'estado-alerta';
                    $estadoTexto = 'Alerta';
                } else {
                    $estadoClass = 'estado-normal';
                    $estadoTexto = 'Normal';
                }
            @endphp
            <tr>
                <td>{{ $presupuesto->anio }}</td>
                <td>{{ $presupuesto->unidadOrganizacional->nombre_unidad ?? 'N/A' }}</td>
                <td>{{ $presupuesto->categoriaProgramatica->nombre ?? 'N/A' }}</td>
                <td>{{ $presupuesto->fuenteOrganismoFinanciero->nombre ?? 'N/A' }}</td>
                <td class="text-right">${{ number_format($presupuesto->monto_asignado, 2) }}</td>
                <td class="text-right">${{ number_format($presupuesto->monto_ejecutado, 2) }}</td>
                <td class="text-right">${{ number_format($montoDisponible, 2) }}</td>
                <td class="text-right">{{ number_format($porcentajeEjecutado, 1) }}%</td>
                <td class="text-center">
                    <span class="badge {{ $estadoClass }}">{{ $estadoTexto }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Reporte generado automáticamente por el Sistema de Control Presupuestario - {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>