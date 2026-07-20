<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class NottinghamForestPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Nottingham Forest
        $forest = Equipo::where('nombre', 'Nottingham Forest Football Club')->first();
        if (!$forest) {
            $this->command->error('Nottingham Forest Football Club team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $forest->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $forest->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Matz Sels', 'Bélgica', 'POR', 83, 82, 80, 79, 83],
            ['Murillo', 'Brasil', 'DFC', 83, 80, 48, 67, 71],
            ['Nikola Milenković', 'Serbia', 'DFC', 83, 57, 46, 63, 62],
            ['Morgan Gibbs-White', 'Inglaterra', 'MCO', 82, 75, 77, 82, 84],
            ['Chris Wood', 'Nueva Zelanda', 'DC', 82, 53, 83, 68, 72],
            ['Elliot Anderson', 'Inglaterra', 'MCD', 80, 71, 68, 79, 81],
            ['Ola Aina', 'Nigeria', 'LD', 80, 88, 63, 71, 75],
            ['Douglas Luiz', 'Brasil', 'MC', 80, 67, 75, 82, 80],
            ['Dan Ndoye', 'Suiza', 'MI', 79, 89, 72, 70, 81],
            ['Nicolás Domínguez', 'Argentina', 'MCD', 79, 69, 65, 72, 76],
            ['Callum Hudson-Odoi', 'Inglaterra', 'MI', 78, 82, 75, 75, 81],
            ['Neco Williams', 'Gales', 'LI', 78, 72, 49, 71, 74],
            ['Dilane Bakwa', 'Francia', 'MD', 78, 88, 72, 73, 81],
            ['Arnaud Kalimuendo', 'Francia', 'DC', 78, 80, 80, 63, 78],
            ['Oleksandr Zinchenko', 'Ucrania', 'LI', 77, 63, 66, 79, 78],
            ['Ibrahim Sangaré', 'Costa de Marfil', 'MCD', 77, 58, 65, 72, 71],
            ['Ryan Yates', 'Inglaterra', 'MCD', 77, 50, 62, 68, 67],
            ['Igor Jesus', 'Brasil', 'DC', 77, 70, 77, 63, 72],
            ['Morato', 'Brasil', 'DFC', 76, 53, 37, 57, 60],
            ['Omari Hutchinson', 'Inglaterra', 'MCO', 75, 80, 73, 70, 79],
            ['Jota Silva', 'Portugal', 'MD', 75, 79, 72, 69, 76],
            ['Taiwo Awoniyi', 'Nigeria', 'DC', 75, 71, 75, 60, 69],
            ['Jair Cunha', 'Brasil', 'DFC', 74, 67, 38, 59, 58],
            ['James McAtee', 'Inglaterra', 'ED', 73, 72, 69, 70, 75],
            ['Willy Boly', 'Costa de Marfil', 'DFC', 73, 34, 36, 59, 55],
            ['Angus Gunn', 'Escocia', 'POR', 72, 73, 71, 70, 74],
            ['Nicolò Savona', 'Italia', 'LD', 71, 68, 44, 58, 63],
            ['Zach Abbott', 'Inglaterra', 'DFC', 60, 58, 32, 48, 48],
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

        $this->command->info('Todos los jugadores de Nottingham Forest han sido agregados correctamente');
    }
}
