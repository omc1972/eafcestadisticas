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
    public function store(Request $request)
    {
        $validated = $this->normalizeVisitantePayload($this->validate($request));

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
            'alineaciones.jugador',
            'eventos.jugador',
            'eventos.tipoEvento',
        ]);
        // Intentar separar alineaciones por plantilla del equipo y del visitante (si existe).
        // Reglas adicionales: si la competición NO es 'fifa' tratamos al visitante como "0" (rival genérico)
        // y la plantilla visitante será únicamente el jugador por defecto (id 168). Los eventos
        // del visitante solo podrán estar asociados a ese jugador por defecto.
        $jugadorExtra = Jugador::find(168);

        // determinar si la competición es FIFA
        $competicion = $partido->competicion ?? Competicion::find($partido->competicion_id);
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
        $jugadorExtra = Jugador::find(168);

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
    public function update(Request $request, Partido $partido)
    {
        $validated = $this->normalizeVisitantePayload($this->validate($request));
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

    private function validate(Request $request) {
        $this->normalizeLocalVisitantePayload($request);

        // NOTE: No realizar merges automáticos entre `visitante_id` y `rival_id`.
        // Los valores vendrán del request y se deben respetar tal cual.

        return $request->validate([
            'partido_como_local' => 'required|boolean',
            'campeonato_id' => 'nullable|exists:campeonatos,id',
            'dificultad' => 'nullable|string|max:255',
            'jornada' => 'nullable|string|max:255',
            'equipo_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->boolean('local_es_rival')) {
                        if (!\App\Models\Rival::where('id', $value)->exists()) {
                            $fail('El local seleccionado no existe en rivales.');
                        }
                        return;
                    }
                    if (!\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail('El equipo local no existe.');
                    }
                },
            ],
            'visitante_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    // permitir null/ausente o 0 (visitante es rival genérico)
                    if (is_null($value) || $value === 0) {
                        return;
                    }

                    if ($request->boolean('visitante_es_rival')) {
                        if (!\App\Models\Rival::where('id', $value)->exists()) {
                            $fail('El visitante seleccionado no existe en rivales.');
                        }
                        return;
                    }

                    if (!\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail('El visitante seleccionado no existe en equipos.');
                    }
                },
            ],
            'visitante_es_rival' => 'required|boolean',
            'local_es_rival' => 'required|boolean',
            'rival_id' => 'nullable|exists:rivals,id',
            'temporada_id' => 'required|exists:temporadas,id',
            'competicion_id' => 'required|exists:competicions,id',
            'minutos_jugados' => 'nullable|integer',
            'puntuacion' => 'nullable|integer',
            'delantera_equipo' => 'nullable|numeric',
            'delantera_rival' => 'nullable|numeric',
            'media_equipo' => 'nullable|numeric',
            'media_rival' => 'nullable|numeric',
            'defensa_equipo' => 'nullable|numeric',
            'defensa_rival' => 'nullable|numeric',
            'posesion_equipo' => 'nullable|numeric',
            'posesion_rival' => 'nullable|numeric',
            'tiros_equipo' => 'nullable|integer',
            'tiros_rival' => 'nullable|integer',
            'tiros_a_puerta_equipo' => 'nullable|integer',
            'tiros_a_puerta_rival' => 'nullable|integer',
            'pases_equipo' => 'nullable|integer',
            'pases_rival' => 'nullable|integer',
            'porcentaje_pases_equipo' => 'nullable|numeric',
            'porcentaje_pases_rival' => 'nullable|numeric',
            'goles_equipo' => 'nullable|integer',
            'goles_rival' => 'nullable|integer',
            'valor_equipo' => 'nullable|integer',
            'valor_rival' => 'nullable|integer',
            'entradas_equipo' => 'nullable|integer',
            'entradas_rival' => 'nullable|integer',
            'entradas_equipo_completadas' => 'nullable|integer',
            'entradas_rival_completadas' => 'nullable|integer',
            'distancia_equipo' => 'nullable|numeric',
            'distancia_rival' => 'nullable|numeric',
            'faltas_equipo' => 'nullable|integer',
            'faltas_rival' => 'nullable|integer',
            'penalties_equipo' => 'nullable|integer',
            'penalties_rival' => 'nullable|integer',
            'tarjetas_amarillas_equipo' => 'nullable|integer',
            'tarjetas_amarillas_rival' => 'nullable|integer',
            'tarjetas_rojas_equipo' => 'nullable|integer',
            'tarjetas_rojas_rival' => 'nullable|integer',
            'corners_equipo' => 'nullable|integer',
            'corners_rival' => 'nullable|integer',
            'interceciones_equipo' => 'nullable|integer',
            'intercepciones_rival' => 'nullable|integer',
            'balones_ganados_equipo' => 'nullable|integer',
            'balones_ganados_rival' => 'nullable|integer',
            'balones_perdidos_equipo' => 'nullable|integer',
            'balones_perdidos_rival' => 'nullable|integer',
            'alineaciones' => 'nullable|array',
            'alineaciones_rival' => 'nullable|array',
            'alineaciones.*.jugador_id' => 'required|exists:jugadors,id',
            'alineaciones.*.equipo_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute.' debe ser null, 0 o un id de equipo válido.');
                    }
                }
            ],
            'alineaciones.*.rival_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Rival::where('id', $value)->exists()) {
                        $fail($attribute.' debe ser null, 0 o un id de rival válido.');
                    }
                }
            ],
            'alineaciones.*.tiros' => 'nullable|integer',
            'alineaciones.*.tiros_a_puerta' => 'nullable|integer',
            'alineaciones.*.tiros_al_palo' => 'nullable|integer',
            'alineaciones.*.pases' => 'nullable|integer',
            'alineaciones.*.pases_exitosos' => 'nullable|integer',
            'alineaciones.*.entradas' => 'nullable|integer',
            'alineaciones.*.entradas_exitosas' => 'nullable|integer',
            'alineaciones.*.regates' => 'nullable|integer',
            'alineaciones.*.regates_exitosos' => 'nullable|integer',
            'alineaciones.*.posesion_ganada' => 'nullable|integer',
            'alineaciones.*.posesion_perdida' => 'nullable|integer',
            'alineaciones.*.fueras_de_juego' => 'nullable|integer',
            'alineaciones.*.faltas_cometidas' => 'nullable|integer',
            'alineaciones.*.faltas_recibidas' => 'nullable|integer',
            'alineaciones.*.posesion' => 'nullable|numeric',
            'alineaciones.*.distancia_recorrida' => 'nullable|numeric',
            'alineaciones.*.rendimiento' => 'nullable|numeric',
            'alineaciones.*.jugador_del_partido' => 'nullable|boolean',
            'eventos' => 'nullable|array',
            'eventos.*.jugador_id' => 'required|exists:jugadors,id',
            'eventos.*.local_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute.' debe ser null, 0 o un id de equipo local válido.');
                    }
                }
            ],
            'eventos.*.visitante_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (is_null($value) || $value === 0) {
                        return;
                    }

                    if ($request->boolean('visitante_es_rival')) {
                        if (!\App\Models\Rival::where('id', $value)->exists()) {
                            $fail($attribute.' debe ser un id de rival válido.');
                        }
                        return;
                    }

                    if (!\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute.' debe ser un id de equipo visitante válido.');
                    }
                }
            ],
            'eventos.*.equipo_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute.' debe ser null, 0 o un id de equipo válido.');
                    }
                }
            ],
            'eventos.*.rival_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Rival::where('id', $value)->exists()) {
                        $fail($attribute.' debe ser null, 0 o un id de rival válido.');
                    }
                }
            ],
            'eventos.*.minuto' => 'required|integer',
            'eventos.*.tipo_evento_id' => 'required|exists:tipo_eventos,id',
        ]);
    }

    private function normalizeVisitantePayload(array $validated): array
    {
        // No derivar ni sobrescribir `rival_id` automáticamente; devolver payload tal cual.
        return $validated;
    }

    private function normalizeLocalVisitantePayload(Request $request): void
    {
        $aliases = [
            'local_id' => 'equipo_id',
            'posesion_local' => 'posesion_equipo',
            'posesion_visitante' => 'posesion_rival',
            'delantera_local' => 'delantera_equipo',
            'delantera_visitante' => 'delantera_rival',
            'media_local' => 'media_equipo',
            'media_visitante' => 'media_rival',
            'defensa_local' => 'defensa_equipo',
            'defensa_visitante' => 'defensa_rival',
            'tiros_local' => 'tiros_equipo',
            'tiros_visitante' => 'tiros_rival',
            'tiros_a_puerta_local' => 'tiros_a_puerta_equipo',
            'tiros_a_puerta_visitante' => 'tiros_a_puerta_rival',
            'pases_local' => 'pases_equipo',
            'pases_visitante' => 'pases_rival',
            'porcentaje_pases_local' => 'porcentaje_pases_equipo',
            'porcentaje_pases_visitante' => 'porcentaje_pases_rival',
            'goles_local' => 'goles_equipo',
            'goles_visitante' => 'goles_rival',
            'valor_local' => 'valor_equipo',
            'valor_visitante' => 'valor_rival',
            'entradas_local' => 'entradas_equipo',
            'entradas_visitante' => 'entradas_rival',
            'entradas_local_completadas' => 'entradas_equipo_completadas',
            'entradas_visitante_completadas' => 'entradas_rival_completadas',
            'distancia_local' => 'distancia_equipo',
            'distancia_visitante' => 'distancia_rival',
            'faltas_local' => 'faltas_equipo',
            'faltas_visitante' => 'faltas_rival',
            'penalties_local' => 'penalties_equipo',
            'penalties_visitante' => 'penalties_rival',
            'tarjetas_amarillas_local' => 'tarjetas_amarillas_equipo',
            'tarjetas_amarillas_visitante' => 'tarjetas_amarillas_rival',
            'tarjetas_rojas_local' => 'tarjetas_rojas_equipo',
            'tarjetas_rojas_visitante' => 'tarjetas_rojas_rival',
            'corners_local' => 'corners_equipo',
            'corners_visitante' => 'corners_rival',
            'intercepciones_local' => 'interceciones_equipo',
            'intercepciones_visitante' => 'intercepciones_rival',
            'balones_ganados_local' => 'balones_ganados_equipo',
            'balones_ganados_visitante' => 'balones_ganados_rival',
            'balones_perdidos_local' => 'balones_perdidos_equipo',
            'balones_perdidos_visitante' => 'balones_perdidos_rival',
        ];

        $merge = [];
        foreach ($aliases as $newKey => $legacyKey) {
            if ($request->filled($newKey) && !$request->filled($legacyKey)) {
                $merge[$legacyKey] = $request->input($newKey);
            }
        }

        if (!empty($merge)) {
            $request->merge($merge);
        }

        $eventos = $request->input('eventos', []);
        if (is_array($eventos)) {
            $eventosNormalizados = array_map(function ($evento) {
                if (!is_array($evento)) {
                    return $evento;
                }

                if (array_key_exists('local_id', $evento) && !array_key_exists('equipo_id', $evento)) {
                    $evento['equipo_id'] = $evento['local_id'];
                }

                return $evento;
            }, $eventos);

            $request->merge(['eventos' => $eventosNormalizados]);
        }

        // Normalizar alineaciones (locales y rival) para aceptar local_id/visitante_id y mapear a equipo_id/rival_id
        $alineaciones = $request->input('alineaciones', []);
        if (is_array($alineaciones)) {
            $alineacionesNormalizadas = array_map(function ($a) {
                if (!is_array($a)) {
                    return $a;
                }

                if (array_key_exists('local_id', $a) && !array_key_exists('equipo_id', $a)) {
                    $a['equipo_id'] = $a['local_id'];
                }

                return $a;
            }, $alineaciones);

            $request->merge(['alineaciones' => $alineacionesNormalizadas]);
        }

        $alineacionesRival = $request->input('alineaciones_rival', []);
        if (is_array($alineacionesRival)) {
            $alineacionesRivalNormalizadas = array_map(function ($a) {
                if (!is_array($a)) {
                    return $a;
                }

                if (array_key_exists('local_id', $a) && !array_key_exists('equipo_id', $a)) {
                    $a['equipo_id'] = $a['local_id'];
                }

                return $a;
            }, $alineacionesRival);

            $request->merge(['alineaciones_rival' => $alineacionesRivalNormalizadas]);
        }
    }
}
