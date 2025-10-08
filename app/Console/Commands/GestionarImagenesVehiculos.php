<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImagenVehiculoService;
use App\Services\AuditoriaImagenService;
use App\Models\UnidadTransporte;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GestionarImagenesVehiculos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehiculos:imagenes 
                            {accion : Acción a realizar (limpiar, estadisticas, optimizar, auditoria, integridad)}
                            {--placa= : Placa específica del vehículo}
                            {--fuerza : Forzar ejecución sin confirmación}
                            {--exportar : Exportar reporte a archivo}
                            {--desde= : Fecha inicio para auditoría (Y-m-d)}
                            {--hasta= : Fecha fin para auditoría (Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestionar imágenes de vehículos: limpiar, estadísticas, optimizar, auditoría, integridad';

    protected ImagenVehiculoService $imagenService;
    protected AuditoriaImagenService $auditoriaService;

    public function __construct(ImagenVehiculoService $imagenService, AuditoriaImagenService $auditoriaService)
    {
        parent::__construct();
        $this->imagenService = $imagenService;
        $this->auditoriaService = $auditoriaService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accion = $this->argument('accion');
        
        match($accion) {
            'limpiar' => $this->limpiarImagenes(),
            'estadisticas' => $this->mostrarEstadisticas(),
            'optimizar' => $this->optimizarImagenes(),
            'auditoria' => $this->mostrarAuditoria(),
            'integridad' => $this->verificarIntegridad(),
            default => $this->error("Acción '{$accion}' no válida. Usa: limpiar, estadisticas, optimizar, auditoria, integridad")
        };
    }

    private function limpiarImagenes()
    {
        $this->info('🧹 Limpiando imágenes huérfanas...');
        
        if (!$this->option('fuerza') && !$this->confirm('¿Estás seguro de limpiar las imágenes huérfanas?')) {
            $this->info('Operación cancelada.');
            return;
        }
        
        $archivosEliminados = $this->imagenService->limpiarImagenesHuerfanas();
        
        $this->info("✅ Se eliminaron {$archivosEliminados} directorios de imágenes huérfanas.");
    }

    private function mostrarEstadisticas()
    {
        $this->info('📊 Estadísticas de imágenes de vehículos');
        $this->line('');
        
        // Estadísticas generales
        $totalVehiculos = UnidadTransporte::count();
        $vehiculosConFoto = UnidadTransporte::whereNotNull('foto_principal')->count();
        $vehiculosConGaleria = UnidadTransporte::whereNotNull('galeria_fotos')->count();
        
        $this->table(['Métrica', 'Valor'], [
            ['Total de vehículos', $totalVehiculos],
            ['Vehículos con foto principal', $vehiculosConFoto],
            ['Vehículos con galería', $vehiculosConGaleria],
            ['% con foto principal', round(($vehiculosConFoto / $totalVehiculos) * 100, 1) . '%'],
        ]);
        
        // Estadísticas por tipo de documento
        $this->line('');
        $this->info('📋 Documentos fotográficos:');
        
        $documentos = [
            'foto_tarjeton_propiedad' => 'Tarjetón de propiedad',
            'foto_cedula_identidad' => 'Cédula de identidad vehicular',
            'foto_seguro' => 'Seguro',
            'foto_revision_tecnica' => 'Revisión técnica'
        ];
        
        $estadisticasDocumentos = [];
        foreach ($documentos as $campo => $nombre) {
            $cantidad = UnidadTransporte::whereNotNull($campo)->count();
            $porcentaje = round(($cantidad / $totalVehiculos) * 100, 1);
            $estadisticasDocumentos[] = [$nombre, $cantidad, $porcentaje . '%'];
        }
        
        $this->table(['Documento', 'Cantidad', '%'], $estadisticasDocumentos);
        
        // Uso de almacenamiento
        $this->line('');
        $this->info('💾 Uso de almacenamiento:');
        
        $rutaVehiculos = 'vehiculos';
        $tamaTotal = 0;
        $archivosTotal = 0;
        
        try {
            // Verificar si existe el directorio de vehículos
            if (!Storage::disk('public')->exists($rutaVehiculos)) {
                Storage::disk('public')->makeDirectory($rutaVehiculos);
            }
            
            $directorios = Storage::disk('public')->directories($rutaVehiculos);
            
            foreach ($directorios as $directorio) {
                try {
                    $archivos = Storage::disk('public')->allFiles($directorio);
                    $archivosTotal += count($archivos);
                    
                    foreach ($archivos as $archivo) {
                        $tamañoArchivo = Storage::disk('public')->size($archivo);
                        if ($tamañoArchivo !== false) {
                            $tamaTotal += $tamañoArchivo;
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn("Error procesando directorio {$directorio}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->error('Error accediendo al storage: ' . $e->getMessage());
        }
        
        $promedioTamaño = ($totalVehiculos > 0 && $tamaTotal > 0) 
            ? $tamaTotal / $totalVehiculos 
            : 0;
        
        $this->table(['Métrica', 'Valor'], [
            ['Total de archivos', number_format($archivosTotal)],
            ['Espacio utilizado', $this->formatearTamaño($tamaTotal)],
            ['Promedio por vehículo', $this->formatearTamaño((int)$promedioTamaño)],
            ['Directorio base', storage_path('app/public/' . $rutaVehiculos)],
            ['Directorios encontrados', count($directorios ?? [])],
        ]);
    }

    private function optimizarImagenes()
    {
        $this->info('🔧 Optimizando imágenes de vehículos...');
        
        $placa = $this->option('placa');
        
        if ($placa) {
            $vehiculos = UnidadTransporte::where('placa', $placa)->get();
            if ($vehiculos->isEmpty()) {
                $this->error("No se encontró vehículo con placa: {$placa}");
                return;
            }
        } else {
            $vehiculos = UnidadTransporte::whereNotNull('foto_principal')
                ->orWhereNotNull('galeria_fotos')
                ->get();
        }
        
        $this->info("Procesando {$vehiculos->count()} vehículos...");
        
        $bar = $this->output->createProgressBar($vehiculos->count());
        $optimizados = 0;
        
        foreach ($vehiculos as $vehiculo) {
            try {
                // Generar thumbnails para galería si no existen
                if ($vehiculo->galeria_fotos) {
                    foreach ($vehiculo->galeria_fotos as $rutaFoto) {
                        if (Storage::disk('public')->exists($rutaFoto)) {
                            $this->imagenService->generarThumbnail($rutaFoto);
                        }
                    }
                }
                
                $optimizados++;
            } catch (\Exception $e) {
                $this->error("Error procesando vehículo {$vehiculo->placa}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->line('');
        $this->info("✅ Se optimizaron {$optimizados} vehículos.");
    }

    private function formatearTamaño(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        
        $unidades = ['B', 'KB', 'MB', 'GB', 'TB'];
        $potencia = min(floor(log($bytes, 1024)), count($unidades) - 1);
        $potencia = max(0, $potencia); // Asegurar que no sea negativo
        
        $valor = round($bytes / pow(1024, $potencia), 2);
        
        return $valor . ' ' . $unidades[$potencia];
    }

    /**
     * Mostrar auditoría de imágenes
     */
    private function mostrarAuditoria(): void
    {
        $placa = $this->option('placa');
        $desde = $this->option('desde');
        $hasta = $this->option('hasta');
        $exportar = $this->option('exportar');

        $fechaDesde = $desde ? Carbon::parse($desde) : now()->subDays(30);
        $fechaHasta = $hasta ? Carbon::parse($hasta) : now();

        $this->info("🔍 Auditoría de imágenes de vehículos");
        $this->line("📅 Período: {$fechaDesde->format('d/m/Y')} - {$fechaHasta->format('d/m/Y')}");

        if ($placa) {
            $vehiculo = UnidadTransporte::where('placa', $placa)->first();
            if (!$vehiculo) {
                $this->error("❌ Vehículo con placa '{$placa}' no encontrado.");
                return;
            }

            $this->mostrarAuditoriaVehiculo($vehiculo);
        } else {
            $this->mostrarAuditoriaGeneral($fechaDesde, $fechaHasta);
        }

        if ($exportar) {
            $this->exportarAuditoria($fechaDesde, $fechaHasta);
        }
    }

    /**
     * Mostrar auditoría de un vehículo específico
     */
    private function mostrarAuditoriaVehiculo(UnidadTransporte $vehiculo): void
    {
        $historial = $vehiculo->obtenerHistorialImagenes();

        $this->table(
            ['Fecha', 'Acción', 'Tipo Imagen', 'Usuario', 'Detalles'],
            collect($historial)->map(function ($registro) {
                return [
                    Carbon::parse($registro['fecha'])->format('d/m/Y H:i'),
                    $registro['accion'],
                    $registro['tipo_imagen'],
                    $registro['usuario'],
                    isset($registro['detalles']['ruta']) ? basename($registro['detalles']['ruta']) : 'N/A'
                ];
            })->toArray()
        );

        $this->info("📊 Total de registros: " . count($historial));
    }

    /**
     * Mostrar auditoría general del sistema
     */
    private function mostrarAuditoriaGeneral(Carbon $fechaDesde, Carbon $fechaHasta): void
    {
        $estadisticas = $this->auditoriaService->obtenerEstadisticasDetalladas();

        $this->line("\n📈 Estadísticas Detalladas:");
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de vehículos', $estadisticas['total_vehiculos']],
                ['Vehículos con documentos completos', $estadisticas['completitud_documentos']['completos']],
                ['Vehículos con documentos parciales', $estadisticas['completitud_documentos']['parciales']],
                ['Vehículos sin documentos', $estadisticas['completitud_documentos']['sin_documentos']],
                ['Total de archivos', $estadisticas['uso_almacenamiento']['total_archivos']],
                ['Espacio utilizado', $this->formatearTamaño($estadisticas['uso_almacenamiento']['total_bytes'])],
            ]
        );

        $this->line("\n📋 Por tipo de imagen:");
        $tiposData = [];
        foreach ($estadisticas['por_tipo_imagen'] as $tipo => $datos) {
            $tiposData[] = [
                $tipo,
                $datos['cantidad'],
                $datos['porcentaje'] . '%',
                $this->formatearTamaño($datos['tamaño_total'])
            ];
        }

        $this->table(['Tipo', 'Cantidad', '%', 'Tamaño'], $tiposData);
    }

    /**
     * Verificar integridad del sistema
     */
    private function verificarIntegridad(): void
    {
        $placa = $this->option('placa');
        $fuerza = $this->option('fuerza');

        $this->info("🔍 Verificando integridad de imágenes...");

        if ($placa) {
            $vehiculo = UnidadTransporte::where('placa', $placa)->first();
            if (!$vehiculo) {
                $this->error("❌ Vehículo con placa '{$placa}' no encontrado.");
                return;
            }

            $this->verificarIntegridadVehiculo($vehiculo, $fuerza);
        } else {
            $this->verificarIntegridadCompleta($fuerza);
        }
    }

    /**
     * Verificar integridad de un vehículo específico
     */
    private function verificarIntegridadVehiculo(UnidadTransporte $vehiculo, bool $reparar = false): void
    {
        $problemas = $vehiculo->verificarIntegridadImagenes();

        if (empty($problemas)) {
            $this->info("✅ Vehículo {$vehiculo->placa}: Sin problemas de integridad.");
            return;
        }

        $this->warn("⚠️  Vehículo {$vehiculo->placa}: {count($problemas)} problemas encontrados:");
        
        foreach ($problemas as $problema) {
            $this->line("  • {$problema['descripcion']}");
        }

        if ($reparar || $this->confirm('¿Desea reparar automáticamente estos problemas?')) {
            $reparaciones = $vehiculo->repararIntegridadImagenes();
            
            if (!empty($reparaciones)) {
                $this->info("🔧 Reparaciones realizadas:");
                foreach ($reparaciones as $reparacion) {
                    $this->line("  ✓ {$reparacion}");
                }
            }
        }
    }

    /**
     * Verificar integridad completa del sistema
     */
    private function verificarIntegridadCompleta(bool $reparar = false): void
    {
        $reporte = $this->auditoriaService->generarReporteIntegridad();

        $this->info("📊 Reporte de Integridad Completo:");
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Fecha del reporte', $reporte['fecha_reporte']->format('d/m/Y H:i:s')],
                ['Vehículos verificados', $reporte['total_vehiculos_verificados']],
                ['Vehículos con problemas', $reporte['vehiculos_con_problemas']],
                ['Archivos faltantes', $reporte['resumen']['archivo_faltante']],
                ['Archivos huérfanos', $reporte['resumen']['archivos_huerfanos']],
            ]
        );

        if ($reporte['vehiculos_con_problemas'] > 0) {
            $this->warn("\n⚠️  Problemas encontrados en {$reporte['vehiculos_con_problemas']} vehículos.");
            
            if ($reparar || $this->confirm('¿Desea reparar automáticamente todos los problemas?')) {
                $reparacionesTotales = [];
                
                foreach ($reporte['problemas_encontrados'] as $placa => $problemas) {
                    $vehiculo = UnidadTransporte::where('placa', $placa)->first();
                    if ($vehiculo) {
                        $reparaciones = $vehiculo->repararIntegridadImagenes();
                        $reparacionesTotales = array_merge($reparacionesTotales, $reparaciones);
                    }
                }

                $this->info("🔧 Total de reparaciones: " . count($reparacionesTotales));
            }
        } else {
            $this->info("✅ Sistema de imágenes íntegro.");
        }
    }

    /**
     * Exportar auditoría a archivo
     */
    private function exportarAuditoria(Carbon $fechaDesde, Carbon $fechaHasta): void
    {
        $this->info("📁 Exportando auditoría...");
        
        $urlArchivo = $this->auditoriaService->exportarAuditoria($fechaDesde, $fechaHasta);
        
        $this->info("✅ Auditoría exportada: {$urlArchivo}");
    }
}
