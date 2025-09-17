<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

use App\Livewire\Unidades\Index as UnidadesIndex;
use App\Livewire\Unidades\Create as UnidadesCreate;
use App\Livewire\Unidades\Edit as UnidadesEdit;
use App\Livewire\Unidades\Show as UnidadesShow;

Route::middleware(['auth'])->group(function () {
    Route::post('/profile/photo', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
    Route::get('unidades', UnidadesIndex::class)->name('unidades.index');
    Route::get('unidades/create', UnidadesCreate::class)->name('unidades.create');
    Route::get('unidades/{id}/edit', UnidadesEdit::class)->name('unidades.edit');
    Route::get('unidades/{id}', UnidadesShow::class)->name('unidades.show');
});
