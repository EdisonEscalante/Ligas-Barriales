<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Http\Request;

/**
 * TorneoController
 * Maneja todas las operaciones CRUD para los torneos
 * Un torneo pertenece a una temporada, liga, disciplina y categoría
 */
class TorneoController extends Controller
{
    /**
     * Lista todos los torneos con opción de filtrar por liga o temporada
     * GET /api/torneos?liga_id={id}&temporada_id={id}
     */
    public function index(Request $request)
    {
        $torneos = Torneo::when($request->liga_id, function ($query, $liga_id) {
                return $query->where('liga_id', $liga_id);
            })
            ->when($request->temporada_id, function ($query, $temporada_id) {
                return $query->where('temporada_id', $temporada_id);
            })
            ->with(['liga', 'temporada', 'disciplina', 'categoria'])
            ->get();

        return response()->json($torneos);
    }

    /**
     * Crea un nuevo torneo dentro de una temporada
     * POST /api/torneos
     */
    public function store(Request $request)
    {
        $request->validate([
            'temporada_id'  => 'required|uuid|exists:temporadas,id',
            'liga_id'       => 'required|uuid|exists:ligas,id',
            'disciplina_id' => 'required|uuid|exists:disciplinas,id',
            'categoria_id'  => 'required|uuid|exists:categorias,id',
            'nombre'        => 'required|string|max:255',
            'tipo_formato'  => 'required|string|in:liga_corrida,eliminacion_directa,grupos_eliminatorias',
            'estado'        => 'sometimes|string|in:pendiente,en_curso,finalizado',
            'fecha_inicio'  => 'nullable|date',
            'fecha_fin'     => 'nullable|date|after:fecha_inicio',
        ]);

        $torneo = Torneo::create($request->all());

        return response()->json($torneo, 201);
    }

    /**
     * Muestra un torneo específico con todas sus relaciones
     * GET /api/torneos/{id}
     */
    public function show(string $id)
    {
        $torneo = Torneo::with([
            'liga',
            'temporada',
            'disciplina',
            'categoria',
            'fases',
            'equipos',
            'tablaPosiciones'
        ])->findOrFail($id);

        return response()->json($torneo);
    }

    /**
     * Actualiza los datos de un torneo existente
     * PUT /api/torneos/{id}
     */
    public function update(Request $request, string $id)
    {
        $torneo = Torneo::findOrFail($id);

        $request->validate([
            'nombre'       => 'sometimes|string|max:255',
            'tipo_formato' => 'sometimes|string|in:liga_corrida,eliminacion_directa,grupos_eliminatorias',
            'estado'       => 'sometimes|string|in:pendiente,en_curso,finalizado',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after:fecha_inicio',
        ]);

        $torneo->update($request->all());

        return response()->json($torneo);
    }

    /**
     * Elimina un torneo del sistema
     * DELETE /api/torneos/{id}
     */
    public function destroy(string $id)
    {
        $torneo = Torneo::findOrFail($id);
        $torneo->delete();

        return response()->json(['mensaje' => 'Torneo eliminado correctamente']);
    }
}