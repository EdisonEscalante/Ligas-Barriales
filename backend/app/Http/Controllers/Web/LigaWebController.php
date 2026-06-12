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
     * Guarda una nueva liga en la base de datos
     * POST /ligas
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'provincia'       => 'required|string|max:100',
            'ciudad'          => 'required|string|max:100',
            'canton'          => 'required|string|max:100',
            'parroquia'       => 'required|string|max:100',
            'descripcion'     => 'nullable|string',
            'escudo_url'      => 'nullable|string|max:255',
        ]);

        Liga::create($request->all());

        return redirect('/ligas')->with('success', 'Liga creada correctamente');
    }

    /**
     * Muestra los detalles de una liga específica
     * GET /ligas/{id}
     */
    public function show(string $id)
    {
        $liga = Liga::with(['disciplinas', 'temporadas', 'equipos'