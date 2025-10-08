<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactoriesSanityTest extends TestCase
{
    use RefreshDatabase;

    public function test_critical_factories_create_successfully()
    {
        // Solo verificar que las factories crean sin exceptions
        $models = [
            \App\Models\User::factory(),
            \App\Models\TipoVehiculo::factory(),
            \App\Models\TipoCombustible::factory(),
            \App\Models\UnidadOrganizacional::factory(),
            \App\Models\UnidadTransporte::factory(),
            \App\Models\RegistroAuditoria::factory(),
        ];

        foreach ($models as $factory) {
            $instance = $factory->create();
            $this->assertNotNull($instance->id ?? $instance->getKey());
        }
    }
}
