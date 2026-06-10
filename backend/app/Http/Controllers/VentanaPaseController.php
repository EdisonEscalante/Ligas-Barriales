<?php

namespace App\Http\Controllers;

use App\Models\VentanaPase;
use Illuminate\Http\Request;

/**
 * VentanaPaseController
 * Maneja las ventanas de transferencia de jugadores
 * Solo durante una ventana abierta se pueden realizar pases entre equipos
 */
class VentanaPaseController extends Controller
{
    /**
     * Lista todas las ventanas de pase de una temporada
     * GET /api/ventanas-pase?temporada_id={id}
     */
    public function index(Request $request)
    {
        $ventanas = VentanaPase::when($request->temporada_id, function ($query, $temporada_id) {
            return $query->where('temporada_id', $temporada_id);
        })->with('temporada')->get();

        return response()->json($ventanas);
    }

    /**
     * Crea una nueva ventana de pase para una temporada
     * POST /api/ventanas-pase
     */
    public function store(Request $request)
    {
        $request->validate([
            'temporada_id'   => 'required|uuid|exists:temporadas,id',
            'fecha_apertura' => 'required|date',
            'fecha_cierre'   => 'required|date|after:fecha_apertura',
            'estado'         => 'sometimes|string|in:abierta,cerrada',
        ]);

        $ventana = VentanaPase::create($request->all());

        return response()->json($ventana, 201);
    }

    /**
     * Muestra una ventana de pase específica con sus pases registrados
     * GET /api/ventanas-pase/{id}
     */
    public function show(string $id)
    {
        $ventana = VentanaPase::with(['temporada', 'pases'])->findOrFail($id);
        return response()->json($ventana);
    }

    /**
     * Actualiza los datos de una ventana de pase
     * PUT /api/ventanas-pase/{id}
     */
    public function update(Request $request, string $id)
    {
        $ventana = VentanaPase::findOrFail($id);

        $request->validate([
            'fecha_apertura' => 'sometimes|date',
            'fecha_cierre'   => 'sometimes|date|after:fecha_apertura',
            'estado'         => 'sometimes|string|in:abierta,cerrada',
        ]);

        $ventana->update($request->all());

        return response()->json($ventana);
    }

    /**
     * Elimina una ventana de pase del sistema
     * DELETE /api/ventanas-pase/{id}
     */
    public function destroy(string $id)
    {
        $ventana = VentanaPase::findOrFail($id);
        $ventana->delete();

        return response()->json(['mensaje' => 'Ventana de pase eliminada correctamente']);
    }
}