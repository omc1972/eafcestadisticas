<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    protected $appends = [
        'local',
        'visitante',
    ];

    protected array $attributeAliases = [
        'local_id' => 'equipo_id',
        'posesion_local' => 'posesion_equipo',
        'posesion_visitante' => 'posesion_rival',
        'delantera_local' => 'delantera_equipo',
        'delantera_visitante' => 'delantera_rival',
        'media_local' => 'media_equipo',
        'media_visitante' => 'media_rival',
        'defensa_local' => 'defensa_equipo',
        'defensa_visitante' => 'defensa_rival',
        'tiros_local' => 'tiros_equipo',
        'tiros_visitante' => 'tiros_rival',
        'tiros_a_puerta_local' => 'tiros_a_puerta_equipo',
        'tiros_a_puerta_visitante' => 'tiros_a_puerta_rival',
        'pases_local' => 'pases_equipo',
        'pases_visitante' => 'pases_rival',
        'porcentaje_pases_local' => 'porcentaje_pases_equipo',
        'porcentaje_pases_visitante' => 'porcentaje_pases_rival',
        'goles_local' => 'goles_equipo',
        'goles_visitante' => 'goles_rival',
        'valor_local' => 'valor_equipo',
        'valor_visitante' => 'valor_rival',
        'entradas_local' => 'entradas_equipo',
        'entradas_visitante' => 'entradas_rival',
        'entradas_local_completadas' => 'entradas_equipo_completadas',
        'entradas_visitante_completadas' => 'entradas_rival_completadas',
        'distancia_local' => 'distancia_equipo',
        'distancia_visitante' => 'distancia_rival',
        'faltas_local' => 'faltas_equipo',
        'faltas_visitante' => 'faltas_rival',
        'penalties_local' => 'penalties_equipo',
        'penalties_visitante' => 'penalties_rival',
        'tarjetas_amarillas_local' => 'tarjetas_amarillas_equipo',
        'tarjetas_amarillas_visitante' => 'tarjetas_amarillas_rival',
        'tarjetas_rojas_local' => 'tarjetas_rojas_equipo',
        'tarjetas_rojas_visitante' => 'tarjetas_rojas_rival',
        'corners_local' => 'corners_equipo',
        'corners_visitante' => 'corners_rival',
        'intercepciones_local' => 'interceciones_equipo',
        'intercepciones_visitante' => 'intercepciones_rival',
        'balones_ganados_local' => 'balones_ganados_equipo',
        'balones_ganados_visitante' => 'balones_ganados_rival',
        'balones_perdidos_local' => 'balones_perdidos_equipo',
        'balones_perdidos_visitante' => 'balones_perdidos_rival',
    ];

    protected $casts = [
        'visitante_es_rival' => 'boolean',
        'local_es_rival' => 'boolean',
    ];

    protected $fillable = [
        'dificultad',
        'partido_como_local',
        'equipo_id',
        'visitante_id',
        'visitante_es_rival',
        'local_es_rival',
        'rival_id',
        'temporada_id',
        'competicion_id',
        'campeonato_id',
        'eliminatoria_id',
        'orden_eliminatoria',
        'jornada',
        'minutos_jugados',
        'puntuacion',
        'posesion_equipo',
        'posesion_rival',
        'delantera_equipo',
        'delantera_rival',
        'media_equipo',
        'media_rival',
        'defensa_equipo',
        'defensa_rival',
        'tiros_equipo',
        'tiros_rival',
        'tiros_a_puerta_equipo',
        'tiros_a_puerta_rival',
        'pases_equipo',
        'pases_rival',
        'porcentaje_pases_equipo',
        'porcentaje_pases_rival',
        'goles_equipo',
        'goles_rival',
        'valor_equipo',
        'valor_rival',
        'entradas_equipo',
        'entradas_rival',
        'entradas_equipo_completadas',
        'entradas_rival_completadas',
        'distancia_equipo',
        'distancia_rival',
        'faltas_equipo',
        'faltas_rival',
        'penalties_rival',
        'penalties_equipo',
        'tarjetas_amarillas_equipo',
        'tarjetas_amarillas_rival',
        'tarjetas_rojas_equipo',
        'tarjetas_rojas_rival',
        'corners_equipo',
        'corners_rival',
        'interceciones_equipo',
        'intercepciones_rival',
        'balones_ganados_equipo',
        'balones_ganados_rival',
        'balones_perdidos_equipo',
        'balones_perdidos_rival',     
    ];
    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function eliminatoria()
    {
        return $this->belongsTo(Eliminatoria::class, 'eliminatoria_id');
    }

    public function local()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function localRival()
    {
        return $this->belongsTo(Rival::class, 'equipo_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function rival()
    {
        return $this->belongsTo(Rival::class, 'rival_id');
    }

    public function visitanteRival()
    {
        return $this->belongsTo(Rival::class, 'visitante_id');
    }

    public function visitanteEquipo()
    {
        return $this->belongsTo(Equipo::class, 'visitante_id');
    }

    public function getVisitanteAttribute()
    {
        return $this->visitante_es_rival ? $this->visitanteRival : $this->visitanteEquipo;
    }

    public function getLocalAttribute()
    {
        return $this->local_es_rival ? $this->localRival : $this->equipo;
    }

    public function getAttribute($key)
    {
        if (is_string($key) && isset($this->attributeAliases[$key])) {
            $key = $this->attributeAliases[$key];
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (is_string($key) && isset($this->attributeAliases[$key])) {
            $key = $this->attributeAliases[$key];
        }

        return parent::setAttribute($key, $value);
    }

    public function alineaciones()
    {
        return $this->hasMany(Alineacion::class, 'partido_id');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'partido_id');
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'temporada_id');
    }

    public function competicion()
    {
        return $this->belongsTo(Competicion::class, 'competicion_id');
    }

}
