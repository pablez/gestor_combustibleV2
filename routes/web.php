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

// Imports de componentes Livewire organizados por módulos
use App\Livewire\DashboardKpis;
use App\Livewire\DashboardUser;

// Unidades Organizacionales
use App\Livewire\Unidades\Index as UnidadesIndex;
use App\Livewire\Unidades\Create as UnidadesCreate;
use App\Livewire\Unidades\Edit as UnidadesEdit;
use App\Livewire\Unidades\Show as UnidadesShow;

// Usuarios
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UserShow;

// Tipos de Vehículos
use App\Livewire\TipoVehiculo\Index as TipoVehiculoIndex;

// Unidades de Transporte
use App\Livewire\UnidadTransporte\Index as UnidadTransporteIndex;

// KPIs
use App\Livewire\Kpis\VehiculosKpis;
use App\Livewire\Kpis\UsersKpis;

// Solicitudes
use App\Livewire\Solicitud\Index as SolicitudIndex;
use App\Livewire\Solicitud\Create as SolicitudCreate;

// Categorías Programáticas
use App\Livewire\CategoriaProgramatica\Index as CategoriaProgramaticaIndex;
use App\Livewire\CategoriaProgramatica\Create as CategoriaProgramaticaCreate;

// Fuentes de Organismo Financiero
use App\Livewire\FuenteOrganismoFinanciero\Index as FuenteOrganismoFinancieroIndex;
use App\Livewire\FuenteOrganismoFinanciero\Create as FuenteOrganismoFinancieroCreate;

Route::middleware(['auth'])->group(function () {
    Route::post('/profile/photo', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
    
    // === DASHBOARDS ===
    Route::get('kpis/dashboard', DashboardKpis::class)->name('kpis.dashboard');
    Route::get('kpis/vehiculos', VehiculosKpis::class)->name('kpis.vehiculos');
    Route::get('kpis/users', UsersKpis::class)->name('kpis.users');
    
    // === UNIDADES ORGANIZACIONALES ===
    Route::prefix('unidades')->name('unidades.')->group(function () {
        Route::get('/', UnidadesIndex::class)->name('index');
        Route::get('/create', UnidadesCreate::class)->name('create');
        Route::get('/{id}/edit', UnidadesEdit::class)->name('edit');
        Route::get('/{id}', UnidadesShow::class)->name('show');
    });
    
    // === GESTIÓN DE USUARIOS ===
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/dashboard', DashboardUser::class)->name('dashboard');
        Route::get('/', UserIndex::class)->name('index');
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/{id}/edit', UserEdit::class)->name('edit');
        Route::get('/{id}', UserShow::class)->name('show');
    });
    
    // === TIPOS DE VEHÍCULOS ===
    Route::prefix('tipos-vehiculo')->name('tipos-vehiculo.')->group(function () {
        Route::get('/', TipoVehiculoIndex::class)->name('index');
        // Note: Create y Edit se manejan mediante modales en el Index
    });
    
    // === UNIDADES DE TRANSPORTE ===
    Route::prefix('unidades-transporte')->name('unidades-transporte.')->group(function () {
        Route::get('/', UnidadTransporteIndex::class)->name('index');
        // Las rutas adicionales se pueden agregar según necesidad
    });
    
    // === SOLICITUDES DE COMBUSTIBLE ===
    Route::prefix('solicitudes')->name('solicitudes.')->group(function () {
        Route::get('/', SolicitudIndex::class)->name('index');
        Route::get('/create', SolicitudCreate::class)->name('create');
        // Las rutas adicionales se pueden agregar según necesidad
    });
    
    // === CATEGORÍAS PROGRAMÁTICAS ===
    Route::prefix('categorias-programaticas')->name('categorias-programaticas.')->group(function () {
        Route::get('/', CategoriaProgramaticaIndex::class)->name('index');
        Route::get('/create', CategoriaProgramaticaCreate::class)->name('create');
    });
    
    // === FUENTES DE ORGANISMO FINANCIERO ===
    Route::prefix('fuentes-organismo-financiero')->name('fuentes-organismo-financiero.')->group(function () {
        Route::get('/', FuenteOrganismoFinancieroIndex::class)->name('index');
        Route::get('/create', FuenteOrganismoFinancieroCreate::class)->name('create');
    });
});

// === RUTAS DE COMPATIBILIDAD (para mantener rutas existentes) ===
Route::middleware(['auth'])->group(function () {
    Route::get('tipos-vehiculo-legacy', TipoVehiculoIndex::class)->name('tipos-vehiculo-legacy.index');
    Route::get('unidades-legacy', UnidadesIndex::class)->name('unidades-legacy.index');
    Route::get('users-legacy', UserIndex::class)->name('users-legacy.index');
});

// === RUTAS DE PRUEBA ===
Route::middleware(['auth'])->group(function () {
    Route::get('/vehiculos/{vehiculo}/imagenes', function (App\Models\UnidadTransporte $vehiculo) {
        return view('vehiculos.imagenes', compact('vehiculo'));
    })->name('vehiculos.imagenes');
});
