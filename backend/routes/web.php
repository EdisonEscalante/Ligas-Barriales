<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\LigaWebController;

/*
|--------------------------------------------------------------------------
| Rutas Web — Para las vistas Blade
|--------------------------------------------------------------------------
*/

// Ruta principal redirige al login
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

    // Rutas de ligas
    Route::resource('ligas', LigaWebController::class);
});