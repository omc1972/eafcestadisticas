<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Temporada;
use App\Models\Competicion;
use App\Support\EstadisticasHelper;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CompeticionEstadisticasController extends Controller
{
    /**
     * Estadísticas por equipos para una competición (opcional por temporada)
     */
    public function show(Competicion $competicion, Request $request)
    {
        $temporadaId = $request->query('temporada_id');
        $scope = $request->query('scope');

        // Soporta llamadas con ?scope=jugadores para mantener compatibilidad con el frontend
        if ($scope === 'jugadores') {
            return $this->jugadores($competicion, $request);
        }

        // Obtener partidos de la competición (y temporada si se pasa)
        $partidosQuery = Partido::where('competicion_id', $competicion->id)
            ->with(['equipo', 'visitanteEquipo', 'rival']);

        if ($temporadaId) {
            $partidosQuery->where('temporada_id', $temporadaId);
        }

        $partidos = $partidosQuery->orderBy('jornada', 'asc')->get();

        // Obtener equipos que aparecen en estos partidos
        $equiposIds = collect($partidos->pluck('equipo_id')->merge($partidos->pluck('visitante_id'))->filter()->unique()->values())->toArray();

        // Calcular estadísticas por equipo
        $estadisticas = [];

        foreach ($equiposIds as $equipoId) {
            $partidosEquipo = $partidos->filter(function ($p) use ($equipoId) {
                return (int) $p->equipo_id === (int) $equipoId || (int) $p->visitante_id === (int) $equipoId;
            });

            $equipoObj = $partidosEquipo->first()?->equipo ?? null;
            if (!$equipoObj) {
                // intentar buscar visitanteEquipo
                $equipoObj = $partidosEquipo->first()?->visitanteEquipo ?? null;
            }

            $stats = EstadisticasHelper::statsEquipo($partidosEquipo, (int) $equipoId, [
                'equipo' => [
                    'id'     => $equipoObj?->id ?? $equipoId,
                    'nombre' => $equipoObj?->nombre ?? '—',
                    'codigo' => $equipoObj?->codigo ?? null,
                    'escudo' => $equipoObj?->escudo ?? null,
                ],
            ]);

            $estadisticas[] = $stats;
        }

        // Ordenar
        usort($estadisticas, function ($a, $b) {
            if ($a['puntos'] != $b['puntos']) return $b['puntos'] - $a['puntos'];
            if ($a['diferencia_goles'] != $b['diferencia_goles']) return $b['diferencia_goles'] - $a['diferencia_goles'];
            return $b['goles_favor'] - $a['goles_favor'];
        });

        // Preparar partidos para respuesta
        $partidosResponse = $partidos->map(fn($p) => [
            'id' => $p->id,
            'jornada' => $p->jornada,
            'equipo_id' => $p->equipo_id,
            'visitante_id' => $p->visitante_id,
            'rival_id' => $p->rival_id,
            'goles_equipo' => $p->goles_equipo,
            'goles_rival' => $p->goles_rival,
            'partido_como_local' => $p->partido_como_local,
            'local_es_rival' => (bool)$p->local_es_rival,
            'visitante_es_rival' => (bool)$p->visitante_es_rival,
            'valor_equipo' => $p->valor_equipo,
            'valor_rival' => $p->valor_rival,
            'equipo' => ['id' => $p->equipo?->id, 'nombre' => $p->equipo?->nombre, 'codigo' => $p->equipo?->codigo, 'escudo' => $p->equipo?->escudo],
            'rival' => ['id' => $p->rival?->id, 'nombre' => $p->rival?->nombre ?? '—', 'codigo' => $p->rival?->codigo ?? null],
            'local' => ['id' => $p->equipo?->id, 'nombre' => $p->equipo?->nombre ?? '—', 'codigo' => $p->equipo?->codigo ?? '—'],
            'visitante' => ['id' => $p->visitanteEquipo?->id, 'nombre' => $p->visitanteEquipo?->nombre ?? '—', 'codigo' => $p->visitanteEquipo?->codigo ?? '—'],
        ])->values();

        // Estadísticas summary por equipo (top lists) - construir similar a liga
        $estadisticas_equipos = [
            'goles_favor' => collect($estadisticas)->sortByDesc('goles_favor')->take(5)->values()->all(),
            'goles_contra' => collect($estadisticas)->sortBy('goles_contra')->take(5)->values()->all(),
            'tiros_realizados' => collect($estadisticas)->sortByDesc('tiros_realizados')->take(5)->values()->all(),
            'tiros_realizados_min' => collect($estadisticas)->sortBy('tiros_realizados')->take(5)->values()->all(),
            'tiros_a_puerta_realizados' => collect($estadisticas)->sortByDesc('tiros_a_puerta_realizados')->take(5)->values()->all(),
            'tiros_a_puerta_realizados_min' => collect($estadisticas)->sortBy('tiros_a_puerta_realizados')->take(5)->values()->all(),
            'tiros_a_puerta_recibidos' => collect($estadisticas)->sortByDesc('tiros_a_puerta_recibidos')->take(5)->values()->all(),
            'tiros_a_puerta_recibidos_min' => collect($estadisticas)->sortBy('tiros_a_puerta_recibidos')->take(5)->values()->all(),
            'tiros_recibidos' => collect($estadisticas)->sortByDesc('tiros_recibidos')->take(5)->values()->all(),
            'tiros_recibidos_min' => collect($estadisticas)->sortBy('tiros_recibidos')->take(5)->values()->all(),
            'pases' => collect($estadisticas)->sortByDesc('pases')->take(5)->values()->all(),
            'posesion' => collect($estadisticas)->sortByDesc('posesion_promedio')->take(5)->values()->all(),
            'tarjetas_amarillas' => collect($estadisticas)->sortByDesc('tarjetas_amarillas')->take(5)->values()->all(),
            'tarjetas_rojas' => collect($estadisticas)->sortByDesc('tarjetas_rojas')->take(5)->values()->all(),
            'distancia_recorrida' => collect($estadisticas)->sortByDesc('distancia_recorrida')->take(5)->values()->all(),
            'penalties_a_favor' => collect($estadisticas)->sortByDesc('penalties_a_favor')->take(5)->values()->all(),
            'penalties_en_contra' => collect($estadisticas)->sortByDesc('penalties_en_contra')->take(5)->values()->all(),
            'balones_ganados_equipo' => collect($estadisticas)->sortByDesc('balones_ganados_equipo')->take(5)->values()->all(),
            'balones_perdidos_equipo' => collect($estadisticas)->sortByDesc('balones_perdidos_equipo')->take(5)->values()->all(),
            'interceciones_equipo' => collect($estadisticas)->sortByDesc('interceciones_equipo')->take(5)->values()->all(),
            'tiros_al_palo' => collect($estadisticas)->sortByDesc('tiros_al_palo')->take(5)->values()->all(),
        ];

        return response()->json([
            'estadisticas' => $estadisticas,
            'partidos' => $partidosResponse,
            'estadisticas_equipos' => $estadisticas_equipos,
        ]);
    }

    /**
     * Estadísticas de jugadores para una competición (opcional por temporada)
     */
    public function jugadores(Competicion $competicion, Request $request)
    {
        $temporadaId = $request->query('temporada_id');

        $partidosQuery = Partido::where('competicion_id', $competicion->id)
            ->with(['alineaciones.jugador', 'eventos'])
            ->orderBy('jornada', 'asc');

        if ($temporadaId) $partidosQuery->where('temporada_id', $temporadaId);

        $partidos = $partidosQuery->get();

        $jugadores = EstadisticasHelper::statsJugadores($partidos);

        $jugadoresArray = array_values($jugadores);

        $topGoleadores = collect($jugadoresArray)->sortByDesc('goles')->take(10)->values()->toArray();
        $topAsistentes = collect($jugadoresArray)->sortByDesc('asistencias')->take(10)->values()->toArray();
        $topTirosAlPalo = collect($jugadoresArray)->sortByDesc('tiros_al_palo')->take(10)->values()->toArray();
        $topPasadores = collect($jugadoresArray)->sortByDesc('pases_exitosos')->take(10)->values()->toArray();
        $topAmarillas = collect($jugadoresArray)->sortByDesc('tarjetas_amarillas')->take(10)->values()->toArray();
        $topMinutos = collect($jugadoresArray)->sortByDesc('minutos_jugados')->take(10)->values()->toArray();
        $topRendimiento = collect($jugadoresArray)->map(fn($j) => array_merge($j, ['rendimiento_promedio' => $j['partidos_jugados'] > 0 ? round($j['rendimiento'] / $j['partidos_jugados'], 2) : 0]))->sortByDesc('rendimiento_promedio')->take(10)->values()->toArray();
        $topKilometros = collect($jugadoresArray)->sortByDesc('distancia_recorrida')->take(10)->values()->toArray();
        $topRecuperaciones = collect($jugadoresArray)->sortByDesc('posesion_ganada')->take(10)->values()->toArray();
        $topPerdidas = collect($jugadoresArray)->sortByDesc('posesion_perdida')->take(10)->values()->toArray();
        $topRegates = collect($jugadoresArray)->sortByDesc('regates_exitosos')->take(10)->values()->toArray();
        $topPenaltiesProvocados = collect($jugadoresArray)->sortByDesc('penalties_provocados')->take(10)->values()->toArray();
        $topFaltasCometidas = collect($jugadoresArray)->sortByDesc('faltas_cometidas')->take(10)->values()->toArray();
        $topFaltasRecibidas = collect($jugadoresArray)->sortByDesc('faltas_recibidas')->take(10)->values()->toArray();
        $topParadas = collect($jugadoresArray)->filter(fn($j) => ($j['jugador']['posicion'] ?? null) == 'POR')->sortByDesc('paradas')->take(10)->values()->toArray();
        $topFuerasJuego = collect($jugadoresArray)->sortByDesc('fueras_de_juego')->take(10)->values()->toArray();
        $topMVP = collect($jugadoresArray)->sortByDesc('jugador_del_partido')->take(10)->values()->toArray();
        $topAmonestados = collect($jugadoresArray)->sortByDesc('puntuacion_amonestaciones')->take(10)->values()->toArray();
        $topRojas = collect($jugadoresArray)->sortByDesc('tarjetas_rojas')->take(10)->values()->toArray();
        $topPenaltiesParados = collect($jugadoresArray)->filter(fn($j) => ($j['jugador']['posicion'] ?? null) == 'POR')->sortByDesc('penalties_parados')->take(10)->values()->toArray();

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

    /**
     * Render Inertia page replicando la vista de LigaEstadisticas para una competición y temporada
     */
    public function showPage(Temporada $temporada, Competicion $competicion)
    {
        // Filtrar partidos de la competición y temporada
        $partidos = Partido::where('competicion_id', $competicion->id)
            ->where('temporada_id', $temporada->id)
            ->with(['equipo', 'visitanteEquipo', 'rival'])
            ->orderBy('jornada', 'asc')
            ->get();

        // Equipos presentes
        $equiposIds = collect($partidos->pluck('equipo_id')->merge($partidos->pluck('visitante_id'))->filter()->unique()->values())->toArray();

        $estadisticas = [];
        foreach ($equiposIds as $equipoId) {
            $partidosEquipo = $partidos->filter(function ($p) use ($equipoId) {
                return (int) $p->equipo_id === (int) $equipoId || (int) $p->visitante_id === (int) $equipoId;
            });

            $equipoObj = $partidosEquipo->first()?->equipo ?? $partidosEquipo->first()?->visitanteEquipo ?? null;
            $stats = EstadisticasHelper::statsEquipo($partidosEquipo, (int) $equipoId, [
                'equipo' => [
                    'id'     => $equipoObj?->id ?? $equipoId,
                    'nombre' => $equipoObj?->nombre ?? '—',
                    'codigo' => $equipoObj?->codigo ?? null,
                    'escudo' => $equipoObj?->escudo ?? null,
                ],
                'orden_liga' => null,
            ]);

            $estadisticas[] = $stats;
        }

        usort($estadisticas, function ($a, $b) {
            if ($a['puntos'] != $b['puntos']) return $b['puntos'] - $a['puntos'];
            if ($a['diferencia_goles'] != $b['diferencia_goles']) return $b['diferencia_goles'] - $a['diferencia_goles'];
            return $b['goles_favor'] - $a['goles_favor'];
        });

        $partidosResponse = $partidos->map(fn($p) => [
            'id' => $p->id,
            'jornada' => $p->jornada,
            'equipo_id' => $p->equipo_id,
            'visitante_id' => $p->visitante_id,
            'rival_id' => $p->rival_id,
            'goles_equipo' => $p->goles_equipo,
            'goles_rival' => $p->goles_rival,
            'partido_como_local' => $p->partido_como_local,
            'local_es_rival' => (bool)$p->local_es_rival,
            'visitante_es_rival' => (bool)$p->visitante_es_rival,
            'valor_equipo' => $p->valor_equipo,
            'valor_rival' => $p->valor_rival,
            'equipo' => ['id' => $p->equipo?->id, 'nombre' => $p->equipo?->nombre, 'codigo' => $p->equipo?->codigo, 'escudo' => $p->equipo?->escudo],
            'rival' => ['id' => $p->rival?->id, 'nombre' => $p->rival?->nombre ?? '—', 'codigo' => $p->rival?->codigo ?? null],
            'local' => ['id' => $p->equipo?->id, 'nombre' => $p->equipo?->nombre ?? '—', 'codigo' => $p->equipo?->codigo ?? '—'],
            'visitante' => ['id' => $p->visitanteEquipo?->id, 'nombre' => $p->visitanteEquipo?->nombre ?? '—', 'codigo' => $p->visitanteEquipo?->codigo ?? '—'],
        ])->values();

        $estadisticas_equipos = [
            'goles_favor' => collect($estadisticas)->sortByDesc('goles_favor')->take(5)->values()->all(),
            'goles_contra' => collect($estadisticas)->sortBy('goles_contra')->take(5)->values()->all(),
            'tiros_realizados' => collect($estadisticas)->sortByDesc('tiros_realizados')->take(5)->values()->all(),
            'tiros_realizados_min' => collect($estadisticas)->sortBy('tiros_realizados')->take(5)->values()->all(),
            'tiros_a_puerta_realizados' => collect($estadisticas)->sortByDesc('tiros_a_puerta_realizados')->take(5)->values()->all(),
            'tiros_a_puerta_realizados_min' => collect($estadisticas)->sortBy('tiros_a_puerta_realizados')->take(5)->values()->all(),
            'tiros_a_puerta_recibidos' => collect($estadisticas)->sortByDesc('tiros_a_puerta_recibidos')->take(5)->values()->all(),
            'tiros_a_puerta_recibidos_min' => collect($estadisticas)->sortBy('tiros_a_puerta_recibidos')->take(5)->values()->all(),
            'tiros_recibidos' => collect($estadisticas)->sortByDesc('tiros_recibidos')->take(5)->values()->all(),
            'tiros_recibidos_min' => collect($estadisticas)->sortBy('tiros_recibidos')->take(5)->values()->all(),
            'pases' => collect($estadisticas)->sortByDesc('pases')->take(5)->values()->all(),
            'posesion' => collect($estadisticas)->sortByDesc('posesion_promedio')->take(5)->values()->all(),
            'tarjetas_amarillas' => collect($estadisticas)->sortByDesc('tarjetas_amarillas')->take(5)->values()->all(),
            'tarjetas_rojas' => collect($estadisticas)->sortByDesc('tarjetas_rojas')->take(5)->values()->all(),
            'distancia_recorrida' => collect($estadisticas)->sortByDesc('distancia_recorrida')->take(5)->values()->all(),
            'penalties_a_favor' => collect($estadisticas)->sortByDesc('penalties_a_favor')->take(5)->values()->all(),
            'penalties_en_contra' => collect($estadisticas)->sortByDesc('penalties_en_contra')->take(5)->values()->all(),
            'balones_ganados_equipo' => collect($estadisticas)->sortByDesc('balones_ganados_equipo')->take(5)->values()->all(),
            'balones_perdidos_equipo' => collect($estadisticas)->sortByDesc('balones_perdidos_equipo')->take(5)->values()->all(),
            'interceciones_equipo' => collect($estadisticas)->sortByDesc('interceciones_equipo')->take(5)->values()->all(),
            'tiros_al_palo' => collect($estadisticas)->sortByDesc('tiros_al_palo')->take(5)->values()->all(),
        ];

        // Reusar el componente de LigaEstadisticas para renderizar idéntica UI
        return Inertia::render('Temporadas/LigaEstadisticas', [
            'temporada' => $temporada,
            'liga' => ['id' => $competicion->id, 'nombre' => $competicion->nombre, 'pais' => null],
            'estadisticas' => $estadisticas,
            'partidos' => $partidosResponse,
            'estadisticas_equipos' => $estadisticas_equipos,
        ]);
    }
}
