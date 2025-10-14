<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class UploadCancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancel_upload_resets_file()
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $file = \Illuminate\Http\UploadedFile::fake()->image('cancel.jpg')->size(50);

        $component = Livewire::test(\App\Livewire\Vehiculo\UploadImagenes::class, ['placa' => 'CANCEL1'])
            ->set('tipo', 'foto_principal')
            ->set('archivo', $file);

        $this->assertNotNull($component->get('archivo'));

        $component->call('cancelarUpload');

        $this->assertNull($component->get('archivo'));
    }
}
