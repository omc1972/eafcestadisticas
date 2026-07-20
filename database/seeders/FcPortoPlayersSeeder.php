<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class FcPortoPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo FC Porto
        $porto = Equipo::where('nombre', 'FC Porto')->first();
        if (!$porto) {
            $this->command->error('FC Porto team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $porto->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $porto->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Diogo Costa', 'Portugal', 'POR', 84, 83, 78, 82, 86],
            ['Alan Varela', 'Argentina', 'MCD', 79, 70, 65, 74, 73],
            ['Samu', 'España', 'DC', 79, 79, 79, 58, 68],
            ['Francisco Moura', 'Portugal', 'LI', 78, 78, 63, 75, 75],
            ['Pepê', 'Brasil', 'MCO', 78, 85, 70, 72, 83],
            ['Luuk de Jong', 'Holanda', 'DC', 78, 40, 78, 72, 72],
            ['Stephen Eustáquio', 'Canadá', 'MCD', 77, 66, 67, 76, 73],
            ['Jakub Kiwior', 'Polonia', 'DFC', 77, 67, 37, 66, 65],
            ['Gabri Veiga', 'España', 'MCO', 76, 76, 75, 70, 77],
            ['Rodrigo Mora', 'Portugal', 'MCO', 76, 77, 66, 74, 79],
            ['Nehuén Pérez', 'Argentina', 'DFC', 76, 60, 37, 54, 55],
            ['Pablo Rosario', 'República Dominicana', 'MCD', 75, 55, 64, 68, 72],
            ['Borja Sainz', 'España', 'EI', 75, 81, 75, 68, 79],
            ['Martim Fernandes', 'Portugal', 'LD', 75, 81, 51, 70, 74],
            ['Cláudio Ramos', 'Portugal', 'POR', 73, 78, 72, 64, 72],
            ['Jan Bednarek', 'Polonia', 'DFC', 73, 43, 22, 55, 55],
            ['Zaidu Sanusi', 'Nigeria', 'LI', 72, 85, 37, 55, 63],
            ['Victor Froholdt', 'Dinamarca', 'MC', 71, 75, 63, 67, 72],
            ['Dominik Prpić', 'Croacia', 'DFC', 71, 71, 27, 56, 60],
            ['Alberto Costa', 'Portugal', 'LD', 69, 68, 56, 64, 68],
            ['João Costa', 'Portugal', 'POR', 69, 70, 67, 68, 73],
            ['Pedro Lima', 'Brasil', 'LD', 68, 76, 48, 67, 71],
            ['William Gomes', 'Brasil', 'EI', 68, 82, 64, 57, 74],
            ['Deniz Gül', 'Turquía', 'DC', 66, 69, 64, 58, 67],
            ['Tomás Pérez', 'Argentina', 'MCD', 65, 66, 53, 54, 65],
            ['Diogo Fernandes', 'Portugal', 'POR', 63, 65, 62, 55, 64],
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

        $this->command->info('Todos los jugadores de FC Porto han sido agregados correctamente');
    }
}
