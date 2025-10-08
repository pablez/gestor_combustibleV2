<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnidadTransporte;
use App\Models\RegistroAuditoria;
use Illuminate\Support\Facades\Storage;

class ImagenesVehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehiculos = UnidadTransporte::limit(5)->get();

        if ($vehiculos->isEmpty()) {
            $this->command->warn('No hay vehículos en la base de datos. Ejecuta primero el seeder de vehículos.');
            return;
        }

        $this->command->info('Creando datos de prueba para imágenes de vehículos...');

        // Crear estructura de directorios
        $this->crearEstructuraDirectorios();

        foreach ($vehiculos as $vehiculo) {
            $this->generarImagenesPrueba($vehiculo);
        }

        $this->command->info('Datos de prueba de imágenes creados exitosamente.');
    }

    /**
     * Crear estructura de directorios necesaria
     */
    private function crearEstructuraDirectorios(): void
    {
        $directorios = [
            'vehiculos',
            'reportes/auditorias',
            'temp/imagenes'
        ];

        foreach ($directorios as $directorio) {
            Storage::disk('public')->makeDirectory($directorio);
        }
    }

    /**
     * Generar imágenes de prueba para un vehículo
     */
    private function generarImagenesPrueba(UnidadTransporte $vehiculo): void
    {
        $placaLimpia = preg_replace('/[^A-Za-z0-9]/', '', $vehiculo->placa);
        
        // Crear carpetas del vehículo
        $carpetas = ['principales', 'galeria', 'documentos'];
        foreach ($carpetas as $carpeta) {
            Storage::disk('public')->makeDirectory("vehiculos/{$placaLimpia}/{$carpeta}");
        }

        // Simular archivos de imagen (crear archivos dummy)
        $rutasImagenes = [];

        // Foto principal
        if (rand(1, 100) > 30) { // 70% probabilidad
            $rutaPrincipal = "vehiculos/{$placaLimpia}/principales/foto_principal_" . now()->format('YmdHis') . "_dummy.jpg";
            Storage::disk('public')->put($rutaPrincipal, $this->crearImagenDummy('Foto Principal ' . $vehiculo->placa));
            $rutasImagenes['foto_principal'] = $rutaPrincipal;
        }

        // Galería de fotos
        $numGaleria = rand(0, 5);
        $galeriaFotos = [];
        for ($i = 0; $i < $numGaleria; $i++) {
            $rutaGaleria = "vehiculos/{$placaLimpia}/galeria/galeria_{$i}_" . now()->format('YmdHis') . "_dummy.jpg";
            Storage::disk('public')->put($rutaGaleria, $this->crearImagenDummy("Galería {$i} " . $vehiculo->placa));
            $galeriaFotos[] = $rutaGaleria;
        }
        if (!empty($galeriaFotos)) {
            $rutasImagenes['galeria_fotos'] = $galeriaFotos;
        }

        // Documentos
        $documentos = [
            'foto_tarjeton_propiedad' => 'Tarjetón de Propiedad',
            'foto_cedula_identidad' => 'Cédula de Identidad',
            'foto_seguro' => 'Seguro',
            'foto_revision_tecnica' => 'Revisión Técnica'
        ];

        foreach ($documentos as $campo => $nombre) {
            if (rand(1, 100) > 40) { // 60% probabilidad
                $rutaDoc = "vehiculos/{$placaLimpia}/documentos/{$campo}_" . now()->format('YmdHis') . "_dummy.jpg";
                Storage::disk('public')->put($rutaDoc, $this->crearImagenDummy($nombre . ' ' . $vehiculo->placa));
                $rutasImagenes[$campo] = $rutaDoc;
            }
        }

        // Actualizar el vehículo con las rutas
        $datosActualizacion = [];
        $metadatos = [];

        foreach ($rutasImagenes as $campo => $ruta) {
            if ($campo === 'galeria_fotos') {
                $datosActualizacion[$campo] = $ruta;
                foreach ($ruta as $index => $rutaImagen) {
                    $metadatos['galeria'][$index] = [
                        'ruta' => $rutaImagen,
                        'fecha_subida' => now(),
                        'usuario_id' => 1,
                        'nombre_original' => basename($rutaImagen),
                        'tipo_mime' => 'image/jpeg',
                        'tamaño' => 1024
                    ];
                }
            } else {
                $datosActualizacion[$campo] = $ruta;
                $metadatos[$campo] = [
                    'ruta' => $ruta,
                    'fecha_subida' => now(),
                    'usuario_id' => 1,
                    'nombre_original' => basename($ruta),
                    'tipo_mime' => 'image/jpeg',
                    'tamaño' => 1024
                ];
            }
        }

        $datosActualizacion['metadatos_imagenes'] = $metadatos;
        $vehiculo->update($datosActualizacion);

        // Crear registros de auditoría de prueba
        $this->crearRegistrosAuditoria($vehiculo, $rutasImagenes);

        $this->command->line("✓ Imágenes generadas para vehículo {$vehiculo->placa}");
    }

    /**
     * Crear registros de auditoría de prueba
     */
    private function crearRegistrosAuditoria(UnidadTransporte $vehiculo, array $rutasImagenes): void
    {
        foreach ($rutasImagenes as $tipoImagen => $ruta) {
            $rutas = is_array($ruta) ? $ruta : [$ruta];
            
            foreach ($rutas as $rutaIndividual) {
                RegistroAuditoria::create([
                    'tabla_afectada' => 'unidad_transportes',
                    'registro_afectado' => json_encode(['id' => $vehiculo->id, 'placa' => $vehiculo->placa]),
                    'accion_realizada' => 'SUBIR_IMAGEN',
                    'valores_anteriores' => json_encode(['valor_anterior' => null]),
                    'valores_nuevos' => json_encode([
                        'vehiculo_id' => $vehiculo->id,
                        'placa' => $vehiculo->placa,
                        'tipo_imagen' => $tipoImagen,
                        'accion' => 'SUBIR_IMAGEN',
                        'datos_adicionales' => ['ruta' => $rutaIndividual],
                        'timestamp' => now(),
                        'usuario_id' => 1,
                        'usuario_nombre' => 'Seeder'
                    ]),
                    'id_usuario' => 1,
                    'fecha_hora' => now()->subDays(rand(1, 30)),
                    'ip_origen' => '127.0.0.1',
                    'user_agent' => 'Seeder Bot',
                    'modulo_sistema' => 'IMAGENES_VEHICULOS',
                    'nivel_criticidad' => 'MEDIO'
                ]);
            }
        }
    }

    /**
     * Crear contenido dummy para imágenes de prueba
     */
    private function crearImagenDummy(string $texto): string
    {
        // Crear un archivo de texto simple que simule una imagen
        return "DUMMY IMAGE FILE\n" .
               "========================\n" .
               "Texto: {$texto}\n" .
               "Fecha: " . now()->toDateTimeString() . "\n" .
               "Tamaño simulado: 1024 bytes\n" .
               "Tipo: image/jpeg (simulado)\n" .
               "========================\n" .
               str_repeat("X", 800); // Llenar para simular contenido
    }
}
