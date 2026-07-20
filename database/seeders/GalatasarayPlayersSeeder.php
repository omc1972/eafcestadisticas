<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Plantilla;

class GalatasarayPlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el equipo Galatasaray Spor Kulübü
        $galatasaray = Equipo::where('nombre', 'Galatasaray Spor Kulübü')->first();
        if (!$galatasaray) {
            $this->command->error('Galatasaray Spor Kulübü team not found');
            return;
        }

        // Obtener la temporada Temporada 25/26
        $temporada = Temporada::where('nombre', 'Temporada 25/26')->first();
        if (!$temporada) {
            $this->command->error('Temporada 25/26 not found');
            return;
        }

        // Obtener o crear la plantilla
        $plantilla = Plantilla::where('equipo_id', $galatasaray->id)
                               ->where('temporada_id', $temporada->id)
                               ->first();
        if (!$plantilla) {
            $plantilla = Plantilla::create([
                'equipo_id' => $galatasaray->id,
                'temporada_id' => $temporada->id,
            ]);
        }

        // Datos de los jugadores
        $jugadores_data = [
            ['Victor Osimhen', 'Nigeria', 'DC', 87, 92, 84, 65, 81],
            ['Lucas Torreira', 'Uruguay', 'MCD', 83, 76, 72, 78, 81],
            ['İlkay Gündoğan', 'Alemania', 'MC', 83, 54, 79, 83, 80],
            ['Davinson Sánchez', 'Colombia', 'DFC', 82, 82, 58, 63, 73],
            ['Leroy Sané', 'Alemania', 'MD', 82, 87, 81, 78, 85],
            ['Mauro Icardi', 'Argentina', 'DC', 81, 67, 82, 71, 76],
            ['Barış Alper Yılmaz', 'Turquía', 'MI', 80, 93, 77, 72, 79],
            ['Gabriel Sara', 'Brasil', 'MC', 80, 74, 74, 83, 79],
            ['Wilfried Singo', 'Costa de Marfil', 'DFC', 80, 80, 59, 70, 73],
            ['Uğurcan Çakır', 'Turquía', 'POR', 80, 81, 76, 74, 79],
            ['Abdülkerim Bardakcı', 'Turquía', 'DFC', 80, 59, 46, 70, 69],
            ['Mario Lemina', 'Gabón', 'MCD', 79, 79, 67, 75, 76],
            ['Roland Sallai', 'Hungría', 'MD', 78, 77, 71, 71, 79],
            ['Yunus Akgün', 'Turquía', 'MD', 78, 87, 78, 74, 81],
            ['Kaan Ayhan', 'Turquía', 'LD', 76, 57, 62, 77, 69],
            ['Günay Güvenç', 'Turquía', 'POR', 75, 75, 72, 76, 75],
            ['Ismail Jakobs', 'Senegal', 'LI', 75, 90, 60, 65, 74],
            ['Eren Elmalı', 'Turquía', 'LI', 75, 83, 52, 72, 72],
            ['Berkan Kutlu', 'Turquía', 'MCD', 73, 75, 63, 65, 69],
            ['Ahmed Kutucu', 'Turquía', 'MI', 69, 77, 74, 63, 69],
            ['Yusuf Demir', 'Austria', 'MD', 67, 79, 59, 61, 76],
            ['Kazımcan Karataş', 'Turquía', 'LI', 66, 75, 57, 59, 65],
            ['Eyüp Aydın', 'Turquía', 'MCD', 66, 66, 54, 67, 67],
            ['Metehan Baltacı', 'Turquía', 'DFC', 66, 63, 33, 50, 59],
            ['Jankat Yılmaz', 'Turquía', 'POR', 64, 66, 63, 58, 68],
            ['Arda Ünyay', 'Turquía', 'DFC', 64, 70, 29, 54, 60],
            ['Batuhan Şen', 'Turquía', 'POR', 64, 64, 63, 62, 66],
            ['Gökdeniz Gürpüz', 'Turquía', 'MC', 60, 72, 58, 58, 67],
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

        $this->command->info('Todos los jugadores de Galatasaray Spor Kulübü han sido agregados correctamente');
    }
}
