<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EstadisticaPartido extends Model
{
    use HasUuids;

    protected $fillable = [
        'partido_id',
        'jugador_id',
        'disciplina_id',
        'tipo_estadistica',
        'valor',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
}