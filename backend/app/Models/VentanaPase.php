<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class VentanaPase extends Model
{
    use HasUuids;

    protected $fillable = [
        'temporada_id',
        'fecha_apertura',
        'fecha_cierre',
        'estado',
    ];

    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }

    public function pases()
    {
        return $this->hasMany(PaseJugador::class);
    }
}