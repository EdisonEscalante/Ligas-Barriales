<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use Illuminate\Http\Request;

/**
 * JugadorController
 * Maneja todas las operaciones CRUD para los jugadores
 * Un jugador es global, no pertenece a ninguna liga específica
 * Se vincula a equipos y torneos mediante inscripciones
 */
class JugadorController extends Controller
{
    /**
     * Lista todos los jugadores con opción de buscar por cédula o nombre
     * GET /api/jugadores?search={texto}
     */
    public function index(Request $request)
    {
        $jugadores = Jugador::when($request->search, function ($query, $search) {
            return $query->where('cedula', 'like', "%$search%")
                         ->orWhere('nombres', 'like', "%$search%")
                         ->orWhere('apellidos', 'like', "%$search%");
        })->get();

        return response()->json($jugadores);
    }

    /**
     * Crea un nuevo jugador en el sistema
     * POST /api/jugadores
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula'           => 'required|string|max:20|unique:jugadores,cedula',
            'nombres'          => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'foto_url'         => 'nullable|string|max:255',
            'telefono'         => 'nullable|string|max:20',
        ]);

        $jugador = Jugador::create($request->all());

        return response()->json($jugador, 201);
    }

    /**
     * Muestra un jugador específico con todas sus inscripciones,
     * estadísticas y amonestaciones
     * GET /api/jugadores/{id}
     */
    public function show(string $id)
    {
        $jugador = Jugador::with([
            'inscripciones.equipo',
            'inscripciones.torneo',
            'estadisticas',
            'amonestaciones',
            'pases',
        ])->findOrFail($id);

        return response()->json($jugador);
    }

    /**
     * Actualiza los datos de un jugador existente
     * PUT /api/jugadores/{id}
     */
    public function update(Request $request, string $id)
    {
        $jugador = Jugador::findOrFail($id);

        $request->validate([
            'cedula'           => 'sometimes|string|max:20|unique:jugadores,cedula,' . $id,
            'nombres'          => 'sometimes|string|max:255',
            'apellidos'        => 'sometimes|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'foto_url'         => 'nullable|string|max:255',
            'telefono'         => 'nullable|string|max:20',
        ]);

        $jugador->update($request->all());

        return response()->json($jugador);
    }

    /**
     * Elimina un jugador del sistema
     * DELETE /api/jugadores/{id}
     */
    public function destroy(string $id)
    {
        $jugador = Jugador::findOrFail($id);
        $jugador->delete();

        return response()->json(['mensaje' => 'Jugador eliminado correctamente']);
    }

    /**
     * Muestra en qué ligas está participando actualmente un jugador
     * GET /api/jugadores/{id}/ligas
     */
    public function ligas(string $id)
    {
        $jugador = Jugador::with([
            'inscripciones.torneo.liga',
            'inscripciones.torneo.disciplina',
        ])->findOrFail($id);

        return response()->json($jugador->inscripciones);
    }
}