<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alineacion extends Model
{
    protected $fillable = [
        'partido_id',
        'equipo_id',
        'rival_id',
        'jugador_id',
        'tiros',
        'tiros_a_puerta',
        'tiros_al_palo',
        'pases',
        'pases_exitosos',        
        'entradas',
        'entradas_exitosas',        
        'regates',
        'regates_exitosos',       
        'posesion_ganada',
        'posesion_perdida',
        'fueras_de_juego',
        'faltas_cometidas',
        'posesion',
        'faltas_recibidas',
        'distancia_recorrida',
        'rendimiento',
        'jugador_del_partido'
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function rival()
    {
        return $this->belongsTo(Rival::class, 'rival_id');
    }
}
