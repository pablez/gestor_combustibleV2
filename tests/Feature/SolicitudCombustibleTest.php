<?php

use App\Models\User;
use App\Models\SolicitudCombustible;

it('creates a solicitud combustible via model', function () {
    $user = User::factory()->create();

    $sol = SolicitudCombustible::factory()->make([
        'id_usuario_solicitante' => $user->id,
        'numero_solicitud' => 'SOL-TEST-1',
    ]);

    $sol->save();

    expect(SolicitudCombustible::where('numero_solicitud', 'SOL-TEST-1')->exists())->toBeTrue();
});

it('validates required cantidad when creating via model', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);

    $user = User::factory()->create();
    // Omitting cantidad_litros_solicitados should cause a DB error (not ideal but basic check)
    $sol = SolicitudCombustible::factory()->make([
        'id_usuario_solicitante' => $user->id,
        'numero_solicitud' => 'SOL-TEST-2',
        'cantidad_litros_solicitados' => null,
    ]);

    $sol->save();
});

