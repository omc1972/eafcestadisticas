<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisiones';

    protected $fillable = ['squad_battle_id', 'rivals_battle_id', 'rival_id', 'nombre', 'orden'];

    public function squadBattle()
    {
        return $this->belongsTo(SquadBattle::class);
    }

    public function rivalsBattle()
    {
        return $this->belongsTo(RivalsBattle::class);
    }

    public function rival()
    {
        return $this->belongsTo(Rival::class);
    }

    public function rivales()
    {
        return $this->hasMany(DivisionRival::class)->orderBy('orden');
    }
}
