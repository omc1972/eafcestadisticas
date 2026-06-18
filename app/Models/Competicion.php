<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competicion extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'competicion_id');
    }

    public function ligas()
    {
        return $this->hasMany(Liga::class);
    }
}
