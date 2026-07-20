<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class NapoliPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo SSC Napoles
        $napoli = Equipo::where('nombre', 'SSC Napoles')->first();
        if (!$napoli) {
            $this->command->error('SSC Napoles team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $napoli->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $napoli->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Kevin De Bruyne', 'Bélgica', 'MC', 87, 66, 83, 92, 84],
            ['Scott McTominay', 'Escocia', 'MC', 85, 76, 82, 77, 80],
            ['Romelu Lukaku', 'Bélgica', 'DC', 84, 79, 82, 75, 75],
            ['Giovanni Di Lorenzo', 'Italia', 'LD', 83, 84, 72, 73, 77],
            ['Stanislav Lobotka', 'Eslovaquia', 'MC', 83, 71, 69, 80, 82],
            ['Amir Rrahmani', 'Kosovo', 'DFC', 83, 69, 51, 60, 63],
            ['André-Franck Zambo Anguissa', 'Camerún', 'MC', 82, 75, 75, 75, 79],
            ['Alex Meret', 'Italia', 'POR', 82, 81, 79, 74, 85],
            ['Alessandro Buongiorno', 'Italia', 'DFC', 82, 69, 45, 67, 65],
            ['Miguel Gutiérrez', 'España', 'LI', 81, 81, 71, 79, 81],
            ['Matteo Politano', 'Italia', 'ED', 81, 80, 78, 80, 83],
            ['David Neres', 'Brasil', 'EI', 81, 86, 76, 75, 86],
            ['Noa Lang', 'Holanda', 'EI', 80, 80, 77, 73, 84],
            ['Vanja Milinković-Savić', 'Serbia', 'POR', 79, 79, 77, 84, 79],
            ['Leonardo Spinazzola', 'Italia', 'LI', 78, 85, 65, 73, 77],
            ['Mathías Olivera', 'Uruguay', 'LI', 78, 70, 51, 69, 72],
            ['Sam Beukema', 'Holanda', 'DFC', 78, 49, 55, 61, 62],
            ['Eljif Elmas', 'Macedonia del Norte', 'MI', 77, 72, 71, 73, 80],
            ['Rasmus Højlund', 'Dinamarca', 'DC', 76, 85, 76, 58, 72],
            ['Lorenzo Lucca', 'Italia', 'DC', 76, 63, 78, 59, 67],
            ['Billy Gilmour', 'Escocia', 'MC', 74, 60, 58, 74, 76],
            ['Juan Jesus', 'Brasil', 'DFC', 74, 58, 33, 53, 61],
            ['Pasquale Mazzocchi', 'Italia', 'LD', 72, 76, 59, 72, 73],
            ['Luca Marianucci', 'Italia', 'DFC', 68, 46, 26, 48, 53],
            ['Nikita Contini', 'Ucrania', 'POR', 67, 68, 66, 61, 69],
            ['Giuseppe Ambrosino', 'Italia', 'DC', 67, 75, 63, 60, 65],
            ['Antonio Vergara', 'Italia', 'ED', 65, 66, 63, 63, 66],
            ['Coli Saco', 'Malí', 'MC', 61, 61, 57, 60, 61],
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

        $this->command->info('Todos los jugadores de SSC Napoles han sido agregados correctamente');
    }
}
