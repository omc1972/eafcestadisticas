<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class ComoPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Como 1907
        $como = Equipo::where('nombre', 'Como 1907')->first();
        if (!$como) {
            $this->command->error('Como 1907 team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $como->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $como->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Morata', 'España', 'DC', 81, 78, 81, 74, 80],
            ['Nico Paz', 'Argentina', 'MCO', 79, 78, 71, 77, 82],
            ['Stefan Posch', 'Austria', 'LD', 79, 75, 55, 68, 66],
            ['Martin Baturina', 'Croacia', 'MCO', 78, 77, 70, 78, 83],
            ['Nicolas Kühn', 'Alemania', 'ED', 78, 91, 73, 70, 81],
            ['Diego Carlos', 'Brasil', 'DFC', 78, 63, 47, 62, 67],
            ['Maxence Caqueret', 'Francia', 'MC', 77, 75, 62, 75, 78],
            ['Sergi Roberto', 'España', 'MCD', 77, 69, 63, 78, 75],
            ['Jean Butez', 'Francia', 'POR', 76, 75, 75, 78, 77],
            ['Assane Diao', 'Senegal', 'MI', 76, 90, 74, 67, 77],
            ['Alberto Moreno', 'España', 'LI', 75, 75, 69, 71, 74],
            ['Mërgim Vojvoda', 'Kosovo', 'LD', 75, 68, 54, 73, 73],
            ['Lucas Da Cunha', 'Francia', 'MCD', 74, 77, 71, 73, 79],
            ['Jesús Rodríguez', 'España', 'MI', 74, 80, 68, 69, 77],
            ['Máximo Perrone', 'Argentina', 'MCD', 73, 72, 55, 70, 76],
            ['Tasos Douvikas', 'Grecia', 'DC', 73, 79, 72, 62, 73],
            ['Marc-Oliver Kempf', 'Alemania', 'DFC', 73, 72, 41, 56, 62],
            ['Alberto Dossena', 'Italia', 'DFC', 73, 50, 43, 52, 59],
            ['Álex Valle', 'España', 'LI', 72, 81, 56, 67, 72],
            ['Ivan Smolčić', 'Croacia', 'LD', 72, 63, 52, 62, 68],
            ['Edoardo Goldaniga', 'Italia', 'DFC', 72, 53, 34, 50, 56],
            ['Ignace Van der Brempt', 'Bélgica', 'LD', 71, 73, 57, 65, 68],
            ['Nikola Čavlina', 'Croacia', 'POR', 67, 68, 59, 61, 73],
            ['Jayden Addai', 'Holanda', 'MD', 67, 83, 62, 59, 70],
            ['Mauro Vigorito', 'Italia', 'POR', 64, 63, 63, 60, 65],
            ['Jacobo Ramón', 'España', 'DFC', 64, 57, 22, 45, 48],
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

        $this->command->info('Todos los jugadores de Como 1907 han sido agregados correctamente');
    }
}
