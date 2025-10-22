<?php

namespace App\Livewire\Solicitud;

use App\Models\SolicitudCombustible;
use App\Models\UnidadTransporte;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;
use App\Models\Presupuesto;
use App\Models\UnidadOrganizacional;
use App\Models\ConsumoCombustible;
use App\Services\NotificacionSolicitudService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    // Propiedades del formulario
    public $id_unidad_transporte;
    public $cantidad_litros_solicitados;
    public $motivo;
    public $urgente = false;
    public $justificacion_urgencia;
    public $id_cat_programatica;
    public $id_fuente_org_fin;
    public $saldo_actual_combustible;
    public $km_actual;
    public $km_proyectado;
    public $rendimiento_estimado;

    // Control de modal/formulario
    public $mostrarFormulario = false;

    // Propiedades calculadas automáticamente
    public $unidadSeleccionada = null;
    public $categoriaSeleccionada = null;
    public $fuenteSeleccionada = null;
    public $presupuestoInfo = null;
    public $unidadOrganizacionalInfo = null;
    public $capacidadTanque = 0;
    public $rendimientoPromedio = 0;
    public $consumoEstimado = 0;
    public $presupuestoDisponible = 0;
    public $costoEstimado = 0;
    public $alertas = [];

    protected $rules = [
        'id_unidad_transporte' => 'required|exists:unidad_transportes,id',
        'cantidad_litros_solicitados' => 'required|numeric|min:0.01|max:9999.99',
        'motivo' => 'required|string|max:500',
        'urgente' => 'boolean',
        'justificacion_urgencia' => 'nullable|string|max:500',
        'id_cat_programatica' => 'nullable|exists:categoria_programaticas,id',
        'id_fuente_org_fin' => 'nullable|exists:fuente_organismo_financieros,id',
        'saldo_actual_combustible' => 'nullable|numeric|min:0',
        'km_actual' => 'nullable|integer|min:0',
        'km_proyectado' => 'nullable|integer|min:0',
        'rendimiento_estimado' => 'nullable|numeric|min:0.1|max:50',
    ];

    protected $messages = [
        'id_unidad_transporte.required' => 'La unidad de transporte es obligatoria.',
        'id_unidad_transporte.exists' => 'La unidad de transporte seleccionada no es válida.',
        'cantidad_litros_solicitados.required' => 'La cantidad de litros es obligatoria.',
        'cantidad_litros_solicitados.numeric' => 'La cantidad de litros debe ser un número.',
        'cantidad_litros_solicitados.min' => 'La cantidad mínima es 0.01 litros.',
        'cantidad_litros_solicitados.max' => 'La cantidad máxima es 9999.99 litros.',
        'motivo.required' => 'El motivo es obligatorio.',
        'motivo.max' => 'El motivo no puede exceder 500 caracteres.',
        'justificacion_urgencia.max' => 'La justificación no puede exceder 500 caracteres.',
    ];

    public function mount()
    {
        // Inicializar variables
        $this->alertas = [];
        $this->categoriaSeleccionada = null;
        $this->fuenteSeleccionada = null;
        $this->unidadSeleccionada = null;
    }

    // Listeners para cambios en tiempo real
    public function updatedIdUnidadTransporte($value)
    {
        if ($value) {
            $this->cargarDatosUnidad($value);
            $this->validarCapacidadTanque();
        }
    }

    public function updatedCantidadLitrosSolicitados($value)
    {
        if ($value && $this->id_unidad_transporte) {
            $this->validarCapacidadTanque();
            $this->calcularConsumoEstimado();
            $this->validarPresupuesto();
        }
    }

    public function updatedKmProyectado($value)
    {
        if ($value && $this->rendimiento_estimado > 0) {
            $this->calcularConsumoEstimado();
        }
    }

    public function updatedRendimientoEstimado($value)
    {
        if ($value && $this->km_proyectado > 0) {
            $this->calcularConsumoEstimado();
        }
    }

    public function updatedIdCatProgramatica($value)
    {
        if ($value) {
            $this->cargarCategoriaProgramatica($value);
            $this->validarPresupuesto();
        } else {
            $this->categoriaSeleccionada = null;
        }
    }

    public function updatedIdFuenteOrgFin($value)
    {
        if ($value) {
            $this->cargarFuenteFinanciera($value);
        } else {
            $this->fuenteSeleccionada = null;
            $this->presupuestoInfo = null;
            $this->presupuestoDisponible = 0;
        }
    }

    protected function cargarDatosUnidad($unidadId)
    {
        $this->unidadSeleccionada = UnidadTransporte::with([
            'tipoVehiculo', 
            'tipoCombustible',
            'unidadOrganizacional',
            'conductorAsignado'
        ])->find($unidadId);
        
        if ($this->unidadSeleccionada) {
            $this->capacidadTanque = $this->unidadSeleccionada->capacidad_tanque ?? 0;
            $this->km_actual = $this->unidadSeleccionada->kilometraje_actual ?? 0;
            
            // Obtener rendimiento promedio de los últimos 5 consumos
            try {
                $this->rendimientoPromedio = ConsumoCombustible::where('id_unidad_transporte', $unidadId)
                    ->whereNotNull('rendimiento')
                    ->where('rendimiento', '>', 0)
                    ->latest()
                    ->limit(5)
                    ->avg('rendimiento') ?? 0;
            } catch (\Exception $e) {
                $this->rendimientoPromedio = $this->unidadSeleccionada->tipoVehiculo->consumo_promedio_ciudad ?? 0;
            }
            
            if ($this->rendimientoPromedio > 0 && !$this->rendimiento_estimado) {
                $this->rendimiento_estimado = round($this->rendimientoPromedio, 2);
            }

            // Calcular información adicional
            $this->calcularInformacionAdicional();
        }
    }

    protected function calcularInformacionAdicional()
    {
        if (!$this->unidadSeleccionada) return;

        // Verificar estado del vehículo
        $this->verificarEstadoVehiculo();
        
        // Calcular autonomía estimada
        if ($this->capacidadTanque > 0 && $this->rendimientoPromedio > 0) {
            $autonomiaEstimada = $this->capacidadTanque * $this->rendimientoPromedio;
        }
    }

    protected function verificarEstadoVehiculo()
    {
        if (!$this->unidadSeleccionada) return;

        $alertas = [];

        // Verificar estado operativo
        if ($this->unidadSeleccionada->estado_operativo !== 'Operativo') {
            $alertas[] = [
                'tipo' => 'estado_vehiculo',
                'nivel' => 'warning',
                'mensaje' => "El vehículo está en estado: {$this->unidadSeleccionada->estado_operativo}"
            ];
        }

        // Verificar documentos vencidos
        if ($this->unidadSeleccionada->seguro_vigente_hasta && $this->unidadSeleccionada->seguro_vigente_hasta < now()) {
            $alertas[] = [
                'tipo' => 'seguro_vencido',
                'nivel' => 'error',
                'mensaje' => "El seguro del vehículo está vencido desde {$this->unidadSeleccionada->seguro_vigente_hasta->format('d/m/Y')}"
            ];
        }

        if ($this->unidadSeleccionada->revision_tecnica_hasta && $this->unidadSeleccionada->revision_tecnica_hasta < now()) {
            $alertas[] = [
                'tipo' => 'revision_vencida',
                'nivel' => 'error',
                'mensaje' => "La revisión técnica está vencida desde {$this->unidadSeleccionada->revision_tecnica_hasta->format('d/m/Y')}"
            ];
        }

        // Verificar mantenimiento
        if ($this->unidadSeleccionada->proximo_mantenimiento_km && 
            $this->unidadSeleccionada->kilometraje_actual >= $this->unidadSeleccionada->proximo_mantenimiento_km) {
            $alertas[] = [
                'tipo' => 'mantenimiento_requerido',
                'nivel' => 'warning',
                'mensaje' => "El vehículo requiere mantenimiento (actual: {$this->unidadSeleccionada->kilometraje_actual} km)"
            ];
        }

        $this->alertas = array_merge($this->alertas, $alertas);
    }

    protected function cargarCategoriaProgramatica($categoriaId)
    {
        try {
            $this->categoriaSeleccionada = CategoriaProgramatica::find($categoriaId);
            if (!$this->categoriaSeleccionada) {
                // Datos de fallback para desarrollo/pruebas
                $this->categoriaSeleccionada = (object) [
                    'id' => $categoriaId,
                    'codigo' => 'CAT-' . str_pad($categoriaId, 3, '0', STR_PAD_LEFT),
                    'descripcion' => 'Categoría Programática ' . $categoriaId,
                    'tipo_categoria' => 'Operativa',
                    'nivel' => 3,
                    'activo' => true,
                    'fecha_inicio' => now()->startOfYear(),
                    'fecha_fin' => now()->endOfYear(),
                ];
            }
            
            // Cargar información de presupuesto si también tenemos fuente seleccionada
            $this->cargarInformacionPresupuesto();
            
        } catch (\Exception $e) {
            $this->categoriaSeleccionada = (object) [
                'id' => $categoriaId,
                'codigo' => 'CAT-' . str_pad($categoriaId, 3, '0', STR_PAD_LEFT),
                'descripcion' => 'Categoría Programática ' . $categoriaId,
                'tipo_categoria' => 'Operativa',
                'nivel' => 3,
                'activo' => true,
                'fecha_inicio' => null,
                'fecha_fin' => null,
            ];
            Log::warning('Error cargando categoría programática, usando datos de fallback: ' . $e->getMessage());
        }
    }

    protected function cargarFuenteFinanciera($fuenteId)
    {
        try {
            $this->fuenteSeleccionada = FuenteOrganismoFinanciero::find($fuenteId);
            if (!$this->fuenteSeleccionada) {
                // Datos de fallback para desarrollo/pruebas
                $this->fuenteSeleccionada = (object) [
                    'id' => $fuenteId,
                    'codigo' => 'FF-' . str_pad($fuenteId, 3, '0', STR_PAD_LEFT),
                    'descripcion' => 'Fuente de Financiamiento ' . $fuenteId,
                    'activo' => true,
                ];
            }
            
            // Cargar información adicional de presupuesto y unidad organizacional
            $this->cargarInformacionPresupuesto();
            
        } catch (\Exception $e) {
            $this->fuenteSeleccionada = (object) [
                'id' => $fuenteId,
                'codigo' => 'FF-' . str_pad($fuenteId, 3, '0', STR_PAD_LEFT),
                'descripcion' => 'Fuente de Financiamiento ' . $fuenteId,
                'activo' => true,
            ];
            Log::warning('Error cargando fuente financiera, usando datos de fallback: ' . $e->getMessage());
        }
    }
    
    protected function cargarInformacionPresupuesto()
    {
        // Reiniciar información anterior
        $this->presupuestoInfo = null;
        $this->unidadOrganizacionalInfo = null;
        $this->presupuestoDisponible = 0;
        
        // Si tenemos fuente seleccionada, cargar su información presupuestaria
        if ($this->fuenteSeleccionada) {
            $this->cargarPresupuestoPorFuente();
        }
        
        // Si también tenemos categoría, cargar información combinada
        if ($this->fuenteSeleccionada && $this->categoriaSeleccionada) {
            $this->cargarPresupuestoCombinado();
        }
    }
    
    protected function cargarPresupuestoPorFuente()
    {
        try {
            $fuenteId = $this->fuenteSeleccionada->id ?? null;
            
            if (!$fuenteId) return;
            
            // Consulta optimizada con JOIN múltiples según diagrama-optimizado
            $presupuestos = Presupuesto::select([
                'presupuestos.*',
                'unidades_organizacionales.nombre_unidad',
                'unidades_organizacionales.codigo_unidad',
                'unidades_organizacionales.tipo_unidad',
                'unidades_organizacionales.nivel_jerarquico',
                'unidades_organizacionales.responsable_unidad',
                'categoria_programaticas.codigo as categoria_codigo',
                'categoria_programaticas.descripcion as categoria_descripcion',
                'categoria_programaticas.nivel as categoria_nivel',
                'fuente_organismo_financieros.codigo as fuente_codigo',
                'fuente_organismo_financieros.descripcion as fuente_descripcion'
            ])
            ->leftJoin('unidades_organizacionales', 'presupuestos.id_unidad_organizacional', '=', 'unidades_organizacionales.id_unidad_organizacional')
            ->leftJoin('categoria_programaticas', 'presupuestos.id_cat_programatica', '=', 'categoria_programaticas.id')
            ->leftJoin('fuente_organismo_financieros', 'presupuestos.id_fuente_org_fin', '=', 'fuente_organismo_financieros.id')
            ->where('presupuestos.id_fuente_org_fin', $fuenteId)
            ->where('presupuestos.activo', true)
            ->where('presupuestos.anio_fiscal', date('Y'))
            ->get();
            
            if ($presupuestos->isNotEmpty()) {
                // Calcular totales de la fuente
                $totalInicial = $presupuestos->sum('presupuesto_inicial');
                $totalActual = $presupuestos->sum('presupuesto_actual');
                $totalDisponible = $presupuestos->sum('saldo_disponible');
                $totalGastado = $presupuestos->sum('total_gastado');
                $totalComprometido = $presupuestos->sum('total_comprometido');
                
                $this->presupuestoDisponible = $totalDisponible;
                
                // Calcular porcentaje de ejecución y alerta de límite
                $porcentajeEjecutado = $totalInicial > 0 ? round(($totalGastado / $totalInicial) * 100, 2) : 0;
                $alertaPorcentaje = $presupuestos->avg('alerta_porcentaje') ?? 80;
                $estaCercaLimite = $porcentajeEjecutado >= $alertaPorcentaje;
                
                // Crear objeto con información agregada y optimizada según diagrama
                $this->presupuestoInfo = (object) [
                    'presupuesto_inicial' => $totalInicial,
                    'presupuesto_actual' => $totalActual,
                    'saldo_disponible' => $totalDisponible,
                    'total_gastado' => $totalGastado,
                    'total_comprometido' => $totalComprometido,
                    'porcentaje_ejecutado' => $porcentajeEjecutado,
                    'cantidad_presupuestos' => $presupuestos->count(),
                    'categorias' => $presupuestos->pluck('categoria_descripcion')->filter()->unique(),
                    'anio_fiscal' => date('Y'),
                    'fuente_codigo' => $presupuestos->first()->fuente_codigo ?? $this->fuenteSeleccionada->codigo,
                    'fuente_descripcion' => $presupuestos->first()->fuente_descripcion ?? $this->fuenteSeleccionada->descripcion,
                    'unidades' => $presupuestos->pluck('nombre_unidad')->filter()->unique(),
                    'esta_cerca_limite' => $estaCercaLimite,
                    'alerta_porcentaje' => $alertaPorcentaje,
                    'activo' => $presupuestos->first()->activo ?? true,
                    'num_documento' => $presupuestos->first()->num_documento ?? null,
                    'numero_comprobante' => $presupuestos->first()->numero_comprobante ?? null,
                    'fecha_aprobacion' => $presupuestos->first()->fecha_aprobacion,
                    'unidades_organizacionales' => $presupuestos->pluck('nombre_unidad', 'id_unidad_organizacional')->filter()->toArray(),
                ];
            }
            
        } catch (\Exception $e) {
            Log::warning('Error cargando presupuesto por fuente: ' . $e->getMessage());
        }
    }
    
    protected function cargarPresupuestoCombinado()
    {
        try {
            $fuenteId = $this->fuenteSeleccionada->id ?? null;
            $categoriaId = $this->categoriaSeleccionada->id ?? null;
            
            if (!$fuenteId || !$categoriaId) return;
            
            // Consulta optimizada según diagrama-optimizado con JOINs eficientes
            $presupuestoEspecifico = Presupuesto::select([
                'presupuestos.*',
                'unidades_organizacionales.codigo_unidad',
                'unidades_organizacionales.nombre_unidad',
                'unidades_organizacionales.tipo_unidad',
                'unidades_organizacionales.responsable_unidad',
                'unidades_organizacionales.telefono',
                'unidades_organizacionales.direccion',
                'unidades_organizacionales.nivel_jerarquico',
                'unidades_organizacionales.presupuesto_asignado',
                'categoria_programaticas.codigo as categoria_codigo',
                'categoria_programaticas.descripcion as categoria_descripcion',
                'categoria_programaticas.nivel as categoria_nivel',
                'categoria_programaticas.tipo_categoria',
                'fuente_organismo_financieros.codigo as fuente_codigo',
                'fuente_organismo_financieros.descripcion as fuente_descripcion',
                'fuente_organismo_financieros.organismo_financiador'
            ])
            ->leftJoin('unidades_organizacionales', 'presupuestos.id_unidad_organizacional', '=', 'unidades_organizacionales.id_unidad_organizacional')
            ->leftJoin('categoria_programaticas', 'presupuestos.id_cat_programatica', '=', 'categoria_programaticas.id')
            ->leftJoin('fuente_organismo_financieros', 'presupuestos.id_fuente_org_fin', '=', 'fuente_organismo_financieros.id')
            ->where('presupuestos.id_fuente_org_fin', $fuenteId)
            ->where('presupuestos.id_cat_programatica', $categoriaId)
            ->where('presupuestos.activo', true)
            ->where('presupuestos.anio_fiscal', date('Y'))
            ->first();
            
            if ($presupuestoEspecifico) {
                $this->presupuestoInfo = $presupuestoEspecifico;
                $this->presupuestoDisponible = $presupuestoEspecifico->saldo_disponible;
                
                // Información completa de la unidad organizacional según el diagrama-optimizado
                $this->unidadOrganizacionalInfo = (object) [
                    'id_unidad_organizacional' => $presupuestoEspecifico->id_unidad_organizacional,
                    'codigo_unidad' => $presupuestoEspecifico->codigo_unidad,
                    'nombre_unidad' => $presupuestoEspecifico->nombre_unidad,
                    'tipo_unidad' => $presupuestoEspecifico->tipo_unidad,
                    'responsable_unidad' => $presupuestoEspecifico->responsable_unidad,
                    'telefono' => $presupuestoEspecifico->telefono,
                    'direccion' => $presupuestoEspecifico->direccion,
                    'nivel_jerarquico' => $presupuestoEspecifico->nivel_jerarquico,
                    'presupuesto_asignado' => $presupuestoEspecifico->presupuesto_asignado,
                    'descripcion' => $presupuestoEspecifico->descripcion ?? '',
                    // Información de categoría programática y fuente de financiamiento
                    'categoria_codigo' => $presupuestoEspecifico->categoria_codigo,
                    'categoria_descripcion' => $presupuestoEspecifico->categoria_descripcion,
                    'categoria_nivel' => $presupuestoEspecifico->categoria_nivel,
                    'fuente_codigo' => $presupuestoEspecifico->fuente_codigo,
                    'fuente_descripcion' => $presupuestoEspecifico->fuente_descripcion,
                    'organismo_financiero' => $presupuestoEspecifico->organismo_financiador,
                ];
            }
            
        } catch (\Exception $e) {
            Log::warning('Error cargando presupuesto combinado: ' . $e->getMessage());
        }
    }

    protected function validarCapacidadTanque()
    {
        $this->alertas = array_filter($this->alertas, function($alerta) {
            return $alerta['tipo'] !== 'capacidad';
        });

        if ($this->cantidad_litros_solicitados && $this->capacidadTanque > 0) {
            if ($this->cantidad_litros_solicitados > $this->capacidadTanque) {
                $this->alertas[] = [
                    'tipo' => 'capacidad',
                    'nivel' => 'error',
                    'mensaje' => "La cantidad solicitada ({$this->cantidad_litros_solicitados}L) excede la capacidad del tanque ({$this->capacidadTanque}L)"
                ];
            } elseif ($this->cantidad_litros_solicitados > ($this->capacidadTanque * 0.9)) {
                $this->alertas[] = [
                    'tipo' => 'capacidad',
                    'nivel' => 'warning',
                    'mensaje' => "La cantidad solicitada está cerca del límite de capacidad del tanque"
                ];
            }
        }
    }

    protected function calcularConsumoEstimado()
    {
        if ($this->km_proyectado > 0 && $this->rendimiento_estimado > 0) {
            $this->consumoEstimado = round($this->km_proyectado / $this->rendimiento_estimado, 2);
            
            // Alerta si la cantidad solicitada difiere mucho del consumo estimado
            if ($this->cantidad_litros_solicitados > 0) {
                $diferencia = abs($this->cantidad_litros_solicitados - $this->consumoEstimado);
                $porcentajeDiferencia = ($diferencia / $this->consumoEstimado) * 100;
                
                $this->alertas = array_filter($this->alertas, function($alerta) {
                    return $alerta['tipo'] !== 'consumo';
                });
                
                if ($porcentajeDiferencia > 30) {
                    $this->alertas[] = [
                        'tipo' => 'consumo',
                        'nivel' => 'warning',
                        'mensaje' => "La cantidad solicitada ({$this->cantidad_litros_solicitados}L) difiere significativamente del consumo estimado ({$this->consumoEstimado}L)"
                    ];
                }
            }
        }
    }

    protected function validarPresupuesto()
    {
        $this->alertas = array_filter($this->alertas, function($alerta) {
            return $alerta['tipo'] !== 'presupuesto';
        });

        if ($this->id_cat_programatica && $this->cantidad_litros_solicitados > 0) {
            // Usar JOIN para obtener información completa del presupuesto con fuente y categoría
            $presupuestoCompleto = Presupuesto::select([
                'presupuestos.*',
                'unidades_organizacionales.nombre_unidad',
                'unidades_organizacionales.codigo_unidad',
                'categoria_programaticas.codigo as categoria_codigo',
                'categoria_programaticas.descripcion as categoria_descripcion',
                'fuente_organismo_financieros.codigo as fuente_codigo',
                'fuente_organismo_financieros.descripcion as fuente_descripcion',
                'fuente_organismo_financieros.tipo_fuente',
                'fuente_organismo_financieros.organismo_financiador'
            ])
            ->leftJoin('unidades_organizacionales', 'presupuestos.id_unidad_organizacional', '=', 'unidades_organizacionales.id_unidad_organizacional')
            ->leftJoin('categoria_programaticas', 'presupuestos.id_cat_programatica', '=', 'categoria_programaticas.id')
            ->leftJoin('fuente_organismo_financieros', 'presupuestos.id_fuente_org_fin', '=', 'fuente_organismo_financieros.id')
            ->where('presupuestos.id_cat_programatica', $this->id_cat_programatica)
            ->where('presupuestos.anio_fiscal', date('Y'))
            ->where('presupuestos.activo', true);
            
            // Si también tenemos fuente seleccionada, filtrar por ella
            if ($this->id_fuente_org_fin) {
                $presupuestoCompleto = $presupuestoCompleto->where('presupuestos.id_fuente_org_fin', $this->id_fuente_org_fin);
            }
            
            $presupuesto = $presupuestoCompleto->first();
            
            if ($presupuesto) {
                $this->presupuestoDisponible = $presupuesto->saldo_disponible;
                
                // Estimar costo usando precio por tipo de combustible si está disponible
                $precioPorLitro = $this->obtenerPrecioCombustible();
                $this->costoEstimado = $this->cantidad_litros_solicitados * $precioPorLitro;
                
                // Validaciones con información detallada
                if ($this->costoEstimado > $this->presupuestoDisponible) {
                    $this->alertas[] = [
                        'tipo' => 'presupuesto',
                        'nivel' => 'error',
                        'mensaje' => "El costo estimado (Bs. " . number_format($this->costoEstimado, 2) . ") excede el presupuesto disponible (Bs. " . number_format($this->presupuestoDisponible, 2) . ") " .
                                   "para " . ($presupuesto->categoria_descripcion ?? 'la categoría seleccionada') . 
                                   " - " . ($presupuesto->nombre_unidad ?? 'Unidad no especificada')
                    ];
                } elseif ($this->costoEstimado > ($this->presupuestoDisponible * 0.8)) {
                    $porcentajeUso = round(($this->costoEstimado / $this->presupuestoDisponible) * 100, 1);
                    $this->alertas[] = [
                        'tipo' => 'presupuesto',
                        'nivel' => 'warning',
                        'mensaje' => "El costo estimado usará el {$porcentajeUso}% del presupuesto disponible " .
                                   "para " . ($presupuesto->categoria_descripcion ?? 'la categoría seleccionada') .
                                   " (" . ($presupuesto->fuente_descripcion ?? 'fuente no especificada') . ")"
                    ];
                }
                
                // Alerta adicional si el presupuesto está cerca del límite
                if ($presupuesto->esta_cerca_limite ?? false) {
                    $this->alertas[] = [
                        'tipo' => 'presupuesto',
                        'nivel' => 'warning',
                        'mensaje' => "El presupuesto de " . ($presupuesto->categoria_descripcion ?? 'esta categoría') . 
                                   " está cerca del límite de ejecución (" . ($presupuesto->alerta_porcentaje ?? 80) . "%)"
                    ];
                }
                
            } else {
                // No se encontró presupuesto para la combinación
                $this->alertas[] = [
                    'tipo' => 'presupuesto',
                    'nivel' => 'warning',
                    'mensaje' => "No se encontró presupuesto activo para la categoría y fuente seleccionadas en el año " . date('Y')
                ];
            }
        }
    }
    
    protected function obtenerPrecioCombustible()
    {
        // Intentar obtener precio del tipo de combustible del vehículo seleccionado
        if ($this->unidadSeleccionada && isset($this->unidadSeleccionada->tipoCombustible)) {
            return $this->unidadSeleccionada->tipoCombustible->precio_referencial ?? 3.74;
        }
        
        // Precio por defecto
        return 3.74;
    }

    public function toggleFormulario()
    {
        $this->mostrarFormulario = !$this->mostrarFormulario;
        
        if (!$this->mostrarFormulario) {
            $this->limpiarFormulario();
        }
    }

    public function limpiarFormulario()
    {
        $this->reset([
            'id_unidad_transporte',
            'cantidad_litros_solicitados',
            'motivo',
            'urgente',
            'justificacion_urgencia',
            'id_cat_programatica',
            'id_fuente_org_fin',
            'saldo_actual_combustible',
            'km_actual',
            'km_proyectado',
            'rendimiento_estimado',
            'unidadSeleccionada',
            'categoriaSeleccionada',
            'fuenteSeleccionada',
            'capacidadTanque',
            'rendimientoPromedio',
            'consumoEstimado',
            'presupuestoDisponible',
            'costoEstimado',
            'alertas'
        ]);
        $this->resetValidation();
    }

    public function crear()
    {
        // Validar que no haya errores críticos
        $erroresCriticos = array_filter($this->alertas, function($alerta) {
            return $alerta['nivel'] === 'error';
        });

        if (!empty($erroresCriticos)) {
            session()->flash('error', 'No se puede crear la solicitud. Hay errores que deben ser corregidos.');
            return;
        }

        $this->validate();

        try {
            // Generar número de solicitud único
            $numeroSolicitud = 'SOL-' . rand(10000, 99999);
            while (SolicitudCombustible::where('numero_solicitud', $numeroSolicitud)->exists()) {
                $numeroSolicitud = 'SOL-' . rand(10000, 99999);
            }

            // Crear la solicitud
            $solicitud = SolicitudCombustible::create([
                'numero_solicitud' => $numeroSolicitud,
                'id_usuario_solicitante' => auth()->id(),
                'id_unidad_transporte' => $this->id_unidad_transporte,
                'fecha_solicitud' => now(),
                'cantidad_litros_solicitados' => $this->cantidad_litros_solicitados,
                'motivo' => $this->motivo,
                'urgente' => $this->urgente,
                'justificacion_urgencia' => $this->justificacion_urgencia,
                'estado_solicitud' => 'Pendiente',
                'id_cat_programatica' => $this->id_cat_programatica,
                'id_fuente_org_fin' => $this->id_fuente_org_fin,
                'saldo_actual_combustible' => $this->saldo_actual_combustible,
                'km_actual' => $this->km_actual,
                'km_proyectado' => $this->km_proyectado,
                'rendimiento_estimado' => $this->rendimiento_estimado,
            ]);

            // Enviar notificaciones
            $notificacionService = app(NotificacionSolicitudService::class);
            
            if ($this->urgente) {
                $notificacionService->notificarSolicitudUrgente($solicitud);
            } else {
                $notificacionService->notificarNuevaSolicitud($solicitud);
            }

            session()->flash('success', 'Solicitud creada exitosamente con número: ' . $numeroSolicitud . 
                ($this->urgente ? ' (URGENTE - Supervisores notificados)' : ''));
            
            $this->limpiarFormulario();
            $this->mostrarFormulario = false;
            
            // Refrescar la lista de solicitudes del componente padre
            $this->dispatch('solicitudCreada');
            
            // Emitir evento global para actualizar notificaciones
            $this->dispatch('solicitudCreada')->to('components.notification-bell');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    public function render()
    {
        try {
            // Obtener unidades de transporte con información completa mediante JOINs
            $unidadesTransporte = UnidadTransporte::select([
                'unidad_transportes.*',
                'tipo_vehiculos.nombre as tipo_vehiculo_nombre',
                'tipo_vehiculos.categoria as tipo_vehiculo_categoria',
                'tipo_vehiculos.consumo_promedio_ciudad',
                'tipo_vehiculos.consumo_promedio_carretera',
                'tipo_combustibles.nombre as tipo_combustible_nombre',
                'tipo_combustibles.precio_referencial',
                'unidades_organizacionales.nombre_unidad',
                'unidades_organizacionales.codigo_unidad as unidad_codigo',
                'usuarios.nombre as conductor_nombre',
                'usuarios.apellido_paterno as conductor_apellido'
            ])
            ->join('tipo_vehiculos', 'unidad_transportes.id_tipo_vehiculo', '=', 'tipo_vehiculos.id_tipo_vehiculo')
            ->join('tipo_combustibles', 'unidad_transportes.id_tipo_combustible', '=', 'tipo_combustibles.id_tipo_combustible')
            ->join('unidades_organizacionales', 'unidad_transportes.id_unidad_organizacional', '=', 'unidades_organizacionales.id_unidad_organizacional')
            ->leftJoin('usuarios', 'unidad_transportes.id_conductor_asignado', '=', 'usuarios.id_usuario')
            ->where('unidad_transportes.estado_operativo', 'Operativo')
            ->where('unidad_transportes.activo', true)
            ->where('tipo_vehiculos.activo', true)
            ->where('tipo_combustibles.activo', true)
            ->where('unidades_organizacionales.activa', true)
            ->orderBy('unidad_transportes.placa')
            ->get()
            ->map(function ($unidad) {
                // Agregar campos calculados para compatibilidad
                $unidad->tipoVehiculo = (object) [
                    'nombre' => $unidad->tipo_vehiculo_nombre,
                    'categoria' => $unidad->tipo_vehiculo_categoria,
                    'consumo_promedio_ciudad' => $unidad->consumo_promedio_ciudad,
                    'consumo_promedio_carretera' => $unidad->consumo_promedio_carretera
                ];
                
                $unidad->tipoCombustible = (object) [
                    'nombre' => $unidad->tipo_combustible_nombre,
                    'precio_referencial' => $unidad->precio_referencial
                ];
                
                $unidad->unidadOrganizacional = (object) [
                    'nombre_unidad' => $unidad->nombre_unidad,
                    'codigo_unidad' => $unidad->unidad_codigo
                ];
                
                $unidad->conductorAsignado = $unidad->conductor_nombre ? (object) [
                    'full_name' => $unidad->conductor_nombre . ' ' . $unidad->conductor_apellido
                ] : null;
                
                return $unidad;
            });

            // Obtener categorías programáticas con información de presupuestos
            $categoriasProgramaticas = CategoriaProgramatica::select([
                'categoria_programaticas.*',
                \DB::raw('COUNT(presupuestos.id_presupuesto) as total_presupuestos'),
                \DB::raw('SUM(presupuestos.presupuesto_actual) as presupuesto_total'),
                \DB::raw('SUM(presupuestos.saldo_disponible) as saldo_total')
            ])
            ->leftJoin('presupuestos', function($join) {
                $join->on('categoria_programaticas.id', '=', 'presupuestos.id_cat_programatica')
                     ->where('presupuestos.activo', true)
                     ->where('presupuestos.anio_fiscal', date('Y'));
            })
            ->where('categoria_programaticas.activo', true)
            ->groupBy('categoria_programaticas.id')
            ->orderBy('categoria_programaticas.codigo')
            ->get();
                
            // Obtener fuentes de financiamiento con información de presupuestos
            $fuentesOrganismo = FuenteOrganismoFinanciero::select([
                'fuente_organismo_financieros.*',
                \DB::raw('COUNT(presupuestos.id_presupuesto) as total_presupuestos'),
                \DB::raw('SUM(presupuestos.presupuesto_actual) as presupuesto_total'),
                \DB::raw('SUM(presupuestos.saldo_disponible) as saldo_total')
            ])
            ->leftJoin('presupuestos', function($join) {
                $join->on('fuente_organismo_financieros.id', '=', 'presupuestos.id_fuente_org_fin')
                     ->where('presupuestos.activo', true)
                     ->where('presupuestos.anio_fiscal', date('Y'));
            })
            ->where('fuente_organismo_financieros.activo', true)
            ->groupBy('fuente_organismo_financieros.id')
            ->orderBy('fuente_organismo_financieros.codigo')
            ->get();
            
        } catch (\Exception $e) {
            // Fallback data para desarrollo cuando no hay conexión DB
            $unidadesTransporte = collect([
                (object) [
                    'id' => 1, 
                    'placa' => 'ABC-123', 
                    'marca' => 'Toyota', 
                    'modelo' => 'Hilux', 
                    'anio' => 2020,
                    'capacidad_tanque' => 80,
                    'kilometraje_actual' => 125000,
                    'estado_operativo' => 'Operativo',
                    'tipoVehiculo' => (object) ['nombre' => 'Camioneta', 'categoria' => 'Transporte'],
                    'tipoCombustible' => (object) ['nombre' => 'Diésel', 'precio_referencial' => 3.74],
                    'unidadOrganizacional' => (object) ['nombre_unidad' => 'Administración Central'],
                    'conductorAsignado' => (object) ['full_name' => 'Juan Pérez']
                ],
            ]);
            
            $categoriasProgramaticas = collect([
                (object) ['id' => 1, 'codigo' => 'CAT-001', 'descripcion' => 'Administración General', 'activo' => true, 'total_presupuestos' => 0],
            ]);
            
            $fuentesOrganismo = collect([
                (object) ['id' => 1, 'codigo' => 'FF-001', 'descripcion' => 'Recursos Propios', 'total_presupuestos' => 0],
            ]);
            
            Log::warning('Usando datos de fallback debido a error de conexión: ' . $e->getMessage());
        }

        return view('livewire.solicitud.create', [
            'unidadesTransporte' => $unidadesTransporte,
            'categoriasProgramaticas' => $categoriasProgramaticas,
            'fuentesOrganismo' => $fuentesOrganismo,
            'presupuestoInfo' => $this->presupuestoInfo,
            'unidadOrganizacionalInfo' => $this->unidadOrganizacionalInfo,
        ]);
    }
}
