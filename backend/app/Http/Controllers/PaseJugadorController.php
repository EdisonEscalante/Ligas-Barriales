<?php

namespace App\Http\Controllers;

use App\Models\PaseJugador;
use App\Models\InscripcionJugador;
use Illuminate\Http\Request;

/**
 * PaseJugadorController
 * Maneja los pases de jugadores entre equipos dentro de la misma liga
 * El pase requiere aprobación del equipo de origen y de la liga
 * Solo se pueden realizar pases durante una ventana de pase abierta
 */
class PaseJugadorController extends Controller
{
    /**
     * Lista todos los pases con opción de filtrar por jugador o ventana
     * GET /api/pases?jugador_id={id}&ventana_pase_id={id}
     */
    public function index(Request $request)
    {
        $pases = PaseJugador::when($request->jugador_id, function ($query, $jugador_id) {
                return $query->where('jugador_id', $jugador_id);
            })
            ->when($request->ventana_pase_id, function ($query, $ventana_pase_id) {
                return $query->where('ventana_pase_id', $ventana_pase_id);
            })
            ->with(['jugador', 'ventanaPase', 'equipoOrigen', 'equipoDestino'])
            ->get();

        return response()->json($pases);
    }

    /**
     * Solicita un pase de jugador entre equipos
     * POST /api/pases
     */
    public function store(Request $request)
    {
        $request->validate([
            'jugador_id'       => 'required|uuid|exists:jugadores,id',
            'ventana_pase_id'  => 'required|uuid|exists:ventanas_pase,id',
            'equipo_origen_id' => 'required|uuid|exists:equipos,id',
            'equipo_destino_id'=> 'required|uuid|exists:equipos,id|different:equipo_origen_id',
        ]);

        // Verificamos que la ventana de pase esté abierta
        $ventana = \App\Models\VentanaPase::findOrFail($request->ventana_pase_id);

        if ($ventana->estado !== 'abierta') {
            return response()->json([
                'mensaje' => 'La ventana de pase está cerrada. No se pueden realizar pases en este momento.'
            ], 422);
        }

        // Verificamos que el jugador pertenezca al equipo de origen
        $inscripcion = InscripcionJugador::where('jugador_id', $request->jugador_id)
            ->where('equipo_id', $request->equipo_origen_id)
            ->where('estado', 'activo')
            ->exists();

        if (!$inscripcion) {
            return response()->json([
                'mensaje' => 'El jugador no pertenece al equipo de origen o no está activo'
            ], 422);
        }

        $pase = PaseJugador::create([
            'jugador_id'        => $request->jugador_id,
            'ventana_pase_id'   => $request->ventana_pase_id,
            'equipo_origen_id'  => $request->equipo_origen_id,
            'equipo_destino_id' => $request->equipo_destino_id,
            'estado'            => 'pendiente',
        ]);

        return response()->json($pase, 201);
    }

    /**
     * Muestra un pase específico con todas sus relaciones
     * GET /api/pases/{id}
     */
    public function show(string $id)
    {
        $pase = PaseJugador::with(['jugador', 'ventanaPase', 'equipoOrigen', 'equipoDestino'])->findOrFail($id);
        return response()->json($pase);
    }

    /**
     * Aprueba o rechaza un pase (equipo origen o liga)
     * PUT /api/pases/{id}
     */
    public function update(Request $request, string $id)
    {
        $pase = PaseJugador::findOrFail($id);

        $request->validate([
            'accion'         => 'required|string|in:aprobar_equipo,aprobar_liga,rechazar',
            'aprobado_por_id'=> 'required|uuid',
            'motivo_rechazo' => 'nullable|string',
        ]);

        if ($request->accion === 'aprobar_equipo') {
            // Aprobación del equipo de origen
            $pase->update([
                'aprobacion_equipo_origen'  => now(),
                'aprobado_por_equipo_id'    => $request->aprobado_por_id,
                'estado'                    => 'aprobado_equipo',
            ]);

        } elseif ($request->accion === 'aprobar_liga') {
            // Aprobación final de la liga — se ejecuta el pase
            $pase->update([
                'aprobacion_liga'       => now(),
                'aprobado_por_liga_id'  => $request->aprobado_por_id,
                'estado'                => 'aprobado',
            ]);

            // Actualizamos la inscripción del jugador al nuevo equipo
            InscripcionJugador::where('jugador_id', $pase->jugador_id)
                ->where('equipo_id', $pase->equipo_origen_id)
                ->update(['equipo_id' => $pase->equipo_destino_id]);

        } elseif ($request->accion === 'rechazar') {
            $pase->update([
                'estado'         => 'rechazado',
                'motivo_rechazo' => $request->motivo_rechazo,
            ]);
        }

        return response()->json($pase);
    }

    /**
     * Elimina un pase del sistema
     * DELETE /api/pases/{id}
     */
    public function destroy(string $id)
    {
        $pase = PaseJugador::findOrFail($id);
        $pase->delete();

        return response()->json(['mensaje' => 'Pase eliminado correctamente']);
    }
}