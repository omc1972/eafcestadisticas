<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class EintrachtFrankfurtPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Eintracht Frankfurt
        $frankfurt = Equipo::where('nombre', 'Eintracht Frankfurt')->first();
        if (!$frankfurt) {
            $this->command->error('Eintracht Frankfurt team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $frankfurt->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $frankfurt->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Jonathan Burkardt', 'Alemania', 'DC', 82, 83, 82, 67, 81],
            ['Mario Götze', 'Alemania', 'MC', 82, 60, 75, 85, 85],
            ['Ritsu Doan', 'Japón', 'MD', 82, 82, 71, 78, 85],
            ['Robin Koch', 'Alemania', 'DFC', 82, 66, 44, 63, 66],
            ['Ellyes Skhiri', 'Túnez', 'MCD', 80, 64, 73, 74, 75],
            ['Arthur Theate', 'Bélgica', 'DFC', 80, 67, 40, 70, 71],
            ['Rasmus Kristensen', 'Dinamarca', 'LD', 79, 69, 65, 70, 72],
            ['Hugo Larsson', 'Suecia', 'MC', 78, 77, 73, 71, 78],
            ['Ansgar Knauff', 'Alemania', 'MD', 77, 89, 71, 69, 76],
            ['Nathaniel Brown', 'Alemania', 'LI', 77, 83, 53, 71, 77],
            ['Michy Batshuayi', 'Bélgica', 'DC', 77, 74, 78, 64, 75],
            ['Michael Zetterer', 'Alemania', 'POR', 76, 76, 71, 81, 77],
            ['Jean-Mattéo Bahoya', 'Francia', 'MI', 75, 94, 67, 67, 76],
            ['Elye Wahi', 'Francia', 'DC', 75, 84, 76, 67, 77],
            ['Farès Chaïbi', 'Argelia', 'MCO', 74, 69, 68, 75, 76],
            ['Nnamdi Collins', 'Alemania', 'DFC', 74, 86, 30, 64, 68],
            ['Can Uzun', 'Turquía', 'MCO', 74, 62, 75, 66, 77],
            ['Aurélio Buta', 'Portugal', 'LD', 73, 87, 60, 68, 76],
            ['Mahmoud Dahoud', 'Alemania', 'MC', 73, 63, 67, 72, 78],
            ['Oscar Højlund', 'Dinamarca', 'MC', 72, 78, 64, 68, 73],
            ['Elias Baum', 'Alemania', 'LD', 72, 75, 50, 61, 68],
            ['Kauã Santos', 'Brasil', 'POR', 71, 76, 65, 63, 77],
            ['Aurèle Amenda', 'Suiza', 'DFC', 71, 68, 27, 51, 48],
            ['Jessic Ngankam', 'Alemania', 'DC', 69, 80, 68, 59, 70],
            ['Timmy Chandler', 'Estados Unidos', 'LD', 64, 38, 67, 68, 64],
            ['Jens Grahl', 'Alemania', 'POR', 64, 68, 60, 58, 66],
            ['Noah Fenyő', 'Hungría', 'MCD', 61, 64, 57, 61, 62],
            ['Amil Šiljević', 'Alemania', 'POR', 58, 59, 54, 59, 62],
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

        $this->command->info('Todos los jugadores de Eintracht Frankfurt han sido agregados correctamente');
    }
}
