<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{
    use HasFactory;

    protected $appends = [
        'visitante',
    ];

    protected array $attributeAliases = [
        'local_id' => 'equipo_id',
        'visitante_id' => 'rival_id',
    ];

    protected $fillable = [
        'jugador_id',
        'partido_id',
        'local_id',
        'visitante_id',
        'equipo_id',
        'rival_id',
        'minuto',
        'tipo_evento_id'
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

    public function local()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function visitanteRival()
    {
        return $this->belongsTo(Rival::class, 'rival_id');
    }

    public function visitanteEquipo()
    {
        return $this->belongsTo(Equipo::class, 'visitante_id');
    }

    public function getVisitanteAttribute()
    {
        if ($this->partido && !$this->partido->visitante_es_rival) {
            return $this->visitanteEquipo;
        }

        return $this->visitanteRival;
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

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'tipo_evento_id');
    }
}
