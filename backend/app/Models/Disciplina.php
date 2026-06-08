<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Disciplina extends Model
{
    use HasUuids;

    protected $fillable = [
        'nombre',
        'tipo',
    ];

    public function ligas()
    {
        return $this->belongsToMany(Liga::class, 'liga_disciplina');
    }

    public function torneos()
    {
        return $this->hasMany(Torneo::class);
    }

    public function estadisticas()
    {
        return $this->hasMany(EstadisticaPartido::class);
    }
}