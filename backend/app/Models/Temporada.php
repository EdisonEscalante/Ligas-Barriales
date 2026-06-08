<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Temporada extends Model
{
    use HasUuids;

    protected $fillable = [
        'liga_id',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function torneos()
    {
        return $this->hasMany(Torneo::class);
    }

    public function ventanasPase()
    {
        return $this->hasMany(VentanaPase::class);
    }
}