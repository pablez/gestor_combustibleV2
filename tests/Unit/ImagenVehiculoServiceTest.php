<?php

use App\Services\ImagenVehiculoService;
use Tests\TestCase;

uses(TestCase::class);
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Asegurar disco fake
    Storage::fake('public');
});

it('optimiza una imagen existente y devuelve true', function () {
    $service = new ImagenVehiculoService();

    // Crear una imagen pequeña en memoria y guardarla en el disco fake
    $img = imagecreatetruecolor(800, 600);
    $bg = imagecolorallocate($img, 255, 0, 0);
    imagefill($img, 0, 0, $bg);

    ob_start();
    imagejpeg($img, null, 90);
    $contents = ob_get_clean();
    imagedestroy($img);

    $ruta = 'vehiculos/TEST/galeria/test_image.jpg';
    Storage::disk('public')->put($ruta, $contents);

    $result = $service->optimizarImagen($ruta);

    expect($result)->toBeTrue();
    expect(Storage::disk('public')->exists($ruta))->toBeTrue();

    // El archivo optimizado debería existir y tener tamaño > 0
    $size = Storage::disk('public')->size($ruta);
    expect($size)->toBeGreaterThan(0);
});