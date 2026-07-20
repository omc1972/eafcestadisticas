<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class ChelseaPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Chelsea
        $chelsea = Equipo::where('nombre', 'Chelsea')->first();
        if (!$chelsea) {
            $this->command->error('Chelsea team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $chelsea->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $chelsea->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Cole Palmer', 'Inglaterra', 'MCO', 87, 75, 83, 87, 87, 50, 65],
            ['Moisés Caicedo', 'Ecuador', 'MCD', 87, 71, 64, 78, 81, 84, 82],
            ['Marc Cucurella', 'España', 'LI', 84, 75, 64, 79, 80, 82, 79],
            ['Enzo Fernández', 'Argentina', 'MC', 84, 68, 75, 85, 81, 73, 75],
            ['Reece James', 'Inglaterra', 'LD', 81, 76, 71, 82, 77, 81, 81],
            ['Andrey Santos', 'Brasil', 'MC', 80, 74, 69, 74, 78, 77, 80],
            ['Pedro Neto', 'Portugal', 'MD', 80, 91, 76, 74, 82, 40, 68],
            ['Levi Colwill', 'Inglaterra', 'DFC', 80, 70, 43, 71, 72, 81, 80],
            ['Malo Gusto', 'Francia', 'LD', 79, 84, 45, 75, 78, 73, 74],
            ['Trevoh Chalobah', 'Inglaterra', 'DFC', 79, 66, 53, 69, 70, 80, 78],
            ['João Pedro', 'Brasil', 'DC', 79, 78, 78, 72, 81, 37, 70],
            ['Wesley Fofana', 'Francia', 'DFC', 79, 74, 41, 62, 71, 80, 78],
            ['Robert Sánchez', 'España', 'POR', 78, 79, 77, 75, 79, 59, 77],
            ['Romeo Lavia', 'Bélgica', 'MCD', 78, 69, 52, 72, 77, 77, 75],
            ['Jorrel Hato', 'Holanda', 'LI', 78, 85, 41, 70, 74, 75, 73],
            ['Estêvão', 'Brasil', 'MD', 78, 90, 74, 73, 82, 33, 57],
            ['Liam Delap', 'Inglaterra', 'DC', 78, 79, 80, 60, 76, 30, 81],
            ['Raheem Sterling', 'Inglaterra', 'EI', 78, 82, 74, 74, 82, 42, 48],
            ['Jamie Gittens', 'Inglaterra', 'MI', 78, 92, 73, 66, 84, 27, 59],
            ['Tosin Adarabioyo', 'Inglaterra', 'DFC', 78, 70, 46, 63, 64, 78, 79],
            ['Filip Jörgensen', 'Dinamarca', 'POR', 77, 77, 76, 72, 78, 45, 77],
            ['Alejandro Garnacho', 'Argentina', 'MCO', 77, 86, 77, 72, 80, 37, 58],
            ['Axel Disasi', 'Francia', 'DFC', 77, 56, 48, 60, 58, 77, 79],
            ['Benoît Badiashile', 'Francia', 'DFC', 76, 60, 45, 64, 62, 76, 77],
            ['Dário Essugo', 'Portugal', 'MCD', 75, 76, 58, 64, 73, 74, 81],
            ['Facundo Buonanotte', 'Argentina', 'MD', 75, 75, 70, 73, 76, 30, 53],
            ['Marc Guiu', 'España', 'DC', 71, 76, 70, 55, 69, 36, 75],
            ['Tyrique George', 'Inglaterra', 'MI', 71, 79, 66, 66, 73, 36, 55],
            ['Josh Acheampong', 'Inglaterra', 'LD', 70, 76, 50, 65, 69, 66, 73],
            ['Gabriel Slonina', 'Estados Unidos', 'POR', 68, 70, 64, 63, 70, 45, 65],
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

        foreach ($jugadores_data as $index => $data) {
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

            // Determinar si es titular (los primeros 11)
            $es_titular = $index < 11 ? true : false;

            // Agregar a la plantilla con dorsal y estado de titular
            $plantilla->jugadores()->attach($jugador->id, [
                'dorsal' => $dorsal,
                'es_titular' => $es_titular
            ]);

            $status = $es_titular ? 'TITULAR' : 'SUPLENTE';
            $dorsal++;

            $this->command->info("Jugador '{$nombre}' agregado a la plantilla como {$status}");
        }

        $this->command->info('Todos los jugadores de Chelsea han sido agregados correctamente');
    }
}
