<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Competicion;
use App\Models\Jugador;
use App\Models\Rival;
use App\Models\TipoEvento;
use App\Models\Plantilla;
use App\Models\Campeonato;
use App\Http\Requests\PartidoRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partidos = Partido::with(['temporada', 'competicion', 'equipo', 'localRival', 'rival', 'visitanteRival', 'visitanteEquipo', 'campeonato'])->get();

        return Inertia::render('Partidos/Index', [
            'partidos' => $partidos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Inicialmente solo enviamos temporadas y competiciones.
        return Inertia::render('Partidos/Create', [
            'temporadas' => Temporada::all(),
            'competiciones' => Competicion::all(),
        ]);
    }

    /**
     * Endpoint AJAX para obtener opciones según la competición seleccionada.
     */
    public function options(Request $request)
    {
        $competicion = null;
        if ($request->filled('competicion_id')) {
            $competicion = Competicion::find($request->input('competicion_id'));
        }

        // Si es FIFA mantenemos el comportamiento anterior (más datos)
        $isFifa = $competicion && strtolower($competicion->nombre) === 'fifa';

        $data = [
            'isFifa' => $isFifa,
            'equipos' => Equipo::all(),
            'rivales' => Rival::all(),
        ];

        // Siempre devolvemos tipos de evento para poblar selects de eventos en frontend
        $data['tiposEventos'] = TipoEvento::all();

        if ($isFifa) {
            $data['campeonatos'] = Campeonato::all();
            $data['jugadores'] = Jugador::all();
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartidoRequest $request)
    {
        $validated = $request->validated();

        $partido = Partido::create($validated);

        // Merge local and visitante alineaciones if both exist in the payload
        $alineacionesToSave = [];
        if (!empty($validated['alineaciones']) && is_array($validated['alineaciones'])) {
            $alineacionesToSave = array_merge($alineacionesToSave, $validated['alineaciones']);
        }
        if (!empty($validated['alineaciones_rival']) && is_array($validated['alineaciones_rival'])) {
            $alineacionesToSave = array_merge($alineacionesToSave, $validated['alineaciones_rival']);
        }

        if (!empty($alineacionesToSave)) {
            foreach ($alineacionesToSave as $alineacion) {
                $partido->alineaciones()->create($alineacion);
            }
        }

        if (!empty($validated['eventos'])) {
            foreach ($validated['eventos'] as $evento) {
                $partido->eventos()->create($evento);
            }
        }
        return redirect()->route('partidos.index')
            ->with('success', 'Partido creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partido $partido)
    {
        $partido->load([
            'equipo',
            'localRival',
            'rival',
            'visitanteRival',
            'visitanteEquipo',
            'competicion',
            'alineaciones.jugador',
            'eventos.jugador',
            'eventos.tipoEvento',
        ]);
        $jugadorExtra = Jugador::find(config('app.jugador_extra_id'));

        // determinar si la competición es FIFA
        $competicion = $partido->competicion;
        $isFifa = $competicion && strtolower($competicion->nombre) === 'fifa';

        $plantillaEquipoPlayers = collect([]);
        if ($partido->equipo_id === 0) {
            if ($jugadorExtra) $plantillaEquipoPlayers->push($jugadorExtra->id);
        } else {
            $plantillaEquipo = Plantilla::with('jugadores')
                ->where('temporada_id', $partido->temporada_id)
                ->where('equipo_id', $partido->equipo_id)
                ->first();
            if ($plantillaEquipo) {
                foreach ($plantillaEquipo->jugadores as $pj) {
                    $plantillaEquipoPlayers->push($pj->id);
                }
            }
        }

        // Si NO es FIFA, forzamos visitante_id a 0 para efectos de renderizado y la plantilla visitante
        // será únicamente el jugador por defecto.
        $visitanteEquipoId = $partido->visitante_id;
        if (!$isFifa) {
            $visitanteEquipoId = 0;
            // no persistimos el cambio, solo lo usamos para clasificar en la vista
            $partido->visitante_id = 0;
        }

        $plantillaVisitantePlayers = collect([]);
        if ($visitanteEquipoId === 0) {
            if ($jugadorExtra) $plantillaVisitantePlayers->push($jugadorExtra->id);
        } else {
            // if visitante is a rival with equipo mapping, try to find plantilla by that equipo
            $tryEquipoId = $visitanteEquipoId;
            if ($partido->visitante_es_rival && $visitanteEquipoId) {
                $r = $partido->visitanteRival;
                $tryEquipoId = $r ? ($r->equipo_id ?? $visitanteEquipoId) : $visitanteEquipoId;
            }

            if ($tryEquipoId) {
                $plantillaVisit = Plantilla::with('jugadores')
                    ->where('temporada_id', $partido->temporada_id)
                    ->where('equipo_id', $tryEquipoId)
                    ->first();
                if ($plantillaVisit) {
                    foreach ($plantillaVisit->jugadores as $pj) {
                        $plantillaVisitantePlayers->push($pj->id);
                    }
                }
            }
        }

        $alineaciones_locales = collect([]);
        $alineaciones_visitantes = collect([]);

        foreach ($partido->alineaciones as $a) {
            $alineacionEquipoId = $a->equipo_id ?? null;

            // Clasificación estricta por `equipo_id` respecto a los ids del partido
            if (!is_null($alineacionEquipoId)) {
                if ($alineacionEquipoId == $partido->equipo_id) {
                    $alineaciones_locales->push($a);
                    continue;
                }

                if ($alineacionEquipoId == $partido->visitante_id) {
                    $alineaciones_visitantes->push($a);
                    continue;
                }

                // también aceptar la coincidencia con el equipo real del visitante (rival->equipo_id)
                if (!is_null($visitanteEquipoId) && $alineacionEquipoId == $visitanteEquipoId) {
                    $alineaciones_visitantes->push($a);
                    continue;
                }
            }

            // Fallback por pertenencia a plantillas por jugador
            $jid = $a->jugador_id;
            if ($plantillaEquipoPlayers->contains($jid)) {
                $alineaciones_locales->push($a);
            } elseif ($plantillaVisitantePlayers->contains($jid)) {
                $alineaciones_visitantes->push($a);
            } else {
                // por defecto considerar local
                $alineaciones_locales->push($a);
            }
        }

        return Inertia::render('Partidos/View', [
            'partido' => $partido,
            'alineaciones_locales' => $alineaciones_locales->values(),
            'alineaciones_visitantes' => $alineaciones_visitantes->values(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partido $partido)
    {
        $partido->load(['alineaciones.jugador', 'eventos.jugador', 'eventos.tipoEvento']);
        $plantilla = Plantilla::with('jugadores')
            ->where('temporada_id', $partido->temporada_id)
            ->where('equipo_id', $partido->equipo_id)
            ->first();
        $jugadorExtra = Jugador::find(config('app.jugador_extra_id'));

        if ($plantilla && $jugadorExtra) {
            $plantilla->jugadores->push($jugadorExtra);
        }
        
        return Inertia::render('Partidos/Edit', [
            'partido' => $partido,
            'equipos' => Equipo::All(),
            'rivales' => Rival::All(),
            'temporadas' => Temporada::all(),
            'competiciones' => Competicion::all(),
            'campeonatos' => Campeonato::all(),
            'jugadores' => $plantilla->jugadores,
            'tiposEventos' => TipoEvento::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartidoRequest $request, Partido $partido)
    {
        $validated = $request->validated();
        $partido->update($validated);
        
        $partido->eventos()->delete();
        if (!empty($validated['eventos'])) {
            foreach ($validated['eventos'] as $evento) {
                $partido->eventos()->create($evento);
            }
        }

        $partido->alineaciones()->delete();
        // Merge both posible arrays and save
        $alineacionesToSave = [];
        if (!empty($validated['alineaciones']) && is_array($validated['alineaciones'])) {
            $alineacionesToSave = array_merge($alineacionesToSave, $validated['alineaciones']);
        }
        if (!empty($validated['alineaciones_rival']) && is_array($validated['alineaciones_rival'])) {
            $alineacionesToSave = array_merge($alineacionesToSave, $validated['alineaciones_rival']);
        }

        if (!empty($alineacionesToSave)) {
            foreach ($alineacionesToSave as $alineacion) {
                $partido->alineaciones()->create($alineacion);
            }
        }
        
        return redirect()->route('partidos.index')
            ->with('success', 'Partido actualizado con éxito');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partido $partido)
    {
        $partido->alineaciones()->delete();
        $partido->eventos()->delete();
        $partido->delete();

        return redirect()->route('partidos.index')
            ->with('success', 'Partido eliminado con éxito');
    }
}
