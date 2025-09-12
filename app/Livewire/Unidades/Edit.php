<?php

namespace App\Livewire\Unidades;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $show = false;
    public $id_unidad;
    public $codigo_unidad;
    public $nombre_unidad;
    public $tipo_unidad;
    public $nivel_jerarquico;
    public $responsable_unidad;
    public $telefono;
    public $direccion;
    public $presupuesto_asignado;
    public $descripcion;

    protected function rules()
    {
        return [
            'codigo_unidad' => 'required|string|max:20|unique:unidades_organizacionales,codigo_unidad,' . $this->id_unidad . ',id_unidad_organizacional',
            'nombre_unidad' => 'required|string|max:100',
            'tipo_unidad' => 'required|in:Superior,Ejecutiva,Operativa',
            'nivel_jerarquico' => 'nullable|string|max:50',
            'responsable_unidad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'presupuesto_asignado' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    protected $listeners = [
        'openEdit' => 'open',
        'closeEdit' => 'close',
    ];

    // Flags para no marcar edición manual de codigo
    public $codigoManual = false;
    private $suppressCodigoManual = false;

    public function updatedNombreUnidad($value)
    {
        if ($this->codigoManual) {
            return;
        }

        $generated = $this->generateSiglas($value);
        $this->suppressCodigoManual = true;
        $this->codigo_unidad = $generated;
        $this->suppressCodigoManual = false;
    }

    public function updatedCodigoUnidad($value)
    {
        if ($this->suppressCodigoManual) {
            $this->suppressCodigoManual = false;
            return;
        }
        $this->codigoManual = true;
    }

    public function regenerateCodigo()
    {
        $generated = $this->generateSiglas($this->nombre_unidad);
        $this->codigoManual = false;
        $this->suppressCodigoManual = true;
        $this->codigo_unidad = $generated;
        $this->suppressCodigoManual = false;
    }

    private function generateSiglas($name)
    {
        $name = trim((string) $name);
        if ($name === '') {
            return '';
        }

        $stopwords = ['de','del','la','las','los','y','e','el','al','para','por','en','a','the','of','and'];
        $words = preg_split('/\s+/u', $name);
        $sig = '';
        foreach ($words as $w) {
            $clean = preg_replace('/[^\p{L}\p{N}]/u', '', $w);
            if ($clean === '') {
                continue;
            }
            if (in_array(mb_strtolower($clean), $stopwords, true)) {
                continue;
            }
            $sig .= mb_strtoupper(mb_substr($clean, 0, 1));
            if (mb_strlen($sig) >= 6) {
                break;
            }
        }
        if ($sig === '') {
            $cleanAll = preg_replace('/[^\p{L}\p{N}]/u', '', $name);
            $sig = mb_strtoupper(mb_substr($cleanAll, 0, 3));
        }
        return $sig;
    }

    public function mount($id = null)
    {
        if (! Auth::check() || ! Auth::user()->hasPermissionTo('usuarios.gestionar')) {
            abort(403);
        }
        if ($id) {
            $this->fillFromId($id);
        }
    }

    public function open($id)
    {
        $this->resetValidation();
        $this->fillFromId($id);
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }

    protected function fillFromId($id)
    {
        $unidad = DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $id)->first();
        if (! $unidad) {
            session()->flash('error', 'Unidad no encontrada.');
            return;
        }

        $this->id_unidad = $unidad->id_unidad_organizacional;
        $this->codigo_unidad = $unidad->codigo_unidad;
        $this->nombre_unidad = $unidad->nombre_unidad;
        $this->tipo_unidad = $unidad->tipo_unidad;
        $this->nivel_jerarquico = $unidad->nivel_jerarquico;
        $this->responsable_unidad = $unidad->responsable_unidad;
        $this->telefono = $unidad->telefono;
        $this->direccion = $unidad->direccion;
        $this->presupuesto_asignado = $unidad->presupuesto_asignado;
        $this->descripcion = $unidad->descripcion;
    }

    public function save()
    {
        $this->validate();
        // Si el código está vacío, generarlo a partir del nombre
        $codigoToSave = $this->codigo_unidad ?: $this->generateSiglas($this->nombre_unidad);

        DB::table('unidades_organizacionales')->where('id_unidad_organizacional', $this->id_unidad)
            ->update([
                'codigo_unidad' => $codigoToSave,
                'nombre_unidad' => $this->nombre_unidad,
                'tipo_unidad' => $this->tipo_unidad,
                'nivel_jerarquico' => $this->nivel_jerarquico,
                'responsable_unidad' => $this->responsable_unidad,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'presupuesto_asignado' => $this->presupuesto_asignado,
                'descripcion' => $this->descripcion,
                'updated_at' => now(),
            ]);

    session()->flash('success', 'Unidad actualizada.');
    $this->show = false;
    $this->dispatch('unidadUpdated');
    }

    public function render()
    {
        return view('livewire.unidades.edit');
    }
}

