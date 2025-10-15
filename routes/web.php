<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\VehiculoImagenController;
use App\Http\Controllers\VehiculoImagenesFrontendController;

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
use App\Livewire\Solicitud\Show as SolicitudShow;
use App\Livewire\Solicitud\Edit as SolicitudEdit;

// Categorías Programáticas
use App\Livewire\CategoriaProgramatica\Index as CategoriaProgramaticaIndex;
use App\Livewire\CategoriaProgramatica\Create as CategoriaProgramaticaCreate;

// Fuentes de Organismo Financiero
use App\Livewire\FuenteOrganismoFinanciero\Index as FuenteOrganismoFinancieroIndex;
use App\Livewire\FuenteOrganismoFinanciero\Create as FuenteOrganismoFinancieroCreate;

// Tipos de Servicio de Proveedor
use App\Livewire\TipoServicioProveedor\Index as TipoServicioProveedorIndex;

// Proveedores
use App\Livewire\Proveedor\Index as ProveedorIndex;
use App\Livewire\Proveedor\Create as ProveedorCreate;
use App\Livewire\Proveedor\Show as ProveedorShow;
use App\Livewire\Proveedor\Edit as ProveedorEdit;

// Despachos de Combustible
use App\Livewire\DespachoCombustible\Index as DespachoCombustibleIndex;
use App\Livewire\DespachoCombustible\Create as DespachoCombustibleCreate;
use App\Livewire\DespachoCombustible\Show as DespachoCombustibleShow;
use App\Livewire\DespachoCombustible\Edit as DespachoCombustibleEdit;

// Consumos de Combustible
use App\Livewire\ConsumoCombustible\Index as ConsumoCombustibleIndex;
use App\Livewire\ConsumoCombustible\Create as ConsumoCombustibleCreate;
use App\Livewire\ConsumoCombustible\Show as ConsumoCombustibleShow;
use App\Livewire\ConsumoCombustible\Edit as ConsumoCombustibleEdit;

// Presupuestos
use App\Livewire\Presupuesto\Index as PresupuestoIndex;
use App\Livewire\Presupuesto\Create as PresupuestoCreate;
use App\Livewire\Presupuesto\Edit as PresupuestoEdit;
use App\Livewire\Presupuesto\Show as PresupuestoShow;

// Códigos de Registro
use App\Livewire\CodigoRegistro\Index as CodigoRegistroIndex;
use App\Livewire\CodigoRegistro\Create as CodigoRegistroCreate;

// Solicitudes de Aprobación de Usuario
use App\Livewire\SolicitudAprobacionUsuario\Index as SolicitudAprobacionUsuarioIndex;
use App\Livewire\SolicitudAprobacionUsuario\Create as SolicitudAprobacionUsuarioCreate;
use App\Livewire\SolicitudAprobacionUsuario\Show as SolicitudAprobacionUsuarioShow;

// Reportes
use App\Livewire\Reportes\ReportesIndex;
use App\Livewire\Reportes\CombustibleReporte;
use App\Livewire\Reportes\PresupuestoReporte;
use App\Livewire\Reportes\ReportesTest;

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
        Route::get('/{solicitud}', SolicitudShow::class)->name('show');
        Route::get('/{solicitud}/edit', SolicitudEdit::class)->name('edit');
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
    
    // === TIPOS DE SERVICIO DE PROVEEDOR ===
    Route::prefix('tipos-servicio-proveedor')->name('tipos-servicio-proveedor.')->group(function () {
        Route::get('/', TipoServicioProveedorIndex::class)->name('index');
        // Note: Create y Edit se manejan mediante modales en el Index
    });
    
    // === PROVEEDORES ===
    Route::prefix('proveedores')->name('proveedores.')->group(function () {
        Route::get('/', ProveedorIndex::class)->name('index');
        Route::get('/create', ProveedorCreate::class)->name('create');
        Route::get('/{proveedor}/edit', ProveedorEdit::class)->name('edit');
        Route::get('/{proveedor}', ProveedorShow::class)->name('show');
    });
    
    // === DESPACHOS DE COMBUSTIBLE ===
    Route::prefix('despachos')->name('despachos.')->group(function () {
        Route::get('/', DespachoCombustibleIndex::class)->name('index');
        Route::get('/create', DespachoCombustibleCreate::class)->name('create');
        Route::get('/{despacho}/edit', DespachoCombustibleEdit::class)->name('edit');
        Route::get('/{despacho}', DespachoCombustibleShow::class)->name('show');
    });
    
    // === CONSUMOS DE COMBUSTIBLE ===
    Route::prefix('consumos')->name('consumos.')->group(function () {
        Route::get('/', ConsumoCombustibleIndex::class)->name('index');
        Route::get('/create', ConsumoCombustibleCreate::class)->name('create');
        Route::get('/{consumo}/edit', ConsumoCombustibleEdit::class)->name('edit');
        Route::get('/{consumo}', ConsumoCombustibleShow::class)->name('show');
    });

    // === PRESUPUESTOS ===
    Route::prefix('presupuestos')->name('presupuestos.')->group(function () {
        Route::get('/', PresupuestoIndex::class)->name('index')->middleware('can:presupuestos.ver');
        Route::get('/create', PresupuestoCreate::class)->name('create')->middleware('can:presupuestos.crear');
        Route::get('/{presupuesto}/edit', PresupuestoEdit::class)->name('edit')->middleware('can:presupuestos.editar');
        Route::get('/{presupuesto}', PresupuestoShow::class)->name('show')->middleware('can:presupuestos.ver');
    });

    // === SOLICITUDES DE APROBACIÓN DE USUARIO ===
    Route::prefix('solicitudes-aprobacion')->name('solicitudes-aprobacion.')->group(function () {
        Route::get('/', \App\Livewire\SolicitudAprobacionUsuario\Index::class)->name('index')->middleware('can:solicitudes_aprobacion.ver');
        Route::get('/create', \App\Livewire\SolicitudAprobacionUsuario\Create::class)->name('create')->middleware('can:solicitudes_aprobacion.crear');
        Route::get('/{solicitud}', \App\Livewire\SolicitudAprobacionUsuario\Show::class)->name('show')->middleware('can:solicitudes_aprobacion.ver');
    });

    // === CÓDIGOS DE REGISTRO ===
    Route::prefix('codigos-registro')->name('codigos-registro.')->group(function () {
        Route::get('/', \App\Livewire\CodigoRegistro\Index::class)->name('index')->middleware('can:codigos_registro.ver');
        Route::get('/create', \App\Livewire\CodigoRegistro\Create::class)->name('create')->middleware('can:codigos_registro.crear');
    });

    // === ADMIN: GESTIÓN DE IMÁGENES DE VEHÍCULOS ===
    Route::prefix('admin/vehiculos/imagenes')->name('admin.vehiculos.imagenes.')->group(function () {
        Route::get('/', \App\Livewire\Admin\ImagenesIndex::class)->name('index');
        Route::get('/{id}', \App\Livewire\Admin\VehiculoImagenesShow::class)->name('show');
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
    Route::get('/admin/vehiculos/{vehiculo}/imagenes', function (App\Models\UnidadTransporte $vehiculo) {
        return view('vehiculos.imagenes', compact('vehiculo'));
    })->name('vehiculos.imagenes');
});

// Ruta autenticada para mostrar imágenes de vehículos con layout
Route::middleware(['auth'])->get('/vehiculos/{vehiculo}/imagenes', [VehiculoImagenesFrontendController::class, 'show'])
    ->name('vehiculos.frontend.imagenes');

// Rutas WEB para gestión de imágenes de vehículos (paralelas a las de la API)
Route::middleware(['auth', \App\Http\Middleware\RegistrarAccionesImagenes::class])->prefix('vehiculos')->group(function () {
    Route::get('imagenes/estadisticas', [VehiculoImagenController::class, 'estadisticas'])
        ->name('vehiculos.imagenes.estadisticas');

    Route::prefix('{vehiculo}')->group(function () {
        Route::get('imagenes/api', [VehiculoImagenController::class, 'show'])
            ->name('vehiculos.imagenes.show');

        Route::get('imagenes/{tipo_imagen}', [VehiculoImagenController::class, 'show'])
            ->name('vehiculos.imagenes.por-tipo');

        Route::post('imagenes/{tipo_imagen}', [VehiculoImagenController::class, 'store'])
            ->name('vehiculos.imagenes.store');

        Route::delete('imagenes/{tipo_imagen}', [VehiculoImagenController::class, 'destroy'])
            ->name('vehiculos.imagenes.destroy');

        Route::delete('imagenes/galeria_fotos/{indice}', [VehiculoImagenController::class, 'destroy'])
            ->name('vehiculos.imagenes.galeria.destroy');

        Route::post('imagenes/thumbnail', [VehiculoImagenController::class, 'thumbnail'])
            ->name('vehiculos.imagenes.thumbnail');
    });
});

// === REPORTES ===
Route::prefix('reportes')->name('reportes.')->middleware(['auth', 'permission:reportes.ver'])->group(function () {
    Route::get('/', ReportesIndex::class)->name('index');
    Route::get('/combustible', CombustibleReporte::class)->name('combustible')->middleware('permission:reportes.combustible');
    Route::get('/combustible/generar', [App\Http\Controllers\ReporteCombustibleController::class, 'generar'])->name('combustible.generar')->middleware('permission:reportes.combustible');
    Route::get('/presupuesto', PresupuestoReporte::class)->name('presupuesto')->middleware('permission:reportes.presupuesto');
});

// Test route temporally
Route::get('/reportes-test', ReportesTest::class)->middleware('auth')->name('reportes.test');

require __DIR__.'/auth.php';
