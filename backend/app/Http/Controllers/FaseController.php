<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use Illuminate\Http\Request;

/**
 * FaseController
 * Maneja las fases dentro de un torneo
 * Ejemplo: Fase de grupos, Semifinal, Final
 */
class FaseController extends Controller
{
    /**
     * Lista todas las fases de un torneo específico
     * GET /api/fases?torneo_id={id}
     */
    public function index(Request $request)
    {
        $fases = Fase::when($request->torneo_id, function ($query, $torneo_id) {
            return $query->where('torneo_id', $torneo_id);
        })
        ->with('torneo')
        ->orderBy('orden')
        ->get();

        return response()->json($fases);
    }

    /**
     * Crea una nueva fase dentro de un torneo
     * POST /api/fases
     */
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|uuid|exists:torneos,id',
            'nombre'    => 'required|string|max:255',
            'tipo'      => 'required|string|in:liga_corrida,grupos,eliminacion_directa,semifinal,final',
            'orden'     => 'required|integer|min:1',
            'estado'    => 'sometimes|string|in:pendiente,en_curso,finalizado',
        ]);

        $fase = Fase::create($request->all());

        return response()->json($fase, 201);
    }

    /**
     * Muestra una fase específica con sus fechas de encuentro
     * GET /api/fases/{id}
     */
    public function show(string $id)
    {
        $fase = Fase::with(['torneo', 'fechasEncuentro'])->findOrFail($id);
        return response()->json($fase);
    }

    /**
     * Actualiza los datos de una fase existente
     * PUT /api/fases/{id}
     */
    public function update(Request $request, string $id)
    {
        $fase = Fase::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'tipo'   => 'sometimes|string|in:liga_corrida,grupos,eliminacion_directa,semifinal,final',
            'orden'  => 'sometimes|integer|min:1',
            'estado' => 'sometimes|string|in:pendiente,en_curso,finalizado',
        ]);

        $fase->update($request->all());

        return response()->json($fase);
    }

    /**
     * Elimina una fase del sistema
     * DELETE /api/fases/{id}
     */
    public function destroy(string $id)
    {
        $fase = Fase::findOrFail($id);
        $fase->delete();

        return response()->json(['mensaje' => 'Fase eliminada correctamente']);
    }
}