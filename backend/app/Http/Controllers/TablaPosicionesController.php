<?php

namespace App\Http\Controllers;

use App\Models\TablaPosiciones;
use Illuminate\Http\Request;

/**
 * TablaPosicionesController
 * Maneja la tabla de posiciones de cada torneo
 * La tabla se actualiza automáticamente al registrar resultados de partidos
 * pero también se puede consultar y ajustar manualmente si es necesario
 */
class TablaPosicionesController extends Controller
{
    /**
     * Muestra la tabla de posiciones de un torneo ordenada por puntos
     * GET /api/tabla-posiciones?torneo_id={id}
     */
    public function index(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|uuid|exists:torneos,id',
        ]);

        $tabla = TablaPosiciones::where('torneo_id', $request->torneo_id)
            ->with(['equipo', 'torneo'])
            ->orderByDesc('puntos')
            ->orderByDesc('goles_favor')
            ->orderBy('goles_contra')
            ->get();

        return response()->json($tabla);
    }

    /**
     * Crea un registro inicial en la tabla para un equipo
     * POST /api/tabla-posiciones
     */
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|uuid|exists:torneos,id',
            'equipo_id' => 'required|uuid|exists:equipos,id',
        ]);

        // Verificamos que el equipo no tenga ya un registro en este torneo
        $existe = TablaPosiciones::where('torneo_id', $request->torneo_id)
            ->where('equipo_id', $request->equipo_id)
            ->exists();

        if ($existe) {
            return response()->json([
                'mensaje' => 'El equipo ya tiene un registro en la tabla de este torneo'
            ], 422);
        }

        $registro = TablaPosiciones::create([
            'torneo_id'        => $request->torneo_id,
            'equipo_id'        => $request->equipo_id,
            'partidos_jugados' => 0,
            'ganados'          => 0,
            'empatados'        => 0,
            'perdidos'         => 0,
            'puntos'           => 0,
            'goles_favor'      => 0,
            'goles_contra'     => 0,
        ]);

        return response()->json($registro, 201);
    }

    /**
     * Muestra el registro de un equipo específico en la tabla
     * GET /api/tabla-posiciones/{id}
     */
    public function show(string $id)
    {
        $registro = TablaPosiciones::with(['equipo', 'torneo'])->findOrFail($id);
        return response()->json($registro);
    }

    /**
     * Ajuste manual de la tabla de posiciones si es necesario
     * PUT /api/tabla-posiciones/{id}
     */
    public function update(Request $request, string $id)
    {
        $registro = TablaPosiciones::findOrFail($id);

        $request->validate([
            'partidos_jugados' => 'sometimes|integer|min:0',
            'ganados'          => 'sometimes|integer|min:0',
            'empatados'        => 'sometimes|integer|min:0',
            'perdidos'         => 'sometimes|integer|min:0',
            'puntos'           => 'sometimes|integer|min:0',
            'goles_favor'      => 'sometimes|integer|min:0',
            'goles_contra'     => 'sometimes|integer|min:0',
        ]);

        $registro->update($request->all());

        return response()->json($registro);
    }

    /**
     * Elimina un registro de la tabla de posiciones
     * DELETE /api/tabla-posiciones/{id}
     */
    public function destroy(string $id)
    {
        $registro = TablaPosiciones::findOrFail($id);
        $registro->delete();

        return response()->json(['mensaje' => 'Registro eliminado correctamente']);
    }
}