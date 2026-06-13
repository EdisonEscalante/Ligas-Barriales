<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Liga;
use Illuminate\Http\Request;

/**
 * LigaWebController
 * Maneja las vistas web para la gestión de ligas
 * Usa sesiones de Laravel para autenticación
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
 * Guarda una nueva liga y crea su administrador en la base de datos
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

    // Procesamos la imagen del escudo si se subió una
    $escudoPath = null;
    if ($request->hasFile('escudo')) {
        // Guardamos la imagen en storage/app/public/escudos
        $escudoPath = $request->file('escudo')->store('escudos', 'public');
    }

    // Creamos la liga normalizando las mayúsculas con ucwords
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

    // Creamos el usuario administrador de la liga
    $admin = \App\Models\User::create([
        'nombre'   => $request->admin_nombre,
        'email'    => $request->admin_email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->admin_password),
    ]);

    // Asignamos el rol admin_liga vinculado a esta liga
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
}
