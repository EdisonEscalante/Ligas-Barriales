<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use Illuminate\Http\Request;

/**
 * TemporadaController
 * Maneja todas las operaciones CRUD para las temporadas de cada liga
 * Ejemplo: Temporada 2024, Temporada 2025
 */
class TemporadaController extends Controller
{
    /**
     * Lista todas las temporadas de una liga específica
     * GET /api/temporadas?liga_id={id}
     */
    public function index(Request $request)
    {
        // Si viene liga_id filtramos por liga, sino devolvemos todas
        $temporadas = Temporada::when($request->liga_id, function ($query, $liga_id) {
            return $query->where('liga_id', $liga_id);
        })->with('liga')->get();

        return response()->json($temporadas);
    }

    /**
     * Crea una nueva temporada para una liga
     * POST /api/temporadas
     */
    public function store(Request $request)
    {
        $request->validate([
            'liga_id'      => 'required|uuid|exists:ligas,id',
            'nombre'       => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'estado'       => 'sometimes|string|in:activa,cerrada',
        ]);

        $temporada = Temporada::create($request->all());

        return response()->json($temporada, 201);
    }

    /**
     * Muestra una temporada específica con sus torneos y ventanas de pase
     * GET /api/temporadas/{id}
     */
    public function show(string $id)
    {
        $temporada = Temporada::with(['liga', 'torneos', 'ventanasPase'])->findOrFail($id);
        return response()->json($temporada);
    }

    /**
     * Actualiza los datos de una temporada existente
     * PUT /api/temporadas/{id}
     */
    public function update(Request $request, string $id)
    {
        $temporada = Temporada::findOrFail($id);

        $request->validate([
            'nombre'       => 'sometimes|string|max:255',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin'    => 'sometimes|date|after:fecha_inicio',
            'estado'       => 'sometimes|string|in:activa,cerrada',
        ]);

        $temporada->update($request->all());

        return response()->json($temporada);
    }

    /**
     * Elimina una temporada del sistema
     * DELETE /api/temporadas/{id}
     */
    public function destroy(string $id)
    {
        $temporada = Temporada::findOrFail($id);
        $temporada->delete();

        return response()->json(['mensaje' => 'Temporada eliminada correctamente']);
    }
}