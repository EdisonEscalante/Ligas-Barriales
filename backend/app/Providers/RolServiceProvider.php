<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UsuarioRol;

/**
 * RolServiceProvider
 * Comparte el rol y la liga del usuario autenticado con todas las vistas Blade
 * Así el menú puede mostrarse según el rol sin repetir consultas en cada controlador
 */
class RolServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Compartimos estas variables con TODAS las vistas automáticamente
        View::composer('*', function ($view) {
            $rolActual = null;
            $ligaActual = null;

            if (Auth::check()) {
                $usuarioRol = UsuarioRol::where('user_id', Auth::id())
                    ->with('rol', 'liga')
                    ->first();

                if ($usuarioRol) {
                    $rolActual  = $usuarioRol->rol->nombre;
                    $ligaActual = $usuarioRol->liga;
                }
            }

            $view->with('rolActual', $rolActual);
            $view->with('ligaActual', $ligaActual);
        });
    }
}