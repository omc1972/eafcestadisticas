<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class AlIttihadPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Al Ittihad
        $ittihad = Equipo::where('nombre', 'Al Ittihad')->first();
        if (!$ittihad) {
            $this->command->error('Al Ittihad team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $ittihad->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $ittihad->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['N\'Golo Kanté', 'Francia', 'MCD', 85, 77, 65, 73, 80],
            ['Karim Benzema', 'Francia', 'DC', 85, 75, 84, 81, 83],
            ['Moussa Diaby', 'Francia', 'MD', 84, 95, 75, 77, 87],
            ['Fabinho', 'Brasil', 'MCD', 82, 53, 69, 78, 77],
            ['Danilo Pereira', 'Portugal', 'DFC', 81, 47, 64, 68, 71],
            ['Steven Bergwijn', 'Holanda', 'MI', 80, 80, 78, 76, 84],
            ['Predrag Rajković', 'Serbia', 'POR', 80, 78, 78, 76, 84],
            ['Houssem Aouar', 'Argelia', 'MCO', 77, 66, 72, 75, 82],
            ['Roger Fernandes', 'Portugal', 'MD', 76, 89, 68, 67, 80],
            ['Hassan Kadish', 'Arabia Saudí', 'LI', 71, 75, 49, 62, 63],
            ['Ahmed Sharahili', 'Arabia Saudí', 'DFC', 71, 72, 33, 60, 63],
            ['Abdulrahman Al Obud', 'Arabia Saudí', 'MD', 70, 88, 63, 59, 72],
            ['Muhannad Al Shanqeeti', 'Arabia Saudí', 'LD', 70, 82, 42, 62, 66],
            ['Saleh Al Shehri', 'Arabia Saudí', 'DC', 69, 66, 67, 51, 64],
            ['Mahamadou Doumbia', 'Malí', 'MCD', 68, 76, 68, 68, 75],
            ['Mario Mitaj', 'Albania', 'LI', 68, 77, 35, 65, 67],
            ['Faisal Al Ghamdi', 'Arabia Saudí', 'MC', 67, 66, 53, 63, 70],
            ['Bryant Ortega', 'Venezuela', 'MCD', 66, 73, 61, 64, 66],
            ['Fawaz Al Sagour', 'Arabia Saudí', 'LD', 66, 91, 46, 59, 64],
            ['Unai Hernández', 'España', 'MI', 66, 80, 59, 58, 70],
            ['Ahmed Al Ghamdi', 'Arabia Saudí', 'MI', 65, 80, 60, 58, 69],
            ['Saad Al Mousa', 'Arabia Saudí', 'DFC', 65, 64, 25, 53, 56],
            ['Abdulaziz Al Bishi', 'Arabia Saudí', 'MD', 63, 84, 56, 58, 66],
            ['Hamed Al Ghamdi', 'Arabia Saudí', 'MC', 62, 68, 62, 63, 70],
            ['Muath Faqihi', 'Arabia Saudí', 'LI', 60, 74, 33, 47, 55],
            ['Awadh Al Nashri', 'Arabia Saudí', 'MCD', 59, 51, 31, 48, 50],
            ['Farha Al Shamrani', 'Arabia Saudí', 'MC', 58, 67, 44, 56, 63],
            ['Ahmed Al Julaydan', 'Arabia Saudí', 'LD', 57, 65, 31, 50, 53],
            ['Osama Al Mermesh', 'Arabia Saudí', 'POR', 55, 56, 51, 50, 59],
            ['Mohammed Al Absi', 'Arabia Saudí', 'POR', 55, 57, 55, 55, 56],
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

        $this->command->info('Todos los jugadores de Al Ittihad han sido agregados correctamente');
    }
}
