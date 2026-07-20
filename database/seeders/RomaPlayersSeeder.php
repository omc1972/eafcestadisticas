<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class RomaPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Associazione Sportiva Roma
        $roma = Equipo::where('nombre', 'Associazione Sportiva Roma')->first();
        if (!$roma) {
            $this->command->error('Associazione Sportiva Roma team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $roma->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $roma->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Paulo Dybala', 'Argentina', 'MCO', 86, 80, 85, 84, 87, 41, 64],
            ['Artem Dovbyk', 'Ucrania', 'DC', 83, 83, 84, 68, 78, 34, 79],
            ['Gianluca Mancini', 'Italia', 'DFC', 83, 70, 50, 57, 64, 85, 82],
            ['Mile Svilar', 'Serbia', 'POR', 82, 80, 80, 77, 85, 58, 82],
            ['Evan Ndicka', 'Costa de Marfil', 'DFC', 81, 74, 53, 69, 69, 81, 82],
            ['Lorenzo Pellegrini', 'Italia', 'MCO', 80, 75, 77, 80, 80, 73, 73],
            ['Bryan Cristante', 'Italia', 'MC', 80, 52, 74, 78, 75, 78, 81],
            ['Mario Hermoso', 'España', 'DFC', 80, 72, 49, 73, 71, 80, 79],
            ['Kouadio Manu Koné', 'Francia', 'MC', 79, 74, 74, 75, 81, 74, 82],
            ['Angeliño', 'España', 'LI', 79, 69, 70, 81, 80, 74, 69],
            ['Stephan El Shaarawy', 'Italia', 'MI', 79, 78, 75, 76, 83, 49, 59],
            ['Leon Bailey', 'Jamaica', 'MD', 79, 87, 74, 73, 84, 38, 61],
            ['Matías Soulé', 'Argentina', 'MCO', 78, 79, 74, 76, 81, 36, 58],
            ['Wesley', 'Brasil', 'LD', 77, 89, 62, 70, 78, 73, 76],
            ['Kostas Tsimikas', 'Grecia', 'LI', 77, 78, 57, 75, 75, 74, 72],
            ['Neil El Aynaoui', 'Marruecos', 'MC', 77, 62, 63, 76, 74, 73, 75],
            ['Zeki Çelik', 'Turquía', 'LD', 76, 72, 59, 69, 73, 73, 74],
            ['Tommaso Baldanzi', 'Italia', 'MCO', 75, 76, 71, 73, 78, 41, 48],
            ['Edoardo Bove', 'Italia', 'MC', 74, 66, 69, 68, 75, 76, 66],
            ['Pierluigi Gollini', 'Italia', 'POR', 74, 74, 72, 71, 76, 47, 74],
            ['Devyne Rensch', 'Holanda', 'LD', 74, 74, 56, 67, 71, 72, 66],
            ['Evan Ferguson', 'República de Irlanda', 'DC', 73, 66, 75, 60, 71, 20, 71],
            ['Niccolò Pisilli', 'Italia', 'MC', 72, 67, 66, 68, 73, 69, 62],
            ['Devis Vásquez', 'Colombia', 'POR', 72, 72, 68, 71, 75, 38, 70],
            ['Daniele Ghilardi', 'Italia', 'DFC', 72, 58, 41, 47, 50, 74, 71],
            ['Jan Ziółkowski', 'Polonia', 'DFC', 65, 62, 28, 44, 46, 64, 67],
            ['Radoslaw Zelezny', 'Polonia', 'POR', 64, 63, 68, 60, 64, 28, 64],
            ['Buba Sangaré', 'España', 'LD', 63, 63, 44, 56, 62, 61, 51],
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

            // Agregar a la plantilla con dorsal (todos suplentes)
            $plantilla->jugadores()->attach($jugador->id, [
                'dorsal' => $dorsal,
                'es_titular' => false
            ]);

            $dorsal++;

            $this->command->info("Jugador '{$nombre}' agregado a la plantilla");
        }

        $this->command->info('Todos los jugadores de AS Roma han sido agregados correctamente');
    }
}
