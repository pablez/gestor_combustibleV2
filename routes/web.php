<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Unidades\Index as UnidadesIndex;
use App\Http\Livewire\Unidades\Create as UnidadesCreate;
use App\Http\Livewire\Unidades\Edit as UnidadesEdit;
use App\Http\Livewire\Unidades\Show as UnidadesShow;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    // Unidades organizacionales CRUD
    Route::get('unidades', UnidadesIndex::class)->name('unidades.index');
    Route::get('unidades/create', UnidadesCreate::class)->name('unidades.create');
    Route::get('unidades/{id}/edit', UnidadesEdit::class)->name('unidades.edit');
    Route::get('unidades/{id}', UnidadesShow::class)->name('unidades.show');

    Route::delete('unidades/{id}', function ($id) {
        if (! auth()->user() || ! auth()->user()->can('usuarios.gestionar')) {
            abort(403);
        }
        $u = App\Models\UnidadOrganizacional::findOrFail($id);
        $u->delete();
        return redirect()->route('unidades.index')->with('success', 'Unidad eliminada');
    })->name('unidades.destroy');
});
