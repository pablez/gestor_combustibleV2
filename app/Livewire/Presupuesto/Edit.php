<?php

namespace App\Livewire\Presupuesto;

use App\Models\Presupuesto;
use App\Models\UnidadOrganizacional;
use App\Models\CategoriaProgramatica;
use App\Models\FuenteOrganismoFinanciero;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    public Presupuesto $presupuesto;

    #[Validate('required|integer|min:2020|max:2050')]
    public $anio_fiscal;

    #[Validate('required|integer|min:1|max:4')]
    public $trimestre;

    #[Validate('required|exists:unidades_organizacionales,id_unidad_organizacional')]
    public $id_unidad_organizacional;

    #[Validate('required|exists:categoria_programaticas,id')]
    public $id_cat_programatica;

    #[Validate('required|exists:fuente_organismo_financieros,id')]
    public $id_fuente_org_fin;

    #[Validate('required|numeric|min:0.01')]
    public $presupuesto_inicial;

    #[Validate('required|numeric|min:0.01')]
    public $presupuesto_actual;

    #[Validate('nullable|numeric|min:0')]
    public $total_gastado = 0;

    #[Validate('nullable|numeric|min:0')]
    public $total_comprometido = 0;

    #[Validate('required|integer|min:50|max:100')]
    public $porcentaje_preventivo = 80;

    #[Validate('required|integer|min:50|max:100')]
    public $alerta_porcentaje = 90;

    #[Validate('nullable|string|max:20')]
    public $num_documento;

    #[Validate('nullable|string|max:20')]
    public $numero_comprobante;

    #[Validate('nullable|date')]
    public $fecha_aprobacion;

    #[Validate('nullable|string|max:500')]
    public $observaciones;

    #[Validate('boolean')]
    public $activo = true;

    // Propiedades calculadas para mostrar al usuario
    public $saldo_disponible;
    public $porcentaje_ejecutado;

    public function mount(Presupuesto $presupuesto)
    {
        $this->presupuesto = $presupuesto;
        
        // Cargar los datos del presupuesto
        $this->anio_fiscal = $presupuesto->anio_fiscal;
        $this->trimestre = $presupuesto->trimestre;
        $this->id_unidad_organizacional = $presupuesto->id_unidad_organizacional;
        $this->id_cat_programatica = $presupuesto->id_cat_programatica;
        $this->id_fuente_org_fin = $presupuesto->id_fuente_org_fin;
        $this->presupuesto_inicial = $presupuesto->presupuesto_inicial;
        $this->presupuesto_actual = $presupuesto->presupuesto_actual;
        $this->total_gastado = $presupuesto->total_gastado;
        $this->total_comprometido = $presupuesto->total_comprometido;
        $this->porcentaje_preventivo = $presupuesto->porcentaje_preventivo;
        $this->alerta_porcentaje = $presupuesto->alerta_porcentaje;
        $this->num_documento = $presupuesto->num_documento;
        $this->numero_comprobante = $presupuesto->numero_comprobante;
        $this->fecha_aprobacion = $presupuesto->fecha_aprobacion?->format('Y-m-d');
        $this->observaciones = $presupuesto->observaciones;
        $this->activo = $presupuesto->activo;

        $this->calcularDatosFinancieros();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['presupuesto_actual', 'total_gastado', 'total_comprometido'])) {
            $this->calcularDatosFinancieros();
        }
    }

    public function calcularDatosFinancieros()
    {
        $presupuesto_actual = (float) $this->presupuesto_actual;
        $total_gastado = (float) $this->total_gastado;
        $total_comprometido = (float) $this->total_comprometido;

        $this->saldo_disponible = $presupuesto_actual - $total_gastado - $total_comprometido;

        if ($presupuesto_actual > 0) {
            $this->porcentaje_ejecutado = round((($total_gastado + $total_comprometido) / $presupuesto_actual) * 100, 1);
        } else {
            $this->porcentaje_ejecutado = 0;
        }
    }

    protected function getUniqueValidationRule()
    {
        // Excluir el registro actual de la validación de duplicados
        return 'unique:presupuesto,anio_fiscal,' . $this->presupuesto->id . ',id,trimestre,' . $this->trimestre . 
               ',id_unidad_organizacional,' . $this->id_unidad_organizacional;
    }

    public function save()
    {
        // Validación personalizada para evitar duplicados (excluyendo el registro actual)
        $existingPresupuesto = Presupuesto::where('anio_fiscal', $this->anio_fiscal)
            ->where('trimestre', $this->trimestre)
            ->where('id_unidad_organizacional', $this->id_unidad_organizacional)
            ->where('id', '!=', $this->presupuesto->id)
            ->first();

        if ($existingPresupuesto) {
            $this->addError('anio_fiscal', 'Ya existe un presupuesto para esta unidad organizacional en el año ' . $this->anio_fiscal . ' trimestre ' . $this->trimestre);
            return;
        }

        // Validación básica
        $this->validate();

        // Validaciones adicionales
        if ($this->presupuesto_actual < ($this->total_gastado + $this->total_comprometido)) {
            $this->addError('presupuesto_actual', 'El presupuesto actual no puede ser menor a la suma del total gastado y comprometido.');
            return;
        }

        if ($this->porcentaje_preventivo >= $this->alerta_porcentaje) {
            $this->addError('porcentaje_preventivo', 'El porcentaje preventivo debe ser menor al porcentaje de alerta.');
            return;
        }

        try {
            $this->presupuesto->update([
                'anio_fiscal' => $this->anio_fiscal,
                'trimestre' => $this->trimestre,
                'id_unidad_organizacional' => $this->id_unidad_organizacional,
                'id_cat_programatica' => $this->id_cat_programatica,
                'id_fuente_org_fin' => $this->id_fuente_org_fin,
                'presupuesto_inicial' => $this->presupuesto_inicial,
                'presupuesto_actual' => $this->presupuesto_actual,
                'total_gastado' => $this->total_gastado,
                'total_comprometido' => $this->total_comprometido,
                'porcentaje_preventivo' => $this->porcentaje_preventivo,
                'alerta_porcentaje' => $this->alerta_porcentaje,
                'num_documento' => $this->num_documento,
                'numero_comprobante' => $this->numero_comprobante,
                'fecha_aprobacion' => $this->fecha_aprobacion,
                'observaciones' => $this->observaciones,
                'activo' => $this->activo,
            ]);

            session()->flash('message', 'Presupuesto actualizado exitosamente.');
            return redirect()->route('presupuestos.show', $this->presupuesto);

        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el presupuesto: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $unidadesOrganizacionales = UnidadOrganizacional::where('activa', true)
            ->orderBy('nombre_unidad')
            ->get();

        $categoriasProgramaticas = CategoriaProgramatica::where('activo', true)
            ->orderBy('descripcion')
            ->get();

        $fuentesFinanciamiento = FuenteOrganismoFinanciero::where('activo', true)
            ->orderBy('descripcion')
            ->get();

        return view('livewire.presupuesto.edit', [
            'unidadesOrganizacionales' => $unidadesOrganizacionales,
            'categoriasProgramaticas' => $categoriasProgramaticas,
            'fuentesFinanciamiento' => $fuentesFinanciamiento,
        ]);
    }
}
