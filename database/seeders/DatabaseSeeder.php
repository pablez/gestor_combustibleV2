<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed roles and permissions and create initial admin
        $this->call(RolesPermissionsSeeder::class);
        // Ensure organizational units exist before creating users that reference them
        $this->call(UnidadOrganizacionalSeeder::class);
        $this->call(AdminUserSeeder::class);
        // Create test users with different roles
        $this->call(UsersTestSeeder::class);
        // Seed vehicle and fuel types before creating transport units
        $this->call(TipoVehiculoSeeder::class);
        $this->call(TipoCombustibleSeeder::class);
        $this->call(UnidadTransporteSeeder::class);
        $this->call(FuenteOrganismoFinancieroSeeder::class);
        $this->call(CategoriaProgramaticaSeeder::class);
        $this->call(SolicitudCombustibleSeeder::class);
    }
}
