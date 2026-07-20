<?php

namespace App\Http\Controllers;

use App\Models\Rival;
use App\Models\Plantilla;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RivalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rivales = Rival::with('divisiones')->get();

        return Inertia::render('Rivales/Index', [
            'rivales' => $rivales,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Rivales/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'usuario' => 'nullable|string|max:255',
            'quimica' => 'nullable|numeric',
            'valoracion' => 'nullable|numeric',
            'tactica' => 'nullable|string|max:255',
            'fecha' => 'nullable|string',
        ]);

        Rival::create($validated);

        return redirect()->route('rivales.index')->with('success', 'Rival creado con éxito');

    }

    /**
     * Display the specified resource.
     */
    public function show(Rival $rival)
    {
        $rival->load('divisiones.rivales.equipo');

        // Recoger todos los equipo_ids presentes en las divisiones
        $equipoIds = $rival->divisiones
            ->flatMap(fn($d) => $d->rivales->pluck('equipo_id'))
            ->unique()
            ->values();

        // Cargar plantillas de esos equipos
        $plantillasQuery = Plantilla::with(['jugadores' => fn($q) => $q->select('jugadors.id', 'media')])
            ->whereIn('equipo_id', $equipoIds)
            ->orderByDesc('id');

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

        $partidos = $partidosQuery->get();

        $standingsMap = [];
        foreach ($equipoIds as $eqId) {
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
        }

        return Inertia::render('Rivales/Show', [
            'rival'         => $rival,
            'equipos'       => Equipo::orderBy('nombre')->get(['id', 'nombre']),
            'plantillasMap' => $plantillasMap,
            'standingsMap'  => $standingsMap,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rival $rival)
    {
        return Inertia::render('Rivales/Edit', [
            'rival' => $rival,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rival $rival)
    {
         $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'usuario' => 'nullable|string|max:255',
            'quimica' => 'nullable|numeric',
            'valoracion' => 'nullable|numeric',
            'tactica' => 'nullable|string|max:255',
            'fecha' => 'nullable|string',
        ]);

        $rival->update($validated);

        return redirect()->route('rivales.index')->with('success', 'Rival modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rival $equipo)
    {
        $equipo->delete();

        return redirect()->route('rivales.index')->with('success', 'Rival eliminado con éxito');
    }
}

