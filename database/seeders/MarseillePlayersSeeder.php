<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class MarseillePlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Olympique de Marseille
        $marseille = Equipo::where('nombre', 'Olympique de Marseille')->first();
        if (!$marseille) {
            $this->command->error('Olympique de Marseille team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $marseille->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $marseille->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Benjamin Pavard', 'Francia', 'DFC', 84, 75, 67, 76, 75, 86, 79],
            ['Gerónimo Rulli', 'Argentina', 'POR', 82, 82, 77, 80, 83, 54, 81],
            ['Pierre-Emile Højbjerg', 'Dinamarca', 'MCD', 82, 49, 73, 78, 74, 78, 80],
            ['Mason Greenwood', 'Inglaterra', 'MD', 82, 83, 83, 77, 83, 37, 63],
            ['Leonardo Balerdi', 'Argentina', 'DFC', 81, 77, 46, 65, 69, 82, 79],
            ['Pierre-Emerick Aubameyang', 'Gabón', 'DC', 81, 82, 83, 72, 78, 36, 63],
            ['Nayef Aguerd', 'Marruecos', 'DFC', 81, 72, 50, 67, 65, 82, 77],
            ['Igor Paixão', 'Brasil', 'MI', 80, 83, 80, 75, 81, 32, 70],
            ['Amine Gouiri', 'Argelia', 'DC', 79, 77, 82, 76, 80, 45, 71],
            ['Facundo Medina', 'Argentina', 'DFC', 79, 74, 44, 70, 72, 79, 81],
            ['Matt O\'Riley', 'Dinamarca', 'MCO', 78, 68, 78, 79, 76, 67, 73],
            ['Geoffrey Kondogbia', 'República Centroafricana', 'DFC', 78, 65, 69, 73, 71, 78, 81],
            ['Timothy Weah', 'Estados Unidos', 'MD', 77, 87, 75, 72, 78, 68, 65],
            ['Emerson', 'Italia', 'LI', 77, 69, 63, 75, 76, 74, 65],
            ['Hamed Junior Traoré', 'Costa de Marfil', 'MI', 77, 78, 70, 72, 80, 57, 61],
            ['Arthur Vermeeren', 'Bélgica', 'MC', 77, 70, 55, 77, 79, 68, 66],
            ['Angel Gomes', 'Inglaterra', 'MCO', 76, 69, 70, 75, 79, 72, 67],
            ['Amir Murillo', 'Panamá', 'LD', 76, 84, 57, 70, 71, 70, 76],
            ['Ulisses Garcia', 'Suiza', 'LI', 75, 79, 49, 66, 71, 70, 74],
            ['Amine Harit', 'Marruecos', 'MCO', 75, 75, 64, 73, 79, 59, 57],
            ['Neal Maupay', 'Francia', 'DC', 75, 67, 76, 68, 75, 42, 71],
            ['CJ Egan-Riley', 'Inglaterra', 'DFC', 75, 76, 32, 61, 67, 75, 78],
            ['Rubén Blanco', 'España', 'POR', 73, 76, 72, 73, 74, 53, 70],
            ['Jeffrey de Lange', 'Holanda', 'POR', 73, 73, 69, 74, 73, 56, 73],
            ['Pol Lirola', 'España', 'LD', 73, 72, 61, 69, 69, 69, 66],
            ['Bilal Nadir', 'Marruecos', 'MC', 71, 56, 64, 68, 73, 59, 65],
            ['François Mughe', 'Camerún', 'MD', 63, 87, 59, 56, 64, 35, 54],
            ['Robinio Vaz', 'Francia', 'DC', 63, 79, 60, 54, 64, 28, 55],
            ['Darryl Bakola', 'Francia', 'MCO', 62, 64, 55, 59, 67, 51, 66],
            ['Keyliane Abdallah', 'Francia', 'MD', 62, 76, 56, 57, 64, 25, 45],
            ['Yanis Sellami', 'Argelia', 'MC', 61, 56, 51, 65, 66, 45, 46],
            ['Théo Vermot', 'Francia', 'POR', 59, 62, 54, 61, 64, 24, 56],
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

        $this->command->info('Todos los jugadores de Olympique de Marseille han sido agregados correctamente');
    }
}
