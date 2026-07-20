<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class MilanoFCPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo AC Milan
        $milanoDFC = Equipo::where('nombre', 'AC Milan')->first();
        if (!$milanoDFC) {
            $this->command->error('AC Milan team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $milanoDFC->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $milanoDFC->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Mike Maignan', 'Francia', 'POR', 87, 84, 83, 85, 88, 64, 84],
            ['Christian Pulisic', 'Estados Unidos', 'ED', 84, 88, 82, 80, 86, 46, 65],
            ['Rafael Leão', 'Portugal', 'EI', 84, 93, 78, 80, 86, 28, 75],
            ['Adrien Rabiot', 'Francia', 'MCO', 83, 81, 79, 81, 80, 77, 84],
            ['Luka Modrić', 'Croacia', 'MC', 83, 68, 74, 86, 86, 70, 62],
            ['Youssouf Fofana', 'Francia', 'MCD', 81, 68, 68, 75, 78, 79, 77],
            ['Christopher Nkunku', 'Francia', 'MCO', 81, 77, 79, 80, 82, 40, 59],
            ['Fikayo Tomori', 'Inglaterra', 'DFC', 81, 81, 40, 60, 67, 82, 78],
            ['Ruben Loftus-Cheek', 'Inglaterra', 'MC', 80, 81, 76, 79, 80, 76, 80],
            ['Pervis Estupiñán', 'Ecuador', 'LI', 79, 76, 57, 77, 78, 75, 72],
            ['Alexis Saelemaekers', 'Bélgica', 'MD', 79, 80, 67, 75, 81, 66, 63],
            ['Santiago Giménez', 'México', 'DC', 79, 78, 80, 70, 77, 46, 69],
            ['Yacine Adli', 'Francia', 'MCD', 78, 70, 69, 80, 78, 73, 68],
            ['Samuele Ricci', 'Italia', 'MCD', 78, 73, 60, 75, 79, 73, 72],
            ['Pietro Terracciano', 'Italia', 'POR', 78, 79, 75, 72, 82, 41, 78],
            ['Matteo Gabbia', 'Italia', 'DFC', 78, 49, 35, 54, 60, 81, 76],
            ['Ardon Jashari', 'Suiza', 'MCD', 77, 75, 67, 75, 73, 72, 82],
            ['Strahinja Pavlović', 'Serbia', 'DFC', 76, 70, 43, 51, 64, 75, 83],
            ['Koni De Winter', 'Bélgica', 'DFC', 74, 67, 40, 58, 66, 75, 72],
            ['Zachary Athekame', 'Suiza', 'LD', 65, 82, 28, 50, 62, 59, 68],
            ['David Odogu', 'Alemania', 'DFC', 65, 67, 32, 45, 52, 65, 67],
            ['Davide Bartesaghi', 'Italia', 'LI', 62, 54, 43, 56, 59, 62, 65],
            ['Lorenzo Torriani', 'Italia', 'POR', 61, 59, 54, 66, 62, 20, 61],
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

        $this->command->info('Todos los jugadores de Milano FC han sido agregados correctamente');
    }
}
