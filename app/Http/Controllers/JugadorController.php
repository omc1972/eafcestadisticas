<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Jugador;
use App\Models\Estilo;

class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Jugadores/Index', ['jugadores'=>Jugador::with('estilos')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Jugadores/Create',['estilos'=>Estilo::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->getValidated($request);
        $jugador = Jugador::create($validated);

        if (!empty($validated['estilos'])) {
            $syncData = [];
            foreach ($validated['estilos'] as $c) {
                $syncData[] = $c;
            }
            $jugador->estilos()->sync($syncData);
        }

        return redirect()->route('jugadores.index')->with('success','Jugador creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jugador $jugador)
    {
        $jugador->load([
            'alineaciones' => function ($q) use ($jugador) {
                $q->where('jugador_id', $jugador->id)
                ->with([
                    'partido.equipo',
                    'partido.eventos' => function ($e) use ($jugador) {
                        $e->where('jugador_id', $jugador->id);
                    }
                ]);
            },
            'estilos'
        ]);
        
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

        foreach($jugador->alineaciones as $alineacion){
            $resumen['tiros'] += $alineacion->tiros ?? 0;
            $resumen['tiros_a_puerta'] += $alineacion->tiros_a_puerta ?? 0;
            $resumen['tiros_al_palo'] += $alineacion->tiros_al_palo ?? 0;
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
            foreach($alineacion->partido->eventos as $evento){
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
                        $sale=$evento->sale;
                        break;
                }
            }
            $resumen['minutos'] += ($sale-$entra);
        }

        return Inertia::render('Jugadores/View', [
            'jugador' => $jugador,
            'resumen' => $resumen
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jugador $jugador)
    {

        $jugador->load('estilos'); 

        $estilosJugador = $jugador->estilos->map(function ($estilo) {
            return $estilo->id;
        });

        return Inertia::render('Jugadores/Edit', [
            'jugador' => $jugador,
            'estilos' => Estilo::all(),
            'estiloJugadores' => $estilosJugador,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jugador $jugador)
    {
        $validated = $this->getValidated($request);
        $jugador->update($validated);

        if (!empty($validated['estilos'])) {
            $syncData = [];
            foreach ($validated['estilos'] as $c) {
                $syncData[] = $c;
            }
            $jugador->estilos()->sync($syncData);
        } else {
            $jugador->estilos()->sync([]);
        }

        return redirect()->route('jugadores.index')->with('success','Jugador modificado con éxito');
    }

    private function getValidated(Request $request) {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required|string|max:100',
            'altura' => 'required|numeric|between:100,250',
            'peso' => 'required|numeric|between:40,150',
            'posicion' => 'required|string|max:50',
            'ritmo' => 'nullable|integer',
            'aceleracion' => 'nullable|integer',
            'velocidad' => 'nullable|integer',
            'tiro' => 'nullable|integer',
            'pos_ataque' => 'nullable|integer',
            'finalizacion' => 'nullable|integer',
            'potencia_de_tiro' => 'nullable|integer',
            'tiro_lejano' => 'nullable|integer',
            'voleas' => 'nullable|integer',
            'penalties' => 'nullable|integer',
            'pase' => 'nullable|integer',
            'vision' => 'nullable|integer',
            'centros' => 'nullable|integer',
            'precision_falta' => 'nullable|integer',
            'pase_corto' => 'nullable|integer',
            'pase_largo' => 'nullable|integer',
            'efecto' => 'nullable|integer',
            'regate' => 'nullable|integer',
            'agilidad' => 'nullable|integer',
            'equilibrio' => 'nullable|integer',
            'anticipacion' => 'nullable|integer',
            'control_de_balon' => 'nullable|integer',
            'regates' => 'nullable|integer',
            'compostura' => 'nullable|integer',
            'defensa' => 'nullable|integer',
            'intercepciones' => 'nullable|integer',
            'precision_cabezazo' => 'nullable|integer',
            'capacidad_defensiva' => 'nullable|integer',
            'robos' => 'nullable|integer',
            'entradas' => 'nullable|integer',
            'fisico' => 'nullable|integer',
            'salto' => 'nullable|integer',
            'resistencia' => 'nullable|integer',
            'fuerza' => 'nullable|integer',
            'agresividad' => 'nullable|integer',
            'media' => 'nullable|integer',
            'estilo_quimica' => 'nullable|string|max:64',
            'estilos' => 'nullable|array',
            'estilos.*' => 'integer|exists:estilos,id',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jugador $jugador)
    {
        $jugador->delete();
        return redirect()->route('jugadores.index')->with('success','Jugador eliminado con éxito');
    }
}
