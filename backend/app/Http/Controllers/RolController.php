<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

/**
 * RolController
 * Maneja los roles del sistema
 * Ejemplos: super_admin, admin_liga, admin_equipo,
 * calificador, sancionador, delegado
 */
class RolController extends Controller
{
    /**
     * Lista todos los roles del sistema
     * GET /api/roles
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles);
    }

    /**
     * Crea un nuevo rol en el sistema
     * POST /api/roles
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:roles,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $rol = Rol::create($request->all());

        return response()->json($rol, 201);
    }

    /**
     * Muestra un rol específico con sus usuarios asignados
     * GET /api/roles/{id}
     */
    public function show(string $id)
    {
        $rol = Rol::with(['usuarios'])->findOrFail($id);
        return response()->json($rol);
    }

    /**
     * Actualiza los datos de un rol existente
     * PUT /api/roles/{id}
     */
    public function update(Request $request, string $id)
    {
        $rol = Rol::findOrFail($id);

        $request->validate([
            'nombre'      => 'sometimes|string|max:100|unique:roles,nombre,' . $id,
            'descripcion' => 'nullable|string',
        ]);

        $rol->update($request->all());

        return response()->json($rol);
    }

    /**
     * Elimina un rol del sistema
     * DELETE /api/roles/{id}
     */
    public function destroy(string $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json(['mensaje' => 'Rol eliminado correctamente']);
    }
}