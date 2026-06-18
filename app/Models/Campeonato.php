<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
    ];

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'campeonato_id');
    }

    public function plantillas()
    {
        return $this->hasMany(Plantilla::class, 'campeonato_id');
    }
    public function eliminatorias()
    {
        return $this->hasMany(Eliminatoria::class, 'campeonato_id');
    }
}
