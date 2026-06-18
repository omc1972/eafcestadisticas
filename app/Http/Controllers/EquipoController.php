<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Entrenador;
use App\Models\Equipacion;
use App\Models\Estadio;
use App\Models\Liga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::with(['entrenador', 'equipacion', 'estadio'])->get();

        return Inertia::render('Equipos/Index', [
            'equipos' => $equipos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Equipos/Create', [
            'entrenadores' => Entrenador::all(),
            'equipaciones' => Equipacion::all(),
            'estadios' => Estadio::all(),
            'ligas' => Liga::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'pais' => 'nullable|string|max:255',
            'liga' => 'nullable|string|max:255',
            'equipacion_id' => 'required|exists:equipacions,id',
            'estadio_id' => 'required|exists:estadios,id',
            'entrenador_id' => 'required|exists:entrenadors,id',
            'liga_id' => 'nullable|exists:ligas,id',
        ]);

        Equipo::create($validated);

        return redirect()->route('equipos.index')->with('success', 'Equipo creado con éxito');

    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {
        $equipo->load(['entrenador', 'equipacion', 'estadio']);

        $partidosStats = DB::table('partidos')
            ->where('equipo_id', $equipo->id)
            ->selectRaw('COUNT(*) as jugados')
            ->selectRaw('SUM(CASE WHEN goles_equipo > goles_rival THEN 1 ELSE 0 END) as ganados')
            ->selectRaw('SUM(CASE WHEN goles_equipo = goles_rival THEN 1 ELSE 0 END) as empatados')
            ->selectRaw('SUM(CASE WHEN goles_equipo < goles_rival THEN 1 ELSE 0 END) as perdidos')
            ->selectRaw('COALESCE(SUM(goles_equipo), 0) as goles_favor')
            ->selectRaw('COALESCE(SUM(goles_rival), 0) as goles_contra')
            ->first();

        $partidosStats->diferencia = ($partidosStats->goles_favor ?? 0) - ($partidosStats->goles_contra ?? 0);

        $partidosStatsPorCompeticion = DB::table('partidos as p')
            ->join('competicions as c', 'c.id', '=', 'p.competicion_id')
            ->where('p.equipo_id', $equipo->id)
            ->groupBy('p.competicion_id', 'c.nombre')
            ->selectRaw('c.nombre as competicion')
            ->selectRaw('COUNT(*) as jugados')
            ->selectRaw('SUM(CASE WHEN goles_equipo > goles_rival THEN 1 ELSE 0 END) as ganados')
            ->selectRaw('SUM(CASE WHEN goles_equipo = goles_rival THEN 1 ELSE 0 END) as empatados')
            ->selectRaw('SUM(CASE WHEN goles_equipo < goles_rival THEN 1 ELSE 0 END) as perdidos')
            ->selectRaw('COALESCE(SUM(goles_equipo), 0) as goles_favor')
            ->selectRaw('COALESCE(SUM(goles_rival), 0) as goles_contra')
            ->get()
            ->map(function ($stat) {
                $stat->diferencia = ($stat->goles_favor ?? 0) - ($stat->goles_contra ?? 0);
                return $stat;
            });

        // Obtener jugadores de la plantilla con sus estadísticas de alineación
        $jugadoresBase = DB::table('jugadors as j')
            ->join('plantilla_jugador as pj', 'pj.jugador_id', '=', 'j.id')
            ->join('plantillas as p', 'p.id', '=', 'pj.plantilla_id')
            ->leftJoin('alineacions as a', 'a.jugador_id', '=', 'j.id')
            ->leftJoin('partidos as pa', function ($join) use ($equipo) {
                $join->on('pa.id', '=', 'a.partido_id')
                    ->where('pa.equipo_id', '=', $equipo->id);
            })
            ->where('p.equipo_id', $equipo->id)
            ->groupBy('j.id', 'j.nombre', 'j.posicion', 'pj.dorsal')
            ->select([
                'j.id',
                'j.nombre',
                'j.posicion',
                'pj.dorsal',
                DB::raw('COUNT(DISTINCT pa.id) as partidos'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.tiros ELSE 0 END), 0) as tiros'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.tiros_a_puerta ELSE 0 END), 0) as tiros_a_puerta'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.pases ELSE 0 END), 0) as pases'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.pases_exitosos ELSE 0 END), 0) as pases_exitosos'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.entradas ELSE 0 END), 0) as entradas'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.entradas_exitosas ELSE 0 END), 0) as entradas_exitosas'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.regates ELSE 0 END), 0) as regates'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.regates_exitosos ELSE 0 END), 0) as regates_exitosos'),
                DB::raw('COALESCE(AVG(CASE WHEN pa.id IS NOT NULL THEN a.rendimiento END), 0) as rendimiento'),
                DB::raw('COALESCE(SUM(CASE WHEN pa.id IS NOT NULL THEN a.jugador_del_partido ELSE 0 END), 0) as mvps'),
            ])
            ->orderBy('j.nombre')
            ->get();

        // Obtener eventos por jugador usando subconsultas separadas
        $eventosStats = DB::table('eventos as e')
            ->join('partidos as pa', 'pa.id', '=', 'e.partido_id')
            ->where('pa.equipo_id', $equipo->id)
            ->whereRaw('COALESCE(e.local_id, e.equipo_id) = ?', [$equipo->id])
            ->groupBy('e.jugador_id')
            ->select([
                'e.jugador_id',
                DB::raw('SUM(CASE WHEN e.tipo_evento_id = 1 THEN 1 ELSE 0 END) as goles'),
                DB::raw('SUM(CASE WHEN e.tipo_evento_id = 10 THEN 1 ELSE 0 END) as asistencias'),
                DB::raw('SUM(CASE WHEN e.tipo_evento_id = 6 THEN 1 ELSE 0 END) as tarjetas_amarillas'),
                DB::raw('SUM(CASE WHEN e.tipo_evento_id = 7 THEN 1 ELSE 0 END) as tarjetas_rojas'),
            ])
            ->get()
            ->keyBy('jugador_id');

        // Calcular minutos jugados para cada jugador (una sola consulta en lugar de N)
        $alineaciones = DB::table('alineacions as a')
            ->join('partidos as pa', 'pa.id', '=', 'a.partido_id')
            ->where('pa.equipo_id', $equipo->id)
            ->select('a.jugador_id', 'a.partido_id', 'pa.minutos_jugados')
            ->get();

        $partidoIds = $alineaciones->pluck('partido_id')->unique()->values();

        $eventosCambios = DB::table('eventos as e')
            ->join('tipo_eventos as te', 'te.id', '=', 'e.tipo_evento_id')
            ->whereIn('e.partido_id', $partidoIds)
            ->whereIn('te.nombre', ['Entra', 'Sale', 'TR Realizada'])
            ->select('e.jugador_id', 'e.partido_id', 'te.nombre', 'e.minuto')
            ->get()
            ->groupBy(fn($e) => $e->jugador_id . '_' . $e->partido_id);

        $minutosStats = [];
        foreach ($alineaciones as $alineacion) {
            $key = $alineacion->jugador_id . '_' . $alineacion->partido_id;
            $eventos = $eventosCambios->get($key, collect());

            $entra = $eventos->firstWhere('nombre', 'Entra')?->minuto;
            $sale  = $eventos->first(fn($e) => in_array($e->nombre, ['Sale', 'TR Realizada']))?->minuto;

            if ($entra === null && $sale === null) {
                $minutosJugados = $alineacion->minutos_jugados;
            } elseif ($entra === null) {
                $minutosJugados = $sale;
            } elseif ($sale === null) {
                $minutosJugados = $alineacion->minutos_jugados - $entra;
            } else {
                $minutosJugados = $sale - $entra;
            }

            $minutosStats[$alineacion->jugador_id] = ($minutosStats[$alineacion->jugador_id] ?? 0) + $minutosJugados;
        }

        // Combinar todas las colecciones
        $jugadores = $jugadoresBase->map(function ($jugador) use ($eventosStats, $minutosStats) {
            $eventos = $eventosStats->get($jugador->id);
            $jugador->goles = $eventos->goles ?? 0;
            $jugador->asistencias = $eventos->asistencias ?? 0;
            $jugador->tarjetas_amarillas = $eventos->tarjetas_amarillas ?? 0;
            $jugador->tarjetas_rojas = $eventos->tarjetas_rojas ?? 0;
            $jugador->minutos_jugados = $minutosStats[$jugador->id] ?? 0;
            return $jugador;
        });

        return Inertia::render('Equipos/View', [
            'equipo' => $equipo,
            'partidosStats' => $partidosStats,
            'partidosStatsPorCompeticion' => $partidosStatsPorCompeticion,
            'jugadores' => $jugadores,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        return Inertia::render('Equipos/Edit', [
            'equipo' => $equipo,
            'entrenadores' => Entrenador::all(),
            'equipaciones' => Equipacion::all(),
            'estadios' => Estadio::all(),
            'ligas' => Liga::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
         $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10',
            'pais' => 'nullable|string|max:255',
            'liga' => 'nullable|string|max:255',
            'equipacion_id' => 'required|exists:equipacions,id',
            'estadio_id' => 'required|exists:estadios,id',
            'entrenador_id' => 'required|exists:entrenadors,id',
            'liga_id' => 'nullable|exists:ligas,id',
        ]);

        $equipo->update($validated);

        return redirect()->route('equipos.index')->with('success', 'Equipo modificado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado con éxito');
    }
}
