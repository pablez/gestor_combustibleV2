<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AuditoriaImagenService;
use App\Models\UnidadTransporte;

class GestionarIntegridadImagenes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vehiculos:integridad {accion : verificar|reparar|reporte|limpiar}
                          {--vehiculo= : ID o placa específica del vehículo}
                          {--auto-fix : Reparar automáticamente los problemas encontrados}
                          {--exportar : Exportar el reporte a archivo}';

    /**
     * The console command description.
     */
    protected $description = 'Gestionar la integridad de las imágenes de vehículos (verificar, reparar, reportes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $auditoriaService = app(AuditoriaImagenService::class);
        $accion = $this->argument('accion');
        $vehiculoSpecifico = $this->option('vehiculo');

        switch ($accion) {
            case 'verificar':
                $this->verificarIntegridad($auditoriaService, $vehiculoSpecifico);
                break;
            case 'reparar':
                $this->repararIntegridad($auditoriaService, $vehiculoSpecifico);
                break;
            case 'reporte':
                $this->generarReporte($auditoriaService);
                break;
            case 'limpiar':
                $this->limpiarImagenesHuerfanas();
                break;
            default:
                $this->error("Acción no válida. Usa: verificar, reparar, reporte, limpiar");
                return 1;
        }

        return 0;
    }

    private function verificarIntegridad(AuditoriaImagenService $service, $vehiculoSpecifico = null)
    {
        $this->info('🔍 Verificando integridad de imágenes...');
        
        if ($vehiculoSpecifico) {
            $vehiculo = $this->obtenerVehiculo($vehiculoSpecifico);
            if (!$vehiculo) return;

            $problemas = $service->verificarIntegridadVehiculo($vehiculo);
            $this->mostrarProblemasVehiculo($vehiculo, $problemas);
        } else {
            $reporte = $service->generarReporteIntegridad();
            $this->mostrarReporteCompleto($reporte);
        }
    }

    private function repararIntegridad(AuditoriaImagenService $service, $vehiculoSpecifico = null)
    {
        $this->info('🔧 Reparando problemas de integridad...');
        
        if ($vehiculoSpecifico) {
            $vehiculo = $this->obtenerVehiculo($vehiculoSpecifico);
            if (!$vehiculo) return;

            $reparaciones = $service->repararIntegridad($vehiculo);
            if (empty($reparaciones)) {
                $this->info("✅ Vehículo {$vehiculo->placa}: Sin problemas encontrados");
            } else {
                $this->warn("🔧 Vehículo {$vehiculo->placa}: Reparaciones realizadas:");
                foreach ($reparaciones as $reparacion) {
                    $this->line("  - {$reparacion}");
                }
            }
        } else {
            $vehiculos = UnidadTransporte::all();
            $totalReparaciones = 0;

            $bar = $this->output->createProgressBar($vehiculos->count());
            $bar->start();

            foreach ($vehiculos as $vehiculo) {
                $reparaciones = $service->repararIntegridad($vehiculo);
                $totalReparaciones += count($reparaciones);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("✅ Reparación completada. Total de acciones: {$totalReparaciones}");
        }
    }

    private function generarReporte(AuditoriaImagenService $service)
    {
        $this->info('📊 Generando reporte detallado...');
        
        $estadisticas = $service->obtenerEstadisticasDetalladas();
        
        $this->newLine();
        $this->line('=== REPORTE DE INTEGRIDAD DE IMÁGENES ===');
        $this->newLine();
        
        // Estadísticas generales
        $this->info('📈 Estadísticas Generales:');
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de vehículos', $estadisticas['total_vehiculos']],
                ['Total de archivos', number_format($estadisticas['uso_almacenamiento']['total_archivos'])],
                ['Espacio usado', $this->formatBytes($estadisticas['uso_almacenamiento']['total_bytes'])],
                ['Promedio por vehículo', $this->formatBytes($estadisticas['uso_almacenamiento']['total_bytes'] / max($estadisticas['total_vehiculos'], 1))],
            ]
        );

        $this->newLine();
        
        // Por tipo de imagen
        $this->info('📋 Por Tipo de Imagen:');
        $datosTipos = [];
        foreach ($estadisticas['por_tipo_imagen'] as $tipo => $datos) {
            $datosTipos[] = [
                $tipo,
                $datos['cantidad'],
                $datos['porcentaje'] . '%',
                $this->formatBytes($datos['tamaño_total'])
            ];
        }
        
        $this->table(
            ['Tipo', 'Cantidad', 'Porcentaje', 'Tamaño Total'],
            $datosTipos
        );

        $this->newLine();
        
        // Completitud de documentos
        $this->info('📄 Completitud de Documentos:');
        $completitud = $estadisticas['completitud_documentos'];
        $this->table(
            ['Estado', 'Cantidad'],
            [
                ['Documentos completos', $completitud['completos']],
                ['Documentos parciales', $completitud['parciales']],
                ['Sin documentos', $completitud['sin_documentos']],
            ]
        );

        // Exportar si se solicita
        if ($this->option('exportar')) {
            $urlExportacion = $service->exportarAuditoria();
            $this->info("📁 Reporte exportado: {$urlExportacion}");
        }
    }

    private function limpiarImagenesHuerfanas()
    {
        $this->info('🧹 Limpiando imágenes huérfanas...');
        
        $imagenService = app(\App\Services\ImagenVehiculoService::class);
        $archivosEliminados = $imagenService->limpiarImagenesHuerfanas();
        
        if ($archivosEliminados > 0) {
            $this->warn("🗑️ Se eliminaron {$archivosEliminados} directorios huérfanos");
        } else {
            $this->info("✅ No se encontraron imágenes huérfanas");
        }
    }

    private function obtenerVehiculo($identificador)
    {
        $vehiculo = is_numeric($identificador) 
            ? UnidadTransporte::find($identificador)
            : UnidadTransporte::where('placa', $identificador)->first();

        if (!$vehiculo) {
            $this->error("❌ Vehículo no encontrado: {$identificador}");
            return null;
        }

        return $vehiculo;
    }

    private function mostrarProblemasVehiculo(UnidadTransporte $vehiculo, array $problemas)
    {
        if (empty($problemas)) {
            $this->info("✅ Vehículo {$vehiculo->placa}: Sin problemas de integridad");
            return;
        }

        $this->warn("⚠️ Vehículo {$vehiculo->placa}: {count($problemas)} problema(s) encontrado(s)");
        
        foreach ($problemas as $i => $problema) {
            $this->line("  " . ($i + 1) . ". {$problema['descripcion']}");
            if (isset($problema['ruta'])) {
                $this->line("     Ruta: {$problema['ruta']}");
            }
            if (isset($problema['archivos'])) {
                $this->line("     Archivos: " . count($problema['archivos']) . " archivo(s)");
            }
        }

        if ($this->option('auto-fix')) {
            $service = app(AuditoriaImagenService::class);
            $reparaciones = $service->repararIntegridad($vehiculo);
            
            if (!empty($reparaciones)) {
                $this->info("🔧 Reparaciones automáticas aplicadas:");
                foreach ($reparaciones as $reparacion) {
                    $this->line("  ✓ {$reparacion}");
                }
            }
        }
    }

    private function mostrarReporteCompleto(array $reporte)
    {
        $this->info("📊 Reporte de integridad generado: {$reporte['fecha_reporte']}");
        $this->newLine();
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Vehículos verificados', $reporte['total_vehiculos_verificados']],
                ['Vehículos con problemas', $reporte['vehiculos_con_problemas']],
                ['Archivos faltantes', $reporte['resumen']['archivo_faltante']],
                ['Archivos faltantes galería', $reporte['resumen']['archivo_faltante_galeria']],
                ['Archivos huérfanos', $reporte['resumen']['archivos_huerfanos']],
            ]
        );

        if ($reporte['vehiculos_con_problemas'] > 0) {
            $this->newLine();
            $this->warn("⚠️ Vehículos con problemas:");
            
            foreach ($reporte['problemas_encontrados'] as $placa => $problemas) {
                $this->line("  - {$placa}: " . count($problemas) . " problema(s)");
            }
            
            $this->newLine();
            $this->comment("💡 Ejecuta 'php artisan vehiculos:integridad reparar' para solucionarlos automáticamente");
        } else {
            $this->info("✅ Todos los vehículos tienen imágenes íntegras");
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}