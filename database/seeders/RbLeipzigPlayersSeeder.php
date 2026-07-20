<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class RbLeipzigPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo RasenBallsport Leipzig
        $leipzig = Equipo::where('nombre', 'RasenBallsport Leipzig')->first();
        if (!$leipzig) {
            $this->command->error('RasenBallsport Leipzig team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $leipzig->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $leipzig->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Péter Gulácsi', 'Hungría', 'POR', 85, 83, 83, 74, 84],
            ['Willi Orban', 'Hungría', 'DFC', 84, 57, 40, 56, 56],
            ['David Raum', 'Alemania', 'LI', 82, 86, 61, 80, 79],
            ['Xaver Schlager', 'Austria', 'MCD', 80, 69, 70, 74, 78],
            ['Benjamin Henrichs', 'Alemania', 'LD', 80, 73, 54, 76, 78],
            ['Castello Lukeba', 'Francia', 'DFC', 80, 72, 43, 68, 73],
            ['Ridle Baku', 'Alemania', 'MD', 78, 81, 72, 73, 81],
            ['Lukas Klostermann', 'Alemania', 'DFC', 78, 82, 50, 64, 70],
            ['Johan Bakayoko', 'Bélgica', 'ED', 78, 85, 72, 70, 81],
            ['Nicolas Seiwald', 'Austria', 'MCD', 77, 72, 62, 74, 74],
            ['Amadou Haidara', 'Malí', 'MC', 77, 63, 70, 77, 79],
            ['Kevin Kampl', 'Eslovenia', 'MC', 77, 66, 66, 79, 81],
            ['Timo Werner', 'Alemania', 'EI', 77, 91, 74, 68, 77],
            ['Christoph Baumgartner', 'Austria', 'MCO', 77, 74, 72, 72, 79],
            ['Maarten Vandevoordt', 'Bélgica', 'POR', 76, 78, 75, 75, 77],
            ['Antonio Nusa', 'Noruega', 'MI', 76, 89, 72, 68, 81],
            ['Rômulo', 'Brasil', 'DC', 76, 82, 75, 63, 75],
            ['El Chadaille Bitshiabu', 'Francia', 'DFC', 75, 72, 36, 60, 56],
            ['Conrad Harder', 'Dinamarca', 'DC', 74, 75, 76, 66, 71],
            ['Kosta Nedeljković', 'Serbia', 'LD', 72, 81, 44, 65, 69],
            ['Andrija Maksimović', 'Serbia', 'ED', 71, 71, 70, 72, 70],
            ['Ezechiel Banzuzi', 'Holanda', 'MC', 70, 66, 61, 64, 68],
            ['Leopold Zingerle', 'Alemania', 'POR', 69, 70, 66, 66, 71],
            ['Max Finkgräfe', 'Alemania', 'LI', 69, 75, 43, 61, 73],
            ['Assan Ouédraogo', 'Alemania', 'MCO', 69, 76, 56, 60, 76],
            ['Tidiam Gomis', 'Francia', 'MI', 68, 85, 55, 59, 73],
            ['Yan Diomande', 'Costa de Marfil', 'MI', 66, 71, 61, 62, 64],
            ['Viggo Gebel', 'Alemania', 'MCO', 63, 72, 57, 61, 69],
            ['Joyeux Masanka Bungi', 'Bélgica', 'MI', 61, 67, 51, 61, 58],
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
        foreach ($jugadores_data as $index => $jugador_info) {
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

            // Determinar si es titular (primeros 11) o suplente
            $es_titular = $index < 11 ? true : false;

            // Asociar jugador a la plantilla
            $plantilla->jugadores()->attach($jugador->id, [
                'dorsal' => $dorsal,
                'es_titular' => $es_titular,
            ]);

            $this->command->info("Jugador '$nombre' agregado a la plantilla");
            $dorsal++;
        }

        $this->command->info('Todos los jugadores de RasenBallsport Leipzig han sido agregados correctamente');
    }
}
