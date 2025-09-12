<?php

namespace App\Livewire\Unidades;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    public $show = false;
    public $codigo_unidad;
    public $nombre_unidad;
    public $tipo_unidad = 'Operativa';
    public $nivel_jerarquico = 1;
    public $responsable_unidad;
    public $telefono;
    public $direccion;
    public $presupuesto_asignado = 0;
    public $descripcion;

    protected $rules = [
        'codigo_unidad' => 'required|string|max:20|unique:unidades_organizacionales,codigo_unidad',
        'nombre_unidad' => 'required|string|max:100|unique:unidades_organizacionales,nombre_unidad',
        'tipo_unidad' => 'required|in:Superior,Ejecutiva,Operativa',
        'nivel_jerarquico' => 'nullable|integer|min:1',
    ];

    public function mount()
    {
        if (! Auth::check() || ! Auth::user()->hasPermissionTo('usuarios.gestionar')) {
            abort(403);
        }
    }

    protected $listeners = [
        'openCreate' => 'open',
        'closeCreate' => 'close',
    ];

    // Flags to avoid overwriting manual edits to codigo_unidad
    public $codigoManual = false;
    private $suppressCodigoManual = false;

    // Livewire lifecycle: when nombre_unidad is updated, regenerate codigo_unidad (si no fue editado manualmente)
    public function updatedNombreUnidad($value)
    {
        if ($this->codigoManual) {
            return;
        }

        $generated = $this->generateSiglas($value);
        $this->suppressCodigoManual = true; // evitar marcar como editado al asignar programáticamente
        $this->codigo_unidad = $generated;
        $this->suppressCodigoManual = false;
    }

    public function updatedCodigoUnidad($value)
    {
        if ($this->suppressCodigoManual) {
            // fue asignado por el componente, no marcar como edición manual
            $this->suppressCodigoManual = false;
            return;
        }

        $this->codigoManual = true;
    }

    /**
     * Regenerar el código (siglas) a partir del nombre y forzar su asignación
     */
    public function regenerateCodigo()
    {
        $generated = $this->generateSiglas($this->nombre_unidad);
        $this->codigoManual = false;
        $this->suppressCodigoManual = true;
        $this->codigo_unidad = $generated;
        $this->suppressCodigoManual = false;
    }

    /**
     * Genera siglas a partir de un nombre completo de unidad.
     * - Ignora palabras comunes (de, del, la, y, etc.)
     * - Toma la primera letra de las palabras significativas
     * - Limita la longitud a 6 caracteres (configurable aquí)
     */
    private function generateSiglas($name)
    {
        $name = trim((string) $name);
        if ($name === '') {
            return '';
        }

        // Palabras que no aportan siglas en español
        $stopwords = ['de','del','la','las','los','y','e','el','al','para','por','en','a','the','of','and'];

        // Split by whitespace (unicode-safe)
        $words = preg_split('/\s+/u', $name);
        $sig = '';
        foreach ($words as $w) {
            // Keep letters and numbers (unicode)
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

        // Fallback: si no quedó nada útil, tomar primeras letras del nombre limpio
        if ($sig === '') {
            $cleanAll = preg_replace('/[^\p{L}\p{N}]/u', '', $name);
            $sig = mb_strtoupper(mb_substr($cleanAll, 0, 3));
        }

        return $sig;
    }

    public function open()
    {
        $this->resetValidation();
        $this->reset(['codigo_unidad','nombre_unidad','tipo_unidad','nivel_jerarquico','responsable_unidad','telefono','direccion','presupuesto_asignado','descripcion']);
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }

    public function save()
    {
        $this->validate();
    // Ensure codigo_unidad exists before saving (fallback generation)
    $codigoToSave = $this->codigo_unidad ?: $this->generateSiglas($this->nombre_unidad);

    $id = DB::table('unidades_organizacionales')->insertGetId([
            'codigo_unidad' => $this->codigo_unidad,
            'nombre_unidad' => $this->nombre_unidad,
            'tipo_unidad' => $this->tipo_unidad,
            'nivel_jerarquico' => $this->nivel_jerarquico ?? 1,
            'responsable_unidad' => $this->responsable_unidad,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'presupuesto_asignado' => $this->presupuesto_asignado ?? 0,
            'descripcion' => $this->descripcion,
            'activa' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    session()->flash('success', 'Unidad creada correctamente.');
    $this->show = false;
    $this->dispatch('unidadSaved');
    }

    public function render()
    {
        return view('livewire.unidades.create');
    }
}

