<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class LyonPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Olympique de Lyon
        $lyon = Equipo::where('nombre', 'Olympique de Lyon')->first();
        if (!$lyon) {
            $this->command->error('Olympique de Lyon team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $lyon->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $lyon->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Corentin Tolisso', 'Francia', 'MC', 81, 68, 78, 78, 76],
            ['Nicolás Tagliafico', 'Argentina', 'LI', 78, 67, 56, 70, 76],
            ['Dominik Greif', 'Eslovaquia', 'POR', 78, 77, 76, 78, 77],
            ['Malick Fofana', 'Bélgica', 'MI', 78, 86, 69, 70, 82],
            ['Clinton Mata', 'Angola', 'DFC', 77, 79, 61, 68, 74],
            ['Ainsley Maitland-Niles', 'Inglaterra', 'LD', 77, 77, 65, 73, 76],
            ['Orel Mangala', 'Bélgica', 'MCD', 77, 68, 58, 72, 79],
            ['Moussa Niakhaté', 'Senegal', 'DFC', 77, 68, 48, 63, 64],
            ['Pavel Šulc', 'República Checa', 'MCO', 77, 71, 73, 74, 77],
            ['Tanner Tessmann', 'Estados Unidos', 'MCD', 75, 57, 63, 71, 71],
            ['Ernest Nuamah', 'Ghana', 'MD', 75, 82, 70, 69, 78],
            ['Abner Vinícius', 'Brasil', 'LI', 74, 79, 58, 72, 74],
            ['Martín Satriano', 'Uruguay', 'DC', 74, 63, 74, 62, 72],
            ['Rémy Descamps', 'Francia', 'POR', 73, 74, 68, 65, 76],
            ['Tyler Morton', 'Inglaterra', 'MCD', 72, 64, 59, 74, 73],
            ['Adam Karabec', 'República Checa', 'MCO', 71, 73, 65, 69, 74],
            ['Afonso Moreira', 'Portugal', 'MI', 67, 81, 59, 61, 73],
            ['Ruben Kluivert', 'Holanda', 'DFC', 67, 71, 31, 53, 56],
            ['Achraf Laâziri', 'Marruecos', 'LI', 65, 74, 48, 61, 66],
            ['Lassine Diarra', 'Malí', 'POR', 64, 65, 60, 58, 66],
            ['Khalis Merah', 'Francia', 'MC', 64, 63, 51, 65, 71],
            ['Enzo Molebe', 'Francia', 'DC', 64, 78, 63, 50, 71],
            ['Alejandro Gomes Rodríguez', 'Inglaterra', 'DC', 62, 70, 60, 44, 63],
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

        $this->command->info('Todos los jugadores de Olympique de Lyon han sido agregados correctamente');
    }
}
