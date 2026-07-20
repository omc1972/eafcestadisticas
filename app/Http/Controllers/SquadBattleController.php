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

        // Recoger todos los equipo_ids presentes en las divisiones
        $equipoIds = $squadBattle->divisiones
            ->flatMap(fn($d) => $d->rivales->pluck('equipo_id'))
            ->unique()
            ->values();

        // Cargar plantillas de esos equipos filtradas por temporada
        $plantillasQuery = Plantilla::with(['jugadores' => fn($q) => $q->select('jugadors.id', 'media')])
            ->whereIn('equipo_id', $equipoIds)
            ->orderByDesc('id');

        if ($squadBattle->temporada_id) {
            $plantillasQuery->where('temporada_id', $squadBattle->temporada_id);
        }

        // Agrupar por equipo y quedarse con la plantilla que tenga más jugadores
        $plantillasMap = $plantillasQuery->get()
            ->groupBy('equipo_id')
            ->map(function ($grupo) {
                $p = $grupo->sortByDesc(fn($p) => $p->jugadores->count())->first();

                $jugadores = $p->jugadores;
                $titulares = $jugadores->filter(fn($j) => $j->pivot->es_titular);

                return [
                    'id'               => $p->id,
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

        // Calcular clasificación a partir de partidos jugados
        $partidosQuery = \App\Models\Partido::query()
            ->whereNotNull('goles_equipo')
            ->whereNotNull('goles_rival')
            ->where(function ($q) use ($equipoIds) {
                $q->whereIn('equipo_id', $equipoIds)
                  ->orWhereIn('visitante_id', $equipoIds);
            });

        if ($squadBattle->temporada_id) {
            $partidosQuery->where('temporada_id', $squadBattle->temporada_id);
        }

        $partidos = $partidosQuery->get();

        // Estadísticas por división
        $divisionesData = [];
        foreach ($squadBattle->divisiones as $division) {
            $divisionEquipoIds = $division->rivales->pluck('equipo_id')->toArray();

            $standingsMap = [];
            $jugadoresStats = [];

            foreach ($divisionEquipoIds as $eqId) {
                $s = ['pj' => 0, 'pg' => 0, 'pe' => 0, 'pp' => 0, 'gf' => 0, 'gc' => 0, 'dif' => 0, 'pts' => 0];

                foreach ($partidos as $p) {
                    $esLocal     = !$p->local_es_rival     && $p->equipo_id    == $eqId;
                    $esVisitante = !$p->visitante_es_rival && $p->visitante_id == $eqId;

                    if ($esLocal) {
                        $s['pj']++; $s['gf'] += $p->goles_equipo; $s['gc'] += $p->goles_rival;
                        if ($p->goles_equipo > $p->goles_rival)      $s['pg']++;
                        elseif ($p->goles_equipo == $p->goles_rival) $s['pe']++;
                        else                                          $s['pp']++;
                    } elseif ($esVisitante) {
                        $s['pj']++; $s['gf'] += $p->goles_rival; $s['gc'] += $p->goles_equipo;
                        if ($p->goles_rival > $p->goles_equipo)      $s['pg']++;
                        elseif ($p->goles_rival == $p->goles_equipo) $s['pe']++;
                        else                                          $s['pp']++;
                    }
                }

                $s['dif'] = $s['gf'] - $s['gc'];
                $s['pts'] = $s['pg'] * 3 + $s['pe'];
                $standingsMap[$eqId] = $s;

                // Estadísticas individuales por equipo
                if (isset($plantillasMap[$eqId])) {
                    $jugadoresStats[$eqId] = $plantillasMap[$eqId]['jugadores'];
                }
            }

            $divisionesData[$division->id] = [
                'standingsMap' => $standingsMap,
                'jugadoresStats' => $jugadoresStats,
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
