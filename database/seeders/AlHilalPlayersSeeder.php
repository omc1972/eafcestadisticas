<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class AlHilalPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Al Hilal
        $hilal = Equipo::where('nombre', 'Al Hilal')->first();
        if (!$hilal) {
            $this->command->error('Al Hilal team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $hilal->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $hilal->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Theo Hernández', 'Francia', 'LI', 84, 90, 76, 78, 83],
            ['João Cancelo', 'Portugal', 'LD', 84, 83, 73, 84, 84],
            ['Sergej Milinković-Savić', 'Serbia', 'MC', 84, 62, 80, 81, 81],
            ['Rúben Neves', 'Portugal', 'MCD', 84, 57, 75, 87, 77],
            ['Salem Al Dawsari', 'Arabia Saudí', 'MI', 82, 87, 75, 75, 84],
            ['Malcom', 'Brasil', 'MCO', 82, 89, 78, 79, 84],
            ['Yassine Bounou', 'Marruecos', 'POR', 82, 81, 82, 76, 85],
            ['Kalidou Koulibaly', 'Senegal', 'DFC', 82, 74, 48, 62, 65],
            ['Darwin Núñez', 'Uruguay', 'DC', 79, 88, 79, 72, 76],
            ['Marcos Leonardo', 'Brasil', 'DC', 79, 78, 78, 63, 75],
            ['Renan Lodi', 'Brasil', 'LI', 78, 75, 69, 76, 77],
            ['Kaio César', 'Brasil', 'MD', 73, 86, 60, 65, 80],
            ['Mohammed Kanno', 'Arabia Saudí', 'MC', 72, 72, 69, 70, 71],
            ['Hassan Tombakti', 'Arabia Saudí', 'DFC', 72, 83, 35, 57, 64],
            ['Ali Lajami', 'Arabia Saudí', 'DFC', 70, 76, 48, 58, 60],
            ['Ali Al Bulayhi', 'Arabia Saudí', 'DFC', 70, 41, 42, 43, 42],
            ['Nasser Al Dawsari', 'Arabia Saudí', 'MC', 68, 68, 60, 70, 68],
            ['Muteb Al Harbi', 'Arabia Saudí', 'LI', 67, 93, 54, 62, 67],
            ['Hamad Al Yami', 'Arabia Saudí', 'LD', 65, 85, 56, 58, 64],
            ['Abdulelah Al Malki', 'Arabia Saudí', 'MCD', 65, 57, 55, 61, 63],
            ['Yusuf Akçiçek', 'Turquía', 'DFC', 65, 64, 30, 49, 57],
            ['Abdullah Al Hamdan', 'Arabia Saudí', 'DC', 64, 73, 62, 63, 61],
            ['Mathieu Patouillet', 'Francia', 'POR', 64, 65, 61, 57, 67],
            ['Mohammed Al Rubaie', 'Arabia Saudí', 'POR', 62, 67, 55, 60, 64],
            ['Mohammed Al Qahtani', 'Arabia Saudí', 'MD', 62, 80, 50, 54, 69],
            ['Abdullah Radif', 'Arabia Saudí', 'DC', 61, 84, 57, 38, 53],
            ['Suhayb Al Zaid', 'Arabia Saudí', 'MCD', 57, 68, 47, 57, 59],
            ['Abdulkarim Darisi', 'Arabia Saudí', 'MD', 57, 67, 40, 51, 57],
            ['Abdulaziz Hadhood', 'Arabia Saudí', 'MCD', 53, 67, 45, 49, 53],
            ['Ahmed Abo Rasain', 'Arabia Saudí', 'POR', 52, 56, 48, 50, 52],
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

        $this->command->info('Todos los jugadores de Al Hilal han sido agregados correctamente');
    }
}
