<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    protected $fillable = [
        'nombre',
        'activo', 
    ];

    public function plantillas()
    {
        return $this->hasMany(Plantilla::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'temporada_id');
    }

    public function ligas()
    {
        return $this->hasManyThrough(Liga::class, Plantilla::class, 'temporada_id', 'id', 'id', 'liga_id')->distinct();
    }
}
