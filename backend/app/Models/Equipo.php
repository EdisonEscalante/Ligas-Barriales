<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Equipo extends Model
{
    use HasUuids;

    protected $fillable = [
        'liga_id',
        'nombre',
        'escudo_url',
        'color_principal',
    ];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'equipo_torneo');
    }

    public function jugadores()
    {
        return $this->hasMany(InscripcionJugador::class);
    }

    public function partidosLocal()
    {
        return $this->hasMany(Partido::class, 'equipo_local_id');
    }

    public function partidosVisitante()
    {
        return $this->hasMany(Partido::class, 'equipo_visitante_id');
    }

    public function tablaPosiciones()
    {
        return $this->hasMany(TablaPosiciones::class);
    }
}