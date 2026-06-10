<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;

/**
 * DisciplinaController
 * Maneja todas las operaciones CRUD para las disciplinas deportivas
 * Ejemplo: Fútbol, Vóley, Básquet
 */
class DisciplinaController extends Controller
{
    /**
     * Lista todas las disciplinas registradas
     * GET /api/disciplinas
     */
    public function index()
    {
        $disciplinas = Disciplina::all();
        return response()->json($disciplinas);
    }

    /**
     * Crea una nueva disciplina deportiva
     * POST /api/disciplinas
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'required|string|max:100',
        ]);

        $disciplina = Disciplina::create($request->all());

        return response()->json($disciplina, 201);
    }

    /**
     * Muestra una disciplina específica con sus torneos
     * GET /api/disciplinas/{id}
     */
    public function show(string $id)
    {
        $disciplina = Disciplina::with(['torneos'])->findOrFail($id);
        return response()->json($disciplina);
    }

    /**
     * Actualiza los datos de una disciplina existente
     * PUT /api/disciplinas/{id}
     */
    public function update(Request $request, string $id)
    {
        $disciplina = Disciplina::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'tipo'   => 'sometimes|string|max:100',
        ]);

        $disciplina->update($request->all());

        return response()->json($disciplina);
    }

    /**
     * Elimina una disciplina del sistema
     * DELETE /api/disciplinas/{id}
     */
    public function destroy(string $id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();

        return response()->json(['mensaje' => 'Disciplina eliminada correctamente']);
    }
}