<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rival extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'usuario',
        'quimica',
        'valoracion',
        'tactica',
        'fecha'
    ];

    public function plantilla()
    {
        return $this->hasMany(Plantilla::class)->where('id', 5);
    }
}
