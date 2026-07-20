<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class SportingCPPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Sporting CP
        $sporting = Equipo::where('nombre', 'Sporting Clube de Portugal')->first();
        if (!$sporting) {
            $this->command->error('Sporting Clube de Portugal team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $sporting->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $sporting->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Morten Hjulmand', 'Dinamarca', 'MCD', 83, 70, 76, 78, 77, 80, 84],
            ['Pedro Gonçalves', 'Portugal', 'MCO', 83, 77, 82, 83, 84, 63, 68],
            ['Trincão', 'Portugal', 'MCO', 82, 80, 80, 79, 84, 40, 71],
            ['Rui Silva', 'Portugal', 'POR', 81, 82, 78, 77, 84, 31, 81],
            ['Gonçalo Inácio', 'Portugal', 'DFC', 81, 77, 37, 71, 73, 82, 81],
            ['Ousmane Diomande', 'Costa de Marfil', 'DFC', 80, 80, 26, 51, 70, 79, 86],
            ['Nuno Santos', 'Portugal', 'MI', 79, 79, 81, 76, 79, 69, 73],
            ['Hidemasa Morita', 'Japón', 'MC', 79, 68, 72, 76, 79, 73, 78],
            ['Geny Catamo', 'Mozambique', 'MD', 78, 91, 74, 69, 82, 64, 59],
            ['Daniel Bragança', 'Portugal', 'MC', 78, 79, 72, 77, 80, 67, 62],
            ['Zeno Debast', 'Bélgica', 'MCD', 78, 70, 56, 76, 74, 77, 76],
            ['Maximiliano Araújo', 'Uruguay', 'MI', 77, 85, 63, 70, 79, 68, 68],
            ['Matheus Reis', 'Brasil', 'DFC', 77, 76, 55, 68, 75, 77, 80],
            ['Eduardo Quaresma', 'Portugal', 'DFC', 77, 82, 54, 66, 73, 78, 75],
            ['Luis Javier Suárez', 'Colombia', 'DC', 77, 83, 77, 66, 75, 38, 78],
            ['Fotis Ioannidis', 'Grecia', 'DC', 77, 77, 76, 62, 77, 35, 77],
            ['Jeremiah St. Juste', 'Holanda', 'DFC', 76, 92, 57, 65, 73, 76, 73],
            ['Geovany Quenda', 'Portugal', 'MD', 76, 85, 62, 71, 79, 41, 67],
            ['Georgios Vagiannidis', 'Grecia', 'LD', 75, 81, 54, 70, 75, 70, 69],
            ['Giorgi Kochorashvili', 'Georgia', 'MC', 74, 72, 71, 72, 75, 68, 75],
            ['Fresneda', 'España', 'LD', 74, 79, 51, 60, 74, 72, 70],
            ['Ricardo Mangas', 'Portugal', 'MI', 72, 77, 63, 65, 73, 64, 74],
            ['João Virgínia', 'Portugal', 'POR', 71, 73, 72, 66, 73, 52, 67],
            ['João Simões', 'Portugal', 'MC', 69, 71, 60, 68, 70, 60, 65],
            ['Diego Calai', 'Brasil', 'POR', 68, 68, 65, 66, 70, 22, 70],
            ['Rodrigo Ribeiro', 'Portugal', 'DC', 68, 71, 66, 58, 71, 22, 59],
            ['Alisson', 'Brasil', 'EI', 66, 83, 58, 60, 72, 31, 56],
            ['Francisco Silva', 'Portugal', 'POR', 64, 64, 59, 64, 65, 42, 65],
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

        $this->command->info('Todos los jugadores de Sporting Clube de Portugal han sido agregados correctamente');
    }
}
