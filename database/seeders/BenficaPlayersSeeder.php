<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class BenficaPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Benfica
        $benfica = Equipo::where('nombre', 'Sport Lisboa e Benfica')->first();
        if (!$benfica) {
            $this->command->error('Sport Lisboa e Benfica team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $benfica->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $benfica->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Vangelis Pavlidis', 'Grecia', 'DC', 82, 79, 82, 71, 80, 47, 82],
            ['Dodi Lukébakio', 'Bélgica', 'MD', 82, 87, 81, 76, 83, 31, 64],
            ['Nicolás Otamendi', 'Argentina', 'DFC', 82, 60, 57, 64, 62, 83, 85],
            ['Fredrik Aursnes', 'Noruega', 'MC', 80, 74, 71, 78, 80, 77, 82],
            ['Georgiy Sudakov', 'Ucrania', 'MCO', 80, 81, 77, 78, 81, 60, 72],
            ['Anatoliy Trubin', 'Ucrania', 'POR', 80, 77, 76, 77, 84, 45, 83],
            ['Richard Ríos', 'Colombia', 'MC', 79, 81, 70, 77, 82, 72, 82],
            ['Alexander Bah', 'Dinamarca', 'LD', 78, 90, 65, 72, 75, 73, 81],
            ['Bruma', 'Portugal', 'EI', 78, 88, 77, 71, 81, 32, 61],
            ['Tomás Araújo', 'Portugal', 'DFC', 78, 82, 30, 69, 72, 77, 79],
            ['António Silva', 'Portugal', 'DFC', 78, 80, 37, 64, 69, 78, 80],
            ['Leandro Barreiro', 'Luxemburgo', 'MC', 77, 80, 68, 69, 79, 73, 67],
            ['Enzo Barrenechea', 'Argentina', 'MC', 77, 57, 69, 72, 75, 72, 71],
            ['Franjo Ivanović', 'Croacia', 'DC', 75, 72, 78, 66, 74, 29, 72],
            ['Andreas Schjelderup', 'Noruega', 'EI', 74, 80, 68, 68, 80, 35, 57],
            ['Amar Dedić', 'Bosnia y Herzegovina', 'LD', 73, 83, 58, 68, 74, 66, 74],
            ['Samuel Dahl', 'Suecia', 'LI', 73, 83, 51, 68, 75, 65, 69],
            ['Manu Silva', 'Portugal', 'MCD', 72, 59, 51, 66, 64, 69, 81],
            ['Gianluca Prestianni', 'Argentina', 'MD', 71, 87, 61, 60, 79, 42, 49],
            ['Samuel Soares', 'Portugal', 'POR', 70, 69, 67, 83, 72, 59, 68],
            ['Henrique Araújo', 'Portugal', 'DC', 70, 70, 70, 59, 63, 26, 70],
            ['Obrador', 'España', 'LI', 69, 81, 32, 61, 63, 65, 65],
            ['Leandro Santos', 'Portugal', 'LD', 67, 83, 47, 57, 68, 58, 57],
            ['João Rego', 'Portugal', 'MCO', 67, 66, 59, 63, 73, 47, 53],
            ['Diogo Prioste', 'Portugal', 'MC', 66, 64, 60, 62, 67, 57, 62],
            ['Joshua Wynder', 'Estados Unidos', 'DFC', 66, 62, 31, 45, 54, 66, 68],
            ['João Veloso', 'Portugal', 'MC', 64, 63, 61, 63, 67, 55, 63],
            ['Nuno Félix', 'Portugal', 'MCD', 64, 59, 45, 59, 63, 62, 57],
            ['Diogo Ferreira', 'Portugal', 'POR', 64, 64, 60, 51, 65, 42, 63],
            ['Gonçalo Oliveira', 'Portugal', 'DFC', 63, 63, 33, 45, 55, 62, 66],
        ];

        // Mapa de posiciones
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

        $dorsal = 1;

        foreach ($jugadores_data as $data) {
            [$nombre, $nacionalidad, $posicion_code, $overall, $pace, $shooting, $passing, $dribbling, $defense, $physical] = $data;

            // Crear el jugador con todos los atributos calculados
            $jugador = Jugador::create([
                'nombre' => $nombre,
                'fecha_nacimiento' => '2000-01-01',
                'nacionalidad' => $nacionalidad,
                'posicion' => $posiciones_map[$posicion_code] ?? $posicion_code,
                'altura' => '180',
                'peso' => '75',
                'ritmo' => $pace,
                'aceleracion' => intval($pace * 0.95),
                'velocidad' => $pace,
                'tiro' => $shooting,
                'pos_ataque' => intval($shooting * 0.9),
                'finalizacion' => $shooting,
                'potencia_de_tiro' => intval($shooting * 0.85),
                'tiro_lejano' => intval($shooting * 0.8),
                'voleas' => intval($shooting * 0.7),
                'penalties' => intval($shooting * 0.75),
                'pase' => $passing,
                'vision' => intval($passing * 0.95),
                'centros' => intval($passing * 0.85),
                'precision_falta' => intval($passing * 0.8),
                'pase_corto' => $passing,
                'pase_largo' => intval($passing * 0.9),
                'efecto' => intval($passing * 0.85),
                'regate' => $dribbling,
                'agilidad' => intval($dribbling * 0.95),
                'equilibrio' => intval($dribbling * 0.9),
                'anticipacion' => intval($dribbling * 0.8),
                'control_de_balon' => $dribbling,
                'regates' => intval($dribbling * 0.85),
                'compostura' => intval($overall * 0.9),
                'defensa' => $defense,
                'intercepciones' => intval($defense * 0.95),
                'precision_cabezazo' => intval($defense * 0.85),
                'capacidad_defensiva' => $defense,
                'robos' => intval($defense * 0.9),
                'entradas' => intval($defense * 0.8),
                'fisico' => $physical,
                'salto' => intval($physical * 0.9),
                'resistencia' => $physical,
                'fuerza' => intval($physical * 0.95),
                'agresividad' => intval($physical * 0.85),
                'media' => $overall,
                'estilo_quimica' => 'Neutral',
            ]);

            // Agregar a la plantilla con dorsal
            $plantilla->jugadores()->attach($jugador->id, ['dorsal' => $dorsal]);

            $dorsal++;

            $this->command->info("Jugador '{$nombre}' agregado a la plantilla");
        }

        $this->command->info('Todos los jugadores de Benfica han sido agregados correctamente');
    }
}
