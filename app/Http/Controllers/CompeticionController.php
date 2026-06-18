<?php

namespace App\Http\Controllers;

use App\Models\Competicion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompeticionController extends Controller
{
    public function index()
    {
        $competiciones = Competicion::all();
        return Inertia::render('Competiciones/Index', [
            'competiciones' => $competiciones
        ]);
    }

    public function show(Competicion $competicione, \Illuminate\Http\Request $request)
    {
        $competicion = $competicione;

        // Pasar sólo las temporadas existentes (sin relacionarlas con la competición)
        $temporadas = \App\Models\Temporada::all();

        $resumenPorTemporada = [];
        foreach ($temporadas as $temporada) {
            // Obtener partidos jugados (con resultado) para esta competición y temporada
            $matches = \App\Models\Partido::query()
                ->where('competicion_id', $competicion->id)
                ->where('temporada_id', $temporada->id)
                ->whereNotNull('goles_equipo')
                ->whereNotNull('goles_rival')
                ->get();

            if ($matches->isEmpty()) {
                $resumenPorTemporada[] = [
                    'temporada_id' => $temporada->id,
                    'temporada_nombre' => $temporada->nombre,
                    'sumatorio' => [
                        'partidos_jugados' => 0,
                        'ganados' => 0,
                        'empatados' => 0,
                        'perdidos' => 0,
                        'goles_equipo' => 0,
                        'goles_visitante' => 0,
                        'diferencia' => 0,
                    ],
                ];
                continue;
            }

            // Construir resumen por equipo y lista de partidos
            $teams = [];
            $matches_list = [];
            foreach ($matches as $m) {
                $home = $m->equipo_id;
                $away = $m->visitante_id;
                $g1 = $m->goles_equipo;
                $g2 = $m->goles_rival;

                // registrar equipos
                if ($home && $home > 0 && !isset($teams[$home])) {
                    $eq = \App\Models\Equipo::find($home);
                    $teams[$home] = ['id' => $home, 'nombre' => $eq ? $eq->nombre : "Equipo $home", 'played' => 0, 'wins' => 0, 'draws' => 0, 'losses' => 0, 'gf' => 0, 'ga' => 0];
                }
                if ($away && $away > 0 && !isset($teams[$away])) {
                    $eq = \App\Models\Equipo::find($away);
                    $teams[$away] = ['id' => $away, 'nombre' => $eq ? $eq->nombre : "Equipo $away", 'played' => 0, 'wins' => 0, 'draws' => 0, 'losses' => 0, 'gf' => 0, 'ga' => 0];
                }

                if (!is_null($g1) && !is_null($g2)) {
                    // actualizar jugados/goles
                    if ($home && isset($teams[$home])) {
                        $teams[$home]['played']++;
                        $teams[$home]['gf'] += (int)$g1;
                        $teams[$home]['ga'] += (int)$g2;
                    }
                    if ($away && isset($teams[$away])) {
                        $teams[$away]['played']++;
                        $teams[$away]['gf'] += (int)$g2;
                        $teams[$away]['ga'] += (int)$g1;
                    }

                    if ($g1 > $g2) {
                        if ($home && isset($teams[$home])) $teams[$home]['wins']++;
                        if ($away && isset($teams[$away])) $teams[$away]['losses']++;
                    } elseif ($g2 > $g1) {
                        if ($away && isset($teams[$away])) $teams[$away]['wins']++;
                        if ($home && isset($teams[$home])) $teams[$home]['losses']++;
                    } else {
                        if ($home && isset($teams[$home])) $teams[$home]['draws']++;
                        if ($away && isset($teams[$away])) $teams[$away]['draws']++;
                    }
                }

                $matches_list[] = [
                    'id' => $m->id,
                    'jornada' => $m->jornada,
                    'equipo_id' => $home,
                    'equipo_nombre' => ($home ? (\App\Models\Equipo::find($home)->nombre ?? null) : null),
                    'visitante_id' => $away,
                    'visitante_nombre' => ($away ? (\App\Models\Equipo::find($away)->nombre ?? null) : null),
                    'goles_equipo' => $g1,
                    'goles_rival' => $g2,
                    'fecha' => $m->fecha ?? null,
                ];
            }

            // Calcular sumatorio global para la temporada (considerando 'equipo' como nuestro equipo)
            $sum = [
                'partidos_jugados' => $matches->count(),
                'ganados' => 0,
                'empatados' => 0,
                'perdidos' => 0,
                'goles_equipo' => 0,
                'goles_visitante' => 0,
                'diferencia' => 0,
            ];

            foreach ($matches as $m) {
                $g1 = (int)$m->goles_equipo;
                $g2 = (int)$m->goles_rival;
                $sum['goles_equipo'] += $g1;
                $sum['goles_visitante'] += $g2;
                if ($g1 > $g2) {
                    $sum['ganados']++;
                } elseif ($g1 < $g2) {
                    $sum['perdidos']++;
                } else {
                    $sum['empatados']++;
                }
            }

            $sum['diferencia'] = $sum['goles_equipo'] - $sum['goles_visitante'];

            // preparar array por equipos con gd
            $por_equipos = array_values($teams);
            foreach ($por_equipos as &$it) { $it['gd'] = $it['gf'] - $it['ga']; }

            $resumenPorTemporada[] = [
                'temporada_id' => $temporada->id,
                'temporada_nombre' => $temporada->nombre,
                'sumatorio' => $sum,
                'por_equipos' => $por_equipos,
                'matches' => $matches_list,
            ];
        }

        return Inertia::render('Competiciones/View', [
            'competicion' => $competicion,
            'resumenPorTemporada' => $resumenPorTemporada,
        ]);
    }

    public function create()
    {
        return Inertia::render('Competiciones/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        Competicion::create($validated);
        return redirect()->route('competiciones.index')->with('success', 'Competición creada correctamente');
    }

    public function edit(Competicion $competicione)
    {
        $competicion = $competicione;
        return Inertia::render('Competiciones/Edit', [
            'competicion' => $competicion,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : []
        ]);
    }

    public function update(Request $request, Competicion $competicione)
    {
        $competicion = $competicione;
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        $competicion->update($validated);
        return redirect()->route('competiciones.index')->with('success', 'Competición actualizada correctamente');
    }

    public function destroy(Competicion $competicione)
    {
        $competicion = $competicione;
        $competicion->delete();
        return redirect()->route('competiciones.index')->with('success', 'Competición eliminada correctamente');
    }
}
