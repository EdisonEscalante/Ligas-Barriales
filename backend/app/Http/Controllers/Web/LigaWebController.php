<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Liga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * LigaWebController
 * Maneja las vistas web para la gestión de ligas
 */
class LigaWebController extends Controller
{
    /**
     * Lista todas las ligas
     * GET /ligas
     */
    public function index()
    {
        $ligas = Liga::all();
        return view('ligas.index', compact('ligas'));
    }

    /**
     * Muestra el formulario para crear una nueva liga
     * GET /ligas/create
     */
    public function create()
    {
        return view('ligas.create');
    }

    /**
     * Guarda una nueva liga y crea su administrador
     * POST /ligas
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'provincia'       => 'required|string|max:100',
            'canton'          => 'required|string|max:100',
            'parroquia'       => 'required|string|max:100',
            'barrio'          => 'nullable|string|max:255',
            'descripcion'     => 'nullable|string',
            'escudo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'admin_nombre'    => 'required|string|max:255',
            'admin_email'     => 'required|email|unique:users,email',
            'admin_password'  => 'required|string|min:8',
        ]);

        $escudoPath = null;
        if ($request->hasFile('escudo')) {
            $escudoPath = $request->file('escudo')->store('escudos', 'public');
        }

        $liga = Liga::create([
            'nombre'          => ucwords(strtolower($request->nombre)),
            'fecha_fundacion' => $request->fecha_fundacion,
            'provincia'       => ucwords(strtolower($request->provincia)),
            'ciudad'          => ucwords(strtolower($request->canton)),
            'canton'          => ucwords(strtolower($request->canton)),
            'parroquia'       => ucwords(strtolower($request->parroquia)),
            'barrio'          => $request->barrio ? ucwords(strtolower($request->barrio)) : null,
            'descripcion'     => $request->descripcion,
            'escudo_url'      => $escudoPath,
        ]);

        $admin = \App\Models\User::create([
            'nombre'   => $request->admin_nombre,
            'email'    => $request->admin_email,
            'password' => Hash::make($request->admin_password),
        ]);

        $rol = \App\Models\Rol::where('nombre', 'admin_liga')->first();

        \App\Models\UsuarioRol::create([
            'user_id' => $admin->id,
            'rol_id'  => $rol->id,
            'liga_id' => $liga->id,
        ]);

        return redirect('/ligas')->with('success', 'Liga y administrador creados correctamente');
    }

    /**
     * Muestra los detalles de una liga específica
     * GET /ligas/{id}
     */
    public function show(string $id)
    {
        $liga = Liga::with(['disciplinas', 'temporadas', 'equipos'])->findOrFail($id);
        return view('ligas.show', compact('liga'));
    }

    /**
     * Muestra el formulario para editar una liga
     * GET /ligas/{id}/edit
     */
    public function edit(string $id)
    {
        $liga = Liga::findOrFail($id);

        // Obtenemos el administrador actual de la liga
        $administrador = \App\Models\User::whereHas('roles', function($q) use ($id) {
            $q->where('liga_id', $id)
              ->whereHas('rol', function($q2) {
                  $q2->where('nombre', 'admin_liga');
              });
        })->first();

        return view('ligas.edit', compact('liga', 'administrador'));
    }

    /**
     * Actualiza los datos de una liga existente
     * PUT /ligas/{id}
     */
    public function update(Request $request, string $id)
    {
        $liga = Liga::findOrFail($id);

        $request->validate([
            'nombre'          => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'provincia'       => 'required|string|max:100',
            'canton'          => 'required|string|max:100',
            'parroquia'       => 'required|string|max:100',
            'barrio'          => 'nullable|string|max:255',
            'descripcion'     => 'nullable|string',
            'escudo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesamos la imagen
        $escudoPath = $liga->escudo_url;
        if ($request->hasFile('escudo')) {
            if ($liga->escudo_url) {
                Storage::disk('public')->delete($liga->escudo_url);
            }
            $escudoPath = $request->file('escudo')->store('escudos', 'public');
        }

        // Actualizamos la liga
        $liga->update([
            'nombre'          => ucwords(strtolower($request->nombre)),
            'fecha_fundacion' => $request->fecha_fundacion,
            'provincia'       => ucwords(strtolower($request->provincia)),
            'ciudad'          => ucwords(strtolower($request->canton)),
            'canton'          => ucwords(strtolower($request->canton)),
            'parroquia'       => ucwords(strtolower($request->parroquia)),
            'barrio'          => $request->barrio ? ucwords(strtolower($request->barrio)) : null,
            'descripcion'     => $request->descripcion,
            'escudo_url'      => $escudoPath,
        ]);

        // Gestionamos el administrador
        $accion = $request->admin_accion ?? 'mantener';

        if ($accion === 'cambiar_password' && $request->admin_password) {
            $administrador = \App\Models\User::whereHas('roles', function($q) use ($id) {
                $q->where('liga_id', $id)
                  ->whereHas('rol', function($q2) {
                      $q2->where('nombre', 'admin_liga');
                  });
            })->first();

            if ($administrador) {
                $administrador->update([
                    'password' => Hash::make($request->admin_password),
                ]);
            }

        } elseif ($accion === 'cambiar_admin' && $request->nuevo_admin_email) {
            $request->validate([
                'nuevo_admin_nombre'   => 'required|string|max:255',
                'nuevo_admin_email'    => 'required|email|unique:users,email',
                'nuevo_admin_password' => 'required|string|min:8',
            ]);

            // Quitamos el rol al administrador anterior
            \App\Models\UsuarioRol::where('liga_id', $id)
                ->whereHas('rol', function($q) {
                    $q->where('nombre', 'admin_liga');
                })->delete();

            // Creamos el nuevo administrador
            $nuevoAdmin = \App\Models\User::create([
                'nombre'   => $request->nuevo_admin_nombre,
                'email'    => $request->nuevo_admin_email,
                'password' => Hash::make($request->nuevo_admin_password),
            ]);

            $rol = \App\Models\Rol::where('nombre', 'admin_liga')->first();

            \App\Models\UsuarioRol::create([
                'user_id' => $nuevoAdmin->id,
                'rol_id'  => $rol->id,
                'liga_id' => $liga->id,
            ]);

        } elseif (!$request->admin_accion && $request->nuevo_admin_email) {
            // Liga sin administrador, creamos uno nuevo
            $request->validate([
                'nuevo_admin_nombre'   => 'required|string|max:255',
                'nuevo_admin_email'    => 'required|email|unique:users,email',
                'nuevo_admin_password' => 'required|string|min:8',
            ]);

            $nuevoAdmin = \App\Models\User::create([
                'nombre'   => $request->nuevo_admin_nombre,
                'email'    => $request->nuevo_admin_email,
                'password' => Hash::make($request->nuevo_admin_password),
            ]);

            $rol = \App\Models\Rol::where('nombre', 'admin_liga')->first();

            \App\Models\UsuarioRol::create([
                'user_id' => $nuevoAdmin->id,
                'rol_id'  => $rol->id,
                'liga_id' => $liga->id,
            ]);
        }

        return redirect('/ligas')->with('success', 'Liga actualizada correctamente');
    }

    /**
     * Elimina una liga del sistema
     * DELETE /ligas/{id}
     */
    public function destroy(string $id)
    {
        $liga = Liga::findOrFail($id);

        if ($liga->escudo_url) {
            Storage::disk('public')->delete($liga->escudo_url);
        }

        $liga->delete();

        return redirect('/ligas')->with('success', 'Liga eliminada correctamente');
    }
}