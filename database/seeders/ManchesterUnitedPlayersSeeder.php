<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class ManchesterUnitedPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Manchester United
        $manUnited = Equipo::where('nombre', 'like', '%Man Utd%')->orWhere('nombre', 'like', '%Manchester%')->first();
        if (!$manUnited) {
            $this->command->error('Manchester United team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $manUnited->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $manUnited->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Bruno Fernandes', 'Portugal', 'MCO', 87, 67, 83, 89, 83, 65, 75],
            ['Bryan Mbeumo', 'Camerún', 'ED', 85, 88, 84, 79, 84, 49, 76],
            ['Matheus Cunha', 'Brasil', 'MCO', 83, 77, 85, 79, 84, 44, 75],
            ['Matthijs de Ligt', 'Holanda', 'DFC', 82, 62, 61, 62, 67, 82, 83],
            ['Lisandro Martínez', 'Argentina', 'DFC', 81, 67, 59, 75, 75, 81, 80],
            ['André Onana', 'Camerún', 'POR', 80, 81, 75, 85, 83, 61, 77],
            ['Noussair Mazraoui', 'Marruecos', 'LD', 80, 75, 66, 76, 80, 77, 72],
            ['Benjamin Šeško', 'Eslovenia', 'DC', 80, 83, 80, 65, 78, 46, 80],
            ['Casemiro', 'Brasil', 'MCD', 80, 36, 74, 76, 69, 80, 76],
            ['Harry Maguire', 'Inglaterra', 'DFC', 80, 35, 57, 70, 65, 80, 82],
            ['Diogo Dalot', 'Portugal', 'LD', 79, 85, 63, 74, 77, 76, 78],
            ['Luke Shaw', 'Inglaterra', 'DFC', 79, 69, 57, 78, 75, 79, 72],
            ['Manuel Ugarte', 'Uruguay', 'MCD', 79, 62, 65, 72, 76, 77, 75],
            ['Amad', 'Costa de Marfil', 'MCO', 79, 85, 74, 75, 83, 54, 52],
            ['Senne Lammens', 'Bélgica', 'POR', 78, 79, 77, 71, 77, 21, 79],
            ['Leny Yoro', 'Francia', 'DFC', 78, 69, 41, 60, 64, 79, 73],
            ['Kobbie Mainoo', 'Inglaterra', 'MC', 77, 68, 69, 74, 81, 73, 74],
            ['Mason Mount', 'Inglaterra', 'MCO', 77, 67, 76, 79, 78, 62, 61],
            ['Joshua Zirkzee', 'Holanda', 'DC', 77, 71, 76, 72, 82, 41, 76],
            ['Tyrell Malacia', 'Holanda', 'LI', 75, 77, 54, 66, 75, 72, 71],
            ['Altay Bayındır', 'Turquía', 'POR', 75, 77, 72, 70, 77, 30, 75],
            ['Patrick Dorgu', 'Dinamarca', 'LI', 74, 86, 59, 69, 74, 69, 73],
            ['Ayden Heaven', 'Inglaterra', 'DFC', 69, 69, 28, 52, 59, 69, 71],
            ['Tom Heaton', 'Inglaterra', 'POR', 67, 68, 66, 65, 67, 42, 68],
            ['Chido Obi', 'Dinamarca', 'DC', 65, 71, 64, 56, 63, 28, 60],
            ['Tyler Fredricson', 'Inglaterra', 'DFC', 65, 65, 29, 48, 56, 65, 68],
            ['Diego León', 'Paraguay', 'LI', 64, 83, 51, 56, 66, 59, 62],
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
            'ED' => 'Extremo Derecho',
            'EI' => 'Extremo Izquierdo',
            'MC' => 'Mediocampista Central',
            'MCD' => 'Mediocampista Central Defensivo',
            'MCO' => 'Mediocampista Central Ofensivo',
        ];

        $dorsal = 1;

        foreach ($jugadores_data as $index => $data) {
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

            // Determinar si es titular (los primeros 11)
            $es_titular = $index < 11 ? true : false;

            // Agregar a la plantilla con dorsal y estado de titular
            $plantilla->jugadores()->attach($jugador->id, [
                'dorsal' => $dorsal,
                'es_titular' => $es_titular
            ]);

            $status = $es_titular ? 'TITULAR' : 'SUPLENTE';
            $dorsal++;

            $this->command->info("Jugador '{$nombre}' agregado a la plantilla como {$status}");
        }

        $this->command->info('Todos los jugadores de Manchester United han sido agregados correctamente');
    }
}
