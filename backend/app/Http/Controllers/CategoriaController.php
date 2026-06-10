<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

/**
 * CategoriaController
 * Maneja todas las operaciones CRUD para las categorías
 * Ejemplo: Máxima, Juvenil, Femenina
 */
class CategoriaController extends Controller
{
    /**
     * Lista todas las categorías registradas
     * GET /api/categorias
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    /**
     * Crea una nueva categoría
     * POST /api/categorias
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json($categoria, 201);
    }

    /**
     * Muestra una categoría específica con sus torneos
     * GET /api/categorias/{id}
     */
    public function show(string $id)
    {
        $categoria = Categoria::with(['torneos'])->findOrFail($id);
        return response()->json($categoria);
    }

    /**
     * Actualiza los datos de una categoría existente
     * PUT /api/categorias/{id}
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
        ]);

        $categoria->update($request->all());

        return response()->json($categoria);
    }

    /**
     * Elimina una categoría del sistema
     * DELETE /api/categorias/{id}
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json(['mensaje' => 'Categoría eliminada correctamente']);
    }
}