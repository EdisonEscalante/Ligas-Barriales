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

    /**
 * Obtiene el administrador de la liga
 * Es el usuario con rol admin_liga asignado a esta liga
 */
public function administrador()
{
    return $this->hasOneThrough(
        \App\Models\User::class,
        \App\Models\UsuarioRol::class,
        'liga_id',
        'id',
        'id',
        'user_id'
    )->whereHas('roles', function($q) {
        $q->whereHas('rol', function($q2) {
            $q2->where('nombre', 'admin_liga');
        });
    });
}
}