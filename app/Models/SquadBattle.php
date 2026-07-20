<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SquadBattle extends Model
{
    protected $fillable = ['nombre', 'temporada_id'];

    public function divisiones()
    {
        return $this->hasMany(Division::class)->orderBy('orden');
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }
}
