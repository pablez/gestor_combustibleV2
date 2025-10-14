<?php

use Illuminate\Support\Facades\Storage;
use App\Models\User;

it('genera_preview_html_de_dashboard_como_usuario_autenticado', function () {
    // Crear o usar un usuario de prueba
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    // Asegurar que la vista cargó (obtener código de estado para diagnóstico)
    $status = $response->getStatusCode();

    // Guardar contenido HTML para preview (si hay contenido)
    $content = $response->getContent() ?: '<!-- Sin contenido -->';
    Storage::disk('public')->put('preview_dashboard.html', $content);

    // Guardar el código de estado para inspección fuera del test
    Storage::disk('public')->put('preview_dashboard_status.txt', (string) $status);
    // Dejar que el test falle visiblemente si no es 200/302
    expect(in_array($status, [200, 302]))->toBeTrue();
});
