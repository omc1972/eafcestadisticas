<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    protected $fillable = [
        'nombre',
        'pais',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function plantillas()
    {
        return $this->hasMany(Plantilla::class);
    }
}
