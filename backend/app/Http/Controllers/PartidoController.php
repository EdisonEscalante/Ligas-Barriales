<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\TablaPosiciones;
use Illuminate\Http\Request;

/**
 * PartidoController
 * Maneja los partidos dentro de cada fecha de encuentro
 * También actualiza automáticamente la tabla de posiciones al registrar resultados
 */
class PartidoController extends Controller
{
    /**
     * Lista todos los partidos con opción de filtrar por fecha de encuentro
     * GET /api/partidos?fecha_encuentro_id={id}
     */
    public function index(Request $request)
    {
        $partidos = Partido::when($request->fecha_encuentro_id, function ($query, $fecha_encuentro_id) {
            return $query->where('fecha_encuentro_id', $fecha_encuentro_id);
        })
        ->with(['fechaEncuentro', 'equipoLocal', 'equipoVisitante'])
        ->get();

        return response()->json($partidos);
    }

    /**
     * Crea un nuevo partido dentro de una fecha de encuentro
     * POST /api/partidos
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_encuentro_id'  => 'required|uuid|exists:fechas_encuentro,id',
            'equipo_local_id'     => 'required|uuid|exists:equipos,id',
            'equipo_visitante_id' => 'required|uuid|exists:equipos,id|different:equipo_local_id',
            'hora'                => 'nullable|date_format:H:i',
            'cancha'              => 'nullable|string|max:255',
            'estado'              => 'sometimes|string|in:pendiente,en_curso,finalizado,suspendido',
        ]);

        $partido = Partido::create($request->all());

        return response()->json($partido, 201);
    }

    /**
     * Muestra un partido específico con estadísticas y amonestaciones
     * GET /api/partidos/{id}
     */
    public function show(string $id)
    {
        $partido = Partido::with([
            'fechaEncuentro.fase.torneo',
            'equipoLocal',
            'equipoVisitante',
            'estadisticas.jugador',
            'amonestaciones.jugador',
        ])->findOrFail($id);

        return response()->json($partido);
    }

    /**
     * Actualiza el resultado de un partido y recalcula la tabla de posiciones
     * PUT /api/partidos/{id}
     */
    public function update(Request $request, string $id)
    {
        $partido = Partido::findOrFail($id);

        $request->validate([
            'hora'                => 'nullable|date_format:H:i',
            'cancha'              => 'nullable|string|max:255',
            'estado'              => 'sometimes|string|in:pendiente,en_curso,finalizado,suspendido',
            'marcador_local'      => 'sometimes|integer|min:0',
            'marcador_visitante'  => 'sometimes|integer|min:0',
        ]);

        $partido->update($request->all());

        // Si el partido finalizó actualizamos la tabla de posiciones
        if ($request->estado === 'finalizado') {
            $this->actualizarTablaPosiciones($partido);
        }

        return response()->json($partido);
    }

    /**
     * Actualiza automáticamente la tabla de posiciones al finalizar un partido
     */
    private function actualizarTablaPosiciones(Partido $partido)
    {
        // Obtenemos el torneo desde la fase
        $torneo_id = $partido->fechaEncuentro->fase->torneo_id;

        $local     = $partido->equipo_local_id;
        $visitante = $partido->equipo_visitante_id;
        $golesL    = $partido->marcador_local;
        $golesV    = $partido->marcador_visitante;

        // Determinamos el resultado
        if ($golesL > $golesV) {
            // Gana el local
            $this->sumarPuntos($torneo_id, $local, 3, 1, 0, 0, $golesL, $golesV);
            $this->sumarPuntos($torneo_id, $visitante, 0, 0, 0, 1, $golesV, $golesL);
        } elseif ($golesL < $golesV) {
            // Gana el visitante
            $this->sumarPuntos($torneo_id, $local, 0, 0, 0, 1, $golesL, $golesV);
            $this->sumarPuntos($torneo_id, $visitante, 3, 1, 0, 0, $golesV, $golesL);
        } else {
            // Empate
            $this->sumarPuntos($torneo_id, $local, 1, 0, 1, 0, $golesL, $golesV);
            $this->sumarPuntos($torneo_id, $visitante, 1, 0, 1, 0, $golesV, $golesL);
        }
    }

    /**
     * Suma los puntos y estadísticas a un equipo en la tabla de posiciones
     */
    private function sumarPuntos($torneo_id, $equipo_id, $puntos, $ganados, $empatados, $perdidos, $gf, $gc)
    {
        $registro = TablaPosiciones::firstOrCreate(
            ['torneo_id' => $torneo_id, 'equipo_id' => $equipo_id],
            ['partidos_jugados' => 0, 'ganados' => 0, 'empatados' => 0, 'perdidos' => 0,
             'puntos' => 0, 'goles_favor' => 0, 'goles_contra' => 0]
        );

        $registro->increment('partidos_jugados');
        $registro->increment('puntos', $puntos);
        $registro->increment('ganados', $ganados);
        $registro->increment('empatados', $empatados);
        $registro->increment('perdidos', $perdidos);
        $registro->increment('goles_favor', $gf);
        $registro->increment('goles_contra', $gc);
    }

    /**
     * Elimina un partido del sistema
     * DELETE /api/partidos/{id}
     */
    public function destroy(string $id)
    {
        $partido = Partido::findOrFail($id);
        $partido->delete();

        return response()->json(['mensaje' => 'Partido eliminado correctamente']);
    }
}