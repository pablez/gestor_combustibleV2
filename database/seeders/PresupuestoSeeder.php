<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Presupuesto;
use App\Models\UnidadOrganizacional;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;

class PresupuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de las tablas relacionadas
        $unidades = UnidadOrganizacional::where('activa', true)->pluck('id_unidad_organizacional')->toArray();
        $categorias = CategoriaProgramatica::where('activo', true)->pluck('id')->toArray();
        $fuentes = FuenteOrganismoFinanciero::where('activo', true)->pluck('id')->toArray();

        // Datos de ejemplo para presupuestos
        $presupuestos = [
            // Año 2024 - Trimestre 1
            [
                'id_cat_programatica' => $categorias[0] ?? 1,
                'id_fuente_org_fin' => $fuentes[0] ?? 1,
                'id_unidad_organizacional' => $unidades[0] ?? 1,
                'anio_fiscal' => 2024,
                'trimestre' => 1,
                'presupuesto_inicial' => 50000.00,
                'presupuesto_actual' => 50000.00,
                'total_gastado' => 15000.00,
                'total_comprometido' => 8000.00,
                'num_documento' => 'DOC-2024-001',
                'numero_comprobante' => 'COMP-001',
                'fecha_aprobacion' => '2024-01-15',
                'porcentaje_preventivo' => 80.00,
                'alerta_porcentaje' => 90.00,
                'activo' => true,
                'observaciones' => 'Presupuesto inicial para proyectos de infraestructura básica',
            ],
            [
                'id_cat_programatica' => $categorias[1] ?? 2,
                'id_fuente_org_fin' => $fuentes[1] ?? 2,
                'id_unidad_organizacional' => $unidades[1] ?? 2,
                'anio_fiscal' => 2024,
                'trimestre' => 1,
                'presupuesto_inicial' => 75000.00,
                'presupuesto_actual' => 78000.00,
                'total_gastado' => 32000.00,
                'total_comprometido' => 15000.00,
                'num_documento' => 'DOC-2024-002',
                'numero_comprobante' => 'COMP-002',
                'fecha_aprobacion' => '2024-01-20',
                'porcentaje_preventivo' => 75.00,
                'alerta_porcentaje' => 85.00,
                'activo' => true,
                'observaciones' => 'Presupuesto para programas sociales y desarrollo comunitario',
            ],

            // Año 2024 - Trimestre 2
            [
                'id_cat_programatica' => $categorias[0] ?? 1,
                'id_fuente_org_fin' => $fuentes[0] ?? 1,
                'id_unidad_organizacional' => $unidades[2] ?? 3,
                'anio_fiscal' => 2024,
                'trimestre' => 2,
                'presupuesto_inicial' => 60000.00,
                'presupuesto_actual' => 65000.00,
                'total_gastado' => 45000.00,
                'total_comprometido' => 12000.00,
                'num_documento' => 'DOC-2024-003',
                'numero_comprobante' => 'COMP-003',
                'fecha_aprobacion' => '2024-04-10',
                'porcentaje_preventivo' => 80.00,
                'alerta_porcentaje' => 90.00,
                'activo' => true,
                'observaciones' => 'Presupuesto segundo trimestre - ampliación de cobertura',
            ],
            [
                'id_cat_programatica' => $categorias[2] ?? 3,
                'id_fuente_org_fin' => $fuentes[1] ?? 2,
                'id_unidad_organizacional' => $unidades[3] ?? 4,
                'anio_fiscal' => 2024,
                'trimestre' => 2,
                'presupuesto_inicial' => 40000.00,
                'presupuesto_actual' => 40000.00,
                'total_gastado' => 38000.00,
                'total_comprometido' => 1500.00,
                'num_documento' => 'DOC-2024-004',
                'numero_comprobante' => 'COMP-004',
                'fecha_aprobacion' => '2024-04-15',
                'porcentaje_preventivo' => 85.00,
                'alerta_porcentaje' => 95.00,
                'activo' => false,
                'observaciones' => 'Presupuesto casi agotado - requiere seguimiento especial',
            ],

            // Año 2024 - Trimestre 3
            [
                'id_cat_programatica' => $categorias[0] ?? 1,
                'id_fuente_org_fin' => $fuentes[0] ?? 1,
                'id_unidad_organizacional' => $unidades[4] ?? 5,
                'anio_fiscal' => 2024,
                'trimestre' => 3,
                'presupuesto_inicial' => 85000.00,
                'presupuesto_actual' => 90000.00,
                'total_gastado' => 25000.00,
                'total_comprometido' => 20000.00,
                'num_documento' => 'DOC-2024-005',
                'numero_comprobante' => 'COMP-005',
                'fecha_aprobacion' => '2024-07-05',
                'porcentaje_preventivo' => 80.00,
                'alerta_porcentaje' => 90.00,
                'activo' => true,
                'observaciones' => 'Presupuesto con refuerzo adicional por demanda incrementada',
            ],

            // Año 2025 - Trimestre 1 (Actual)
            [
                'id_cat_programatica' => $categorias[1] ?? 2,
                'id_fuente_org_fin' => $fuentes[0] ?? 1,
                'id_unidad_organizacional' => $unidades[5] ?? 6,
                'anio_fiscal' => 2025,
                'trimestre' => 1,
                'presupuesto_inicial' => 95000.00,
                'presupuesto_actual' => 95000.00,
                'total_gastado' => 12000.00,
                'total_comprometido' => 8000.00,
                'num_documento' => 'DOC-2025-001',
                'numero_comprobante' => 'COMP-006',
                'fecha_aprobacion' => '2025-01-10',
                'porcentaje_preventivo' => 80.00,
                'alerta_porcentaje' => 90.00,
                'activo' => true,
                'observaciones' => 'Presupuesto 2025 - primer trimestre con proyección optimizada',
            ],
            [
                'id_cat_programatica' => $categorias[2] ?? 3,
                'id_fuente_org_fin' => $fuentes[1] ?? 2,
                'id_unidad_organizacional' => $unidades[6] ?? 7,
                'anio_fiscal' => 2025,
                'trimestre' => 1,
                'presupuesto_inicial' => 120000.00,
                'presupuesto_actual' => 125000.00,
                'total_gastado' => 35000.00,
                'total_comprometido' => 25000.00,
                'num_documento' => 'DOC-2025-002',
                'numero_comprobante' => 'COMP-007',
                'fecha_aprobacion' => '2025-01-15',
                'porcentaje_preventivo' => 75.00,
                'alerta_porcentaje' => 85.00,
                'activo' => true,
                'observaciones' => 'Presupuesto aumentado por nuevos proyectos estratégicos',
            ],

            // Año 2025 - Trimestre 2 (Planificado)
            [
                'id_cat_programatica' => $categorias[0] ?? 1,
                'id_fuente_org_fin' => $fuentes[0] ?? 1,
                'id_unidad_organizacional' => $unidades[7] ?? 8,
                'anio_fiscal' => 2025,
                'trimestre' => 2,
                'presupuesto_inicial' => 80000.00,
                'presupuesto_actual' => 80000.00,
                'total_gastado' => 0.00,
                'total_comprometido' => 5000.00,
                'num_documento' => 'DOC-2025-003',
                'numero_comprobante' => null,
                'fecha_aprobacion' => '2025-03-01',
                'porcentaje_preventivo' => 80.00,
                'alerta_porcentaje' => 90.00,
                'activo' => true,
                'observaciones' => 'Presupuesto segundo trimestre - en proceso de planificación',
            ],

            // Presupuesto en alerta (cerca del límite)
            [
                'id_cat_programatica' => $categorias[1] ?? 2,
                'id_fuente_org_fin' => $fuentes[1] ?? 2,
                'id_unidad_organizacional' => $unidades[8] ?? 9,
                'anio_fiscal' => 2024,
                'trimestre' => 4,
                'presupuesto_inicial' => 30000.00,
                'presupuesto_actual' => 32000.00,
                'total_gastado' => 28000.00,
                'total_comprometido' => 3500.00,
                'num_documento' => 'DOC-2024-006',
                'numero_comprobante' => 'COMP-008',
                'fecha_aprobacion' => '2024-10-01',
                'porcentaje_preventivo' => 80.00,
                'alerta_porcentaje' => 90.00,
                'activo' => true,
                'observaciones' => '⚠️ ALERTA: Presupuesto cerca del límite máximo - requiere autorización especial',
            ],

            // Presupuesto sobregiro
            [
                'id_cat_programatica' => $categorias[2] ?? 3,
                'id_fuente_org_fin' => $fuentes[0] ?? 1,
                'id_unidad_organizacional' => $unidades[9] ?? 10,
                'anio_fiscal' => 2024,
                'trimestre' => 3,
                'presupuesto_inicial' => 25000.00,
                'presupuesto_actual' => 25000.00,
                'total_gastado' => 22000.00,
                'total_comprometido' => 5000.00,
                'num_documento' => 'DOC-2024-007',
                'numero_comprobante' => 'COMP-009',
                'fecha_aprobacion' => '2024-07-20',
                'porcentaje_preventivo' => 85.00,
                'alerta_porcentaje' => 95.00,
                'activo' => false,
                'observaciones' => '🚨 CRÍTICO: Presupuesto en sobregiro - suspendido hasta revisión',
            ],
        ];

        // Crear los presupuestos
        foreach ($presupuestos as $presupuesto) {
            Presupuesto::create($presupuesto);
        }

        $this->command->info('✅ Se han creado ' . count($presupuestos) . ' presupuestos de ejemplo');
        $this->command->info('📊 Incluye presupuestos de 2024 y 2025 con diferentes estados');
        $this->command->info('⚠️  Algunos presupuestos tienen alertas y estados críticos para pruebas');
    }
}
