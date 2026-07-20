<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionRival extends Model
{
    protected $table = 'divisiones_equipos';

    protected $fillable = ['division_id', 'equipo_id', 'orden'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
