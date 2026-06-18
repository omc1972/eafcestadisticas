<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'pais',
        'liga',
        'equipacion_id',
        'estadio_id',
        'entrenador_id',
        'liga_id',
        'escudo'
    ];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function entrenador()
    {
        return $this->belongsTo(Entrenador::class, 'entrenador_id');
    }

    public function equipacion()
    {
        return $this->belongsTo(Equipacion::class, 'equipacion_id');
    }

    public function estadio()
    {
        return $this->belongsTo(Estadio::class, 'estadio_id');
    }

    public function plantillas()
    {
        return $this->hasMany(Plantilla::class);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    public function partidosComoLocal()
    {
        return $this->hasMany(Partido::class, 'equipo_casa_id');
    }

    public function partidosComoVisitante()
    {
        return $this->hasMany(Partido::class, 'equipo_fuera_id');
    }

    public function partidos()
    {
        return $this->partidosComoLocal->merge($this->partidosComoVisitante);
    }
}
