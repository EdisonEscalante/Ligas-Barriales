<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FechaEncuentro extends Model
{
    use HasUuids;

    protected $fillable = [
        'fase_id',
        'numero_fecha',
        'fecha_programada',
        'estado',
    ];

    public function fase()
    {
        return $this->belongsTo(Fase::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}
