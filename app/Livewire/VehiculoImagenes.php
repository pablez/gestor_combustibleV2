<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\UnidadTransporte;
use App\Services\ImagenVehiculoService;
use Illuminate\Support\Facades\Storage;

class VehiculoImagenes extends Component
{
    use WithFileUploads;

    public UnidadTransporte $vehiculo;
    public string $tipoImagenActivo = 'foto_principal';
    public $imagenes = [];
    public $nuevasImagenes = [];
    public bool $mostrarModal = false;
    public string $modalTipo = '';
    public bool $cargando = false;
    public array $erroresValidacion = [];
    public array $configuracionTipos = [];

    protected $listeners = [
        'abrirModalImagenes' => 'abrirModal',
        'cerrarModalImagenes' => 'cerrarModal'
    ];

    public function mount(UnidadTransporte $vehiculo)
    {
        $this->vehiculo = $vehiculo;
        $this->configuracionTipos = config('vehiculos-imagenes.tipos', []);
        $this->cargarImagenes();
    }

    public function cargarImagenes()
    {
        try {
            $this->cargando = true;
            
            foreach ($this->configuracionTipos as $tipo => $config) {
                if ($tipo === 'galeria_fotos') {
                    $this->imagenes[$tipo] = $this->vehiculo->galeria_fotos_urls ?? [];
                } else {
                    $tipoLimpio = str_replace('foto_', '', $tipo);
                    $url = $this->vehiculo->getFotoDocumentoUrl($tipoLimpio);
                    $this->imagenes[$tipo] = $url ? [$url] : [];
                }
            }
        } catch (\Exception $e) {
            $this->erroresValidacion['carga'] = 'Error al cargar imágenes: ' . $e->getMessage();
        } finally {
            $this->cargando = false;
        }
    }

    public function cambiarTipoImagen($tipo)
    {
        $this->tipoImagenActivo = $tipo;
    }

    public function abrirModal($tipo)
    {
        // Validar que el tipo existe en la configuración
        if (!isset($this->configuracionTipos[$tipo])) {
            session()->flash('error', 'Tipo de imagen no válido.');
            return;
        }
        
        $this->modalTipo = $tipo;
        $this->mostrarModal = true;
        $this->nuevasImagenes = [];
        $this->erroresValidacion = [];
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->modalTipo = '';
        $this->nuevasImagenes = [];
        $this->erroresValidacion = [];
        $this->cargarImagenes();
    }

    public function subirImagen()
    {
        $this->erroresValidacion = [];
        
        if (!$this->modalTipo || !isset($this->configuracionTipos[$this->modalTipo])) {
            $this->erroresValidacion['tipo'] = 'Tipo de imagen no válido.';
            return;
        }
        
        $config = $this->configuracionTipos[$this->modalTipo];
        
        // Validar que se hayan seleccionado imágenes
        if (empty($this->nuevasImagenes)) {
            $this->erroresValidacion['imagenes'] = 'Debe seleccionar al menos una imagen.';
            return;
        }
        
        // Validaciones según tipo
        try {
            if ($config['multiple'] ?? false) {
                $this->validate([
                    'nuevasImagenes.*' => [
                        'required',
                        'image',
                        'max:' . ($config['max_size_kb'] ?? 2048),
                        'mimes:jpeg,jpg,png,webp'
                    ]
                ], [
                    'nuevasImagenes.*.required' => 'Todas las imágenes son requeridas.',
                    'nuevasImagenes.*.image' => 'El archivo debe ser una imagen válida.',
                    'nuevasImagenes.*.max' => 'La imagen no puede ser mayor a ' . number_format(($config['max_size_kb'] ?? 2048) / 1024, 1) . 'MB.',
                    'nuevasImagenes.*.mimes' => 'Solo se permiten archivos JPG, PNG o WEBP.'
                ]);
                
                // Validar cantidad máxima
                $maxFiles = $config['max_files'] ?? 10;
                if (count($this->nuevasImagenes) > $maxFiles) {
                    $this->erroresValidacion['cantidad'] = "Máximo {$maxFiles} archivos permitidos.";
                    return;
                }
            } else {
                $this->validate([
                    'nuevasImagenes.0' => [
                        'required',
                        'image',
                        'max:' . ($config['max_size_kb'] ?? 2048),
                        'mimes:jpeg,jpg,png,webp'
                    ]
                ], [
                    'nuevasImagenes.0.required' => 'La imagen es requerida.',
                    'nuevasImagenes.0.image' => 'El archivo debe ser una imagen válida.',
                    'nuevasImagenes.0.max' => 'La imagen no puede ser mayor a ' . number_format(($config['max_size_kb'] ?? 2048) / 1024, 1) . 'MB.',
                    'nuevasImagenes.0.mimes' => 'Solo se permiten archivos JPG, PNG o WEBP.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->erroresValidacion = $e->errors();
            return;
        }
        
        try {
            $this->cargando = true;
            $imagenService = app(ImagenVehiculoService::class);
            
            if ($config['multiple'] ?? false) {
                $subidas = 0;
                foreach ($this->nuevasImagenes as $imagen) {
                    if ($imagen && is_object($imagen)) {
                            $resultado = $imagenService->guardarImagen($imagen, $this->modalTipo, $this->vehiculo->placa);
                            // Como el procesamiento puede ser asíncrono, agregamos la referencia a la galería ahora
                            $this->vehiculo->agregarFotoAGaleria($resultado['ruta'], $resultado['metadatos']);
                            $subidas++;
                        }
                }
                session()->flash('message', "{$subidas} imágenes subidas correctamente.");
            } else {
                $imagen = $this->nuevasImagenes[0] ?? null;
                    if ($imagen && is_object($imagen)) {
                    $resultado = $imagenService->guardarImagen($imagen, $this->modalTipo, $this->vehiculo->placa);

                    // Actualizamos la referencia y dejamos que el job procese/optimice y registre auditoría
                    $this->vehiculo->actualizarImagenConAuditoria(
                        $this->modalTipo, 
                        $resultado['ruta'], 
                        $resultado['metadatos']
                    );

                    session()->flash('message', 'Imagen subida correctamente. Procesamiento en segundo plano.');
                }
            }
            
            $this->cerrarModal();
            $this->dispatch('imagenesActualizadas');
            
        } catch (\Exception $e) {
            $this->erroresValidacion['subida'] = 'Error al subir la imagen: ' . $e->getMessage();
        } finally {
            $this->cargando = false;
        }
    }

    public function eliminarImagen($tipo, $indice = null)
    {
        try {
            $imagenService = app(ImagenVehiculoService::class);

            if ($tipo === 'galeria_fotos' && $indice !== null) {
                // Obtener la URL de la imagen antes de eliminarla
                $galeria = $this->vehiculo->galeria_fotos ?? [];
                if (isset($galeria[$indice])) {
                    $rutaImagen = $galeria[$indice];
                    $imagenService->eliminarImagen($rutaImagen);
                    $this->vehiculo->eliminarFotoDeGaleria($indice);
                    session()->flash('message', 'Imagen eliminada de la galería.');
                }
            } else {
                // Eliminar documento específico
                $rutaActual = $this->vehiculo->{$tipo};
                if ($rutaActual) {
                    $imagenService->eliminarImagen($rutaActual);
                    $this->vehiculo->eliminarImagenConAuditoria($tipo);
                    
                    session()->flash('message', 'Imagen eliminada correctamente.');
                }
            }

            $this->cargarImagenes();
            $this->dispatch('imagenesActualizadas');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Obtener el progreso de completitud de documentos
     */
    public function getProgresoDocumentos()
    {
        $tipos = array_filter($this->configuracionTipos, function($config, $tipo) {
            return $tipo !== 'galeria_fotos' && ($config['required'] ?? false);
        }, ARRAY_FILTER_USE_BOTH);
        
        $completados = 0;
        foreach ($tipos as $tipo => $config) {
            if (!empty($this->imagenes[$tipo])) {
                $completados++;
            }
        }
        
        return [
            'completados' => $completados,
            'total' => count($tipos),
            'porcentaje' => count($tipos) > 0 ? round(($completados / count($tipos)) * 100) : 0
        ];
    }

    /**
     * Validar archivo antes de la subida
     */
    public function validarArchivo($archivo, $tipo)
    {
        $config = $this->configuracionTipos[$tipo] ?? [];
        $errores = [];

        // Validar tipo de archivo
        if (!in_array(strtolower($archivo->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
            $errores[] = 'Solo se permiten archivos JPG, PNG o WEBP.';
        }

        // Validar tamaño
        $maxSize = ($config['max_size_kb'] ?? 2048) * 1024; // Convertir a bytes
        if ($archivo->getSize() > $maxSize) {
            $errores[] = 'El archivo es demasiado grande. Máximo: ' . number_format($maxSize / 1024 / 1024, 1) . 'MB';
        }

        // Validar dimensiones si están especificadas
        if (isset($config['min_width']) || isset($config['min_height'])) {
            try {
                $dimensions = getimagesize($archivo->getPathname());
                if ($dimensions) {
                    $width = $dimensions[0];
                    $height = $dimensions[1];
                    
                    if (isset($config['min_width']) && $width < $config['min_width']) {
                        $errores[] = "Ancho mínimo: {$config['min_width']}px (actual: {$width}px)";
                    }
                    
                    if (isset($config['min_height']) && $height < $config['min_height']) {
                        $errores[] = "Alto mínimo: {$config['min_height']}px (actual: {$height}px)";
                    }
                }
            } catch (\Exception $e) {
                $errores[] = 'No se pudieron validar las dimensiones de la imagen.';
            }
        }

        return $errores;
    }

    /**
     * Optimizar todas las imágenes del vehículo
     */
    public function optimizarImagenes()
    {
        try {
            $this->cargando = true;
            $imagenService = app(ImagenVehiculoService::class);
            $optimizadas = 0;

            // Optimizar imágenes de documentos
            foreach ($this->configuracionTipos as $tipo => $config) {
                if ($tipo !== 'galeria_fotos' && $this->vehiculo->{$tipo}) {
                    $imagenService->optimizarImagen($this->vehiculo->{$tipo});
                    $optimizadas++;
                }
            }

            // Optimizar galería
            $galeria = $this->vehiculo->galeria_fotos ?? [];
            foreach ($galeria as $ruta) {
                $imagenService->optimizarImagen($ruta);
                $optimizadas++;
            }

            session()->flash('message', "{$optimizadas} imágenes optimizadas correctamente.");
            $this->cargarImagenes();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al optimizar imágenes: ' . $e->getMessage());
        } finally {
            $this->cargando = false;
        }
    }

    /**
     * Generar reporte de imágenes
     */
    public function generarReporte()
    {
        $reporte = [
            'vehiculo' => $this->vehiculo->placa,
            'total_imagenes' => 0,
            'documentos' => [],
            'galeria_count' => count($this->vehiculo->galeria_fotos ?? []),
            'espacio_usado' => 0,
            'fecha_reporte' => now()->format('Y-m-d H:i:s')
        ];

        foreach ($this->configuracionTipos as $tipo => $config) {
            if ($tipo === 'galeria_fotos') continue;
            
            $tiene_imagen = !empty($this->vehiculo->{$tipo});
            $reporte['documentos'][$tipo] = [
                'nombre' => $config['nombre'] ?? $tipo,
                'tiene_imagen' => $tiene_imagen,
                'requerido' => $config['required'] ?? false,
                'ruta' => $tiene_imagen ? $this->vehiculo->{$tipo} : null
            ];
            
            if ($tiene_imagen) {
                $reporte['total_imagenes']++;
            }
        }

        $reporte['total_imagenes'] += $reporte['galeria_count'];
        
        return $reporte;
    }

    public function render()
    {
        return view('livewire.vehiculo-imagenes', [
            'tiposImagenes' => $this->configuracionTipos,
            'estadisticas' => [
                'total_fotos' => $this->vehiculo->total_fotos,
                'documentos_completos' => $this->vehiculo->hasDocumentosCompletos(),
                'progreso' => $this->getProgresoDocumentos()
            ],
            'cargando' => $this->cargando,
            'errores' => $this->erroresValidacion
        ]);
    }
}
