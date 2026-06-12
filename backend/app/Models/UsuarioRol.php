<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Modelo UsuarioRol
 * Vincula usuarios con roles en ligas, equipos y disciplinas específicas
 * Se especifica la tabla porque Laravel pluraliza UsuarioRol incorrectamente
 */
class UsuarioRol extends Model
{
    use HasUuids;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'usuario_rol';

    protected $fillable = [
        'user_id',
        'rol_id',
        'liga_id',
        'equipo_id',
        'disciplina_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
}