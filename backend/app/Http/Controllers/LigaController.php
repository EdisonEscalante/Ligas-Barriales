<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use Illuminate\Http\Request;

/**
 * LigaController
 * Maneja todas las operaciones CRUD para las ligas barriales
 */
class LigaController extends Controller
{
    /**
     * Lista todas las ligas registradas en el sistema
     * GET /api/ligas
     */
    public function index()
    {
        $ligas = Liga::all();
        return response()->json($ligas);
    }

    /**
     * Crea una nueva liga
     * POST /api/ligas
     */
    public function store(Request $request)
    {
        // Validamos los datos que llegan en la petición
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'fecha_fundacion'  => 'nullable|date',
            'provincia'        => 'required|string|max:100',
            'ciudad'           => 'required|string|max:100',
            'canton'           => 'required|string|max:100',
            'parroquia'        => 'required|string|max:100',
            'descripcion'      => 'nullable|string',
            'escudo_url'       => 'nullable|string|max:255',
        ]);

        // Creamos la liga con los datos validados
        $liga = Liga::create($request->all());

        return response()->json($liga, 201);
    }

    /**
     * Muestra una liga específica con sus relaciones
     * GET /api/ligas/{id}
     */
    public function show(string $id)
    {
        // Cargamos la liga con sus disciplinas, temporadas y equipos
        $liga = Liga::with(['disciplinas', 'temporadas', 'equipos'])->findOrFail($id);
        return response()->json($liga);
    }

    /**
     * Actualiza los datos de una liga existente
     * PUT /api/ligas/{id}
     */
    public function update(Request $request, string $id)
    {
        $liga = Liga::findOrFail($id);

        $request->validate([
            'nombre'           => 'sometimes|string|max:255',
            'fecha_fundacion'  => 'nullable|date',
            'provincia'        => 'sometimes|string|max:100',
            'ciudad'           => 'sometimes|string|max:100',
            'canton'           => 'sometimes|string|max:100',
            'parroquia'        => 'sometimes|string|max:100',
            'descripcion'      => 'nullable|string',
            'escudo_url'       => 'nullable|string|max:255',
        ]);

        $liga->update($request->all());

        return response()->json($liga);
    }

    /**
     * Elimina una liga del sistema
     * DELETE /api/ligas/{id}
     */
    public function destroy(string $id)
    {
        $liga = Liga::findOrFail($id);
        $liga->delete();

        return response()->json(['mensaje' => 'Liga eliminada correctamente']);
    }
}