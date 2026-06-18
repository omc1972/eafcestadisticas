<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\Competicion;
use App\Models\Eliminatoria;
use App\Models\Partido;
use Illuminate\Support\Collection;

class EliminatoriaController extends Controller
{
    public function show($campeonatoId)
    {
        $campeonato = Campeonato::with([
            'plantillas.equipo',
            'eliminatorias.partidos.local',
            'eliminatorias.partidos.visitanteEquipo',
            'eliminatorias.partidos.alineaciones',
            'eliminatorias.partidos.eventos',
        ])->findOrFail($campeonatoId);

        $equipos = $campeonato->plantillas->map(function ($plantilla) {
            return [
                'id' => $plantilla->equipo->id,
                'nombre' => $plantilla->equipo->nombre,
                'escudo' => $plantilla->equipo->escudo,
            ];
        });

        $ganadoresPrevios = collect();

        $rondas = $campeonato->eliminatorias
            ->sortBy('orden')
            ->values()
            ->map(function ($eliminatoria) use (&$ganadoresPrevios) {
                $cruces = $this->construirCruces($eliminatoria, $ganadoresPrevios);

                $ganadoresPrevios = $cruces
                    ->pluck('ganador')
                    ->filter(fn ($ganador) => !empty($ganador['id']))
                    ->values();

                return [
                    'nombre' => $eliminatoria->nombre,
                    'orden' => $eliminatoria->orden,
                    'cruces' => $cruces->values()->all(),
                ];
            });

        return inertia('Eliminatorias/Bracket', [
            'campeonato' => $campeonato,
            'equipos_participantes' => $equipos,
            'eliminatoria_rondas' => $rondas,
        ]);
    }

    public function sortear($campeonatoId)
    {
        $campeonato = Campeonato::with(['plantillas.equipo', 'eliminatorias'])->findOrFail($campeonatoId);
        $equipos = $campeonato->plantillas
            ->pluck('equipo')
            ->filter()
            ->shuffle()
            ->values();

        // Eliminar partidos de cuadro previos y rondas previas.
        Partido::where('campeonato_id', $campeonato->id)
            ->whereNotNull('eliminatoria_id')
            ->delete();

        foreach ($campeonato->eliminatorias as $elim) {
            $elim->delete();
        }

        if ($equipos->isEmpty()) {
            return redirect()->route('campeonatos.eliminatorias', ['campeonato' => $campeonato->id]);
        }

        $this->crearRondas($campeonato, $equipos);

        return redirect()->route('campeonatos.eliminatorias', ['campeonato' => $campeonato->id]);
    }

    private function construirCruces(Eliminatoria $eliminatoria, Collection $ganadoresPrevios): Collection
    {
        $partidosBracket = $eliminatoria->partidos->sortBy('orden_eliminatoria')->values();
        $esFinal = mb_strtolower($eliminatoria->nombre) === 'final';

        $series = $esFinal
            ? $partidosBracket->map(fn ($partido) => collect([$partido]))
            : $partidosBracket->groupBy(fn ($partido) => (int) ceil($partido->orden_eliminatoria / 2))->values();

        return $series->values()->map(function (Collection $serie, int $indiceCruce) use ($ganadoresPrevios) {
            [$equipoLocal, $equipoVisitante] = $this->resolverParticipantes($serie, $indiceCruce, $ganadoresPrevios);
            $this->sincronizarParticipantesSerie($serie, $equipoLocal, $equipoVisitante);
            $partidosMostrados = $this->mapearPartidosParaVista($serie, $equipoLocal, $equipoVisitante);
            $resultadoGlobal = $this->calcularResultadoGlobal($serie, $equipoLocal, $equipoVisitante);
            $ganador = $this->resolverGanador($resultadoGlobal, $equipoLocal, $equipoVisitante);

            return [
                'id' => $serie->first()->id,
                'orden' => $indiceCruce + 1,
                'equipo_local' => $equipoLocal?->nombre ?? 'TBD',
                'equipo_visitante' => $equipoVisitante?->nombre ?? 'TBD',
                'resultado_global' => $resultadoGlobal
                    ? $resultadoGlobal['local'] . ' - ' . $resultadoGlobal['visitante']
                    : null,
                'ganador' => $ganador,
                'partidos' => $partidosMostrados->values()->all(),
            ];
        });
    }

    private function resolverParticipantes(Collection $serie, int $indiceCruce, Collection $ganadoresPrevios): array
    {
        $partidoBase = $serie->sortBy('orden_eliminatoria')->first();
        $equipoLocal = $partidoBase?->local;
        $equipoVisitante = $partidoBase?->visitanteEquipo;

        if (!$equipoLocal && $ganadoresPrevios->has($indiceCruce * 2)) {
            $equipoLocal = $ganadoresPrevios->get($indiceCruce * 2)['equipo'] ?? null;
        }

        if (!$equipoVisitante && $ganadoresPrevios->has(($indiceCruce * 2) + 1)) {
            $equipoVisitante = $ganadoresPrevios->get(($indiceCruce * 2) + 1)['equipo'] ?? null;
        }

        return [$equipoLocal, $equipoVisitante];
    }

    private function sincronizarParticipantesSerie(Collection $serie, $equipoLocal, $equipoVisitante): void
    {
        if (!$equipoLocal) {
            return;
        }

        foreach ($serie->sortBy('orden_eliminatoria')->values() as $indice => $partido) {
            $localId = $indice === 0 ? $equipoLocal?->id : $equipoVisitante?->id;
            $visitanteId = $indice === 0 ? $equipoVisitante?->id : $equipoLocal?->id;

            if ((int) $partido->equipo_id !== (int) $localId || (int) $partido->visitante_id !== (int) $visitanteId || $partido->visitante_es_rival) {
                $partido->equipo_id = $localId;
                $partido->visitante_id = $visitanteId;
                $partido->visitante_es_rival = false;
                $partido->save();
            }
        }
    }

    private function mapearPartidosParaVista(Collection $serie, $equipoLocal, $equipoVisitante): Collection
    {
        $esFinal = $serie->count() === 1;
        $partidosVista = collect();

        foreach ($serie->sortBy('orden_eliminatoria')->values() as $indice => $partidoBracket) {
            $localProgramado = $partidoBracket->local
                ?? ($indice === 0 ? $equipoLocal : $equipoVisitante);
            $visitanteProgramado = $partidoBracket->visitanteEquipo
                ?? ($indice === 0 ? $equipoVisitante : $equipoLocal);

            $partidosVista->push([
                'id' => $partidoBracket->id,
                'etiqueta' => $esFinal ? 'Final' : ($indice === 0 ? 'Ida' : 'Vuelta'),
                'equipo_local' => $localProgramado?->nombre ?? 'TBD',
                'equipo_visitante' => $visitanteProgramado?->nombre ?? 'TBD',
                'goles_local' => $partidoBracket->goles_equipo,
                'goles_visitante' => $partidoBracket->goles_rival,
                'gestionado' => $this->estaGestionado($partidoBracket),
                'gestionar_url' => $localProgramado && $visitanteProgramado
                    ? route('partidos.edit', ['partido' => $partidoBracket->id])
                    : null,
            ]);
        }

        return $partidosVista;
    }

    private function calcularResultadoGlobal(Collection $partidosSerie, $equipoLocal, $equipoVisitante): ?array
    {
        if (!$equipoLocal || !$equipoVisitante || $partidosSerie->isEmpty()) {
            return null;
        }

        $resultado = [
            'local' => 0,
            'visitante' => 0,
            'completado' => true,
        ];

        foreach ($partidosSerie as $partido) {
            $golesLocal = $partido->goles_equipo;
            $golesVisitante = $partido->goles_rival;

            if ($golesLocal === null || $golesVisitante === null) {
                $resultado['completado'] = false;
                continue;
            }

            if ((int) $partido->equipo_id === (int) $equipoLocal->id) {
                $resultado['local'] += $golesLocal;
                $resultado['visitante'] += $golesVisitante;
            } else {
                $resultado['local'] += $golesVisitante;
                $resultado['visitante'] += $golesLocal;
            }
        }

        return $resultado;
    }

    private function resolverGanador(?array $resultadoGlobal, $equipoLocal, $equipoVisitante): ?array
    {
        if ($equipoLocal && !$equipoVisitante) {
            return [
                'id' => $equipoLocal->id,
                'nombre' => $equipoLocal->nombre,
                'equipo' => $equipoLocal,
            ];
        }

        if (!$resultadoGlobal || !$resultadoGlobal['completado']) {
            return null;
        }

        if ($resultadoGlobal['local'] === $resultadoGlobal['visitante']) {
            return null;
        }

        $ganador = $resultadoGlobal['local'] > $resultadoGlobal['visitante']
            ? $equipoLocal
            : $equipoVisitante;

        if (!$ganador) {
            return null;
        }

        return [
            'id' => $ganador->id,
            'nombre' => $ganador->nombre,
            'equipo' => $ganador,
        ];
    }

    private function estaGestionado(Partido $partido): bool
    {
        return $partido->dificultad !== null
            || $partido->jornada !== null
            || $partido->goles_equipo !== null
            || $partido->goles_rival !== null
            || $partido->alineaciones()->exists()
            || $partido->eventos()->exists();
    }

    private function crearRondas(Campeonato $campeonato, Collection $equipos): void
    {
        $cruces = (int) ceil($equipos->count() / 2);
        $ordenRonda = 1;
        $temporadaId = $campeonato->plantillas()->pluck('temporada_id')->filter()->first();

        while ($cruces > 0) {
            $eliminatoria = Eliminatoria::create([
                'campeonato_id' => $campeonato->id,
                'nombre' => $this->nombreRonda($cruces),
                'orden' => $ordenRonda,
            ]);

            if ($ordenRonda === 1) {
                $this->crearPrimeraRonda($eliminatoria, $equipos, $cruces === 1, $temporadaId);
            } else {
                $this->crearRondaPosterior($eliminatoria, $cruces, $cruces === 1, $temporadaId);
            }

            if ($cruces === 1) {
                break;
            }

            $cruces = (int) ceil($cruces / 2);
            $ordenRonda++;
        }
    }

    private function crearPrimeraRonda(Eliminatoria $eliminatoria, Collection $equipos, bool $esFinal, $temporadaId): void
    {
        $ordenPartido = 1;

        for ($i = 0; $i < $equipos->count(); $i += 2) {
            $local = $equipos->get($i);
            $visitante = $equipos->get($i + 1);

            $this->crearPartidoCruce(
                $eliminatoria,
                $ordenPartido,
                $local?->id,
                $visitante?->id,
                $temporadaId
            );
            $ordenPartido++;

            if (!$esFinal && $visitante) {
                $this->crearPartidoCruce(
                    $eliminatoria,
                    $ordenPartido,
                    $visitante->id,
                    $local?->id,
                    $temporadaId
                );
                $ordenPartido++;
            }
        }
    }

    private function crearRondaPosterior(Eliminatoria $eliminatoria, int $cruces, bool $esFinal, $temporadaId): void
    {
        $ordenPartido = 1;

        for ($indice = 0; $indice < $cruces; $indice++) {
            $this->crearPartidoCruce($eliminatoria, $ordenPartido, null, null, $temporadaId);
            $ordenPartido++;

            if (!$esFinal) {
                $this->crearPartidoCruce($eliminatoria, $ordenPartido, null, null, $temporadaId);
                $ordenPartido++;
            }
        }
    }

    private function crearPartidoCruce(Eliminatoria $eliminatoria, int $orden, ?int $equipoLocalId, ?int $equipoVisitanteId, $temporadaId): void
    {
        $competicion = Competicion::firstOrCreate([
            'nombre' => $eliminatoria->nombre,
        ]);

        if ($equipoLocalId && $equipoVisitanteId) {
            $existeMismoOrden = Partido::query()
                ->where('campeonato_id', $eliminatoria->campeonato_id)
                ->where('temporada_id', $temporadaId)
                ->where('eliminatoria_id', $eliminatoria->id)
                ->where('equipo_id', $equipoLocalId)
                ->where('visitante_id', $equipoVisitanteId)
                ->where('visitante_es_rival', false)
                ->exists();

            if ($existeMismoOrden) {
                return;
            }
        }

        Partido::create([
            'partido_como_local' => true,
            'dificultad' => 'Pendiente',
            'equipo_id' => $equipoLocalId,
            'visitante_id' => $equipoVisitanteId,
            'visitante_es_rival' => false,
            'temporada_id' => $temporadaId,
            'competicion_id' => $competicion->id,
            'campeonato_id' => $eliminatoria->campeonato_id,
            'eliminatoria_id' => $eliminatoria->id,
            'orden_eliminatoria' => $orden,
            'jornada' => 'ELI-' . $eliminatoria->orden . '-' . $orden,
        ]);
    }

    private function nombreRonda(int $cruces): string
    {
        return match ($cruces) {
            8 => 'Octavos de final',
            4 => 'Cuartos de final',
            2 => 'Semifinal',
            1 => 'Final',
            default => 'Dieciseisavos de final',
        };
    }
}
