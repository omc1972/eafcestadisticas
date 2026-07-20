<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class LazioPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Lazio
        $lazio = Equipo::where('nombre', 'Lazio')->first();
        if (!$lazio) {
            $this->command->error('Lazio team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $lazio->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $lazio->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Mattia Zaccagni', 'Italia', 'MI', 84, 88, 78, 77, 87],
            ['Mattéo Guendouzi', 'Francia', 'MCD', 82, 72, 73, 80, 80],
            ['Ivan Provedel', 'Italia', 'POR', 82, 83, 79, 80, 84],
            ['Alessio Romagnoli', 'Italia', 'DFC', 82, 64, 40, 55, 69],
            ['Valentin Castellanos', 'Argentina', 'DC', 80, 75, 80, 71, 81],
            ['Boulaye Dia', 'Senegal', 'DC', 80, 84, 81, 67, 78],
            ['Nicolò Rovella', 'Italia', 'MCD', 79, 76, 63, 77, 78],
            ['Mario Gila', 'España', 'DFC', 79, 85, 53, 67, 75],
            ['Nuno Tavares', 'Portugal', 'LI', 78, 88, 67, 72, 80],
            ['Adam Marušić', 'Montenegro', 'LD', 78, 80, 66, 67, 74],
            ['Manuel Lazzari', 'Italia', 'LD', 78, 90, 61, 75, 81],
            ['Danilo Cataldi', 'Italia', 'MCD', 77, 68, 71, 77, 77],
            ['Matías Vecino', 'Uruguay', 'MC', 77, 53, 73, 75, 76],
            ['Pedro', 'España', 'MD', 77, 64, 74, 75, 85],
            ['Samuel Gigot', 'Francia', 'DFC', 77, 62, 38, 62, 60],
            ['Gustav Isaksen', 'Dinamarca', 'MD', 76, 88, 72, 67, 78],
            ['Patric', 'España', 'DFC', 75, 63, 53, 68, 70],
            ['Tijjani Noslin', 'Holanda', 'DC', 74, 78, 73, 66, 77],
            ['Luca Pellegrini', 'Italia', 'LI', 74, 71, 54, 66, 76],
            ['Christos Mandas', 'Grecia', 'POR', 74, 76, 71, 72, 78],
            ['Fisayo Dele-Bashiru', 'Nigeria', 'MCO', 73, 85, 72, 65, 76],
            ['Elseid Hysaj', 'Albania', 'LI', 72, 71, 51, 66, 71],
            ['Matteo Cancellieri', 'Italia', 'MI', 72, 79, 71, 69, 77],
            ['Toma Bašić', 'Croacia', 'MC', 71, 40, 64, 73, 70],
            ['Reda Belahyane', 'Marruecos', 'MC', 68, 66, 49, 64, 75],
            ['Oliver Provstgaard', 'Dinamarca', 'DFC', 67, 66, 39, 53, 57],
            ['Alessio Furlanetto', 'Italia', 'POR', 61, 63, 60, 58, 65],
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

        $this->command->info('Todos los jugadores de Lazio han sido agregados correctamente');
    }
}
