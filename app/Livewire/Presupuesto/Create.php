<?php

namespace App\Livewire\Presupuesto;

use App\Models\Presupuesto;
use App\Models\UnidadOrganizacional;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Create extends Component
{
    #[Rule('required|exists:categoria_programaticas,id')]
    public $id_cat_programatica;
    
    #[Rule('required|exists:fuente_organismo_financieros,id')]
    public $id_fuente_org_fin;
    
    #[Rule('required|exists:unidades_organizacionales,id_unidad_organizacional')]
    public $id_unidad_organizacional;
    
    #[Rule('required|integer|min:2020|max:2050')]
    public $anio_fiscal;
    
    #[Rule('required|integer|min:1|max:4')]
    public $trimestre = 1;
    
    #[Rule('required|numeric|min:0.01')]
    public $presupuesto_inicial;
    
    #[Rule('required|numeric|min:0')]
    public $presupuesto_actual;
    
    #[Rule('nullable|numeric|min:0')]
    public $total_gastado = 0;
    
    #[Rule('nullable|numeric|min:0')]
    public $total_comprometido = 0;
    
    #[Rule('nullable|string|max:100')]
    public $num_documento;
    
    #[Rule('nullable|string|max:100')]
    public $numero_comprobante;
    
    #[Rule('nullable|date')]
    public $fecha_aprobacion;
    
    #[Rule('required|numeric|min:0|max:100')]
    public $porcentaje_preventivo = 80;
    
    #[Rule('required|numeric|min:0|max:100')]
    public $alerta_porcentaje = 85;
    
    #[Rule('boolean')]
    public $activo = true;
    
    #[Rule('nullable|string|max:1000')]
    public $observaciones;

    // Propiedades calculadas
    public $saldo_disponible = 0;
    public $porcentaje_ejecutado = 0;

    public function mount()
    {
        $this->anio_fiscal = date('Y');
        $this->presupuesto_actual = $this->presupuesto_inicial;
        $this->calcularSaldoYPorcentaje();
    }
    
    public function updated($property)
    {
        if (in_array($property, ['presupuesto_inicial', 'presupuesto_actual', 'total_gastado', 'total_comprometido'])) {
            $this->calcularSaldoYPorcentaje();
        }
        
        // Auto-sincronizar presupuesto actual con inicial si no se ha modificado manualmente
        if ($property === 'presupuesto_inicial' && !$this->presupuesto_actual) {
            $this->presupuesto_actual = $this->presupuesto_inicial;
        }
    }
    
    public function calcularSaldoYPorcentaje()
    {
        $presupuestoActual = (float) $this->presupuesto_actual ?: 0;
        $totalGastado = (float) $this->total_gastado ?: 0;
        $totalComprometido = (float) $this->total_comprometido ?: 0;
        $presupuestoInicial = (float) $this->presupuesto_inicial ?: 0;
        
        $this->saldo_disponible = $presupuestoActual - $totalGastado - $totalComprometido;
        
        if ($presupuestoInicial > 0) {
            $this->porcentaje_ejecutado = round(($totalGastado / $presupuestoInicial) * 100, 2);
        } else {
            $this->porcentaje_ejecutado = 0;
        }
    }
    
    public function save()
    {
        $this->validate();
        
        try {
            // Verificar que no existe un presupuesto duplicado
            $existe = Presupuesto::where('id_unidad_organizacional', $this->id_unidad_organizacional)
                ->where('id_cat_programatica', $this->id_cat_programatica)
                ->where('id_fuente_org_fin', $this->id_fuente_org_fin)
                ->where('anio_fiscal', $this->anio_fiscal)
                ->where('trimestre', $this->trimestre)
                ->exists();
                
            if ($existe) {
                session()->flash('error', 'Ya existe un presupuesto para esta combinación de unidad, categoría, fuente, año y trimestre.');
                return;
            }
            
            Presupuesto::create([
                'id_cat_programatica' => $this->id_cat_programatica,
                'id_fuente_org_fin' => $this->id_fuente_org_fin,
                'id_unidad_organizacional' => $this->id_unidad_organizacional,
                'anio_fiscal' => $this->anio_fiscal,
                'trimestre' => $this->trimestre,
                'presupuesto_inicial' => $this->presupuesto_inicial,
                'presupuesto_actual' => $this->presupuesto_actual,
                'total_gastado' => $this->total_gastado,
                'total_comprometido' => $this->total_comprometido,
                'num_documento' => $this->num_documento,
                'numero_comprobante' => $this->numero_comprobante,
                'fecha_aprobacion' => $this->fecha_aprobacion,
                'porcentaje_preventivo' => $this->porcentaje_preventivo,
                'alerta_porcentaje' => $this->alerta_porcentaje,
                'activo' => $this->activo,
                'observaciones' => $this->observaciones,
            ]);
            
            session()->flash('message', 'Presupuesto creado exitosamente.');
            
            return redirect()->route('presupuestos.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el presupuesto: ' . $e->getMessage());
        }
    }
    
    public function cancel()
    {
        return redirect()->route('presupuestos.index');
    }

    public function render()
    {
        $unidades = UnidadOrganizacional::where('activa', true)->orderBy('nombre_unidad')->get();
        $categorias = CategoriaProgramatica::orderBy('descripcion')->get();
        $fuentes = FuenteOrganismoFinanciero::orderBy('descripcion')->get();
        
        return view('livewire.presupuesto.create', [
            'unidades' => $unidades,
            'categorias' => $categorias,
            'fuentes' => $fuentes,
        ]);
    }
}
