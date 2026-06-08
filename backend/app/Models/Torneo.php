<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Torneo extends Model
{
    use HasUuids;

    protected $fillable = [
        'temporada_id',
        'liga_id',
        'disciplina_id',
        'categoria_id',
        'nombre',
        'tipo_formato',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function fases()
    {
        return $this->hasMany(Fase::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_torneo');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionJugador::class);
    }

    public function tablaPosiciones()
    {
        return $this->hasMany(TablaPosiciones::class);
    }
}