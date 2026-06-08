<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PaseJugador extends Model
{
    use HasUuids;

    protected $fillable = [
        'jugador_id',
        'ventana_pase_id',
        'equipo_origen_id',
        'equipo_destino_id',
        'estado',
        'aprobacion_equipo_origen',
        'aprobado_por_equipo_id',
        'aprobacion_liga',
        'aprobado_por_liga_id',
        'motivo_rechazo',
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function ventanaPase()
    {
        return $this->belongsTo(VentanaPase::class);
    }

    public function equipoOrigen()
    {
        return $this->belongsTo(Equipo::class, 'equipo_origen_id');
    }

    public function equipoDestino()
    {
        return $this->belongsTo(Equipo::class, 'equipo_destino_id');
    }
}