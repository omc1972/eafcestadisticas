<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class HoffenheimPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Hoffenheim
        $hoffenheim = Equipo::where('nombre', 'Hoffenheim')->first();
        if (!$hoffenheim) {
            $this->command->error('Hoffenheim team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $hoffenheim->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $hoffenheim->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Oliver Baumann', 'Alemania', 'POR', 83, 83, 81, 78, 83],
            ['Andrej Kramarić', 'Croacia', 'MCO', 81, 67, 82, 78, 84],
            ['Adam Hložek', 'República Checa', 'DC', 77, 78, 78, 72, 77],
            ['Koki Machida', 'Japón', 'DFC', 76, 78, 40, 60, 56],
            ['Grischa Prömel', 'Alemania', 'MC', 75, 62, 72, 70, 73],
            ['Bernardo', 'Brasil', 'DFC', 75, 69, 53, 63, 68],
            ['Mergim Berisha', 'Alemania', 'DC', 75, 69, 76, 72, 75],
            ['Ihlas Bebou', 'Togo', 'DC', 75, 82, 74, 68, 78],
            ['Ozan Kabak', 'Turquía', 'DFC', 75, 60, 37, 59, 66],
            ['Alexander Prass', 'Austria', 'LI', 74, 74, 65, 74, 75],
            ['Kevin Akpoguma', 'Nigeria', 'DFC', 74, 76, 48, 59, 70],
            ['Valentin Gendrey', 'Francia', 'LD', 74, 75, 37, 60, 67],
            ['Arthur Chaves', 'Brasil', 'DFC', 74, 78, 37, 51, 52],
            ['Wouter Burger', 'Holanda', 'MCD', 73, 75, 63, 69, 68],
            ['Vladimír Coufal', 'República Checa', 'LD', 73, 58, 56, 70, 70],
            ['Tim Lemperle', 'Alemania', 'DC', 73, 75, 71, 60, 73],
            ['Dennis Geiger', 'Alemania', 'MCD', 73, 55, 53, 75, 78],
            ['Fisnik Asllani', 'Kosovo', 'DC', 73, 65, 74, 59, 72],
            ['Muhammed Damar', 'Alemania', 'MCO', 71, 68, 66, 69, 73],
            ['Robin Hranáč', 'República Checa', 'DFC', 71, 75, 38, 51, 52],
            ['Leon Avdullahu', 'Kosovo', 'MCD', 70, 60, 59, 73, 69],
            ['Umut Tohumcu', 'Alemania', 'MCO', 70, 68, 62, 68, 72],
            ['Bazoumana Touré', 'Costa de Marfil', 'MI', 70, 91, 59, 62, 71],
            ['Albian Hajdari', 'Suiza', 'DFC', 70, 60, 32, 48, 51],
            ['Luca Philipp', 'Alemania', 'POR', 68, 68, 64, 64, 70],
            ['Max Moerstedt', 'Alemania', 'DC', 67, 61, 66, 46, 62],
            ['David Mokwa', 'Francia', 'DC', 64, 67, 64, 47, 64],
            ['Deniz Zeitler', 'Alemania', 'DC', 63, 78, 62, 54, 64],
            ['Hennes Behrens', 'Alemania', 'LI', 62, 63, 31, 48, 57],
            ['Luka Đurić', 'Alemania', 'MC', 61, 62, 51, 59, 58],
            ['Lúkas Petersson', 'Islandia', 'POR', 60, 60, 59, 57, 60],
            ['Kelven Frees', 'Alemania', 'DFC', 60, 58, 23, 38, 37],
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

        $this->command->info('Todos los jugadores de Hoffenheim han sido agregados correctamente');
    }
}
