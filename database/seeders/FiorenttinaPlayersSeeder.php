<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class FiorenttinaPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Fiorentina
        $fiorentina = Equipo::where('nombre', 'Fiorentina')->first();
        if (!$fiorentina) {
            $this->command->error('Fiorentina team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $fiorentina->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $fiorentina->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['De Gea', 'España', 'PO', 85, 85, 78, 71, 87],
            ['Moise Kean', 'Italia', 'DC', 83, 86, 82, 58, 81],
            ['Edin Džeko', 'Bosnia y Herzegovina', 'DC', 81, 55, 82, 74, 77],
            ['Robin Gosens', 'Alemania', 'DFI', 80, 72, 74, 73, 76],
            ['Albert Guðmundsson', 'Islandia', 'DC', 79, 87, 79, 70, 83],
            ['Dodô', 'Brasil', 'DFD', 78, 86, 59, 74, 79],
            ['Nicolò Fagioli', 'Italia', 'MC', 77, 74, 65, 78, 79],
            ['Rolando Mandragora', 'Italia', 'MC', 77, 46, 73, 76, 75],
            ['Luca Ranieri', 'Italia', 'DFC', 76, 68, 41, 59, 69],
            ['Fabiano Parisi', 'Italia', 'DFI', 75, 75, 67, 70, 76],
            ['Hans Nicolussi Caviglia', 'Italia', 'MCD', 75, 66, 68, 77, 75],
            ['Roberto Piccoli', 'Italia', 'DC', 75, 73, 75, 55, 67],
            ['Simon Sohm', 'Suiza', 'MC', 74, 75, 69, 70, 71],
            ['Marin Pongračić', 'Croacia', 'DFC', 74, 61, 26, 52, 62],
            ['Pablo Marí', 'España', 'DFC', 74, 52, 40, 49, 53],
            ['Pietro Comuzzo', 'Italia', 'DFC', 74, 57, 27, 50, 57],
            ['Abdelhamid Sabiri', 'Marruecos', 'MCO', 73, 59, 71, 71, 75],
            ['Tariq Lamptey', 'Ghana', 'DFD', 73, 84, 44, 66, 76],
            ['Amir Richardson', 'Marruecos', 'MC', 72, 73, 64, 68, 72],
            ['Jacopo Fazzini', 'Italia', 'MCO', 72, 68, 68, 71, 75],
            ['Mattia Viti', 'Italia', 'DFC', 72, 66, 35, 50, 53],
            ['Cher Ndour', 'Italia', 'MC', 70, 55, 62, 69, 71],
            ['Luca Lezzerini', 'Italia', 'PO', 69, 70, 67, 62, 71],
            ['Tommaso Martinelli', 'Italia', 'PO', 68, 69, 67, 64, 70],
            ['Niccolò Fortini', 'Italia', 'MI', 67, 71, 55, 62, 69],
        ];

        // Mapeo de posiciones a códigos
        $posiciones_map = [
            'POR' => 'Portero',
            'PO' => 'Portero',
            'DFC' => 'Defensa Central',
            'DC' => 'Defensa Central',
            'DFI' => 'Lateral Izquierdo',
            'DFD' => 'Lateral Derecho',
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

        $this->command->info('Todos los jugadores de Fiorentina han sido agregados correctamente');
    }
}
