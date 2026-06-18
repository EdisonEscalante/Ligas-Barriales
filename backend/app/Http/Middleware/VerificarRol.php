<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UsuarioRol;

/**
 * VerificarRol
 * Middleware que verifica si el usuario autenticado tiene alguno
 * de los roles permitidos para acceder a una ruta
 *
 * Uso en rutas: ->middleware('rol:super_admin,admin_liga')
 */
class VerificarRol
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$rolesPermitidos): Response
    {
        // Obtenemos los roles del usuario autenticado
        $rolesUsuario = UsuarioRol::where('user_id', Auth::id())
            ->with('rol')
            ->get()
            ->pluck('rol.nombre')
            ->toArray();

        // Verificamos si el usuario tiene al menos uno de los roles permitidos
        $tieneAcceso = count(array_intersect($rolesUsuario, $rolesPermitidos)) > 0;

        if (!$tieneAcceso) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}