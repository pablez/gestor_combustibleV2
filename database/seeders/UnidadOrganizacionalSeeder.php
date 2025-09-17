<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UnidadOrganizacionalSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'SECRETARÍA DEPARTAMENTAL DE PLANIFICACIÓN Y DESARROLLO ESTRATÉGICO',
            'SECRETARÍA DEPARTAMENTAL DE FINANZAS Y ADMINISTRACIÓN',
            'SECRETARÍA DEPARTAMENTAL DE DESARROLLO HUMANO, CULTURAS Y TURISMO',
            'SECRETARÍA DEPARTAMENTAL DE SALUD',
            'SECRETARÍA DEPARTAMENTAL GENERAL Y GOBERNABILIDAD',
            'SECRETARÍA DEPARTAMENTAL DE DESARROLLO PRODUCTIVO Y TRANSFORMACIÓN',
            'SECRETARÍA DEPARTAMENTAL DE MINERÍA E HIDROCARBUROS',
            'SECRETARÍA DEPARTAMENTAL DE MEDIO AMBIENTE Y RECURSOS HÍDRICOS',
            'SECRETARÍA DEPARTAMENTAL DE OBRAS PÚBLICAS Y SERVICIOS',
            'DESPACHO DE LA GOBERNACIÓN',
        ];

    // Remove existing entries with these exact names to allow reseeding/updating
    DB::table('unidades_organizacionales')->whereIn('nombre_unidad', $names)->delete();

    foreach ($names as $i => $fullname) {
            $siglas = $this->generateSiglas($fullname);

            // Default: 'Ejecutiva' (ejecutivo menor) with nivel_jerarquico = 2
            $tipo = 'Ejecutiva';
            $nivel = 2;

            // DESPACHO DE LA GOBERNACIÓN is superior
            if (mb_stripos($fullname, 'DESPACHO DE LA GOBERNACIÓN') !== false) {
                $tipo = 'Superior';
                $nivel = 1;
            }

            // Prepare codigo_unidad as siglas; if collision, append incremental suffix
            $baseCode = mb_substr($siglas, 0, 8);
            $code = $baseCode;
            $suffix = 1;
            while (DB::table('unidades_organizacionales')->where('codigo_unidad', $code)->exists()) {
                $suffix++;
                $code = $baseCode . '-' . $suffix;
            }

            DB::table('unidades_organizacionales')->insert([
                'codigo_unidad' => $code,
                'nombre_unidad' => $fullname,
                'tipo_unidad' => $tipo,
                'id_unidad_padre' => null,
                'nivel_jerarquico' => $nivel,
                'responsable_unidad' => null,
                'telefono' => null,
                'direccion' => null,
                'presupuesto_asignado' => 0,
                'descripcion' => null,
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateSiglas(string $name): string
    {
        $stopwords = ['de','del','la','las','los','y','e','el','al','para','por','en','a','the','of','and','departamental'];
        $words = preg_split('/\s+/u', $name);
        $sig = '';
        foreach ($words as $w) {
            $clean = preg_replace('/[^\p{L}\p{N}]/u', '', $w);
            if ($clean === '') continue;
            if (in_array(mb_strtolower($clean), $stopwords, true)) continue;
            $sig .= mb_strtoupper(mb_substr($clean, 0, 1));
            if (mb_strlen($sig) >= 8) break;
        }
        return $sig ?: strtoupper(mb_substr(preg_replace('/[^\p{L}\p{N}]/u', '', $name), 0, 6));
    }
}
