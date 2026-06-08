<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Categoria extends Model
{
    use HasUuids;

    protected $fillable = [
        'nombre',
    ];

    public function torneos()
    {
        return $this->hasMany(Torneo::class);
    }
}
