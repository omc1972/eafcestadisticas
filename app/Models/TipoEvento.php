<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoEvento extends Model
{
    use HasFactory;

    const GOL                = 1;
    const PENALTY_PROVOCADO  = 2;
    const TARJETA_AMARILLA   = 6;
    const TARJETA_ROJA       = 7;
    const ENTRA              = 8;
    const SALE               = 9;
    const ASISTENCIA         = 10;
    const PENALTY_MARCADO    = 11;
    const PENALTY_FALLADO    = 12;
    const PENALTY_PARADO     = 13;

    protected $fillable = [
        'name',
    ];
}
