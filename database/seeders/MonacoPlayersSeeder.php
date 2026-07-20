<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class MonacoPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo AS Monaco
        $monaco = Equipo::where('nombre', 'Association Sportive Monaco Football Club')->first();
        if (!$monaco) {
            $this->command->error('Association Sportive Monaco Football Club team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $monaco->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $monaco->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Denis Zakaria', 'Suiza', 'MCD', 82, 80, 67, 74, 77],
            ['Lukáš Hrádecký', 'Finlandia', 'POR', 81, 83, 79, 64, 83],
            ['Maghnes Akliouche', 'Francia', 'MD', 80, 74, 70, 78, 83],
            ['Paul Pogba', 'Francia', 'MC', 79, 64, 76, 81, 83],
            ['Alexandr Golovin', 'Rusia', 'MI', 79, 73, 76, 79, 82],
            ['Eric Dier', 'Inglaterra', 'DFC', 79, 54, 62, 67, 62],
            ['Thilo Kehrer', 'Alemania', 'DFC', 79, 69, 44, 66, 66],
            ['Takumi Minamino', 'Japón', 'MI', 78, 77, 74, 75, 81],
            ['Mika Biereth', 'Dinamarca', 'DC', 78, 75, 78, 63, 72],
            ['Lamine Camara', 'Senegal', 'MC', 77, 79, 66, 78, 77],
            ['Vanderson', 'Brasil', 'LD', 77, 78, 62, 74, 77],
            ['Caio Henrique', 'Brasil', 'LI', 77, 78, 58, 77, 77],
            ['Philipp Köhn', 'Suiza', 'POR', 77, 78, 74, 69, 78],
            ['Folarin Balogun', 'Estados Unidos', 'DC', 77, 84, 77, 62, 77],
            ['Jordan Teze', 'Holanda', 'LD', 76, 77, 49, 69, 69],
            ['Mohammed Salisu', 'Ghana', 'DFC', 76, 59, 36, 50, 54],
            ['Ansu Fati', 'España', 'MI', 75, 81, 72, 72, 78],
            ['Christian Mawissa', 'Francia', 'DFC', 75, 79, 31, 57, 64],
            ['Krépin Diatta', 'Senegal', 'MD', 74, 88, 69, 67, 76],
            ['Kassoum Ouattara', 'Francia', 'LI', 72, 77, 44, 65, 69],
            ['George Ilenikhena', 'Nigeria', 'DC', 72, 79, 71, 56, 71],
            ['Stanis Idumbo', 'Bélgica', 'MCO', 67, 78, 61, 61, 69],
            ['Paris Brunner', 'Alemania', 'DC', 66, 67, 67, 54, 68],
            ['Yann Lienard', 'Francia', 'POR', 65, 65, 61, 60, 66],
            ['Mamadou Coulibaly', 'Francia', 'MC', 64, 59, 53, 61, 64],
            ['Lucas Michal', 'Francia', 'MI', 64, 74, 58, 58, 68],
            ['Aladji Bamba', 'Francia', 'MCD', 63, 60, 53, 59, 59],
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

        $this->command->info('Todos los jugadores de AS Monaco han sido agregados correctamente');
    }
}
