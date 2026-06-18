<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;
use App\Models\Equipo;
use App\Models\Temporada;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Campeonato;
use App\Models\Partido;
use App\Models\Rival;

class PlantillaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plantillas = Plantilla::with(['equipo', 'temporada', 'jugadores', 'campeonato', 'liga'])->get();

        $plantillasData = $plantillas->map(function ($plantilla) {
            $titulares = $plantilla->jugadores->filter(fn($j) => $j->pivot->es_titular);
            $mediaPromedio = $plantilla->jugadores->count() > 0
                ? round($plantilla->jugadores->avg('media'), 1)
                : null;
            $mediaTitulares = $titulares->count() > 0
                ? round($titulares->avg('media'), 1)
                : null;
            return [
                'id' => $plantilla->id,
                'equipo' => [
                    'id' => $plantilla->equipo->id,
                    'nombre' => $plantilla->equipo->nombre,
                    'escudo' => $plantilla->equipo->escudo,
                ],
                'temporada' => ['nombre' => $plantilla->temporada->nombre],
                'liga' => $plantilla->liga ? ['nombre' => $plantilla->liga->nombre] : null,
                'campeonato' => $plantilla->campeonato ? ['nombre' => $plantilla->campeonato->nombre] : null,
                'num_jugadores' => $plantilla->jugadores->count(),
                'media_promedio' => $mediaPromedio,
                'media_titulares' => $mediaTitulares,
                'titulares' => $titulares->map(fn($j) => [
                    'nombre' => $j->nombre,
                    'media'  => $j->media,
                ])->values(),
            ];
        });

        return Inertia::render('Plantillas/Index', [
            'plantillas' => $plantillasData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Plantillas/Create', [
            'equipos' => Equipo::all(),
            'temporadas' => Temporada::all(),
            'campeonatos' => Campeonato::all(),
            'jugadores' => Jugador::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'temporada_id' => 'required|exists:temporadas,id',
            'jugadores' => 'nullable|array',
            'jugadores.*.jugador_id' => 'exists:jugadors,id',
            'jugadores.*.dorsal' => 'required|string',
            'jugadores.*.es_titular' => 'boolean',
        ]);

        $plantilla = Plantilla::create([
            'equipo_id' => $validated['equipo_id'],
            'temporada_id' => $validated['temporada_id'],
            'campeonato_id' => $request->input('campeonato_id'),
        ]);

        if (!empty($validated['jugadores'])) {
            $titulares = 0;
            $syncData = [];
            foreach ($validated['jugadores'] as $j) {
                $esTitular = !empty($j['es_titular']) && $titulares < 11;
                if ($esTitular) $titulares++;
                $syncData[$j['jugador_id']] = [
                    'dorsal' => $j['dorsal'],
                    'es_titular' => $esTitular,
                ];
            }
            $plantilla->jugadores()->sync($syncData);
        }

        return redirect()->route('plantillas.index')
            ->with('success', 'Plantilla creada con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plantilla $plantilla)
    {
        $plantilla->load(['temporada', 'equipo', 'campeonato', 'jugadores']);

        $partidoQuery = Partido::where('temporada_id', $plantilla->temporada_id)
            ->where('equipo_id', $plantilla->equipo_id);
        if (!empty($plantilla->campeonato_id)) {
            $partidoQuery->where('campeonato_id', $plantilla->campeonato_id);
        }
        $partidoIds = $partidoQuery->pluck('id');

        // Batch-load alineaciones + events for all jugadores in one round of queries
        $plantilla->jugadores->load([
            'alineaciones' => fn($q) => $q
                ->whereIn('partido_id', $partidoIds)
                ->with(['partido', 'partido.eventos.tipoEvento']),
            'estilos',
        ]);

        $valor = 0;
        foreach ($plantilla->jugadores as $jugador) {
        
            $resumen = [
                'goles'=> 0,
                'asistencias' => 0,
                'ta_p'=> 0,
                'tr_p' => 0,
                'ta_r'=> 0,
                'tr_r' => 0,
                'minutos'=>0,
                'penalties_provocados'=>0,
                'penalties_realizados'=>0,
                'penalties_marcados'=>0,
                'penalties_fallados'=>0,
                'penalties_parados'=>0,
                'tiros'=>0,
                'tiros_a_puerta'=>0,
                'tiros_al_palo'=>0,
                'pases'=>0,
                'pases_exitosos'=>0,        
                'entradas'=>0,
                'entradas_exitosas'=>0,        
                'regates'=>0,
                'regates_exitosos'=>0,       
                'posesion_ganada'=>0,
                'posesion_perdida'=>0,
                'fueras_de_juego'=>0,
                'faltas_cometidas'=>0,
                'posesion'=>0,
                'faltas_recibidas'=>0,
                'distancia_recorrida'=>0,
                'rendimiento'=>0,
                'partidos'=>0,
                'jugador_del_partido'=>0
            ];
            $valor +=$jugador->media;
            foreach($jugador->alineaciones as $alineacion){
                $esPortero = ($jugador->posicion ?? null) === 'POR';
                  
                $resumen['tiros'] += $alineacion->tiros ?? 0;
                if (!$esPortero) {
                    $resumen['tiros_a_puerta'] += $alineacion->tiros_a_puerta ?? 0;
                    $resumen['tiros_al_palo'] += $alineacion->tiros_al_palo ?? 0;
                }
                $resumen['pases'] += $alineacion->pases ?? 0;
                $resumen['pases_exitosos'] += $alineacion->pases_exitosos ?? 0;
                $resumen['entradas'] += $alineacion->entradas ?? 0;
                $resumen['entradas_exitosas'] += $alineacion->entradas_exitosas ?? 0;
                $resumen['regates'] += $alineacion->regates ?? 0;
                $resumen['regates_exitosos'] += $alineacion->regates_exitosos ?? 0;
                $resumen['posesion_ganada'] += $alineacion->posesion_ganada ?? 0;
                $resumen['posesion_perdida'] += $alineacion->posesion_perdida ?? 0;
                $resumen['fueras_de_juego'] += $alineacion->fueras_de_juego ?? 0;
                $resumen['faltas_cometidas'] += $alineacion->faltas_cometidas ?? 0;
                $resumen['posesion'] += $alineacion->posesion ?? 0;
                $resumen['faltas_recibidas'] += $alineacion->faltas_recibidas ?? 0;
                $resumen['distancia_recorrida'] += $alineacion->distancia_recorrida ?? 0;
                $resumen['rendimiento'] += $alineacion->rendimiento ?? 0;
                $resumen['partidos'] ++;
                $resumen['jugador_del_partido'] += $alineacion->jugador_del_partido ?? 0;
                $entra=0;
                $sale=$alineacion->partido->minutos_jugados;
                foreach($alineacion->partido->eventos->where('jugador_id', $jugador->id) as $evento){
                    switch($evento->tipoEvento->nombre){
                        case 'Gol':
                            $resumen['goles']++;
                            break;
                        case 'Asistencia':
                            $resumen['asistencias']++;
                            break;
                        case 'Penalty Provocado':
                            $resumen['penalties_provocados']++;
                            break;
                        case 'Penalty Realizado':
                            $resumen['penalties_realizados']++;
                            break;
                        case 'TA Provocada':
                            $resumen['ta_p']++;
                            break;
                        case 'TR Provocada':
                            $resumen['tr_p']++;
                            break;
                        case 'TA Realizada':
                            $resumen['ta_r']++;
                            break;
                        case 'TR Realizada':
                            $resumen['tr_r']++;
                            $sale=$evento->minuto;
                            break;
                        case 'Penalti marcado':
                            $resumen['penalties_marcados']++;
                            break;
                        case 'Penalti Fallado':
                            $resumen['penalties_fallados']++;
                            break;
                        case 'Penalti Parado':
                            $resumen['penalties_parados']++;
                            break;
                        case 'Entra':
                            $entra=$evento->minuto;
                            break;
                        case 'Sale':
                            $sale=$evento->minuto;
                            break;
                    }
                }
                $resumen['minutos'] += ($sale-$entra);
            }
            $jugador->resumen = $resumen;
        }
        $plantilla->media = $valor / max(count($plantilla->jugadores), 1);

        return Inertia::render('Plantillas/View', [
            'plantilla' => $plantilla,
        ]);
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plantilla $plantilla)
    {      
        $jugadoresPlantilla = $plantilla->jugadores()->get()->map(function ($j) {
            return [
                'jugador_id' => $j->id,
                'dorsal' => $j->pivot->dorsal,
                'es_titular' => (bool) $j->pivot->es_titular,
            ];
        });

         return Inertia::render('Plantillas/Edit', [
            'plantilla' => $plantilla->load('jugadores'),
            'equipos' => Equipo::all(),
            'temporadas' => Temporada::all(),
            'campeonatos' => Campeonato::all(),
            'jugadores' => Jugador::all(),
            'jugadoresPlantilla' => $jugadoresPlantilla,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plantilla $plantilla)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'temporada_id' => 'required|exists:temporadas,id',
            'jugadores' => 'nullable|array',
            'jugadores.*.jugador_id' => 'exists:jugadors,id',
            'jugadores.*.dorsal' => 'required|string',
            'jugadores.*.es_titular' => 'boolean',
        ]);

        $plantilla->update([
            'equipo_id' => $validated['equipo_id'],
            'temporada_id' => $validated['temporada_id'],
            'campeonato_id' => $request->input('campeonato_id'),
        ]);

        if (!empty($validated['jugadores'])) {
            $titulares = 0;
            $syncData = [];
            foreach ($validated['jugadores'] as $j) {
                $esTitular = !empty($j['es_titular']) && $titulares < 11;
                if ($esTitular) $titulares++;
                $syncData[$j['jugador_id']] = [
                    'dorsal' => $j['dorsal'],
                    'es_titular' => $esTitular,
                ];
            }
            $plantilla->jugadores()->sync($syncData);
        } else {
            $plantilla->jugadores()->sync([]);
        }

        return redirect()->route('plantillas.index')
            ->with('success', 'Plantilla actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plantilla $plantilla)
    {
         $plantilla->delete();
        return redirect()->route('plantillas.index')
            ->with('success', 'Plantilla eliminada con éxito');
    }

    public function getPlantillas(Request $request)
    {
        $temporadaId = $request->query('temporada_id');
        $equipoId = $request->query('equipo_id');
        $visitanteIdRaw = $request->query('visitante_id');
        $localEsRival = $request->boolean('local_es_rival');

        // visitante_id convention: null => no visitante; 0 => visitante es rival (usar jugador genérico);
        // >0 => id de equipo visitante
        $visitanteId = null;
        if ($visitanteIdRaw !== null && $visitanteIdRaw !== '') {
            $visitanteId = intval($visitanteIdRaw);
        }

        $jugadorExtra = Jugador::find(168);

        // inicializamos variables que pueden no definirse en ramas condicionales
        $plantillaRival = null;
        $rivalObj = null;

        // Plantilla del equipo (si local no es rival y existe plantilla)
        $plantillaEquipo = Plantilla::with('jugadores')
            ->where('temporada_id', $temporadaId)
            ->where('equipo_id', $equipoId)
            ->first();

        $plantillaEquipoJugadores = [];
        if ($plantillaEquipo) {
            $plantillaEquipoJugadores = $plantillaEquipo->jugadores->map(function($j){
                return [
                    'id' => $j->id,
                    'nombre' => $j->nombre,
                    'dorsal' => $j->pivot->dorsal,
                    'es_titular' => (bool) $j->pivot->es_titular,
                ];
            })->toArray();
        }

        // Plantilla Rival: si llega un visitante_id preferimos cargar la plantilla de ese equipo
        // para la temporada. Si no hay visitante_id y visitante_es_rival es true devolvemos
        // solo el jugador genérico. Si no hay visitante_id y visitante_es_rival es false
        // no devolvemos jugadores.
        $plantillaRivalJugadores = [];
        if ($visitanteId !== null) {
            if ($visitanteId === 0) {
                // visitante es rival -> devolver jugador genérico
                if ($jugadorExtra) {
                    $plantillaRivalJugadores[] = [ 'id' => $jugadorExtra->id, 'nombre' => $jugadorExtra->nombre ];
                }
            } elseif ($visitanteId > 0) {
                // Intentamos cargar plantilla usando visitanteId como equipo_id
                $plantillaRival = Plantilla::with('jugadores')
                    ->where('temporada_id', $temporadaId)
                    ->where('equipo_id', $visitanteId)
                    ->first();

                // Si no hay plantilla para ese equipo, quizá se pasó un id de `Rival`.
                if (!$plantillaRival) {
                    $rivalObj = Rival::find($visitanteId);
                    if ($rivalObj && !empty($rivalObj->equipo_id)) {
                        $plantillaRival = Plantilla::with('jugadores')
                            ->where('temporada_id', $temporadaId)
                            ->where('equipo_id', $rivalObj->equipo_id)
                            ->first();
                    }
                }

                if ($plantillaRival) {
                    $plantillaRivalJugadores = $plantillaRival->jugadores->map(function($j){
                        return [
                            'id' => $j->id,
                            'nombre' => $j->nombre,
                            'dorsal' => $j->pivot->dorsal,
                            'es_titular' => (bool) $j->pivot->es_titular,
                        ];
                    })->toArray();
                }
            }
        }

        // En caso de que no haya plantilla para el equipo local pero exista jugador genérico añadimos al final
        if (empty($plantillaEquipoJugadores) && $jugadorExtra && !$localEsRival) {
            $plantillaEquipoJugadores[] = [ 'id' => $jugadorExtra->id, 'nombre' => $jugadorExtra->nombre ];
        }

        return response()->json([
            'plantillaEquipo' => $plantillaEquipoJugadores,
            'plantillaRival' => $plantillaRivalJugadores,
            // visitante_equipo_id: cuando la plantilla rival corresponde a un equipo
            'visitante_equipo_id' => $plantillaRival ? $plantillaRival->equipo_id : (isset($rivalObj) && !empty($rivalObj->equipo_id) ? $rivalObj->equipo_id : null),
        ]);
    }
}
