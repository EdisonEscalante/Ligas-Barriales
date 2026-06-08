<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Jugador extends Model
{
    use HasUuids;

    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'foto_url',
        'telefono',
    ];

    public function inscripciones()
    {
        return $this->hasMany(InscripcionJugador::class);
    }

    public function estadisticas()
    {
        return $this->hasMany(EstadisticaPartido::class);
    }

    public function amonestaciones()
    {
        return $this->hasMany(Amonestacion::class);
    }

    public function pases()
    {
        return $this->hasMany(PaseJugador::class);
    }
}