<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

class UploadImagenesValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_large_file()
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Crear archivo muy grande (6MB)
        $file = \Illuminate\Http\UploadedFile::fake()->image('big.jpg')->size(6144);

        $component = Livewire::test(\App\Livewire\Vehiculo\UploadImagenes::class, ['placa' => 'VAL1'])
            ->set('tipo', 'foto_principal')
            ->set('archivo', $file)
            ->call('subir');

        // Livewire validation should produce an error for archivo (max)
        $component->assertHasErrors(['archivo']);
    }

    public function test_rejects_invalid_format()
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Archivo con formato no permitido (pdf)
        $file = \Illuminate\Http\UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $component = Livewire::test(\App\Livewire\Vehiculo\UploadImagenes::class, ['placa' => 'VAL2'])
            ->set('tipo', 'foto_principal')
            ->set('archivo', $file)
            ->call('subir');

        $component->assertHasErrors(['archivo']);
    }

    public function test_requires_authentication()
    {
        Storage::fake('public');

        // No autenticamos al usuario
        $file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg')->size(100);

        $component = Livewire::test(\App\Livewire\Vehiculo\UploadImagenes::class, ['placa' => 'VAL3'])
            ->set('tipo', 'foto_principal')
            ->set('archivo', $file)
            ->call('subir');

    // Como no hay autenticación, el componente debe agregar un error Livewire en 'auth'
    $component->assertHasErrors(['auth']);
    }
}
