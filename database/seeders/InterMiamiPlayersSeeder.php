<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class InterMiamiPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Inter Miami FC
        $miami = Equipo::where('nombre', 'Inter Miami FC')->first();
        if (!$miami) {
            $this->command->error('Inter Miami FC team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $miami->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $miami->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Rodrigo De Paul', 'Argentina', 'MC', 84, 75, 78, 83, 83],
            ['Sergio Busquets', 'España', 'MCD', 80, 35, 61, 76, 77],
            ['Luis Suárez', 'Uruguay', 'DC', 79, 53, 82, 74, 76],
            ['Jordi Alba', 'España', 'LI', 78, 82, 69, 78, 80],
            ['Tadeo Allende', 'Argentina', 'MD', 72, 80, 74, 65, 69],
            ['Baltasar Rodríguez', 'Argentina', 'MC', 70, 74, 60, 70, 74],
            ['Maximiliano Falcón', 'Uruguay', 'DFC', 70, 75, 42, 34, 39],
            ['Oscar Ustari', 'Argentina', 'POR', 69, 68, 71, 68, 66],
            ['Telasco Segovia', 'Venezuela', 'MC', 69, 71, 66, 65, 69],
            ['Marcelo Weigandt', 'Argentina', 'LD', 69, 78, 47, 57, 67],
            ['Fafà Picault', 'Haití', 'EI', 69, 87, 66, 62, 71],
            ['Tomás Avilés', 'Argentina', 'DFC', 69, 60, 48, 61, 57],
            ['Gonzalo Luján', 'Argentina', 'DFC', 69, 62, 34, 56, 59],
            ['Yannick Bright', 'Italia', 'MCD', 68, 70, 36, 61, 61],
            ['William Yarbrough', 'Estados Unidos', 'POR', 66, 65, 64, 66, 66],
            ['Noah Allen', 'Grecia', 'DFC', 65, 78, 40, 59, 64],
            ['David Ruiz', 'Honduras', 'MC', 64, 68, 49, 59, 69],
            ['Ian Fray', 'Estados Unidos', 'LD', 64, 74, 41, 54, 61],
            ['Allen Obando', 'Ecuador', 'DC', 64, 66, 61, 49, 63],
            ['Ryan Sailor', 'Estados Unidos', 'DFC', 63, 63, 24, 34, 39],
            ['Rocco Ríos Novo', 'Argentina', 'POR', 62, 61, 57, 60, 66],
            ['Santiago Morales', 'Estados Unidos', 'MCO', 57, 64, 46, 55, 60],
            ['Tyler Hall', 'Estados Unidos', 'DFC', 57, 57, 28, 53, 56],
            ['Israel Boatwright', 'República Dominicana', 'LD', 57, 70, 22, 33, 62],
        ];

        // Mapeo de posiciones a códigos
        $posiciones_map = [
            'POR' => 'Portero',
            'DFC' => 'Defensa Central',
            'DC' => 'Defensa Central',
            'LD' => 'Lateral Derecho',
            'LI' => 'Lateral Izquierdo',
            'MD' => 'Mediapunta Derecho',
            'MI' => 'Mediapunta Izquierdo',
            'ED' => 'Extremo Derecho',
            'EI' => 'Extremo Izquierdo',
            'MC' => 'Mediocampista Central',
            'MCD' => 'Mediocampista Central Defensivo',
            'MCO' => 'Mediocampista Central Ofensivo',
        ];

        // Insertar jugadores
        $dorsal = 1;
        foreach ($jugadores_data as $jugador_info) {
            [$nombre, $nacionalidad, $posicion, $pace, $shooting, $passing, $dribbling, $defense] = $jugador_info;

            // Calcular physical (promedio de los últimos valores observados)
            $physical = intval(($pace + $defense) * 0.5);

            // Calcular atributos derivados
            $ritmo = $pace;
            $aceleracion = intval($pace * 0.95);
            $velocidad = $pace;
            $tiro = $shooting;
            $pos_ataque = intval($shooting * 0.9);
            $finalizacion = $shooting;
            $potencia_de_tiro = intval($shooting * 0.85);
            $tiro_lejano = intval($shooting * 0.8);
            $voleas = intval($shooting * 0.75);
            $penalties = intval($shooting * 0.7);
            $pase = $passing;
            $vision = intval($passing * 0.95);
            $centros = intval($passing * 0.9);
            $precision_falta = intval($passing * 0.85);
            $pase_corto = intval($passing * 0.95);
            $pase_largo = intval($passing * 0.85);
            $efecto = intval($passing * 0.8);
            $regate = $dribbling;
            $agilidad = intval($dribbling * 0.95);
            $equilibrio = intval($dribbling * 0.9);
            $anticipacion = intval($defense * 0.95);
            $control_de_balon = intval($dribbling * 0.95);
            $regates = intval($dribbling * 0.9);
            $compostura = intval(($passing + $dribbling) * 0.475);
            $defensa = $defense;
            $intercepciones = intval($defense * 0.95);
            $precision_cabezazo = intval($defense * 0.85);
            $capacidad_defensiva = intval($defense * 0.9);
            $robos = intval($defense * 0.85);
            $entradas = intval($defense * 0.8);
            $fisico = $physical;
            $salto = intval($physical * 0.95);
            $resistencia = intval($physical * 0.9);
            $fuerza = intval($physical * 0.95);
            $agresividad = intval($physical * 0.8);
            $media = intval(($pace + $shooting + $passing + $dribbling + $defense + $physical) / 6);

            // Crear jugador
            $jugador = Jugador::create([
                'nombre' => $nombre,
                'fecha_nacimiento' => '2000-01-01',
                'nacionalidad' => $nacionalidad,
                'altura' => '180',
                'peso' => '75',
                'posicion' => $posiciones_map[$posicion] ?? $posicion,
                'estilo_quimica' => 'Neutral',
                'ritmo' => $ritmo,
                'aceleracion' => $aceleracion,
                'velocidad' => $velocidad,
                'tiro' => $tiro,
                'pos_ataque' => $pos_ataque,
                'finalizacion' => $finalizacion,
                'potencia_de_tiro' => $potencia_de_tiro,
                'tiro_lejano' => $tiro_lejano,
                'voleas' => $voleas,
                'penalties' => $penalties,
                'pase' => $pase,
                'vision' => $vision,
                'centros' => $centros,
                'precision_falta' => $precision_falta,
                'pase_corto' => $pase_corto,
                'pase_largo' => $pase_largo,
                'efecto' => $efecto,
                'regate' => $regate,
                'agilidad' => $agilidad,
                'equilibrio' => $equilibrio,
                'anticipacion' => $anticipacion,
                'control_de_balon' => $control_de_balon,
                'regates' => $regates,
                'compostura' => $compostura,
                'defensa' => $defensa,
                'intercepciones' => $intercepciones,
                'precision_cabezazo' => $precision_cabezazo,
                'capacidad_defensiva' => $capacidad_defensiva,
                'robos' => $robos,
                'entradas' => $entradas,
                'fisico' => $fisico,
                'salto' => $salto,
                'resistencia' => $resistencia,
                'fuerza' => $fuerza,
                'agresividad' => $agresividad,
                'media' => $media,
            ]);

            // Asociar jugador a la plantilla
            $plantilla->jugadores()->attach($jugador->id, [
                'dorsal' => $dorsal,
                'es_titular' => false,
            ]);

            $this->command->info("Jugador '$nombre' agregado a la plantilla");
            $dorsal++;
        }

        $this->command->info('Todos los jugadores de Inter Miami FC han sido agregados correctamente');
    }
}
