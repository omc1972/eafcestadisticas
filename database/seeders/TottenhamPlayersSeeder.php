<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class TottenhamPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Tottenham
        $tottenham = Equipo::where('nombre', 'Totenhan Hotspur')->first();
        if (!$tottenham) {
            $this->command->error('Tottenham team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $tottenham->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $tottenham->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Xavi Simons', 'Holanda', 'MCO', 84, 77, 77, 80, 87, 61, 70],
            ['James Maddison', 'Inglaterra', 'MC', 84, 67, 81, 86, 85, 58, 64],
            ['Dejan Kulusevski', 'Suecia', 'MC', 83, 74, 79, 84, 85, 62, 81],
            ['Palhinha', 'Portugal', 'MCD', 83, 56, 68, 70, 72, 84, 85],
            ['Pedro Porro', 'España', 'LD', 82, 78, 73, 81, 80, 77, 76],
            ['Guglielmo Vicario', 'Italia', 'POR', 82, 83, 78, 78, 84, 52, 80],
            ['Micky van de Ven', 'Holanda', 'DFC', 82, 90, 49, 64, 72, 82, 80],
            ['Cristian Romero', 'Argentina', 'DFC', 82, 66, 48, 62, 65, 83, 81],
            ['Randal Kolo Muani', 'Francia', 'DC', 81, 90, 79, 71, 80, 38, 65],
            ['Destiny Udogie', 'Italia', 'LI', 80, 88, 64, 73, 78, 76, 79],
            ['Mohammed Kudus', 'Ghana', 'ED', 80, 89, 76, 72, 86, 60, 74],
            ['Rodrigo Bentancur', 'Uruguay', 'MCD', 80, 64, 68, 78, 79, 78, 72],
            ['Dominic Solanke', 'Inglaterra', 'DC', 80, 71, 80, 66, 77, 42, 83],
            ['Pape Matar Sarr', 'Senegal', 'MC', 79, 68, 66, 76, 78, 77, 76],
            ['Brennan Johnson', 'Gales', 'ED', 79, 86, 76, 72, 78, 43, 59],
            ['Kevin Danso', 'Austria', 'DFC', 79, 73, 32, 57, 62, 78, 83],
            ['Yves Bissouma', 'Malí', 'MCD', 78, 66, 66, 74, 79, 76, 74],
            ['Richarlison', 'Brasil', 'DC', 78, 76, 78, 72, 78, 53, 75],
            ['Djed Spence', 'Inglaterra', 'LD', 78, 88, 44, 71, 79, 74, 72],
            ['Lucas Bergvall', 'Suecia', 'MC', 77, 75, 63, 76, 77, 71, 73],
            ['Mathys Tel', 'Francia', 'DC', 77, 86, 80, 67, 78, 29, 64],
            ['Archie Gray', 'Inglaterra', 'MCD', 75, 71, 60, 71, 73, 73, 73],
            ['Antonín Kinský', 'República Checa', 'POR', 75, 76, 76, 76, 75, 25, 74],
            ['Ben Davies', 'Gales', 'DFC', 75, 53, 58, 71, 71, 77, 68],
            ['Wilson Odobert', 'Francia', 'EI', 75, 86, 70, 68, 78, 30, 49],
            ['Radu Drăgușin', 'Rumanía', 'DFC', 75, 67, 37, 53, 65, 75, 74],
            ['Kota Takai', 'Japón', 'DFC', 72, 66, 32, 55, 62, 72, 69],
            ['Brandon Austin', 'Inglaterra', 'POR', 67, 69, 65, 64, 66, 41, 65],
            ['Dane Scarlett', 'Inglaterra', 'DC', 65, 75, 63, 52, 66, 23, 63],
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

        $this->command->info('Todos los jugadores de Tottenham han sido agregados correctamente');
    }
}
