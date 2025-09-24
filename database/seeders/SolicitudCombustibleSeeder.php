<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SolicitudCombustible;

class SolicitudCombustibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        for ($i = 0; $i < 10; $i++) {
            $sol = SolicitudCombustible::factory()->make();
            $sol->id_usuario_solicitante = $users->isNotEmpty() ? $users->random()->id : null;
            $sol->save();
        }
    }
}
