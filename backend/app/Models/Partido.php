<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Partido extends Model
{
    use HasUuids;

    protected $fillable = [
        'fecha_encuentro_id',
        'equipo_local_id',
        'equipo_visitante_id',
        'hora',
        'cancha',
        'estado',
        'marcador_local',
        'marcador_visitante',
    ];

    public function fechaEncuentro()
    {
        return $this->belongsTo(FechaEncuentro::class);
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function estadisticas()
    {
        return $this->hasMany(EstadisticaPartido::class);
    }

    public function amonestaciones()
    {
        return $this->hasMany(Amonestacion::class);
    }
}