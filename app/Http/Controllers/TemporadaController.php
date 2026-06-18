<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use Illuminate\Http\Request;
use Inertia\Inertia;


class TemporadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $temporadas = Temporada::all();

        return Inertia::render('Temporadas/Index', [
            'temporadas' => $temporadas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return Inertia::render('Temporadas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'required',
        ]);

        $validated['activo'] = filter_var($validated['activo'], FILTER_VALIDATE_BOOLEAN);

        Temporada::create($validated);

        return redirect()->route('temporadas.index')
            ->with('success', 'Temporada creada con éxito');
    }


    /**
     * Display the specified resource.
     */
    public function show(Temporada $temporada)
    {
        // Cargar las plantillas de esta temporada con sus ligas y equipos
        $plantillas = \App\Models\Plantilla::where('temporada_id', $temporada->id)
            ->whereNotNull('liga_id')
            ->with(['liga', 'equipo'])
            ->get();

        // Agrupar por liga
        $ligasData = $plantillas->groupBy('liga_id')->map(function ($plantillasLiga) use ($temporada) {
            $liga = $plantillasLiga->first()->liga;
            $equiposIds = $plantillasLiga->pluck('equipo_id')->toArray();
            
            // Obtener partidos de los equipos de esta liga en esta temporada
            $partidos = \App\Models\Partido::where('temporada_id', $temporada->id)
                ->whereIn('equipo_id', $equiposIds)
                ->get();
            
            $partidosJugados = $partidos->count();
            $golesAFavor = $partidos->sum('goles_equipo');
            $golesEnContra = $partidos->sum('goles_rival');
            $victorias = $partidos->filter(function ($p) { 
                return $p->goles_equipo > $p->goles_rival; 
            })->count();
            $empates = $partidos->filter(function ($p) { 
                return $p->goles_equipo == $p->goles_rival; 
            })->count();
            $derrotas = $partidos->filter(function ($p) { 
                return $p->goles_equipo < $p->goles_rival; 
            })->count();
            
            return [
                'id' => $liga->id,
                'nombre' => $liga->nombre,
                'pais' => $liga->pais,
                'equipos_count' => $plantillasLiga->count(),
                'partidos_jugados' => $partidosJugados,
                'goles_favor' => $golesAFavor,
                'goles_contra' => $golesEnContra,
                'diferencia_goles' => $golesAFavor - $golesEnContra,
                'victorias' => $victorias,
                'empates' => $empates,
                'derrotas' => $derrotas,
            ];
        })->values();

        return Inertia::render('Temporadas/Show', [
            'temporada' => $temporada,
            'ligas' => $ligasData,
        ]);
    }

    /**
     * Display old statistics view.
     */
    public function estadisticas(Temporada $temporada)
    {
        $temporada->load([
            'partidos' => function ($q) {
                $q->orderBy('jornada', 'asc');
            },
            'partidos.equipo',
            'partidos.rival',
            'partidos.eventos.jugador',
            'partidos.eventos.equipo',
            'partidos.alineaciones.jugador'
        ]);

        $estadisticas = [];
        $jugadores = [];
        $camposJugador = [
            'tiros', 'tiros_a_puerta', 'tiros_al_palo', 'pases', 'pases_exitosos', 'entradas', 'entradas_exitosas',
            'regates', 'regates_exitosos', 'posesion_ganada', 'posesion_perdida', 'fueras_de_juego',
            'faltas_cometidas', 'posesion', 'faltas_recibidas', 'distancia_recorrida', 'rendimiento',
            'jugador_del_partido','tarjetas_amarillas', 'tarjetas_rojas', 'penalties_fallados', 'penalties_marcados'
        ];
        $camposPortero = [
            'pases', 'pases_exitosos', 'entradas', 'entradas_exitosas',
            'regates', 'regates_exitosos', 'posesion_ganada', 'posesion_perdida', 'fueras_de_juego',
            'faltas_cometidas', 'posesion', 'faltas_recibidas', 'distancia_recorrida', 'rendimiento',
            'jugador_del_partido', 'tarjetas_amarillas', 'tarjetas_rojas', 'penalties_fallados', 'penalties_parados', 'penalties_marcados'
        ];

        foreach ($temporada->partidos as $partido) {
            $equipoId = $partido->equipo_id;

            if (!isset($estadisticas[$equipoId])) {
                $estadisticas[$equipoId] = [
                    'equipo' => $partido->equipo->nombre ?? 'Sin nombre',
                    'escudo' => $partido->equipo->escudo ?? 'nologo.png',
                    'partidos_jugados' => 0,
                    'ganados' => 0,
                    'empatados' => 0,
                    'perdidos' => 0,
                    'goles_favor' => 0,
                    'goles_contra' => 0,
                    'diferencia_goles' => 0,
                    'puntos' => 0,
                    'distancia_recorrida' => 0,
                    'pases' => 0,
                    'pases_correctos' => 0,
                    'entradas' => 0,
                    'entradas_correctas' => 0,
                    'regates' => 0,
                    'regates_correctos' => 0,
                    'tarjetas_amarillas' => 0,
                    'tarjetas_rojas' => 0,
                    'penalties' => 0,
                    'tiros' => 0,
                    'tiros_a_puerta' => 0,
                    'tiros_r' => 0,
                    'tiros_a_puerta_r' => 0,
                    'posesion' => 0,
                    'valoracion' => 0,
                    'puntuacion' => 0
                ];
            }
            $estadisticas[$equipoId]['valoracion'] += $partido->rival->valoracion;
            $estadisticas[$equipoId]['partidos_jugados']++;
            $estadisticas[$equipoId]['goles_favor'] += $partido->goles_equipo;
            $estadisticas[$equipoId]['goles_contra'] += $partido->goles_rival;
            $estadisticas[$equipoId]['tarjetas_amarillas'] += $partido->tarjetas_amarillas_equipo;
            $estadisticas[$equipoId]['tarjetas_rojas'] += $partido->tarjetas_rojas_equipo;
            $estadisticas[$equipoId]['penalties'] += $partido->penalties_equipo;
            $estadisticas[$equipoId]['posesion'] += $partido->posesion_equipo;
            $estadisticas[$equipoId]['puntuacion'] += $partido->puntuacion;

            if ($partido->goles_equipo > $partido->goles_rival) {
                $estadisticas[$equipoId]['ganados']++;
                $estadisticas[$equipoId]['puntos'] += 3;
            } elseif ($partido->goles_equipo == $partido->goles_rival) {
                $estadisticas[$equipoId]['empatados']++;
                $estadisticas[$equipoId]['puntos'] += 1;
            } else {
                $estadisticas[$equipoId]['perdidos']++;
            }

            $estadisticas[$equipoId]['diferencia_goles'] =
                $estadisticas[$equipoId]['goles_favor'] - $estadisticas[$equipoId]['goles_contra'];

            foreach($partido->alineaciones as $alineacion){               
                $jugadorId = $alineacion->jugador_id;
                if (!isset($jugadores[$jugadorId])) {
                    $jugadores[$jugadorId] = [
                        'jugador' => $alineacion->jugador->nombre,
                        'equipo' => $partido->equipo->nombre,
                        'goles' => 0,
                        'asistencias' => 0,
                        'tiros' => 0,
                        'tiros_a_puerta' => 0,
                        'tiros_al_palo' => 0,
                        'pases' => 0,
                        'pases_exitosos' => 0,        
                        'entradas' => 0,
                        'entradas_exitosas' => 0,        
                        'regates' => 0,
                        'regates_exitosos' => 0,       
                        'posesion_ganada' => 0,
                        'posesion_perdida' => 0,
                        'fueras_de_juego' => 0,
                        'faltas_cometidas' => 0,
                        'posesion' => 0,
                        'faltas_recibidas' => 0,
                        'distancia_recorrida' => 0,
                        'rendimiento' => 0,
                        'jugador_del_partido' => 0,
                        'partidos_jugados' => 0,
                        'tarjetas_amarillas' => 0,
                        'tarjetas_rojas' => 0,
                        'penalties_fallados' => 0,
                        'penalties_parados' => 0,
                        'penalties_marcados' => 0,
                    ];
                    
                } 
                    $estadisticas[$equipoId]['distancia_recorrida'] += $alineacion->distancia_recorrida;
                    $estadisticas[$equipoId]['pases'] += $alineacion->pases;
                    $estadisticas[$equipoId]['pases_correctos'] += $alineacion->pases_exitosos;
                    $estadisticas[$equipoId]['entradas'] += $alineacion->entradas;
                    $estadisticas[$equipoId]['entradas_correctas'] += $alineacion->entradas_exitosas;
                    $estadisticas[$equipoId]['regates'] += $alineacion->regates;
                    $estadisticas[$equipoId]['regates_correctos'] += $alineacion->regates_exitosos;
                    if($alineacion->jugador->posicion=='POR'){
                        $estadisticas[$equipoId]['tiros_r'] += $alineacion->tiros;
                        $estadisticas[$equipoId]['tiros_a_puerta_r'] += $alineacion->tiros_a_puerta;  
                    } else {
                        $estadisticas[$equipoId]['tiros'] += $alineacion->tiros;
                        $estadisticas[$equipoId]['tiros_a_puerta'] += $alineacion->tiros_a_puerta;                
                    }
                    

                if($alineacion->jugador->posicion=='POR'){
                    foreach ($camposPortero as $campo) {
                        $jugadores[$jugadorId][$campo] += $alineacion->$campo;
                    } 
                } else {
                    foreach ($camposJugador as $campo) {
                        $jugadores[$jugadorId][$campo] += $alineacion->$campo;
                    } 
                } 
                 $jugadores[$jugadorId]['partidos_jugados']++;      
            }

            foreach ($partido->eventos as $evento) {
                if ($evento->jugador_id == 168) {
                    continue;
                }

                $jugadorId = $evento->jugador_id;

                if ($evento->tipo_evento_id == 1) { // Gol
                    $jugadores[$jugadorId]['goles']++;
                } elseif ($evento->tipo_evento_id == 10) { // Asistencia
                    $jugadores[$jugadorId]['asistencias']++;
                } elseif ($evento->tipo_evento_id == 6) { // Asistencia
                    $jugadores[$jugadorId]['tarjetas_amarillas']++;
                } elseif ($evento->tipo_evento_id == 7) { // Asistencia
                    $jugadores[$jugadorId]['tarjetas_rojas']++;
                } elseif ($evento->tipo_evento_id == 12) { // Asistencia
                    $jugadores[$jugadorId]['penalties_fallados']++;
                } elseif ($evento->tipo_evento_id == 13) { // Asistencia
                    $jugadores[$jugadorId]['penalties_parados']++;
                } elseif ($evento->tipo_evento_id == 11) { // Asistencia
                    $jugadores[$jugadorId]['penalties_marcados']++;
                }
            }        
        }

        usort($estadisticas, function ($a, $b) {
            if ($a['puntos'] == $b['puntos']) {
                return $b['diferencia_goles'] <=> $a['diferencia_goles'];
            }
            return $b['puntos'] <=> $a['puntos'];
        });

        $amonestaciones_roja = array_filter($jugadores, fn($j) => $j['tarjetas_rojas'] > 0);
        usort($amonestaciones_roja, fn($a, $b) => $b['tarjetas_rojas'] <=> $a['tarjetas_rojas']);

        $amonestaciones_amarilla = array_filter($jugadores, fn($j) => $j['tarjetas_amarillas'] > 0);
        usort($amonestaciones_amarilla, fn($a, $b) => $b['tarjetas_amarillas'] <=> $a['tarjetas_amarillas']);

        $goleadores = array_filter($jugadores, fn($j) => $j['goles'] > 0);
        usort($goleadores, fn($a, $b) => $b['goles'] <=> $a['goles']);

        $asistentes = array_filter($jugadores, fn($j) => $j['asistencias'] > 0);
        usort($asistentes, fn($a, $b) => $b['asistencias'] <=> $a['asistencias']);

        $jugador_del_partido = array_values(
            array_filter($jugadores, fn($j) => $j['jugador_del_partido'] > 0)
        );

        usort($jugador_del_partido, fn($a, $b) => $b['jugador_del_partido'] <=> $a['jugador_del_partido']);

        $posesion_ganada = array_filter($jugadores, fn($j) => $j['posesion_ganada'] > 0);
        usort($posesion_ganada, fn($a, $b) => $b['posesion_ganada'] <=> $a['posesion_ganada']);

        $posesion_perdida = array_filter($jugadores, fn($j) => $j['posesion_perdida'] > 0);
        usort($posesion_perdida, fn($a, $b) => $b['posesion_perdida'] <=> $a['posesion_perdida']);

        $faltas_recibidas = array_filter($jugadores, fn($j) => $j['faltas_recibidas'] > 0);
        usort($faltas_recibidas, fn($a, $b) => $b['faltas_recibidas'] <=> $a['faltas_recibidas']);

        $faltas_cometidas = array_filter($jugadores, fn($j) => $j['faltas_cometidas'] > 0);
        usort($faltas_cometidas, fn($a, $b) => $b['faltas_cometidas'] <=> $a['faltas_cometidas']);

        $distancia_recorrida = array_filter($jugadores, fn($j) => $j['distancia_recorrida'] > 0);
        usort($distancia_recorrida, fn($a, $b) => $b['distancia_recorrida'] <=> $a['distancia_recorrida']);

        $pases = array_filter($jugadores, fn($j) => $j['pases'] > 0);
        usort($pases, fn($a, $b) => $b['pases'] <=> $a['pases']);

        $regates = array_filter($jugadores, fn($j) => $j['regates'] > 0);
        usort($regates, fn($a, $b) => $b['regates'] <=> $a['regates']);

        $entradas = array_filter($jugadores, fn($j) => $j['entradas'] > 0);
        usort($entradas, fn($a, $b) => $b['entradas'] <=> $a['entradas']);

        $rendimiento = array_filter($jugadores, fn($j) => $j['rendimiento'] > 0 && $j['partidos_jugados'] > 0);
            usort($rendimiento, function ($a, $b) {
                $mediaA = $a['rendimiento'] / $a['partidos_jugados'];
                $mediaB = $b['rendimiento'] / $b['partidos_jugados'];
                return $mediaB <=> $mediaA;
            });

        $tiros = array_filter($jugadores, fn($j) => $j['tiros'] > 0);
        usort($tiros, fn($a, $b) => $b['tiros'] <=> $a['tiros']);

        $pases_rendimiento = array_filter($jugadores, fn($j) => $j['pases'] > 0);
        usort($pases_rendimiento, function ($a, $b) {
            $ratioA = $a['pases_exitosos'] / $a['pases'];
            $ratioB = $b['pases_exitosos'] / $b['pases'];
            
            if ($ratioA == $ratioB) {
                return $b['pases'] <=> $a['pases']; // empate: más pases primero
            }
            return $ratioB <=> $ratioA;
        });

        $entradas_rendimiento = array_filter($jugadores, fn($j) => $j['entradas'] > 0);
        usort($entradas_rendimiento, function ($a, $b) {
            $ratioA = $a['entradas_exitosas'] / $a['entradas'];
            $ratioB = $b['entradas_exitosas'] / $b['entradas'];

            if ($ratioA == $ratioB) {
                return $b['entradas'] <=> $a['entradas']; // empate: más entradas primero
            }
            return $ratioB <=> $ratioA;
        });

        // Regates
        $regates_rendimiento = array_filter($jugadores, fn($j) => $j['regates'] > 0);
        usort($regates_rendimiento, function ($a, $b) {
            $ratioA = $a['regates_exitosos'] / $a['regates'];
            $ratioB = $b['regates_exitosos'] / $b['regates'];

            if ($ratioA == $ratioB) {
                return $b['regates'] <=> $a['regates']; // empate: más regates primero
            }
            return $ratioB <=> $ratioA;
        });


        // Arrays clasificatorios de equipos por estadística
        $estadisticas_equipos = [
            'goles_favor' => collect($estadisticas)->sortByDesc('goles_favor')->take(5)->values()->all(),
            'goles_contra' => collect($estadisticas)->sortBy('goles_contra')->take(5)->values()->all(),
            'pases' => collect($estadisticas)->sortByDesc('pases')->take(5)->values()->all(),
            'posesion' => collect($estadisticas)->sortByDesc('posesion')->take(5)->values()->all(),
            'tarjetas_amarillas' => collect($estadisticas)->sortByDesc('tarjetas_amarillas')->take(5)->values()->all(),
            'tarjetas_rojas' => collect($estadisticas)->sortByDesc('tarjetas_rojas')->take(5)->values()->all(),
            'distancia_recorrida' => collect($estadisticas)->sortByDesc('distancia_recorrida')->take(5)->values()->all(),
            'penalties' => collect($estadisticas)->sortByDesc('penalties')->take(5)->values()->all(),
        ];

        return Inertia::render('Temporadas/View', [
            'temporada' => $temporada,
            'estadisticas' => array_values($estadisticas),
            'estadisticas_equipos' => $estadisticas_equipos,
            'goleadores' => array_slice(array_values($goleadores), 0, 20),
            'asistentes' => array_slice(array_values($asistentes), 0, 20),
            'tiros' => array_slice(array_values($tiros), 0, 20),
            'pases' => array_slice(array_values($pases), 0, 20),
            'regates' => array_slice(array_values($regates), 0, 20),
            'entradas' => array_slice(array_values($entradas), 0, 20),
            'pases_rendimiento' => array_slice(array_values($pases_rendimiento), 0, 20),
            'regates_rendimiento' => array_slice(array_values($regates_rendimiento), 0, 20),
            'entradas_rendimiento' => array_slice(array_values($entradas_rendimiento), 0, 20),
            'distancia_recorrida' => array_slice(array_values($distancia_recorrida), 0, 20),
            'jugador_del_partido' => array_slice(array_values($jugador_del_partido), 0, 20),
            'posesion_ganada' => array_slice(array_values($posesion_ganada), 0, 20),
            'posesion_perdida' => array_slice(array_values($posesion_perdida), 0, 20),
            'tarjetas_rojas' => array_slice(array_values($amonestaciones_roja), 0, 20),
            'tarjetas_amarillas' => array_slice(array_values($amonestaciones_amarilla), 0, 20),
            'rendimiento' => array_slice(array_values($rendimiento), 0, 20),
        ]);
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Temporada $temporada)
    {
        return Inertia::render('Temporadas/Edit', [
            'temporada' => $temporada
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Temporada $temporada)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'required',
        ]);
        
        $validated['activo'] = filter_var($validated['activo'], FILTER_VALIDATE_BOOLEAN);
        
        $temporada->update($validated);

        return redirect()->route('temporadas.index')
            ->with('success', 'Temporada actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Temporada $temporada)
    {
         $temporada->delete();

        return redirect()->route('temporadas.index')
            ->with('success', 'Temporada eliminada con éxito');
    }
}
