<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    protected $fillable = [
        'nombre',
        'fecha_nacimiento',
        'nacionalidad',
        'altura',
        'peso',
        'posicion',
        'ritmo',
        'aceleracion',
        'velocidad',
        'tiro',
        'pos_ataque',
        'finalizacion',
        'potencia_de_tiro',
        'tiro_lejano',
        'voleas',	
        'penalties',
        'pase',
        'vision',
        'centros',
        'precision_falta',
        'pase_corto',
        'pase_largo',
        'efecto',
        'regate',
        'agilidad',
        'equilibrio',
        'anticipacion',
        'control_de_balon',	
        'regates',
        'compostura',
        'defensa',
        'intercepciones',
        'precision_cabezazo',
        'capacidad_defensiva',
        'robos',
        'entradas',
        'fisico',
        'salto',
        'resistencia',
        'fuerza',
        'agresividad',
        'media',
        'estilo_quimica',
    ];

    public function alineaciones()
    {
        return $this->hasMany(Alineacion::class, 'jugador_id');
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'jugador_id');
    }


    public function estilos()
    {
        return $this->belongsToMany(
            Estilo::class,   
            'estilo_jugador',
            'jugador_id',    
            'estilo_id'  
        )->withTimestamps();
    }
    public function plantillas()
    {
        return $this->belongsToMany(Plantilla::class, 'plantilla_jugador')
                    ->withPivot('dorsal')
                    ->withTimestamps();
    }
}