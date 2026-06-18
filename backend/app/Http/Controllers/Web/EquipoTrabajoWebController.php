<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsuarioRol;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * EquipoTrabajoWebController
 * Permite al Administrador de Liga crear y gestionar
 * los usuarios auxiliares de su liga (calificador, programador, sancionador)
 */
class EquipoTrabajoWebController extends Controller
{
    /**
     * Obtiene la liga asignada al administrador autenticado
     */
    private function obtenerMiLigaId()
    {
        $usuarioRol = UsuarioRol::where('user_id', Auth::id())
            ->whereHas('rol', function($q) {
                $q->where('nombre', 'admin_liga');
            })
            ->first();

        if (!$usuarioRol) {
            abort(403, 'No tienes una liga asignada.');
        }

        return $usuarioRol->liga_id;
    }

    /**
     * Lista todos los usuarios auxiliares de la liga del administrador
     * GET /usuarios-liga
     */
    public function index()
    {
        $ligaId = $this->obtenerMiLigaId();

        // Obtenemos los roles auxiliares (no admin_liga ni super_admin)
        $rolesAuxiliares = ['admin_equipos', 'calificador', 'programador', 'sancionador'];

        $usuariosLiga = UsuarioRol::where('liga_id', $ligaId)
            ->whereHas('rol', function($q) use ($rolesAuxiliares) {
                $q->whereIn('nombre', $rolesAuxiliares);
            })
            ->with(['usuario', 'rol'])
            ->get();

        $roles = Rol::whereIn('nombre', $rolesAuxiliares)->get();

        return view('equipo-trabajo.index', compact('usuariosLiga', 'roles'));
    }

    /**
     * Crea un nuevo usuario auxiliar para la liga
     * POST /usuarios-liga
     */
    public function store(Request $request)
    {
        $ligaId = $this->obtenerMiLigaId();

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'rol_id'   => 'required|uuid|exists:roles,id',
        ]);

        // Creamos el usuario auxiliar
        $usuario = User::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignamos el rol vinculado a la liga del administrador
        UsuarioRol::create([
            'user_id' => $usuario->id,
            'rol_id'  => $request->rol_id,
            'liga_id' => $ligaId,
        ]);

        return redirect('/usuarios-liga')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Elimina un usuario auxiliar de la liga
     * DELETE /usuarios-liga/{id}
     */
    public function destroy(string $id)
    {
        $ligaId = $this->obtenerMiLigaId();

        // Verificamos que el usuario pertenezca a esta liga antes de eliminar
        $usuarioRol = UsuarioRol::where('liga_id', $ligaId)
            ->where('user_id', $id)
            ->first();

        if (!$usuarioRol) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        // Eliminamos el rol y el usuario
        $usuarioRol->delete();
        User::where('id', $id)->delete();

        return redirect('/usuarios-liga')->with('success', 'Usuario eliminado correctamente');
    }
}
