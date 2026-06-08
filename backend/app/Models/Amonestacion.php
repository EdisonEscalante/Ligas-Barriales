<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Amonestacion extends Model
{
    use HasUuids;

    protected $fillable = [
        'partido_id',
        'jugador_id',
        'tipo',
        'minuto',
        'motivo',
        'cumplida',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
