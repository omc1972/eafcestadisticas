<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class ParnaPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Parna
        $parna = Equipo::where('nombre', 'Parna')->first();
        if (!$parna) {
            $this->command->error('Parna team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $parna->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $parna->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Emanuele Valeri', 'Italia', 'LI', 75, 74, 70, 73, 74],
            ['Hernani', 'Brasil', 'MCD', 75, 58, 70, 71, 72],
            ['Enrico Delprato', 'Italia', 'LD', 75, 81, 54, 63, 69],
            ['Patrick Cutrone', 'Italia', 'DC', 75, 73, 74, 64, 76],
            ['Abdoulaye Ndiaye', 'Senegal', 'DFC', 75, 67, 31, 51, 51],
            ['Adrián Bernabé', 'España', 'MCD', 74, 77, 70, 75, 79],
            ['Zion Suzuki', 'Japón', 'POR', 74, 73, 72, 71, 76],
            ['Gaetano Oristanio', 'Italia', 'MCO', 74, 85, 72, 69, 77],
            ['Oliver Sørensen', 'Dinamarca', 'MC', 73, 73, 68, 69, 71],
            ['Mandela Keita', 'Bélgica', 'MCD', 73, 77, 56, 66, 75],
            ['Jacob Ondrejka', 'Suecia', 'MI', 73, 78, 73, 71, 75],
            ['Matija Frigan', 'Croacia', 'DC', 73, 79, 73, 60, 72],
            ['Christian Ordoñez', 'Argentina', 'MCD', 72, 83, 45, 64, 70],
            ['Pontus Almqvist', 'Suecia', 'MD', 72, 84, 67, 62, 75],
            ['Nahuel Estévez', 'Argentina', 'MCD', 71, 68, 70, 73, 73],
            ['Adrian Benedyczak', 'Polonia', 'MI', 71, 84, 70, 65, 68],
            ['Alessandro Circati', 'Australia', 'DFC', 71, 59, 35, 53, 49],
            ['Milan Đurić', 'Bosnia y Herzegovina', 'DC', 71, 33, 71, 55, 61],
            ['Benjamin Cremaschi', 'Estados Unidos', 'MC', 68, 74, 57, 64, 72],
            ['Lautaro Valenti', 'Argentina', 'DFC', 68, 69, 51, 55, 57],
            ['Mateo Pellegrino', 'Argentina', 'DC', 67, 55, 67, 49, 62],
            ['Mariano Troilo', 'Argentina', 'DFC', 66, 43, 29, 43, 45],
            ['Tjaš Begić', 'Eslovenia', 'MC', 65, 79, 61, 64, 69],
            ['Mathias Løvik', 'Noruega', 'LI', 65, 81, 39, 57, 64],
            ['Edoardo Corvi', 'Italia', 'POR', 65, 66, 63, 65, 68],
            ['Filippo Rinaldi', 'Italia', 'POR', 62, 61, 62, 62, 65],
            ['Dario Sits', 'Letonia', 'DC', 61, 53, 62, 48, 56],
            ['Sascha Britschgi', 'Suiza', 'LD', 52, 67, 27, 31, 47],
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

        $this->command->info('Todos los jugadores de Parna han sido agregados correctamente');
    }
}
