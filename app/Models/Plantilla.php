<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $fillable = [
        'equipo_id',
        'temporada_id',
        'liga_id',
        'campeonato_id',
        'orden_liga',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }


    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function jugadores()
    {
        return $this->belongsToMany(Jugador::class, 'plantilla_jugador')
                    ->withPivot('dorsal', 'es_titular')
                    ->withTimestamps();
    }
}
