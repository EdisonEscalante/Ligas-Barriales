<?php

namespace App\Http\Controllers;

use App\Models\Amonestacion;
use App\Models\InscripcionJugador;
use Illuminate\Http\Request;

/**
 * AmonestacionController
 * Maneja las amonestaciones de los jugadores por partido
 * Controla automáticamente si un jugador está habilitado
 * según la acumulación de tarjetas amarillas y rojas
 */
class AmonestacionController extends Controller
{
    /**
     * Lista las amonestaciones con opción de filtrar por partido o jugador
     * GET /api/amonestaciones?partido_id={id}&jugador_id={id}
     */
    public function index(Request $request)
    {
        $amonestaciones = Amonestacion::when($request->partido_id, function ($query, $partido_id) {
                return $query->where('partido_id', $partido_id);
            })
            ->when($request->jugador_id, function ($query, $jugador_id) {
                return $query->where('jugador_id', $jugador_id);
            })
            ->with(['partido', 'jugador'])
            ->get();

        return response()->json($amonestaciones);
    }

    /**
     * Registra una amonestación y verifica si el jugador queda suspendido
     * POST /api/amonestaciones
     */
    public function store(Request $request)
    {
        $request->validate([
            'partido_id' => 'required|uuid|exists:partidos,id',
            'jugador_id' => 'required|uuid|exists:jugadores,id',
            'tipo'       => 'required|string|in:amarilla,roja,doble_amarilla',
            'minuto'     => 'nullable|integer|min:1|max:120',
            'motivo'     => 'nullable|string',
        ]);

        $amonestacion = Amonestacion::create($request->all());

        // Verificamos si el jugador debe ser suspendido automáticamente
        $this->verificarSuspension($request->jugador_id, $request->partido_id, $request->tipo);

        return response()->json($amonestacion, 201);
    }

    /**
     * Verifica si el jugador acumuló tarjetas suficientes para ser suspendido
     * Regla: 3 amarillas = 1 partido de suspensión, roja = 1 partido mínimo
     */
    private function verificarSuspension($jugador_id, $partido_id, $tipo)
    {
        // Obtenemos el torneo desde el partido
        $partido  = \App\Models\Partido::with('fechaEncuentro.fase.torneo')->findOrFail($partido_id);
        $torneo_id = $partido->fechaEncuentro->fase->torneo_id;

        // Contamos tarjetas amarillas no cumplidas en este torneo
        $amarillas = Amonestacion::where('jugador_id', $jugador_id)
            ->where('cumplida', false)
            ->whereIn('tipo', ['amarilla'])
            ->whereHas('partido.fechaEncuentro.fase', function ($q) use ($torneo_id) {
                $q->where('torneo_id', $torneo_id);
            })
            ->count();

        // Si acumuló 3 amarillas o recibió roja, lo suspendemos
        if ($amarillas >= 3 || in_array($tipo, ['roja', 'doble_amarilla'])) {
            InscripcionJugador::where('jugador_id', $jugador_id)
                ->whereHas('torneo', function ($q) use ($torneo_id) {
                    $q->where('id', $torneo_id);
                })
                ->update(['estado' => 'suspendido']);
        }
    }

    /**
     * Muestra una amonestación específica
     * GET /api/amonestaciones/{id}
     */
    public function show(string $id)
    {
        $amonestacion = Amonestacion::with(['partido', 'jugador'])->findOrFail($id);
        return response()->json($amonestacion);
    }

    /**
     * Actualiza una amonestación existente
     * PUT /api/amonestaciones/{id}
     */
    public function update(Request $request, string $id)
    {
        $amonestacion = Amonestacion::findOrFail($id);

        $request->validate([
            'tipo'    => 'sometimes|string|in:amarilla,roja,doble_amarilla',
            'minuto'  => 'nullable|integer|min:1|max:120',
            'motivo'  => 'nullable|string',
            'cumplida'=> 'sometimes|boolean',
        ]);

        $amonestacion->update($request->all());

        // Si se marca como cumplida rehabilitamos al jugador
        if ($request->cumplida) {
            $partido   = \App\Models\Partido::with('fechaEncuentro.fase')->findOrFail($amonestacion->partido_id);
            $torneo_id = $partido->fechaEncuentro->fase->torneo_id;

            InscripcionJugador::where('jugador_id', $amonestacion->jugador_id)
                ->whereHas('torneo', function ($q) use ($torneo_id) {
                    $q->where('id', $torneo_id);
                })
                ->update(['estado' => 'activo']);
        }

        return response()->json($amonestacion);
    }

    /**s
     * Elimina una amonestación del sistema
     * DELETE /api/amonestaciones/{id}
     */
    public function destroy(string $id)
    {
        $amonestacion = Amonestacion::findOrFail($id);
        $amonestacion->delete();

        return response()->json(['mensaje' => 'Amonestación eliminada correctamente']);
    }
}