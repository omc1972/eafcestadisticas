<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use App\Models\Liga;
use App\Models\Plantilla;
use App\Models\Partido;
use App\Models\TipoEvento;
use Inertia\Inertia;

class LigaEstadisticasController extends Controller
{
    /**
     * Display detailed statistics for a specific league in a season
     */
    public function show(Temporada $temporada, Liga $liga)
    {
        // Obtener las plantillas de esta liga en esta temporada
        $plantillas = Plantilla::where('temporada_id', $temporada->id)
            ->where('liga_id', $liga->id)
            ->with(['equipo', 'jugadores'])
            ->orderBy('orden_liga')
            ->get();

        $equiposIds = $plantillas->pluck('equipo_id')->toArray();
        $campeonatosIds = $plantillas->pluck('campeonato_id')->filter()->unique()->values();

        // Obtener solo partidos de la temporada/liga y del campeonato asociado.
        // Se consideran equipos tanto en local (equipo_id) como en visitante (visitante_id).
        $partidosQuery = Partido::where('temporada_id', $temporada->id)
            ->whereIn('equipo_id', $equiposIds)
            ->whereIn('visitante_id', $equiposIds)
            ->with(['equipo', 'visitanteEquipo'])
            ->orderBy('jornada', 'asc');

        if ($campeonatosIds->isNotEmpty()) {
            $partidosQuery->whereIn('campeonato_id', $campeonatosIds->all());
        }

        $partidos = $partidosQuery->get();

        // Calcular estadÃ­sticas por equipo
        $estadisticas = [];
        foreach ($plantillas as $plantilla) {
            $equipoId = $plantilla->equipo_id;
            $equipo = $plantilla->equipo;
            $partidosEquipo = $partidos->filter(function ($partido) use ($equipoId) {
                return (int) $partido->equipo_id === (int) $equipoId
                    || (int) $partido->visitante_id === (int) $equipoId;
            });

            // Calcular la media de valoración de los oponentes y la media de campo rival.
            $valoracionesRivales = [];
            $mediasRival = [];
            foreach ($partidosEquipo as $partido) {
                $esLocal = (int) $partido->equipo_id === (int) $equipoId;
                $oponente = $esLocal ? $partido->visitanteEquipo : $partido->equipo;

                if ($oponente && isset($oponente->valoracion)) {
                    $valoracionesRivales[] = $oponente->valoracion;
                }

                $mediaOponente = $esLocal ? $partido->media_rival : $partido->media_equipo;
                if (!is_null($mediaOponente)) {
                    $mediasRival[] = $mediaOponente;
                }
            }
            $mediaValoracionRivales = count($valoracionesRivales) > 0 ? round(array_sum($valoracionesRivales) / count($valoracionesRivales), 2) : null;
            $mediaMediaRival = count($mediasRival) > 0 ? round(array_sum($mediasRival) / count($mediasRival), 2) : null;

            // Mostrar ambas medias junto al nombre
            $nombreEquipoClasificacion = $equipo->nombre;
            $extras = [];
            if ($mediaValoracionRivales !== null) $extras[] = $mediaValoracionRivales;
            if ($mediaMediaRival !== null) $extras[] = $mediaMediaRival;
            if (count($extras) > 0) {
                $nombreEquipoClasificacion .= ' (' . implode(' / ', $extras) . ')';
            }

            $stats = [
                'equipo' => [
                    'id' => $equipo->id,
                    'nombre' => $nombreEquipoClasificacion,
                    'codigo' => $equipo->codigo,
                    'escudo' => $equipo->escudo,
                    'media_valoracion_rivales' => $mediaValoracionRivales,
                    'media_media_rival' => $mediaMediaRival,
                ],
                'orden_liga' => $plantilla->orden_liga,
                'partidos_jugados' => $partidosEquipo->count(),
                'victorias' => 0,
                'empates' => 0,
                'derrotas' => 0,
                'goles_favor' => 0,
                'goles_contra' => 0,
                'diferencia_goles' => 0,
                'puntos' => 0,
                'tarjetas_amarillas' => 0,
                'tarjetas_rojas' => 0,
                'posesion_promedio' => 0,
                'pases' => 0,
                'distancia_recorrida' => 0,
                'penalties_a_favor' => 0,
                'penalties_en_contra' => 0,
                'tiros_al_palo' => 0,
                'tiros_realizados' => 0,
                'tiros_recibidos' => 0,
                'tiros_a_puerta_realizados' => 0,
                'tiros_a_puerta_recibidos' => 0,
                'balones_ganados_equipo' => 0,
                'balones_perdidos_equipo' => 0,
                'interceciones_equipo' => 0,
            ];

            $totalPosesion = 0;
            foreach ($partidosEquipo as $partido) {
                $esLocal = (int) $partido->equipo_id === (int) $equipoId;

                $golesFavor = $esLocal ? $partido->goles_equipo : $partido->goles_rival;
                $golesContra = $esLocal ? $partido->goles_rival : $partido->goles_equipo;

                $stats['goles_favor'] += $golesFavor;
                $stats['goles_contra'] += $golesContra;
                $stats['tarjetas_amarillas'] += $esLocal ? $partido->tarjetas_amarillas_equipo : $partido->tarjetas_amarillas_rival;
                $stats['tarjetas_rojas'] += $esLocal ? $partido->tarjetas_rojas_equipo : $partido->tarjetas_rojas_rival;
                $totalPosesion += $esLocal ? $partido->posesion_equipo : $partido->posesion_rival;
                $stats['pases'] += $esLocal ? $partido->pases_equipo : $partido->pases_rival;
                $stats['distancia_recorrida'] += $esLocal ? $partido->distancia_equipo : $partido->distancia_rival;
                $stats['penalties_a_favor'] += $esLocal ? $partido->penalties_equipo : $partido->penalties_rival;
                $stats['penalties_en_contra'] += $esLocal ? $partido->penalties_rival : $partido->penalties_equipo;
                $stats['tiros_realizados'] += $esLocal ? ($partido->tiros_equipo ?? 0) : ($partido->tiros_rival ?? 0);
                $stats['tiros_recibidos'] += $esLocal ? ($partido->tiros_rival ?? 0) : ($partido->tiros_equipo ?? 0);
                $stats['tiros_a_puerta_realizados'] += $esLocal ? ($partido->tiros_a_puerta_equipo ?? 0) : ($partido->tiros_a_puerta_rival ?? 0);
                $stats['tiros_a_puerta_recibidos'] += $esLocal ? ($partido->tiros_a_puerta_rival ?? 0) : ($partido->tiros_a_puerta_equipo ?? 0);
                $stats['balones_ganados_equipo'] += $esLocal ? ($partido->balones_ganados_equipo ?? 0) : ($partido->balones_ganados_rival ?? 0);
                $stats['balones_perdidos_equipo'] += $esLocal ? ($partido->balones_perdidos_equipo ?? 0) : ($partido->balones_perdidos_rival ?? 0);
                $stats['interceciones_equipo'] += $esLocal ? ($partido->interceciones_equipo ?? 0) : ($partido->intercepciones_rival ?? 0);

                if ($golesFavor > $golesContra) {
                    $stats['victorias']++;
                    $stats['puntos'] += 3;
                } elseif ($golesFavor == $golesContra) {
                    $stats['empates']++;
                    $stats['puntos'] += 1;
                } else {
                    $stats['derrotas']++;
                }
            }

            $stats['diferencia_goles'] = $stats['goles_favor'] - $stats['goles_contra'];
            if ($stats['partidos_jugados'] > 0) {
                $stats['posesion_promedio'] = round($totalPosesion / $stats['partidos_jugados'], 1);
            }

            $estadisticas[] = $stats;
        }

        // Ordenar por puntos, diferencia de goles, y goles a favor
        usort($estadisticas, function ($a, $b) {
            if ($a['puntos'] != $b['puntos']) {
                return $b['puntos'] - $a['puntos'];
            }
            if ($a['diferencia_goles'] != $b['diferencia_goles']) {
                return $b['diferencia_goles'] - $a['diferencia_goles'];
            }
            return $b['goles_favor'] - $a['goles_favor'];
        });

        foreach ($estadisticas as &$estadisticaEquipo) {
            $estadisticaEquipo['tiros_a_puerta_realizados'] = max(0, $estadisticaEquipo['tiros_a_puerta_realizados']);
        }
        unset($estadisticaEquipo);

        // EstadÃ­sticas de equipos para los cards (sin valores entre parÃ©ntesis en el nombre)
        $estadisticas_equipos = [
            'balones_ganados_equipo' => collect($estadisticas)->sortByDesc('balones_ganados_equipo')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'balones_perdidos_equipo' => collect($estadisticas)->sortByDesc('balones_perdidos_equipo')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'interceciones_equipo' => collect($estadisticas)->sortByDesc('interceciones_equipo')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_a_puerta_realizados' => collect($estadisticas)->sortByDesc('tiros_a_puerta_realizados')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_a_puerta_realizados_min' => collect($estadisticas)->sortBy('tiros_a_puerta_realizados')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_realizados' => collect($estadisticas)->sortByDesc('tiros_realizados')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_realizados_min' => collect($estadisticas)->sortBy('tiros_realizados')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_al_palo' => collect($estadisticas)->sortByDesc('tiros_al_palo')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_a_puerta_recibidos' => collect($estadisticas)->sortByDesc('tiros_a_puerta_recibidos')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_a_puerta_recibidos_min' => collect($estadisticas)->sortBy('tiros_a_puerta_recibidos')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_recibidos' => collect($estadisticas)->sortByDesc('tiros_recibidos')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tiros_recibidos_min' => collect($estadisticas)->sortBy('tiros_recibidos')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'goles_favor' => collect($estadisticas)->sortByDesc('goles_favor')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'goles_contra' => collect($estadisticas)->sortBy('goles_contra')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'pases' => collect($estadisticas)->sortByDesc('pases')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'posesion' => collect($estadisticas)->sortByDesc('posesion_promedio')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tarjetas_amarillas' => collect($estadisticas)->sortByDesc('tarjetas_amarillas')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'tarjetas_rojas' => collect($estadisticas)->sortByDesc('tarjetas_rojas')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'distancia_recorrida' => collect($estadisticas)->sortByDesc('distancia_recorrida')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'penalties_a_favor' => collect($estadisticas)->sortByDesc('penalties_a_favor')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
            'penalties_en_contra' => collect($estadisticas)->sortByDesc('penalties_en_contra')->take(5)->map(function ($e) { $e['equipo']['nombre'] = preg_replace('/\s*\([^)]*\)/', '', $e['equipo']['nombre']); return $e; })->values()->all(),
        ];

        return Inertia::render('Temporadas/LigaEstadisticas', [
            'temporada' => $temporada,
            'liga' => $liga,
            'estadisticas' => $estadisticas,
            'partidos' => $partidos->map(fn ($p) => [
                'id' => $p->id,
                'jornada' => $p->jornada,
                'equipo_id' => $p->equipo_id,
                'visitante_id' => $p->visitante_id,
                'rival_id' => $p->rival_id,
                'goles_equipo' => $p->goles_equipo,
                'goles_rival' => $p->goles_rival,
                'partido_como_local' => $p->partido_como_local,
                'local_es_rival' => (bool) $p->local_es_rival,
                'visitante_es_rival' => (bool) $p->visitante_es_rival,
                'valor_equipo' => $p->valor_equipo,
                'valor_rival' => $p->valor_rival,
                'equipo' => ['id' => $p->equipo->id, 'nombre' => $p->equipo->nombre, 'codigo' => $p->equipo->codigo, 'escudo' => $p->equipo->escudo],
                'rival' => ['id' => $p->rival?->id, 'nombre' => $p->rival?->nombre ?? '—', 'codigo' => $p->rival?->codigo ?? null],
                'local' => [
                    'id' => $p->equipo?->id,
                    'nombre' => $p->equipo?->nombre ?? '—',
                    'codigo' => $p->equipo?->codigo ?? '—',
                ],
                'visitante' => [
                    'id' => $p->visitanteEquipo?->id,
                    'nombre' => $p->visitanteEquipo?->nombre ?? '—',
                    'codigo' => $p->visitanteEquipo?->codigo ?? '—',
                ],
            ])->values(),
            'estadisticas_equipos' => $estadisticas_equipos,
        ]);
    }

    public function jugadores(Temporada $temporada, Liga $liga)
    {
        $plantillas = Plantilla::where('temporada_id', $temporada->id)
            ->where('liga_id', $liga->id)
            ->with(['equipo', 'jugadores'])
            ->get();

        $equiposIds = $plantillas->pluck('equipo_id')->toArray();
        $campeonatosIds = $plantillas->pluck('campeonato_id')->filter()->unique()->values();

        $partidosQuery = Partido::where('temporada_id', $temporada->id)
            ->whereIn('equipo_id', $equiposIds)
            ->with(['alineaciones.jugador', 'eventos'])
            ->orderBy('jornada', 'asc');

        if ($campeonatosIds->isNotEmpty()) {
            $partidosQuery->whereIn('campeonato_id', $campeonatosIds->all());
        }

        $partidos = $partidosQuery->get();

        $jugadores = [];
        $jugadorEquipoIds = [];
        foreach ($plantillas as $plantilla) {
            $equipoId = $plantilla->equipo_id;
            foreach ($plantilla->jugadores as $jugador) {
                $jugadorId = $jugador->id;
                $jugadorEquipoIds[$jugadorId] = $equipoId;
                if (!isset($jugadores[$jugadorId])) {
                    $jugadores[$jugadorId] = [
                        'jugador' => [
                            'id' => $jugador->id,
                            'nombre' => $jugador->nombre,
                            'posicion' => $jugador->posicion,
                        ],
                        'equipo' => $plantilla->equipo->nombre,
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
                        'tarjetas_amarillas' => 0,
                        'tarjetas_rojas' => 0,
                        'distancia_recorrida' => 0,
                        'rendimiento' => 0,
                        'partidos_jugados' => 0,
                        'minutos_jugados' => 0,
                        'penalties_provocados' => 0,
                        'faltas_cometidas' => 0,
                        'faltas_recibidas' => 0,
                        'fueras_de_juego' => 0,
                        'paradas' => 0,
                        'jugador_del_partido' => 0,
                        'puntuacion_amonestaciones' => 0,
                        'penalties_parados' => 0,
                    ];
                }
            }
        }
        // Recorrer partidos y sumar stats SOLO si el jugador estÃ¡ en la plantilla del equipo para esa temporada y liga
        foreach ($partidos as $partido) {
            foreach ($partido->alineaciones as $alineacion) {
                $jugadorId = $alineacion->jugador_id;
                if (!isset($jugadores[$jugadorId])) continue;
                $esPortero = ($alineacion->jugador->posicion ?? null) === 'POR';
                // Sumar stats
                $jugadores[$jugadorId]['tiros'] += $alineacion->tiros ?? 0;
                if (!$esPortero) {
                    $jugadores[$jugadorId]['tiros_a_puerta'] += $alineacion->tiros_a_puerta ?? 0;
                    $jugadores[$jugadorId]['tiros_al_palo'] += $alineacion->tiros_al_palo ?? 0;
                }

                $jugadores[$jugadorId]['pases'] += $alineacion->pases ?? 0;
                $jugadores[$jugadorId]['pases_exitosos'] += $alineacion->pases_exitosos ?? 0;
                $jugadores[$jugadorId]['entradas'] += $alineacion->entradas ?? 0;
                $jugadores[$jugadorId]['entradas_exitosas'] += $alineacion->entradas_exitosas ?? 0;
                $jugadores[$jugadorId]['regates'] += $alineacion->regates ?? 0;
                $jugadores[$jugadorId]['regates_exitosos'] += $alineacion->regates_exitosos ?? 0;
                $jugadores[$jugadorId]['posesion_ganada'] += $alineacion->posesion_ganada ?? 0;
                $jugadores[$jugadorId]['posesion_perdida'] += $alineacion->posesion_perdida ?? 0;
                $jugadores[$jugadorId]['distancia_recorrida'] += $alineacion->distancia_recorrida ?? 0;
                $jugadores[$jugadorId]['rendimiento'] += $alineacion->rendimiento ?? 0;
                $jugadores[$jugadorId]['faltas_cometidas'] += $alineacion->faltas_cometidas ?? 0;
                $jugadores[$jugadorId]['faltas_recibidas'] += $alineacion->faltas_recibidas ?? 0;
                $jugadores[$jugadorId]['fueras_de_juego'] += $alineacion->fueras_de_juego ?? 0;
                $jugadores[$jugadorId]['jugador_del_partido'] += ($alineacion->jugador_del_partido ?? 0);
                $jugadores[$jugadorId]['partidos_jugados']++;
            }
            // Calcular minutos jugados correctamente y paradas de porteros
            $duracionPartido = $partido->minutos_jugados ?? 90;
            foreach ($partido->alineaciones as $alineacion) {
                $jugadorId = $alineacion->jugador_id;
                if (!isset($jugadores[$jugadorId])) continue;
                // Buscar eventos de sustituciÃ³n para este jugador
                $eventoSale = $partido->eventos->first(function ($e) use ($jugadorId) {
                    return $e->jugador_id == $jugadorId && $e->tipo_evento_id == TipoEvento::SALE;
                });
                $eventoEntra = $partido->eventos->first(function ($e) use ($jugadorId) {
                    return $e->jugador_id == $jugadorId && $e->tipo_evento_id == TipoEvento::ENTRA;
                });
                if ($eventoEntra && $eventoSale) {
                    $minutos = ($eventoSale->minuto ?? 0) - ($eventoEntra->minuto ?? 0);
                } elseif ($eventoSale) {
                    $minutos = $eventoSale->minuto ?? $duracionPartido;
                } elseif ($eventoEntra) {
                    $minutos = $duracionPartido - ($eventoEntra->minuto ?? 0);
                } else {
                    $minutos = $duracionPartido;
                }
                $jugadores[$jugadorId]['minutos_jugados'] += $minutos;
                // Calcular paradas para porteros: tiros a puerta rival - goles encajados
                if ($alineacion->jugador->posicion == 'POR') {
                    $tirosAPuertaRival = $partido->tiros_a_puerta_rival ?? 0;
                    $golesEncajados = $partido->goles_rival ?? 0;
                    $paradas = max(0, $tirosAPuertaRival - $golesEncajados);
                    $jugadores[$jugadorId]['paradas'] += $paradas;
                }
            }
            // Procesar eventos
            foreach ($partido->eventos as $evento) {
                if ($evento->jugador_id == config('app.jugador_extra_id')) continue;
                $jugadorId = $evento->jugador_id;
                if (!isset($jugadores[$jugadorId])) continue;
                if ($evento->tipo_evento_id == TipoEvento::GOL) {
                    $jugadores[$jugadorId]['goles']++;
                } elseif ($evento->tipo_evento_id == TipoEvento::ASISTENCIA) {
                    $jugadores[$jugadorId]['asistencias']++;
                } elseif ($evento->tipo_evento_id == TipoEvento::PENALTY_PROVOCADO) {
                    $jugadores[$jugadorId]['penalties_provocados']++;
                } elseif ($evento->tipo_evento_id == TipoEvento::TARJETA_AMARILLA) {
                    $jugadores[$jugadorId]['tarjetas_amarillas']++;
                    $jugadores[$jugadorId]['puntuacion_amonestaciones'] += 1;
                } elseif ($evento->tipo_evento_id == TipoEvento::TARJETA_ROJA) {
                    $jugadores[$jugadorId]['tarjetas_rojas']++;
                    $jugadores[$jugadorId]['puntuacion_amonestaciones'] += 3;
                } elseif ($evento->tipo_evento_id == TipoEvento::PENALTY_PARADO) {
                    $jugadores[$jugadorId]['penalties_parados']++;
                }
            }
        }

        $jugadoresArray = array_values($jugadores);

        $topGoleadores = collect($jugadoresArray)->sortByDesc('goles')->take(10)->values()->toArray();
        $topAsistentes = collect($jugadoresArray)->sortByDesc('asistencias')->take(10)->values()->toArray();
        $topTirosAlPalo = collect($jugadoresArray)->sortByDesc('tiros_al_palo')->take(10)->values()->toArray();
        $topPasadores = collect($jugadoresArray)->sortByDesc('pases_exitosos')->take(10)->values()->toArray();
        $topAmarillas = collect($jugadoresArray)->sortByDesc('tarjetas_amarillas')->take(10)->values()->toArray();
        $topMinutos = collect($jugadoresArray)->sortByDesc('minutos_jugados')->take(10)->values()->toArray();
        $topRendimiento = collect($jugadoresArray)->map(function ($j) {
            $j['rendimiento_promedio'] = $j['partidos_jugados'] > 0 ? round($j['rendimiento'] / $j['partidos_jugados'], 2) : 0;
            return $j;
        })->sortByDesc('rendimiento_promedio')->take(10)->values()->toArray();
        $topKilometros = collect($jugadoresArray)->sortByDesc('distancia_recorrida')->take(10)->values()->toArray();
        $topRecuperaciones = collect($jugadoresArray)->sortByDesc('posesion_ganada')->take(10)->values()->toArray();
        $topPerdidas = collect($jugadoresArray)->sortByDesc('posesion_perdida')->take(10)->values()->toArray();
        $topRegates = collect($jugadoresArray)->sortByDesc('regates_exitosos')->take(10)->values()->toArray();
        $topPenaltiesProvocados = collect($jugadoresArray)->sortByDesc('penalties_provocados')->take(10)->values()->toArray();
        $topFaltasCometidas = collect($jugadoresArray)->sortByDesc('faltas_cometidas')->take(10)->values()->toArray();
        $topFaltasRecibidas = collect($jugadoresArray)->sortByDesc('faltas_recibidas')->take(10)->values()->toArray();
        $topParadas = collect($jugadoresArray)->filter(fn ($j) => $j['jugador']['posicion'] == 'POR')->sortByDesc('paradas')->take(10)->values()->toArray();
        $topFuerasJuego = collect($jugadoresArray)->sortByDesc('fueras_de_juego')->take(10)->values()->toArray();
        $topMVP = collect($jugadoresArray)->sortByDesc('jugador_del_partido')->take(10)->values()->toArray();
        $topAmonestados = collect($jugadoresArray)->sortByDesc('puntuacion_amonestaciones')->take(10)->values()->toArray();
        $topRojas = collect($jugadoresArray)->sortByDesc('tarjetas_rojas')->take(10)->values()->toArray();
        $topPenaltiesParados = collect($jugadoresArray)->filter(fn ($j) => $j['jugador']['posicion'] == 'POR')->sortByDesc('penalties_parados')->take(10)->values()->toArray();

        return response()->json([
            'topGoleadores' => $topGoleadores,
            'topAsistentes' => $topAsistentes,
            'topTirosAlPalo' => $topTirosAlPalo,
            'topPasadores' => $topPasadores,
            'topAmarillas' => $topAmarillas,
            'topMinutos' => $topMinutos,
            'topRendimiento' => $topRendimiento,
            'topKilometros' => $topKilometros,
            'topRecuperaciones' => $topRecuperaciones,
            'topPerdidas' => $topPerdidas,
            'topRegates' => $topRegates,
            'topPenaltiesProvocados' => $topPenaltiesProvocados,
            'topFaltasCometidas' => $topFaltasCometidas,
            'topFaltasRecibidas' => $topFaltasRecibidas,
            'topParadas' => $topParadas,
            'topFuerasJuego' => $topFuerasJuego,
            'topMVP' => $topMVP,
            'topAmonestados' => $topAmonestados,
            'topRojas' => $topRojas,
            'topPenaltiesParados' => $topPenaltiesParados,
        ]);
    }
}
