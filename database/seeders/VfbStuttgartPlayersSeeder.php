<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class VfbStuttgartPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo VfB Stuttgart
        $stuttgart = Equipo::where('nombre', 'VfB Stuttgart')->first();
        if (!$stuttgart) {
            $this->command->error('VfB Stuttgart team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $stuttgart->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $stuttgart->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Angelo Stiller', 'Alemania', 'MCD', 83, 56, 58, 83, 84],
            ['Maximilian Mittelstädt', 'Alemania', 'LI', 82, 81, 62, 77, 77],
            ['Alexander Nübel', 'Alemania', 'POR', 81, 81, 76, 84, 82],
            ['Ermedin Demirović', 'Bosnia y Herzegovina', 'DC', 80, 72, 81, 73, 78],
            ['Deniz Undav', 'Alemania', 'DC', 80, 66, 82, 74, 80],
            ['Julian Chabot', 'Alemania', 'DFC', 78, 47, 29, 48, 56],
            ['Chris Führich', 'Alemania', 'MI', 77, 84, 69, 71, 83],
            ['Tiago Tomás', 'Portugal', 'EI', 76, 86, 74, 67, 79],
            ['Lorenz Assignon', 'Francia', 'LD', 76, 76, 53, 64, 73],
            ['Bilal El Khannouss', 'Marruecos', 'MCO', 76, 76, 64, 76, 82],
            ['Atakan Karazor', 'Turquía', 'MCD', 76, 45, 36, 63, 68],
            ['Dan-Axel Zagadou', 'Francia', 'DFC', 76, 46, 34, 60, 51],
            ['Jamie Leweling', 'Alemania', 'MD', 75, 83, 71, 65, 78],
            ['Badredine Bouanani', 'Argelia', 'ED', 75, 70, 68, 75, 78],
            ['Josha Vagnoman', 'Alemania', 'LD', 74, 87, 61, 69, 72],
            ['Silas', 'RD Congo', 'MD', 74, 90, 69, 61, 79],
            ['Finn Jeltsch', 'Alemania', 'DFC', 72, 69, 38, 56, 62],
            ['Ameen Al Dakhil', 'Bélgica', 'DFC', 72, 68, 27, 51, 62],
            ['Leonidas Stergiou', 'Suiza', 'DFC', 71, 78, 42, 60, 67],
            ['Fabian Bredlow', 'Alemania', 'POR', 71, 71, 67, 66, 74],
            ['Yannik Keitel', 'Alemania', 'MCD', 71, 63, 48, 63, 66],
            ['Pascal Stenzel', 'Alemania', 'LD', 70, 53, 61, 70, 68],
            ['Ramon Hendriks', 'Holanda', 'DFC', 70, 81, 33, 55, 58],
            ['Luca Jaquez', 'Suiza', 'DFC', 69, 64, 42, 52, 62],
            ['Nikolas Nartey', 'Dinamarca', 'MC', 68, 67, 49, 67, 73],
            ['Stefan Drljača', 'Alemania', 'POR', 66, 68, 62, 55, 71],
            ['Justin Diehl', 'Alemania', 'MI', 66, 89, 65, 55, 70],
            ['Lazar Jovanović', 'Serbia', 'MD', 64, 75, 56, 57, 70],
            ['Chema Andrés', 'España', 'MCD', 63, 58, 39, 61, 57],
            ['Noah Darvich', 'Alemania', 'MCO', 62, 73, 51, 58, 69],
            ['Mirza Ćatović', 'Serbia', 'MCD', 60, 58, 46, 55, 59],
            ['Florian Hellstern', 'Alemania', 'POR', 59, 63, 58, 60, 63],
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

        $this->command->info('Todos los jugadores de VfB Stuttgart han sido agregados correctamente');
    }
}
