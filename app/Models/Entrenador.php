<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    protected $fillable = [
        'nombre',
        'pais', 
        'tactica'
    ];

    public function plantillas()
    {
        return $this->hasMany(Plantilla::class, 'entrenador_id');
    }
}
