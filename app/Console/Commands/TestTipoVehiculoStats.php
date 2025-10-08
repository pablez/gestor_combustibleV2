<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TipoVehiculo;

class TestTipoVehiculoStats extends Command
{
    protected $signature = 'test:tipo-vehiculo-stats';
    protected $description = 'Test TipoVehiculo statistics and relationships';

    public function handle()
    {
        $this->info('Testing TipoVehiculo statistics and relationships...');
        
        // Test basic counts
        $total = TipoVehiculo::count();
        $activos = TipoVehiculo::where('activo', true)->count();
        $categorias = TipoVehiculo::distinct()->count('categoria');
        
        $this->line("Total tipos de vehículo: {$total}");
        $this->line("Tipos activos: {$activos}");
        $this->line("Número de categorías: {$categorias}");
        
        // Test relationship (if UnidadTransporte exists)
        try {
            $enUso = TipoVehiculo::has('unidadesTransporte')->count();
            $this->line("Tipos en uso: {$enUso}");
        } catch (\Exception $e) {
            $this->warn("Error testing relationship: " . $e->getMessage());
        }
        
        // Show all types
        $this->info("\nTipos de vehículo existentes:");
        TipoVehiculo::all()->each(function ($tipo) {
            $this->line("- {$tipo->nombre} ({$tipo->categoria}) - " . ($tipo->activo ? 'Activo' : 'Inactivo'));
        });
        
        $this->info("\nTest completed successfully!");
    }
}