<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LigaController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TemporadaController;
use App\Http\Controllers\VentanaPaseController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\FaseController;
use App\Http\Controllers\FechaEncuentroController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\InscripcionJugadorController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\EstadisticaPartidoController;
use App\Http\Controllers\AmonestacionController;
use App\Http\Controllers\TablaPosicionesController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PaseJugadorController;

/*
|--------------------------------------------------------------------------
| Rutas públicas — No requieren autenticación
| Estas rutas son accesibles sin token (login, registro, consultas públicas)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/registro', [AuthController::class, 'registro']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Rutas públicas de consulta para la app Android
Route::get('/ligas',                    [LigaController::class, 'index']);
Route::get('/ligas/{id}',               [LigaController::class, 'show']);
Route::get('/torneos',                  [TorneoController::class, 'index']);
Route::get('/torneos/{id}',             [TorneoController::class, 'show']);
Route::get('/tabla-posiciones',         [TablaPosicionesController::class, 'index']);
Route::get('/partidos',                 [PartidoController::class, 'index']);
Route::get('/partidos/{id}',            [PartidoController::class, 'show']);
Route::get('/jugadores',                [JugadorController::class, 'index']);
Route::get('/jugadores/{id}',           [JugadorController::class, 'show']);
Route::get('/jugadores/{id}/ligas',     [JugadorController::class, 'ligas']);
Route::get('/fechas-encuentro',         [FechaEncuentroController::class, 'index']);
Route::get('/fechas-encuentro/{id}',    [FechaEncuentroController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Rutas protegidas — Requieren autenticación con token
| El middleware auth:sanctum verifica el token en cada petición
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Perfil y logout del usuario autenticado
    Route::post('/auth/logout',  [AuthController::class, 'logout']);
    Route::get('/auth/perfil',   [AuthController::class, 'perfil']);

    // Gestión de ligas
    Route::apiResource('ligas', LigaController::class)->except(['index', 'show']);

    // Gestión de disciplinas y categorías
    Route::apiResource('disciplinas', DisciplinaController::class);
    Route::apiResource('categorias',  CategoriaController::class);

    // Gestión de temporadas y ventanas de pase
    Route::apiResource('temporadas',     TemporadaController::class);
    Route::apiResource('ventanas-pase',  VentanaPaseController::class);

    // Gestión de equipos
    Route::apiResource('equipos', EquipoController::class);

    // Gestión de torneos y fases
    Route::apiResource('torneos', TorneoController::class)->except(['index', 'show']);
    Route::apiResource('fases',   FaseController::class);

    // Gestión de fechas de encuentro y generación automática
    Route::post('/fechas-encuentro/generar', [FechaEncuentroController::class, 'generar']);
    Route::apiResource('fechas-encuentro', FechaEncuentroController::class)->except(['index', 'show']);

    // Gestión de jugadores e inscripciones
    Route::apiResource('jugadores',    JugadorController::class)->except(['index', 'show']);
    Route::apiResource('inscripciones', InscripcionJugadorController::class);

    // Gestión de partidos y resultados
    Route::apiResource('partidos', PartidoController::class)->except(['index', 'show']);

    // Estadísticas y amonestaciones
    Route::apiResource('estadisticas',   EstadisticaPartidoController::class);
    Route::apiResource('amonestaciones', AmonestacionController::class);

    // Tabla de posiciones
    Route::apiResource('tabla-posiciones', TablaPosicionesController::class)->except(['index']);

    // Pases de jugadores
    Route::apiResource('pases', PaseJugadorController::class);

    // Roles del sistema
    Route::apiResource('roles', RolController::class);
});