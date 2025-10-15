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
            border-bottom: 2px solid #3B82F6;
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
        .estadisticas {
            display: flex;
            justify-content: space-around;
            margin-bottom: 25px;
            background-color: #EBF8FF;
            padding: 15px;
            border-radius: 5px;
        }
        .estadistica {
            text-align: center;
        }
        .estadistica .numero {
            font-size: 16px;
            font-weight: bold;
            color: #1E40AF;
            display: block;
        }
        .estadistica .label {
            font-size: 10px;
            color: #6B7280;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #3B82F6;
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
        table tr:hover {
            background-color: #EBF8FF;
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
        <p>Sistema de Gestión de Combustible</p>
    </div>

    <!-- Información del Reporte -->
    <div class="info-section">
        <div>
            <strong>Período:</strong><br>
            Del {{ $fechaInicio }} al {{ $fechaFin }}
        </div>
        <div>
            <strong>Unidad:</strong><br>
            {{ $unidad }}
        </div>
        <div>
            <strong>Generado:</strong><br>
            {{ $fechaGeneracion }}
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="estadisticas">
        <div class="estadistica">
            <span class="numero">{{ number_format($estadisticas['total_consumos']) }}</span>
            <div class="label">Total Consumos</div>
        </div>
        <div class="estadistica">
            <span class="numero">{{ number_format($estadisticas['total_litros'], 2) }}</span>
            <div class="label">Total Litros</div>
        </div>
        <div class="estadistica">
            <span class="numero">{{ number_format($estadisticas['total_kilometros']) }}</span>
            <div class="label">Total Kilómetros</div>
        </div>
        <div class="estadistica">
            <span class="numero">{{ number_format($estadisticas['rendimiento_promedio'], 2) }}</span>
            <div class="label">Rendimiento Prom. (Km/L)</div>
        </div>
    </div>

    <!-- Tabla de Consumos -->
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Vehículo</th>
                <th>Placa</th>
                <th>Conductor</th>
                <th class="text-right">Km Inicial</th>
                <th class="text-right">Km Final</th>
                <th class="text-right">Km Recorridos</th>
                <th class="text-right">Litros</th>
                <th class="text-right">Rendimiento</th>
                <th>Lugar</th>
                <th>Tipo Carga</th>
                <th class="text-center">Validado</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consumos as $consumo)
            <tr>
                <td>{{ \Carbon\Carbon::parse($consumo->fecha_registro)->format('d/m/Y') }}</td>
                <td>{{ $consumo->unidadTransporte->placa ?? 'N/A' }}</td>
                <td>{{ $consumo->unidadTransporte->placa ?? 'N/A' }}</td>
                <td>{{ $consumo->conductor->name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($consumo->kilometraje_inicial) }}</td>
                <td class="text-right">{{ number_format($consumo->kilometraje_fin) }}</td>
                <td class="text-right">{{ number_format($consumo->kilometros_recorridos) }}</td>
                <td class="text-right">{{ number_format($consumo->litros_cargados, 2) }}</td>
                <td class="text-right">{{ number_format($consumo->rendimiento, 2) }}</td>
                <td>{{ $consumo->lugar_carga }}</td>
                <td>{{ ucfirst($consumo->tipo_carga) }}</td>
                <td class="text-center">
                    <span class="badge {{ $consumo->validado ? 'badge-success' : 'badge-warning' }}">
                        {{ $consumo->validado ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td>{{ $consumo->despacho->proveedor->nombre_proveedor ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión de Combustible - {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>