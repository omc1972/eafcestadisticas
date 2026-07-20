<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class JuventusPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Juventus
        $juventus = Equipo::where('nombre', 'Juventus')->first();
        if (!$juventus) {
            $this->command->error('Juventus team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $juventus->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $juventus->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Bremer', 'Brasil', 'DFC', 85, 82, 50, 58, 66, 86, 80],
            ['Manuel Locatelli', 'Italia', 'MCD', 84, 63, 69, 80, 76, 81, 78],
            ['Loïs Openda', 'Bélgica', 'DC', 83, 95, 81, 69, 81, 31, 80],
            ['Jonathan David', 'Canadá', 'DC', 82, 81, 82, 71, 80, 34, 78],
            ['Dušan Vlahović', 'Serbia', 'DC', 82, 78, 84, 69, 77, 29, 82],
            ['Khéphren Thuram', 'Francia', 'MC', 81, 78, 76, 77, 80, 81, 81],
            ['Filip Kostić', 'Serbia', 'MI', 81, 82, 76, 81, 80, 70, 77],
            ['Michele Di Gregorio', 'Italia', 'POR', 81, 82, 78, 79, 85, 56, 83],
            ['Teun Koopmeiners', 'Holanda', 'MCO', 81, 70, 79, 83, 78, 75, 75],
            ['Pierre Kalulu', 'Francia', 'DFC', 80, 80, 53, 68, 70, 81, 76],
            ['Federico Gatti', 'Italia', 'DFC', 80, 76, 42, 53, 65, 81, 80],
            ['Andrea Cambiaso', 'Italia', 'LI', 79, 78, 70, 77, 79, 75, 70],
            ['Kenan Yıldız', 'Turquía', 'MCO', 79, 84, 78, 74, 83, 35, 66],
            ['Edon Zhegrova', 'Kosovo', 'MD', 79, 84, 71, 76, 85, 25, 62],
            ['Francisco Conceição', 'Portugal', 'MD', 79, 87, 68, 73, 85, 36, 50],
            ['Arkadiusz Milik', 'Polonia', 'DC', 79, 50, 82, 72, 77, 40, 67],
            ['Weston McKennie', 'Estados Unidos', 'MC', 78, 77, 71, 77, 77, 80, 80],
            ['Mattia Perin', 'Italia', 'POR', 78, 78, 78, 71, 78, 57, 79],
            ['João Mário', 'Portugal', 'LD', 77, 84, 63, 71, 77, 69, 67],
            ['Daniele Rugani', 'Italia', 'DFC', 75, 34, 40, 54, 60, 78, 70],
            ['Fabio Miretti', 'Italia', 'MC', 74, 73, 60, 75, 76, 66, 68],
            ['Lloyd Kelly', 'Inglaterra', 'DFC', 74, 67, 40, 65, 67, 73, 79],
            ['Juan David Cabal', 'Colombia', 'LI', 74, 72, 38, 69, 65, 74, 69],
            ['Carlo Pinsoglio', 'Italia', 'POR', 69, 70, 70, 62, 70, 42, 69],
            ['Jonas Rouhi', 'Suecia', 'LI', 66, 64, 43, 59, 63, 66, 53],
            ['Vasilije Adžić', 'Montenegro', 'MCO', 62, 67, 55, 54, 66, 35, 51],
        ];

        // Mapa de posiciones
        $posiciones_map = [
            'POR' => 'Portero',
            'DFC' => 'Defensa Central',
            'DC' => 'Defensa Central',
            'LD' => 'Lateral Derecho',
            'LI' => 'Lateral Izquierdo',
            'MD' => 'Mediapunta Derecho',
            'MI' => 'Mediapunta Izquierdo',
            'MC' => 'Mediocampista Central',
            'MCD' => 'Mediocampista Central Defensivo',
            'MCO' => 'Mediocampista Central Ofensivo',
        ];

        $dorsal = 1;

        foreach ($jugadores_data as $data) {
            [$nombre, $nacionalidad, $posicion_code, $overall, $pace, $shooting, $passing, $dribbling, $defense, $physical] = $data;

            // Crear el jugador con todos los atributos calculados
            $jugador = Jugador::create([
                'nombre' => $nombre,
                'fecha_nacimiento' => '2000-01-01',
                'nacionalidad' => $nacionalidad,
                'posicion' => $posiciones_map[$posicion_code] ?? $posicion_code,
                'altura' => '180',
                'peso' => '75',
                'ritmo' => $pace,
                'aceleracion' => intval($pace * 0.95),
                'velocidad' => $pace,
                'tiro' => $shooting,
                'pos_ataque' => intval($shooting * 0.9),
                'finalizacion' => $shooting,
                'potencia_de_tiro' => intval($shooting * 0.85),
                'tiro_lejano' => intval($shooting * 0.8),
                'voleas' => intval($shooting * 0.7),
                'penalties' => intval($shooting * 0.75),
                'pase' => $passing,
                'vision' => intval($passing * 0.95),
                'centros' => intval($passing * 0.85),
                'precision_falta' => intval($passing * 0.8),
                'pase_corto' => $passing,
                'pase_largo' => intval($passing * 0.9),
                'efecto' => intval($passing * 0.85),
                'regate' => $dribbling,
                'agilidad' => intval($dribbling * 0.95),
                'equilibrio' => intval($dribbling * 0.9),
                'anticipacion' => intval($dribbling * 0.8),
                'control_de_balon' => $dribbling,
                'regates' => intval($dribbling * 0.85),
                'compostura' => intval($overall * 0.9),
                'defensa' => $defense,
                'intercepciones' => intval($defense * 0.95),
                'precision_cabezazo' => intval($defense * 0.85),
                'capacidad_defensiva' => $defense,
                'robos' => intval($defense * 0.9),
                'entradas' => intval($defense * 0.8),
                'fisico' => $physical,
                'salto' => intval($physical * 0.9),
                'resistencia' => $physical,
                'fuerza' => intval($physical * 0.95),
                'agresividad' => intval($physical * 0.85),
                'media' => $overall,
                'estilo_quimica' => 'Neutral',
            ]);

            // Agregar a la plantilla con dorsal
            $plantilla->jugadores()->attach($jugador->id, ['dorsal' => $dorsal]);

            $dorsal++;

            $this->command->info("Jugador '{$nombre}' agregado a la plantilla");
        }

        $this->command->info('Todos los jugadores de Juventus han sido agregados correctamente');
    }
}
