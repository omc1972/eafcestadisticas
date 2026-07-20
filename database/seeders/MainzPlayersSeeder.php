<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class MainzPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Mainz
        $mainz = Equipo::where('nombre', 'Mainz')->first();
        if (!$mainz) {
            $this->command->error('Mainz team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $mainz->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $mainz->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Nadiem Amiri', 'Alemania', 'MC', 81, 65, 79, 82, 82],
            ['Kaishū Sano', 'Japón', 'MCD', 79, 79, 51, 67, 76],
            ['Robin Zentner', 'Alemania', 'POR', 78, 81, 74, 59, 83],
            ['Anthony Caci', 'Francia', 'LD', 78, 72, 56, 72, 72],
            ['Paul Nebel', 'Alemania', 'MCO', 78, 71, 75, 72, 82],
            ['Lee Jae Sung', 'República de Corea', 'MCO', 77, 72, 71, 72, 81],
            ['Dominik Kohr', 'Alemania', 'DFC', 77, 74, 63, 60, 69],
            ['Benedict Hollerbach', 'Alemania', 'DC', 77, 85, 76, 66, 79],
            ['Phillipp Mwene', 'Austria', 'LI', 76, 78, 55, 69, 75],
            ['Danny da Costa', 'Alemania', 'DFC', 75, 80, 56, 64, 69],
            ['Andreas Hanche-Olsen', 'Noruega', 'DFC', 75, 70, 38, 57, 63],
            ['Stefan Bell', 'Alemania', 'DFC', 74, 42, 38, 52, 48],
            ['Arnaud Nordin', 'Francia', 'MD', 73, 76, 70, 70, 74],
            ['Silvan Widmer', 'Suiza', 'LD', 72, 73, 62, 70, 71],
            ['Lennard Maloney', 'Estados Unidos', 'MCD', 72, 62, 44, 56, 60],
            ['Maxim Leitsch', 'Alemania', 'DFC', 72, 72, 37, 57, 58],
            ['Sota Kawasaki', 'Japón', 'MC', 71, 71, 62, 64, 75],
            ['William Bøving', 'Dinamarca', 'DC', 71, 81, 69, 66, 73],
            ['Armindo Sieb', 'Alemania', 'DC', 71, 83, 70, 57, 76],
            ['Nelson Weiper', 'Alemania', 'DC', 70, 75, 67, 48, 67],
            ['Nikolas Veratschnig', 'Austria', 'LD', 69, 83, 52, 59, 66],
            ['Daniel Batz', 'Alemania', 'POR', 68, 67, 67, 64, 69],
            ['Niklas Tauer', 'Alemania', 'MCD', 66, 66, 46, 57, 64],
            ['Lasse Rieß', 'Alemania', 'POR', 66, 64, 64, 62, 68],
            ['Ben Bobzien', 'Alemania', 'DC', 66, 73, 68, 58, 71],
            ['Kasey Bos', 'Australia', 'LI', 65, 73, 46, 54, 59],
            ['Daniel Gleiber', 'Alemania', 'MC', 61, 60, 56, 64, 66],
            ['Maxim Dal', 'Alemania', 'DFC', 58, 58, 41, 39, 46],
            ['Konstantin Schopp', 'Austria', 'DFC', 56, 59, 25, 37, 37],
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

        $this->command->info('Todos los jugadores de Mainz han sido agregados correctamente');
    }
}
