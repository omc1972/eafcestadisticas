<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eliminatoria extends Model
{
    protected $fillable = [
        'campeonato_id',
        'nombre', // Ej: "Cuartos de final", "Semifinal", "Final"
        'orden', // Para el orden de la ronda
    ];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'eliminatoria_id')->orderBy('orden_eliminatoria');
    }
}
