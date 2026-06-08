<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Fase extends Model
{
    use HasUuids;

    protected $fillable = [
        'torneo_id',
        'nombre',
        'tipo',
        'orden',
        'estado',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function fechasEncuentro()
    {
        return $this->hasMany(FechaEncuentro::class);
    }
}