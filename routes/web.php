<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PreciosController;
use App\Http\Controllers\UsuariosController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::redirect('/', '/login');

// Route::get('/register', function () {
//     return Inertia::render('Auth/Register', [
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// })->name('register');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');  

    // ===== Usuarios =====

    Route::get('/listarusuarios', function () {
        return Inertia::render('ListarUsuarios/index');
    })->name('listarusuarios.index');

    Route::get('/fn_traerDatosUsuarios', [UsuariosController::class, 'traerDatosUsuarios']);

    // ===== Reportes =====

    Route::get('/reportes', function () {
        return Inertia::render('Reportes/index');
    })->name('reportes.index');

    Route::get('/fn_traerDatosReporteDiario', [ReporteController::class, 'traerDatosReporteDiario']);
    Route::get('/fn_traerPreciosServisGrupo', [ReporteController::class, 'traerPreciosServisGrupo']);
    
    // ===== Personal =====

    Route::get('/personal', function () {
        return Inertia::render('Personal/index');
    })->name('personal.index');

    Route::get('/personal/create', function () {
        return Inertia::render('Personal/create');
    })->name('personal.create');

    Route::get('/personal/edit-personal', [PersonalController::class, 'edit'])->name('personal.edit-personal');
    Route::put('/personal/update/{idEmpleado}', [PersonalController::class, 'update'])->name('personal.update');
    Route::post('/personal/store', [PersonalController::class, 'store'])->name('personal.store');

    Route::get('/fn_traerDatosTrabajadores', [PersonalController::class, 'traerDatosTrabajadores']);
    Route::get('/fn_traerDatosServis', [PersonalController::class, 'traerDatosServis']);
    Route::get('/fn_traerCodigoPersonal', [PersonalController::class, 'traerCodigoPersonal']);
    
    // ===== Precios =====

    Route::get('/precios', function () {
        return Inertia::render('Precios/index');
    })->name('precios.index');

    Route::get('/fn_traerPreciosServis', [PreciosController::class, 'traerPreciosServis']);
    Route::get('/fn_guardarNuevoPrecioServis', [PreciosController::class, 'guardarNuevoPrecioServis']);
});