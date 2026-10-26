<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class FenerbahcePlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Fenerbahçe Spor Kulübü
        $fenerbahce = Equipo::where('nombre', 'Fenerbahçe Spor Kulübü')->first();
        if (!$fenerbahce) {
            $this->command->error('Fenerbahçe Spor Kulübü team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $fenerbahce->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $fenerbahce->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores: [nombre, nacionalidad, posicion, ritmo, tiro, pase, regate, defensa, fisico, general]
        $jugadores_data = [
            ['N\'Golo Kanté', 'Francia', 'MCD', 74, 65, 73, 80, 83, 79, 83],
            ['Mason Greenwood', 'Inglaterra', 'MD', 83, 85, 78, 84, 37, 63, 83],
            ['Ederson', 'Brasil', 'POR', 80, 81, 90, 81, 64, 83, 82],
            ['Romelu Lukaku', 'Bélgica', 'DC', 77, 82, 75, 73, 41, 78, 82],
            ['Marco Asensio', 'España', 'MCO', 73, 81, 82, 82, 44, 63, 82],
            ['Mattéo Guendouzi', 'Francia', 'MCD', 72, 73, 79, 80, 77, 79, 81],
            ['Anderson Talisca', 'Brasil', 'MCO', 78, 83, 80, 81, 40, 73, 81],
            ['Vedat Muriqi', 'Kosovo', 'DC', 62, 83, 66, 71, 33, 83, 81],
            ['Milan Škriniar', 'Eslovaquia', 'DFC', 54, 52, 60, 65, 83, 82, 81],
            ['Nathan Aké', 'Holanda', 'DFC', 66, 53, 71, 74, 82, 71, 80],
            ['Sofyan Amrabat', 'Marruecos', 'MCD', 73, 70, 74, 76, 76, 84, 79],
            ['Kerem Aktürkoğlu', 'Turquía', 'MI', 86, 76, 75, 79, 42, 58, 79],
            ['Dominik Livaković', 'Croacia', 'POR', 78, 75, 70, 80, 56, 78, 78],
            ['Diego Carlos', 'Brasil', 'DFC', 68, 46, 63, 67, 79, 69, 78],
            ['Nélson Semedo', 'Portugal', 'LD', 80, 63, 71, 78, 73, 73, 77],
            ['İsmail Yüksek', 'Turquía', 'MCD', 74, 59, 70, 75, 76, 79, 77],
            ['Jayden Oosterwolde', 'Holanda', 'DFC', 88, 42, 64, 72, 75, 81, 76],
            ['Dorgeles Nene', 'Malí', 'MD', 85, 73, 69, 76, 39, 62, 75],
            ['Oğuz Aydın', 'Turquía', 'MI', 84, 70, 68, 76, 56, 67, 74],
            ['İrfan Can Kahveci', 'Turquía', 'MD', 71, 74, 75, 75, 58, 67, 74],
            ['Çağlar Söyüncü', 'Turquía', 'DFC', 69, 47, 57, 64, 73, 79, 74],
            ['Archie Brown', 'Inglaterra', 'LI', 87, 54, 68, 73, 65, 80, 73],
            ['Mert Günok', 'Turquía', 'POR', 73, 73, 73, 72, 56, 73, 73],
            ['Levent Mercan', 'Turquía', 'LI', 74, 63, 74, 73, 68, 68, 73],
            ['Mert Müldür', 'Turquía', 'LD', 76, 54, 65, 72, 70, 75, 73],
            ['Cengiz Ünder', 'Turquía', 'MD', 74, 72, 74, 73, 36, 54, 73],
            ['Rodrigo Becão', 'Brasil', 'DFC', 51, 35, 53, 56, 71, 74, 71],
            ['Ognjen Mimović', 'Serbia', 'LD', 76, 44, 64, 70, 66, 71, 70],
            ['Tarık Çetin', 'Turquía', 'POR', 67, 69, 63, 73, 20, 69, 69],
            ['Bartuğ Elmaz', 'Turquía', 'MCD', 59, 56, 65, 67, 62, 63, 66],
            ['Yiğit Efe Demir', 'Turquía', 'DFC', 63, 30, 53, 54, 64, 69, 65],
            ['Emre Demir', 'Turquía', 'MCO', 73, 57, 64, 69, 37, 51, 64],
            ['Amara Diouf', 'Senegal', 'MI', 76, 66, 51, 66, 26, 52, 60],
        ];

        // Insertar jugadores
        $dorsal = 1;
        foreach ($jugadores_data as $jugador_info) {
            [$nombre, $nacionalidad, $posicion, $pace, $shooting, $passing, $dribbling, $defense, $physical, $general] = $jugador_info;

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
            $media = $general;

            // Crear jugador
            $jugador = Jugador::create([
                'nombre' => $nombre,
                'fecha_nacimiento' => '2000-01-01',
                'nacionalidad' => $nacionalidad,
                'altura' => '180',
                'peso' => '75',
                'posicion' => $posicion,
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

        $this->command->info('Todos los jugadores de Fenerbahçe Spor Kulübü han sido agregados correctamente');
    }
}
