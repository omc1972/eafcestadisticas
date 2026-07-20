<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class NewcastleUnitedPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Newcastle
        $newcastle = Equipo::where('nombre', 'like', '%Newcastle%')->first();
        if (!$newcastle) {
            $this->command->error('Newcastle United team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $newcastle->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $newcastle->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Bruno Guimarães', 'Brasil', 'MC', 86, 66, 75, 84, 84, 79, 81],
            ['Sandro Tonali', 'Italia', 'MCD', 86, 79, 74, 82, 80, 81, 83],
            ['Anthony Gordon', 'Inglaterra', 'EI', 83, 91, 79, 78, 83, 50, 71],
            ['Joelinton', 'Brasil', 'MC', 82, 74, 73, 77, 81, 82, 90],
            ['Fabian Schär', 'Suiza', 'DFC', 82, 51, 68, 76, 73, 84, 80],
            ['Yoane Wissa', 'RD Congo', 'DC', 82, 85, 82, 70, 80, 31, 71],
            ['Sven Botman', 'Holanda', 'DFC', 82, 56, 37, 62, 65, 83, 82],
            ['Jacob Murphy', 'Inglaterra', 'ED', 81, 82, 77, 78, 80, 61, 70],
            ['Nick Pope', 'Inglaterra', 'POR', 81, 81, 81, 68, 82, 49, 78],
            ['Anthony Elanga', 'Suecia', 'ED', 81, 92, 72, 76, 82, 39, 70],
            ['Kieran Trippier', 'Inglaterra', 'LD', 80, 66, 65, 83, 77, 80, 71],
            ['Lewis Hall', 'Inglaterra', 'LI', 80, 77, 62, 78, 79, 77, 64],
            ['Tino Livramento', 'Inglaterra', 'LD', 80, 82, 51, 72, 78, 76, 74],
            ['Harvey Barnes', 'Inglaterra', 'EI', 80, 83, 80, 75, 81, 43, 68],
            ['Nick Woltemade', 'Alemania', 'DC', 79, 67, 78, 65, 79, 41, 74],
            ['Dan Burn', 'Inglaterra', 'DFC', 79, 42, 36, 67, 64, 79, 85],
            ['Jacob Ramsey', 'Inglaterra', 'MI', 78, 77, 75, 73, 80, 68, 72],
            ['Malick Thiaw', 'Alemania', 'DFC', 78, 72, 47, 69, 72, 78, 80],
            ['Aaron Ramsdale', 'Inglaterra', 'POR', 77, 77, 76, 80, 79, 48, 74],
            ['Joe Willock', 'Inglaterra', 'MC', 76, 75, 72, 73, 79, 72, 68],
            ['Emil Krafth', 'Suecia', 'DFC', 73, 53, 59, 69, 70, 74, 67],
            ['Jamaal Lascelles', 'Inglaterra', 'DFC', 73, 37, 35, 51, 50, 74, 71],
            ['Lewis Miley', 'Inglaterra', 'MC', 72, 65, 61, 72, 73, 64, 66],
            ['John Ruddy', 'Inglaterra', 'POR', 68, 66, 69, 66, 67, 35, 68],
            ['William Osula', 'Dinamarca', 'DC', 68, 73, 66, 53, 68, 24, 63],
            ['Harrison Ashby', 'Escocia', 'LD', 64, 75, 44, 56, 60, 57, 62],
            ['Alex Murphy', 'República de Irlanda', 'LI', 64, 64, 41, 53, 57, 63, 71],
            ['Mark Gillespie', 'Inglaterra', 'POR', 62, 63, 61, 60, 64, 32, 61],
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

        $this->command->info('Todos los jugadores de Newcastle United han sido agregados correctamente');
    }
}
