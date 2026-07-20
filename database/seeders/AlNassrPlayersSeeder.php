<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class AlNassrPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Al-Nassr Football Club
        $nassr = Equipo::where('nombre', 'Al-Nassr Football Club')->first();
        if (!$nassr) {
            $this->command->error('Al-Nassr Football Club team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $nassr->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $nassr->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Iñigo Martínez', 'España', 'DFC', 85, 71, 57, 72, 68],
            ['Cristiano Ronaldo', 'Portugal', 'DC', 85, 76, 88, 76, 80],
            ['Sadio Mané', 'Senegal', 'MI', 83, 82, 81, 78, 85],
            ['Kingsley Coman', 'Francia', 'MI', 83, 87, 75, 79, 87],
            ['Aymeric Laporte', 'España', 'DFC', 82, 51, 50, 74, 70],
            ['Marcelo Brozović', 'Croacia', 'MCD', 81, 63, 73, 78, 79],
            ['Mohamed Simakan', 'Francia', 'DFC', 81, 76, 43, 65, 69],
            ['Bento', 'Brasil', 'POR', 80, 79, 76, 77, 83],
            ['João Félix', 'Portugal', 'MCO', 79, 77, 78, 77, 82],
            ['Ângelo', 'Brasil', 'MD', 73, 84, 60, 69, 80],
            ['Sultan Al Ghannam', 'Arabia Saudí', 'LD', 72, 82, 60, 72, 74],
            ['Nawaf Al Aqidi', 'Arabia Saudí', 'POR', 72, 75, 69, 67, 74],
            ['Wesley', 'Brasil', 'EI', 71, 73, 68, 63, 75],
            ['Abdulrahman Ghareeb', 'Arabia Saudí', 'MI', 70, 84, 62, 64, 74],
            ['Abdulelah Al Amri', 'Arabia Saudí', 'DFC', 70, 69, 29, 51, 47],
            ['Abdullah Al Khaibari', 'Arabia Saudí', 'MCD', 68, 73, 43, 63, 66],
            ['Ayman Yahya', 'Arabia Saudí', 'MD', 68, 84, 61, 59, 67],
            ['Nawaf Boushal', 'Arabia Saudí', 'LI', 67, 82, 39, 51, 64],
            ['Nader Al Sharari', 'Arabia Saudí', 'DFC', 67, 72, 25, 48, 47],
            ['Ali Al Hassan', 'Arabia Saudí', 'MCD', 66, 68, 53, 60, 64],
            ['Saad Al Nasser', 'Arabia Saudí', 'LI', 65, 83, 59, 57, 68],
            ['Mohammed Al Fatil', 'Arabia Saudí', 'DFC', 65, 57, 30, 49, 49],
            ['Sami Al Najai', 'Arabia Saudí', 'MCO', 63, 65, 59, 60, 66],
            ['Salem Al Najdi', 'Arabia Saudí', 'LI', 63, 68, 35, 48, 57],
            ['Haroune Camara', 'Arabia Saudí', 'DC', 61, 83, 59, 50, 59],
            ['Raghid Al Najjar', 'Arabia Saudí', 'POR', 59, 62, 55, 56, 59],
            ['Mansour Al Shammari', 'Arabia Saudí', 'LI', 58, 68, 28, 50, 56],
            ['Meshari Al Nemer', 'Arabia Saudí', 'DC', 58, 71, 53, 40, 55],
            ['Mohammed Marran', 'Arabia Saudí', 'DC', 57, 63, 56, 41, 55],
            ['Rakan Al Ghamdi', 'Arabia Saudí', 'MCO', 53, 69, 44, 53, 56],
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

        $this->command->info('Todos los jugadores de Al-Nassr Football Club han sido agregados correctamente');
    }
}
