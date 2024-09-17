<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\PersonalController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::redirect('/', '/login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/listarusuarios', function () {
        return Inertia::render('ListarUsuarios/index');
    })->name('listarusuarios.index');

    Route::get('/reportes', function () {
        return Inertia::render('Reportes/index');
    })->name('reportes.index');
    
    Route::get('/personal', function () {
        return Inertia::render('Personal/index');
    })->name('personal.index');
    
    Route::get('/precios', function () {
        return Inertia::render('Precios/index');
    })->name('precios.index');
});

Route::get('/fn_traerDatosTrabajadores', [PersonalController::class, 'traerDatosTrabajadores']);