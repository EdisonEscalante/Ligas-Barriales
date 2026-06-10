<?php

namespace App\Http\Controllers;

use App\Models\InscripcionJugador;
use Illuminate\Http\Request;

/**
 * InscripcionJugadorController
 * Maneja la inscripción de jugadores en equipos dentro de un torneo
 * Controla el estado del jugador (activo, suspendido, inhabilitado)
 */
class InscripcionJugadorController extends Controller
{
    /**
     * Lista todas las inscripciones con opción de filtrar por equipo o torneo
     * GET /api/inscripciones?equipo_id={id}&torneo_id={id}
     */
    public function index(Request $request)
    {
        $inscripciones = InscripcionJugador::when($request->equipo_id, function ($query, $equipo_id) {
                return $query->where('equipo_id', $equipo_id);
            })
            ->when($request->torneo_id, function ($query, $torneo_id) {
                return $query->where('torneo_id', $torneo_id);
            })
            ->with(['jugador', 'equipo', 'torneo'])
            ->get();

        return response()->json($inscripciones);
    }

    /**
     * Inscribe un jugador en un equipo dentro de un torneo
     * POST /api/inscripciones
     */
    public function store(Request $request)
    {
        $request->validate([
            'jugador_id'       => 'required|uuid|exists:jugadores,id',
            'equipo_id'        => 'required|uuid|exists:equipos,id',
            'torneo_id'        => 'required|uuid|exists:torneos,id',
            'dorsal'           => 'nullable|string|max:10',
            'posicion'         => 'nullable|string|max:50',
            'estado'           => 'sometimes|string|in:activo,suspendido,inhabilitado',
            'fecha_inscripcion'=> 'required|date',
        ]);

        // Verificamos que el jugador no esté ya inscrito en el mismo torneo
        $existe = InscripcionJugador::where('jugador_id', $request->jugador_id)
            ->where('torneo_id', $request->torneo_id)
            ->exists();

        if ($existe) {
            return response()->json([
                'mensaje' => 'El jugador ya está inscrito en este torneo'
            ], 422);
        }

        $inscripcion = InscripcionJugador::create($request->all());

        return response()->json($inscripcion, 201);
    }

    /**
     * Muestra una inscripción específica
     * GET /api/inscripciones/{id}
     */
    public function show(string $id)
    {
        $inscripcion = InscripcionJugador::with(['jugador', 'equipo', 'torneo'])->findOrFail($id);
        return response()->json($inscripcion);
    }

    /**
     * Actualiza los datos de una inscripción
     * PUT /api/inscripciones/{id}
     */
    public function update(Request $request, string $id)
    {
        $inscripcion = InscripcionJugador::findOrFail($id);

        $request->validate([
            'dorsal'   => 'nullable|string|max:10',
            'posicion' => 'nullable|string|max:50',
            'estado'   => 'sometimes|string|in:activo,suspendido,inhabilitado',
        ]);

        $inscripcion->update($request->all());

        return response()->json($inscripcion);
    }

    /**
     * Elimina una inscripción del sistema
     * DELETE /api/inscripciones/{id}
     */
    public function destroy(string $id)
    {
        $inscripcion = InscripcionJugador::findOrFail($id);
        $inscripcion->delete();

        return response()->json(['mensaje' => 'Inscripción eliminada correctamente']);
    }
}