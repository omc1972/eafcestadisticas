<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;
use App\Models\SquadBattle;
use App\Models\Equipo;
use App\Models\Temporada;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SquadBattleController extends Controller
{
    public function index()
    {
        $squadBattles = SquadBattle::with(['divisiones', 'temporada'])->get();
        return Inertia::render('SquadBattles/Index', [
            'squadBattles' => $squadBattles,
        ]);
    }

    public function create()
    {
        return Inertia::render('SquadBattles/Create', [
            'temporadas' => Temporada::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'temporada_id' => 'nullable|exists:temporadas,id',
        ]);
        SquadBattle::create($validated);
        return redirect()->route('squad-battles.index')->with('success', 'Squad Battle creado correctamente');
    }

    public function show(SquadBattle $squadBattle)
    {
        $squadBattle->load(['divisiones.rivales.equipo', 'temporada']);

        $equipoIds = $squadBattle->divisiones
            ->flatMap(fn($d) => $d->rivales->pluck('equipo_id'))
            ->unique()
            ->values();

        $plantillasQuery = Plantilla::with(['equipo', 'jugadores' => fn($q) => $q->select('jugadors.id', 'nombre', 'media')])
            ->whereIn('equipo_id', $equipoIds)
            ->orderByDesc('id');

        if ($squadBattle->temporada_id) {
            $plantillasQuery->where('temporada_id', $squadBattle->temporada_id);
        }

        $plantillasMap = $plantillasQuery->get()
            ->groupBy('equipo_id')
            ->map(function ($grupo) {
                $p = $grupo->sortByDesc(fn($p) => $p->jugadores->count())->first();
                $jugadores = $p->jugadores;
                $titulares = $jugadores->filter(fn($j) => $j->pivot->es_titular);

                return [
                    'id'               => $p->id,
                    'equipo'           => ['id' => $p->equipo->id, 'nombre' => $p->equipo->nombre],
                    'media_plantilla'  => $jugadores->count() ? round($jugadores->avg('media'), 1) : null,
                    'media_alineacion' => $titulares->count() ? round($titulares->avg('media'), 1) : null,
                    'jugadores'        => $jugadores->map(fn($j) => [
                        'id' => $j->id,
                        'nombre' => $j->nombre,
                        'media' => $j->media,
                        'es_titular' => $j->pivot->es_titular,
                    ])->values(),
                ];
            });

        $partidosQuery = \App\Models\Partido::query()
            ->whereNotNull('goles_equipo')
            ->whereNotNull('goles_rival')
            ->with(['equipo', 'visitanteEquipo'])
            ->where(function ($q) use ($equipoIds) {
                $q->whereIn('equipo_id', $equipoIds)
                  ->orWhereIn('visitante_id', $equipoIds);
            });

        if ($squadBattle->temporada_id) {
            $partidosQuery->where('temporada_id', $squadBattle->temporada_id);
        }

        $partidos = $partidosQuery->orderBy('jornada', 'asc')->get();

        $divisionesData = [];
        foreach ($squadBattle->divisiones as $division) {
            $divisionEquipoIds = $division->rivales->pluck('equipo_id')->toArray();

            $estadisticas = [];
            foreach ($divisionEquipoIds as $eqId) {
                $equipoPlantilla = $plantillasMap[$eqId] ?? null;
                $equipo = Equipo::find($eqId);

                $stats = [
                    'equipo' => ['id' => $equipo->id, 'nombre' => $equipo->nombre, 'codigo' => $equipo->codigo, 'escudo' => $equipo->escudo],
                    'partidos_jugados' => 0,
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
                    'tiros_al_palo' => 0,
                    'tiros_realizados' => 0,
                    'tiros_recibidos' => 0,
                    'pases' => 0,
                    'distancia_recorrida' => 0,
                    'balones_ganados_equipo' => 0,
                    'interceciones_equipo' => 0,
                    'penalties_equipo' => 0,
                    'penalties_rival' => 0,
                ];

                $totalPosesion = 0;
                foreach ($partidos as $p) {
                    $esLocal     = !$p->local_es_rival     && $p->equipo_id    == $eqId;
                    $esVisitante = !$p->visitante_es_rival && $p->visitante_id == $eqId;

                    if ($esLocal || $esVisitante) {
                        $stats['partidos_jugados']++;

                        $golesFavor = $esLocal ? $p->goles_equipo : $p->goles_rival;
                        $golesContra = $esLocal ? $p->goles_rival : $p->goles_equipo;

                        $stats['goles_favor'] += $golesFavor;
                        $stats['goles_contra'] += $golesContra;
                        $stats['tarjetas_amarillas'] += $esLocal ? ($p->tarjetas_amarillas_equipo ?? 0) : ($p->tarjetas_amarillas_rival ?? 0);
                        $stats['tarjetas_rojas'] += $esLocal ? ($p->tarjetas_rojas_equipo ?? 0) : ($p->tarjetas_rojas_rival ?? 0);
                        $totalPosesion += $esLocal ? ($p->posesion_equipo ?? 0) : ($p->posesion_rival ?? 0);
                        $stats['pases'] += $esLocal ? ($p->pases_equipo ?? 0) : ($p->pases_rival ?? 0);
                        $stats['distancia_recorrida'] += $esLocal ? ($p->distancia_equipo ?? 0) : ($p->distancia_rival ?? 0);
                        $stats['tiros_realizados'] += $esLocal ? ($p->tiros_equipo ?? 0) : ($p->tiros_rival ?? 0);
                        $stats['tiros_recibidos'] += $esLocal ? ($p->tiros_rival ?? 0) : ($p->tiros_equipo ?? 0);
                        $stats['tiros_al_palo'] += $esLocal ? ($p->tiros_al_palo_equipo ?? 0) : ($p->tiros_al_palo_rival ?? 0);
                        $stats['balones_ganados_equipo'] += $esLocal ? ($p->balones_ganados_equipo ?? 0) : ($p->balones_ganados_rival ?? 0);
                        $stats['interceciones_equipo'] += $esLocal ? ($p->interceciones_equipo ?? 0) : ($p->intercepciones_rival ?? 0);
                        $stats['penalties_equipo'] += $esLocal ? ($p->penalties_equipo ?? 0) : ($p->penalties_rival ?? 0);
                        $stats['penalties_rival'] += $esLocal ? ($p->penalties_rival ?? 0) : ($p->penalties_equipo ?? 0);

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
                }

                $stats['diferencia_goles'] = $stats['goles_favor'] - $stats['goles_contra'];
                if ($stats['partidos_jugados'] > 0) {
                    $stats['posesion_promedio'] = round($totalPosesion / $stats['partidos_jugados'], 1);
                }

                $estadisticas[] = $stats;
            }

            usort($estadisticas, function ($a, $b) {
                if ($a['puntos'] != $b['puntos']) return $b['puntos'] - $a['puntos'];
                if ($a['diferencia_goles'] != $b['diferencia_goles']) return $b['diferencia_goles'] - $a['diferencia_goles'];
                return $b['goles_favor'] - $a['goles_favor'];
            });

            // Top 5 estadísticas por categoría
            $estadisticas_equipos = [
                'goles_favor' => collect($estadisticas)->sortByDesc('goles_favor')->take(5)->values()->all(),
                'goles_contra' => collect($estadisticas)->sortBy('goles_contra')->take(5)->values()->all(),
                'tiros_realizados' => collect($estadisticas)->sortByDesc('tiros_realizados')->take(5)->values()->all(),
                'tiros_al_palo' => collect($estadisticas)->sortByDesc('tiros_al_palo')->take(5)->values()->all(),
                'tiros_recibidos' => collect($estadisticas)->sortByDesc('tiros_recibidos')->take(5)->values()->all(),
                'pases' => collect($estadisticas)->sortByDesc('pases')->take(5)->values()->all(),
                'distancia_recorrida' => collect($estadisticas)->sortByDesc('distancia_recorrida')->take(5)->values()->all(),
                'posesion' => collect($estadisticas)->sortByDesc('posesion_promedio')->take(5)->values()->all(),
                'balones_ganados_equipo' => collect($estadisticas)->sortByDesc('balones_ganados_equipo')->take(5)->values()->all(),
                'interceciones_equipo' => collect($estadisticas)->sortByDesc('interceciones_equipo')->take(5)->values()->all(),
                'tarjetas_amarillas' => collect($estadisticas)->sortByDesc('tarjetas_amarillas')->take(5)->values()->all(),
                'tarjetas_rojas' => collect($estadisticas)->sortByDesc('tarjetas_rojas')->take(5)->values()->all(),
                'penalties_equipo' => collect($estadisticas)->sortByDesc('penalties_equipo')->take(5)->values()->all(),
            ];

            $divisionesData[$division->id] = [
                'estadisticas' => $estadisticas,
                'estadisticas_equipos' => $estadisticas_equipos,
                'partidos' => $partidos->filter(fn($p) => in_array($p->equipo_id, $divisionEquipoIds) && in_array($p->visitante_id, $divisionEquipoIds)),
            ];
        }

        return Inertia::render('SquadBattles/Show', [
            'squadBattle'    => $squadBattle,
            'equipos'        => Equipo::orderBy('nombre')->get(['id', 'nombre']),
            'plantillasMap'  => $plantillasMap,
            'divisionesData' => $divisionesData,
        ]);
    }

    public function edit(SquadBattle $squadBattle)
    {
        return Inertia::render('SquadBattles/Edit', [
            'squadBattle' => $squadBattle,
            'temporadas'  => Temporada::all(),
        ]);
    }

    public function update(Request $request, SquadBattle $squadBattle)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'temporada_id' => 'nullable|exists:temporadas,id',
        ]);
        $squadBattle->update($validated);
        return redirect()->route('squad-battles.index')->with('success', 'Squad Battle actualizado correctamente');
    }

    public function destroy(SquadBattle $squadBattle)
    {
        $squadBattle->delete();
        return redirect()->route('squad-battles.index')->with('success', 'Squad Battle eliminado correctamente');
    }
}
