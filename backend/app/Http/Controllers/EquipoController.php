<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

/**
 * EquipoController
 * Maneja todas las operaciones CRUD para los equipos de cada liga
 * Un equipo pertenece a una sola liga pero puede participar en varios torneos
 */
class EquipoController extends Controller
{
    /**
     * Lista todos los equipos, con opción de filtrar por liga
     * GET /api/equipos?liga_id={id}
     */
    public function index(Request $request)
    {
        $equipos = Equipo::when($request->liga_id, function ($query, $liga_id) {
            return $query->where('liga_id', $liga_id);
        })->with('liga')->get();

        return response()->json($equipos);
    }

    /**
     * Crea un nuevo equipo dentro de una liga
     * POST /api/equipos
     */
    public function store(Request $request)
    {
        $request->validate([
            'liga_id'         => 'required|uuid|exists:ligas,id',
            'nombre'          => 'required|string|max:255',
            'escudo_url'      => 'nullable|string|max:255',
            'color_principal' => 'nullable|string|max:50',
        ]);

        $equipo = Equipo::create($request->all());

        return response()->json($equipo, 201);
    }

    /**
     * Muestra un equipo específico con sus jugadores y torneos
     * GET /api/equipos/{id}
     */
    public function show(string $id)
    {
        $equipo = Equipo::with(['liga', 'torneos', 'jugadores'])->findOrFail($id);
        return response()->json($equipo);
    }

    /**
     * Actualiza los datos de un equipo existente
     * PUT /api/equipos/{id}
     */
    public function update(Request $request, string $id)
    {
        $equipo = Equipo::findOrFail($id);

        $request->validate([
            'nombre'          => 'sometimes|string|max:255',
            'escudo_url'      => 'nullable|string|max:255',
            'color_principal' => 'nullable|string|max:50',
        ]);

        $equipo->update($request->all());

        return response()->json($equipo);
    }

    /**
     * Elimina un equipo del sistema
     * DELETE /api/equipos/{id}
     */
    public function destroy(string $id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return response()->json(['mensaje' => 'Equipo eliminado correctamente']);
    }
}