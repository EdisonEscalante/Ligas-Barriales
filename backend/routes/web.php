<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\LigaWebController;
use App\Http\Controllers\Web\MiLigaWebController;
use App\Http\Controllers\Web\EquipoTrabajoWebController;

/*
|--------------------------------------------------------------------------
| Rutas Web — Para las vistas Blade
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación web
Route::get('/login',  [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/auth/logout', [AuthWebController::class, 'logout'])->name('logout');

// Rutas protegidas — requieren sesión iniciada
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas exclusivas del Super Administrador
    Route::middleware('rol:super_admin')->group(function () {
        Route::resource('ligas', LigaWebController::class);
    });

    // Rutas exclusivas del Administrador de Liga
    Route::middleware('rol:admin_liga')->group(function () {
        Route::get('/mi-liga', [MiLigaWebController::class, 'show'])->name('mi-liga');
        Route::get('/mi-liga/edit', [MiLigaWebController::class, 'edit'])->name('mi-liga.edit');
        Route::put('/mi-liga', [MiLigaWebController::class, 'update'])->name('mi-liga.update');

        // Equipo de trabajo (calificadores, programadores, sancionadores)
        Route::get('/usuarios-liga', [EquipoTrabajoWebController::class, 'index'])->name('usuarios-liga.index');
        Route::post('/usuarios-liga', [EquipoTrabajoWebController::class, 'store'])->name('usuarios-liga.store');
        Route::delete('/usuarios-liga/{id}', [EquipoTrabajoWebController::class, 'destroy'])->name('usuarios-liga.destroy');
    });
});