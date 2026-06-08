<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InscripcionJugador extends Model
{
    use HasUuids;

    protected $fillable = [
        'jugador_id',
        'equipo_id',
        'torneo_id',
        'dorsal',
        'posicion',
        'estado',
        'fecha_inscripcion',
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}
