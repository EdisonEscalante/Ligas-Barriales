<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo User
 * Representa a los usuarios del sistema (administradores, delegados, etc.)
 * Extiende Authenticatable para manejar autenticación con Laravel
 * HasApiTokens permite generar tokens de acceso para la API REST
 */
class User extends Authenticatable
{
    // HasUuids genera automáticamente un UUID como clave primaria
    // HasApiTokens permite crear y gestionar tokens de acceso a la API
    use HasUuids, HasApiTokens;

    /**
     * Campos que se pueden llenar masivamente
     * Solo estos campos se aceptan al crear o actualizar un usuario
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    /**
     * Campos ocultos en las respuestas JSON
     * El password nunca se expone en la API
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Relación: un usuario puede tener varios roles
     * Un usuario puede ser admin de liga, delegado de equipo, etc.
     */
    public function roles()
    {
        return $this->hasMany(UsuarioRol::class);
    }
}