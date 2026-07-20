<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RivalsBattle extends Model
{
    protected $table = 'rivals_battles';
    protected $fillable = ['nombre', 'temporada_id'];

    public function divisiones()
    {
        return $this->hasMany(Division::class, 'rivals_battle_id')->orderBy('orden');
    }

    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }
}
