<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * Modelo Rol
 * Representa los roles del sistema
 * Se especifica el nombre de la tabla porque Laravel pluraliza Rol como rols
 */
class Rol extends Model
{
    use HasUuids;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function usuarios()
    {
        return $this->hasMany(UsuarioRol::class);
    }
}