<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Liga extends Model
{
    use HasUuids;

    protected $fillable = [
        'nombre',
        'fecha_fundacion',
        'provincia',
        'ciudad',
        'canton',
        'parroquia',
        'barrio',
        'descripcion',
        'escudo_url',
    ];

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'liga_disciplina');
    }

    public function temporadas()
    {
        return $this->hasMany(Temporada::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function torneos()
    {
        return $this->hasMany(Torneo::class);
    }
}