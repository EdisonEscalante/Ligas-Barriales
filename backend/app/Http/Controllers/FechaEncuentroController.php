<?php

namespace App\Http\Controllers;

use App\Models\FechaEncuentro;
use App\Models\Equipo;
use App\Models\Partido;
use Illuminate\Http\Request;

/**
 * FechaEncuentroController
 * Maneja las fechas de encuentros dentro de una fase
 * Permite generar automáticamente los partidos según el formato del torneo
 */
class FechaEncuentroController extends Controller
{
    /**
     * Lista todas las fechas de encuentro de una fase
     * GET /api/fechas-encuentro?fase_id={id}
     */
    public function index(Request $request)
    {
        $fechas = FechaEncuentro::when($request->fase_id, function ($query, $fase_id) {
            return $query->where('fase_id', $fase_id);
        })
        ->with(['fase', 'partidos'])
        ->orderBy('numero_fecha')
        ->get();

        return response()->json($fechas);
    }

    /**
     * Crea una nueva fecha de encuentro dentro de una fase
     * POST /api/fechas-encuentro
     */
    public function store(Request $request)
    {
        $request->validate([
            'fase_id'          => 'required|uuid|exists:fases,id',
            'numero_fecha'     => 'required|integer|min:1',
            'fecha_programada' => 'nullable|date',
            'estado'           => 'sometimes|string|in:pendiente,en_curso,finalizada',
        ]);

        $fecha = FechaEncuentro::create($request->all());

        return response()->json($fecha, 201);
    }

    /**
     * Genera automáticamente todas las fechas y partidos de una fase
     * según el formato del torneo (liga corrida = todos contra todos)
     * POST /api/fechas-encuentro/generar
     */
    public function generar(Request $request)
    {
        $request->validate([
            'fase_id' => 'required|uuid|exists:fases,id',
        ]);

        $fase = \App\Models\Fase::with('torneo.equipos')->findOrFail($request->fase_id);
        $equipos = $fase->torneo->equipos->pluck('id')->toArray();
        $totalEquipos = count($equipos);

        if ($totalEquipos < 2) {
            return response()->json(['mensaje' => 'Se necesitan al menos 2 equipos para generar fechas'], 422);
        }

        // Algoritmo de todos contra todos (round robin)
        $fechasGeneradas = [];
        $numeroFecha = 1;

        if ($totalEquipos % 2 !== 0) {
            $equipos[] = null; // equipo ficticio para descanso
            $totalEquipos++;
        }

        $rondas = $totalEquipos - 1;

        for ($ronda = 0; $ronda < $rondas; $ronda++) {
            // Creamos la fecha de encuentro
            $fecha = FechaEncuentro::create([
                'fase_id'      => $fase->id,
                'numero_fecha' => $numeroFecha,
                'estado'       => 'pendiente',
            ]);

            // Generamos los partidos de esta fecha
            for ($i = 0; $i < $totalEquipos / 2; $i++) {
                $local    = $equipos[$i];
                $visitante = $equipos[$totalEquipos - 1 - $i];

                // Ignoramos partidos con equipo ficticio (descanso)
                if ($local !== null && $visitante !== null) {
                    Partido::create([
                        'fecha_encuentro_id'   => $fecha->id,
                        'equipo_local_id'      => $local,
                        'equipo_visitante_id'  => $visitante,
                        'estado'               => 'pendiente',
                        'marcador_local'       => 0,
                        'marcador_visitante'   => 0,
                    ]);
                }
            }

            $fechasGeneradas[] = $fecha->load('partidos');
            $numeroFecha++;

            // Rotamos los equipos (el primero queda fijo)
            $ultimo = array_pop($equipos);
            array_splice($equipos, 1, 0, [$ultimo]);
        }

        return response()->json([
            'mensaje' => 'Fechas generadas correctamente',
            'fechas'  => $fechasGeneradas,
        ], 201);
    }

    /**
     * Muestra una fecha de encuentro con sus partidos
     * GET /api/fechas-encuentro/{id}
     */
    public function show(string $id)
    {
        $fecha = FechaEncuentro::with(['fase', 'partidos.equipoLocal', 'partidos.equipoVisitante'])->findOrFail($id);
        return response()->json($fecha);
    }

    /**
     * Actualiza los datos de una fecha de encuentro
     * PUT /api/fechas-encuentro/{id}
     */
    public function update(Request $request, string $id)
    {
        $fecha = FechaEncuentro::findOrFail($id);

        $request->validate([
            'numero_fecha'     => 'sometimes|integer|min:1',
            'fecha_programada' => 'nullable|date',
            'estado'           => 'sometimes|string|in:pendiente,en_curso,finalizada',
        ]);

        $fecha->update($request->all());

        return response()->json($fecha);
    }

    /**
     * Elimina una fecha de encuentro del sistema
     * DELETE /api/fechas-encuentro/{id}
     */
    public function destroy(string $id)
    {
        $fecha = FechaEncuentro::findOrFail($id);
        $fecha->delete();

        return response()->json(['mensaje' => 'Fecha de encuentro eliminada correctamente']);
    }
}