<?php

namespace App\Http\Controllers;

use App\Models\EstadisticaPartido;
use Illuminate\Http\Request;

/**
 * EstadisticaPartidoController
 * Maneja las estadísticas individuales de cada jugador por partido
 * Las estadísticas varían según la disciplina:
 * - Fútbol: goles, asistencias, tarjetas
 * - Vóley: puntos, aces, bloqueos
 * - Básquet: puntos, rebotes, asistencias, faltas
 */
class EstadisticaPartidoController extends Controller
{
    /**
     * Lista las estadísticas con opción de filtrar por partido o jugador
     * GET /api/estadisticas?partido_id={id}&jugador_id={id}
     */
    public function index(Request $request)
    {
        $estadisticas = EstadisticaPartido::when($request->partido_id, function ($query, $partido_id) {
                return $query->where('partido_id', $partido_id);
            })
            ->when($request->jugador_id, function ($query, $jugador_id) {
                return $query->where('jugador_id', $jugador_id);
            })
            ->with(['partido', 'jugador', 'disciplina'])
            ->get();

        return response()->json($estadisticas);
    }

    /**
     * Registra una estadística de un jugador en un partido
     * POST /api/estadisticas
     */
    public function store(Request $request)
    {
        $request->validate([
            'partido_id'      => 'required|uuid|exists:partidos,id',
            'jugador_id'      => 'required|uuid|exists:jugadores,id',
            'disciplina_id'   => 'required|uuid|exists:disciplinas,id',
            'tipo_estadistica'=> 'required|string|max:100',
            'valor'           => 'required|numeric|min:0',
        ]);

        $estadistica = EstadisticaPartido::create($request->all());

        return response()->json($estadistica, 201);
    }

    /**
     * Muestra una estadística específica
     * GET /api/estadisticas/{id}
     */
    public function show(string $id)
    {
        $estadistica = EstadisticaPartido::with(['partido', 'jugador', 'disciplina'])->findOrFail($id);
        return response()->json($estadistica);
    }

    /**
     * Actualiza una estadística existente
     * PUT /api/estadisticas/{id}
     */
    public function update(Request $request, string $id)
    {
        $estadistica = EstadisticaPartido::findOrFail($id);

        $request->validate([
            'tipo_estadistica' => 'sometimes|string|max:100',
            'valor'            => 'sometimes|numeric|min:0',
        ]);

        $estadistica->update($request->all());

        return response()->json($estadistica);
    }

    /**
     * Elimina una estadística del sistema
     * DELETE /api/estadisticas/{id}
     */
    public function destroy(string $id)
    {
        $estadistica = EstadisticaPartido::findOrFail($id);
        $estadistica->delete();

        return response()->json(['mensaje' => 'Estadística eliminada correctamente']);
    }
}