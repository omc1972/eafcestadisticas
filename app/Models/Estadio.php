<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estadio extends Model
{
    protected $fillable = [
        'nombre', 
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'estadio_id');
    }
}
