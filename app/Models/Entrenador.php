<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    protected $fillable = [
        'nombre',
        'pais', 
        'tactica'
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'entrenador_id');
    }
}
