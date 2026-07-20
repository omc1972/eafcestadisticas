<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class BayerLeverkusenPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Bayer Leverkusen
        $leverkusen = Equipo::where('nombre', 'Bayer Leverkusen')->first();
        if (!$leverkusen) {
            $this->command->error('Bayer Leverkusen team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $leverkusen->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $leverkusen->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Patrik Schick', 'República Checa', 'DC', 85, 72, 83, 71, 81, 38, 77],
            ['Grimaldo', 'España', 'MI', 84, 72, 73, 87, 85, 74, 67],
            ['Exequiel Palacios', 'Argentina', 'MC', 84, 62, 76, 80, 82, 81, 71],
            ['Aleix García', 'España', 'MC', 83, 65, 75, 85, 84, 75, 66],
            ['Malik Tillman', 'Estados Unidos', 'MCO', 82, 84, 76, 77, 83, 65, 77],
            ['Robert Andrich', 'Alemania', 'MCD', 81, 57, 77, 76, 73, 83, 81],
            ['Edmond Tapsoba', 'Burkina Faso', 'DFC', 81, 75, 56, 68, 70, 82, 80],
            ['Martin Terrier', 'Francia', 'DC', 79, 78, 79, 75, 80, 36, 71],
            ['Jonas Hofmann', 'Alemania', 'MCO', 78, 64, 76, 78, 79, 64, 68],
            ['Mark Flekken', 'Holanda', 'POR', 78, 79, 76, 80, 80, 32, 76],
            ['Nathan Tella', 'Nigeria', 'MD', 78, 83, 75, 71, 80, 46, 60],
            ['Loïc Badé', 'Francia', 'DFC', 78, 61, 45, 56, 66, 78, 78],
            ['Eliesse Ben Seghir', 'Marruecos', 'MI', 76, 77, 73, 72, 79, 28, 62],
            ['Ezequiel Fernández', 'Argentina', 'MCD', 75, 70, 47, 67, 72, 70, 78],
            ['Jarell Quansah', 'Inglaterra', 'DFC', 75, 70, 34, 59, 68, 74, 75],
            ['Claudio Echeverri', 'Argentina', 'MCO', 74, 84, 66, 71, 80, 38, 47],
            ['Janis Blaswich', 'Alemania', 'POR', 73, 73, 68, 65, 73, 43, 76],
            ['Ibrahim Maza', 'Argelia', 'MCO', 71, 75, 66, 66, 79, 40, 62],
            ['Arthur', 'Brasil', 'LD', 71, 83, 40, 66, 73, 63, 57],
            ['Ernest Poku', 'Holanda', 'MD', 70, 94, 60, 59, 72, 32, 60],
            ['Jeanuël Belocian', 'Francia', 'DFC', 70, 67, 41, 64, 65, 70, 67],
            ['Christian Kofane', 'Camerún', 'DC', 68, 63, 70, 61, 68, 26, 57],
            ['Niklas Lomb', 'Alemania', 'POR', 66, 68, 58, 64, 70, 38, 63],
            ['Axel Tape', 'Francia', 'DFC', 65, 68, 42, 57, 60, 66, 58],
            ['Alejo Sarco', 'Argentina', 'DC', 64, 72, 63, 51, 63, 28, 60],
            ['Jeremiah Mensah', 'Alemania', 'MC', 61, 68, 57, 60, 66, 55, 55],
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

        $this->command->info('Todos los jugadores de Bayer Leverkusen han sido agregados correctamente');
    }
}
