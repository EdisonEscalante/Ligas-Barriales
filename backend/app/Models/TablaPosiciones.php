<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TablaPosiciones extends Model
{
    use HasUuids;

    protected $fillable = [
        'torneo_id',
        'equipo_id',
        'partidos_jugados',
        'ganados',
        'empatados',
        'perdidos',
        'puntos',
        'goles_favor',
        'goles_contra',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablaPosiciones extends Model
{
    //
}
