<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estilo extends Model
{

    protected $fillable = [
        'nombre',
        'categoria', 
        'descripcion',
        'nivel'
    ];

    public function jugadores()
    {
        return $this->belongsToMany(
            Jugador::class,   
            'estilo_jugador', 
            'estilo_id',
            'jugador_id'
        )->withTimestamps();
    }
}
