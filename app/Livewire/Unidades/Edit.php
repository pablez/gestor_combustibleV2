<?php

namespace App\Livewire\Unidades;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UnidadOrganizacional;

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
            'nombre_unidad' => 'required|string|max:100|unique:unidades_organizacionales,nombre_unidad,' . $this->id_unidad . ',id_unidad_organizacional',
            'tipo_unidad' => 'required|in:Superior,Ejecutiva,Operativa',
            'nivel_jerarquico' => 'nullable|integer|min:1',
            'responsable_unidad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:200',
            'presupuesto_asignado' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
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
        // Avoid aborting during mount to prevent full-page 403 when the component
        // is included on pages where the current user may not have edit rights.
        // Permission checks are performed when attempting to open or save.
        if ($id) {
            $this->fillFromId($id);
        }
    }

    public function open($id)
    {
        // Authorization: only allow opening the edit modal to users with the permission
        $unidad = UnidadOrganizacional::find($id);
        if (! $unidad) {
            session()->flash('error', 'Unidad no encontrada.');
            return;
        }

        if (! Auth::check() || ! \Gate::allows('update', $unidad)) {
            session()->flash('error', 'No tiene permisos para editar unidades organizacionales.');
            return;
        }
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
        $unidad = UnidadOrganizacional::find($id);
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
        \Log::info('Edit::save() iniciado', ['user_id' => Auth::id(), 'id_unidad' => $this->id_unidad]);
        
        if (! Auth::check() ) {
            \Log::warning('Sin sesión al intentar editar', ['user_id' => Auth::id()]);
            session()->flash('error', 'No tiene permisos para guardar cambios en unidades organizacionales.');
            return;
        }

        $unidad = UnidadOrganizacional::find($this->id_unidad);
        if (! $unidad) {
            session()->flash('error', 'Unidad no encontrada.');
            return;
        }

        if (! \Gate::allows('update', $unidad)) {
            \Log::warning('Sin permisos para editar', ['user_id' => Auth::id()]);
            session()->flash('error', 'No tiene permisos para guardar cambios en unidades organizacionales.');
            return;
        }

        \Log::info('Iniciando validación');
        $this->validate();
        \Log::info('Validación exitosa');
        
        try {
            $unidad = UnidadOrganizacional::find($this->id_unidad);
            if (! $unidad) {
                \Log::error('Unidad no encontrada', ['id' => $this->id_unidad]);
                session()->flash('error', 'Unidad no encontrada.');
                return;
            }

            // Si el código está vacío, generarlo a partir del nombre
            $codigoToSave = $this->codigo_unidad ?: $this->generateSiglas($this->nombre_unidad);

            $data = [
                'codigo_unidad' => $codigoToSave,
                'nombre_unidad' => $this->nombre_unidad,
                'tipo_unidad' => $this->tipo_unidad,
                'nivel_jerarquico' => $this->nivel_jerarquico,
                'responsable_unidad' => $this->responsable_unidad,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'presupuesto_asignado' => $this->presupuesto_asignado,
                'descripcion' => $this->descripcion,
            ];
            
            \Log::info('Datos para actualizar', $data);
            
            $unidad->update($data);

            \Log::info('Unidad actualizada exitosamente');
            session()->flash('success', 'Unidad actualizada correctamente.');
            $this->show = false;
            $this->dispatch('unidadUpdated');
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar unidad', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Error al actualizar la unidad: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.unidades.edit');
    }
}

