<?php

namespace App\Support;

use App\Models\TipoEvento;
use Illuminate\Support\Collection;

class EstadisticasHelper
{
    /**
     * Acumula las estadísticas de un equipo a partir de una colección de partidos.
     *
     * El array $base permite al caller pre-inyectar campos extra (equipo, orden_liga, etc.)
     * que no pertenecen al cálculo numérico.
     */
    public static function statsEquipo(Collection $partidos, int $equipoId, array $base = []): array
    {
        $stats = array_merge([
            'partidos_jugados'        => 0,
            'victorias'               => 0,
            'empates'                 => 0,
            'derrotas'                => 0,
            'goles_favor'             => 0,
            'goles_contra'            => 0,
            'diferencia_goles'        => 0,
            'puntos'                  => 0,
            'tarjetas_amarillas'      => 0,
            'tarjetas_rojas'          => 0,
            'posesion_promedio'       => 0,
            'pases'                   => 0,
            'distancia_recorrida'     => 0,
            'penalties_a_favor'       => 0,
            'penalties_en_contra'     => 0,
            'tiros_al_palo'           => 0,
            'tiros_realizados'        => 0,
            'tiros_recibidos'         => 0,
            'tiros_a_puerta_realizados' => 0,
            'tiros_a_puerta_recibidos'  => 0,
            'balones_ganados_equipo'  => 0,
            'balones_perdidos_equipo' => 0,
            'interceciones_equipo'    => 0,
        ], $base);

        $totalPosesion = 0;
        foreach ($partidos as $p) {
            $esLocal = (int) $p->equipo_id === $equipoId;
            $gFavor  = $esLocal ? ($p->goles_equipo ?? 0) : ($p->goles_rival ?? 0);
            $gContra = $esLocal ? ($p->goles_rival  ?? 0) : ($p->goles_equipo ?? 0);

            $stats['partidos_jugados']++;
            $stats['goles_favor']  += $gFavor;
            $stats['goles_contra'] += $gContra;
            $stats['tarjetas_amarillas'] += $esLocal ? ($p->tarjetas_amarillas_equipo ?? 0) : ($p->tarjetas_amarillas_rival ?? 0);
            $stats['tarjetas_rojas']     += $esLocal ? ($p->tarjetas_rojas_equipo    ?? 0) : ($p->tarjetas_rojas_rival    ?? 0);
            $totalPosesion               += $esLocal ? ($p->posesion_equipo          ?? 0) : ($p->posesion_rival          ?? 0);
            $stats['pases']              += $esLocal ? ($p->pases_equipo             ?? 0) : ($p->pases_rival             ?? 0);
            $stats['distancia_recorrida']+= $esLocal ? ($p->distancia_equipo         ?? 0) : ($p->distancia_rival         ?? 0);
            $stats['penalties_a_favor']  += $esLocal ? ($p->penalties_equipo         ?? 0) : ($p->penalties_rival         ?? 0);
            $stats['penalties_en_contra']+= $esLocal ? ($p->penalties_rival          ?? 0) : ($p->penalties_equipo        ?? 0);
            $stats['tiros_realizados']   += $esLocal ? ($p->tiros_equipo             ?? 0) : ($p->tiros_rival             ?? 0);
            $stats['tiros_recibidos']    += $esLocal ? ($p->tiros_rival              ?? 0) : ($p->tiros_equipo            ?? 0);
            $stats['tiros_a_puerta_realizados'] += $esLocal ? ($p->tiros_a_puerta_equipo ?? 0) : ($p->tiros_a_puerta_rival ?? 0);
            $stats['tiros_a_puerta_recibidos']  += $esLocal ? ($p->tiros_a_puerta_rival  ?? 0) : ($p->tiros_a_puerta_equipo ?? 0);
            $stats['balones_ganados_equipo']  += $esLocal ? ($p->balones_ganados_equipo  ?? 0) : ($p->balones_ganados_rival  ?? 0);
            $stats['balones_perdidos_equipo'] += $esLocal ? ($p->balones_perdidos_equipo ?? 0) : ($p->balones_perdidos_rival ?? 0);
            $stats['interceciones_equipo']    += $esLocal ? ($p->interceciones_equipo    ?? 0) : ($p->intercepciones_rival  ?? 0);

            if ($gFavor > $gContra)      { $stats['victorias']++; $stats['puntos'] += 3; }
            elseif ($gFavor == $gContra) { $stats['empates']++;   $stats['puntos'] += 1; }
            else                         { $stats['derrotas']++; }
        }

        $stats['diferencia_goles'] = $stats['goles_favor'] - $stats['goles_contra'];
        if ($stats['partidos_jugados'] > 0) {
            $stats['posesion_promedio'] = round($totalPosesion / $stats['partidos_jugados'], 1);
        }

        return $stats;
    }

    /**
     * Acumula estadísticas de jugadores desde alineaciones y eventos.
     *
     * Si se pasa $jugadores pre-inicializado (ej: sembrado desde plantilla), los jugadores
     * que no estén en él serán ignorados. Si se pasa vacío, se autodetectan desde alineaciones.
     */
    public static function statsJugadores(Collection $partidos, array $jugadores = []): array
    {
        $autoSeed = empty($jugadores);

        // Inicializar desde alineaciones cuando no viene pre-sembrado
        if ($autoSeed) {
            foreach ($partidos as $partido) {
                foreach ($partido->alineaciones as $alineacion) {
                    $jugador = $alineacion->jugador;
                    if (!$jugador) continue;
                    $id = $jugador->id;
                    if (!isset($jugadores[$id])) {
                        $jugadores[$id] = self::jugadorVacio(
                            ['id' => $jugador->id, 'nombre' => $jugador->nombre, 'posicion' => $jugador->posicion],
                            $alineacion->equipo?->nombre ?? null
                        );
                    }
                }
            }
        }

        // Acumular stats de alineación por jugador
        foreach ($partidos as $partido) {
            foreach ($partido->alineaciones as $alineacion) {
                $id = $alineacion->jugador_id;
                if (!isset($jugadores[$id])) continue;

                $esPortero = ($alineacion->jugador->posicion ?? null) === 'POR';
                $jugadores[$id]['tiros'] += $alineacion->tiros ?? 0;
                if (!$esPortero) {
                    $jugadores[$id]['tiros_a_puerta'] += $alineacion->tiros_a_puerta ?? 0;
                    $jugadores[$id]['tiros_al_palo']  += $alineacion->tiros_al_palo  ?? 0;
                }
                $jugadores[$id]['pases']             += $alineacion->pases             ?? 0;
                $jugadores[$id]['pases_exitosos']    += $alineacion->pases_exitosos    ?? 0;
                $jugadores[$id]['entradas']          += $alineacion->entradas          ?? 0;
                $jugadores[$id]['entradas_exitosas'] += $alineacion->entradas_exitosas ?? 0;
                $jugadores[$id]['regates']           += $alineacion->regates           ?? 0;
                $jugadores[$id]['regates_exitosos']  += $alineacion->regates_exitosos  ?? 0;
                $jugadores[$id]['posesion_ganada']   += $alineacion->posesion_ganada   ?? 0;
                $jugadores[$id]['posesion_perdida']  += $alineacion->posesion_perdida  ?? 0;
                $jugadores[$id]['distancia_recorrida']+= $alineacion->distancia_recorrida ?? 0;
                $jugadores[$id]['rendimiento']       += $alineacion->rendimiento       ?? 0;
                $jugadores[$id]['faltas_cometidas']  += $alineacion->faltas_cometidas  ?? 0;
                $jugadores[$id]['faltas_recibidas']  += $alineacion->faltas_recibidas  ?? 0;
                $jugadores[$id]['fueras_de_juego']   += $alineacion->fueras_de_juego   ?? 0;
                $jugadores[$id]['jugador_del_partido']+= $alineacion->jugador_del_partido ?? 0;
                $jugadores[$id]['partidos_jugados']++;
            }
        }

        // Minutos jugados + paradas de portero
        foreach ($partidos as $partido) {
            $duracion = $partido->minutos_jugados ?? 90;
            foreach ($partido->alineaciones as $alineacion) {
                $id = $alineacion->jugador_id;
                if (!isset($jugadores[$id])) continue;

                $sale  = $partido->eventos->first(fn($e) => $e->jugador_id == $id && $e->tipo_evento_id == TipoEvento::SALE);
                $entra = $partido->eventos->first(fn($e) => $e->jugador_id == $id && $e->tipo_evento_id == TipoEvento::ENTRA);

                if ($entra && $sale)   $minutos = ($sale->minuto ?? 0) - ($entra->minuto ?? 0);
                elseif ($sale)         $minutos = $sale->minuto ?? $duracion;
                elseif ($entra)        $minutos = $duracion - ($entra->minuto ?? 0);
                else                   $minutos = $duracion;

                $jugadores[$id]['minutos_jugados'] += $minutos;

                if ($alineacion->jugador->posicion === 'POR') {
                    $jugadores[$id]['paradas'] += max(
                        0,
                        ($partido->tiros_a_puerta_rival ?? 0) - ($partido->goles_rival ?? 0)
                    );
                }
            }
        }

        // Acumular eventos (goles, asistencias, tarjetas, etc.)
        $jugadorExtraId = config('app.jugador_extra_id');
        foreach ($partidos as $partido) {
            foreach ($partido->eventos as $evento) {
                if ($evento->jugador_id == $jugadorExtraId) continue;
                $jid = $evento->jugador_id;
                if (!isset($jugadores[$jid])) continue;

                match ($evento->tipo_evento_id) {
                    TipoEvento::GOL               => $jugadores[$jid]['goles']++,
                    TipoEvento::ASISTENCIA        => $jugadores[$jid]['asistencias']++,
                    TipoEvento::PENALTY_PROVOCADO => $jugadores[$jid]['penalties_provocados']++,
                    TipoEvento::PENALTY_PARADO    => $jugadores[$jid]['penalties_parados']++,
                    TipoEvento::TARJETA_AMARILLA  => (function () use (&$jugadores, $jid) {
                        $jugadores[$jid]['tarjetas_amarillas']++;
                        $jugadores[$jid]['puntuacion_amonestaciones'] += 1;
                    })(),
                    TipoEvento::TARJETA_ROJA      => (function () use (&$jugadores, $jid) {
                        $jugadores[$jid]['tarjetas_rojas']++;
                        $jugadores[$jid]['puntuacion_amonestaciones'] += 3;
                    })(),
                    default => null,
                };
            }
        }

        return $jugadores;
    }

    public static function jugadorVacio(array $jugador, ?string $equipoNombre): array
    {
        return [
            'jugador'                 => $jugador,
            'equipo'                  => $equipoNombre,
            'goles'                   => 0,
            'asistencias'             => 0,
            'tiros'                   => 0,
            'tiros_a_puerta'          => 0,
            'tiros_al_palo'           => 0,
            'pases'                   => 0,
            'pases_exitosos'          => 0,
            'entradas'                => 0,
            'entradas_exitosas'       => 0,
            'regates'                 => 0,
            'regates_exitosos'        => 0,
            'posesion_ganada'         => 0,
            'posesion_perdida'        => 0,
            'tarjetas_amarillas'      => 0,
            'tarjetas_rojas'          => 0,
            'distancia_recorrida'     => 0,
            'rendimiento'             => 0,
            'partidos_jugados'        => 0,
            'minutos_jugados'         => 0,
            'penalties_provocados'    => 0,
            'faltas_cometidas'        => 0,
            'faltas_recibidas'        => 0,
            'fueras_de_juego'         => 0,
            'paradas'                 => 0,
            'jugador_del_partido'     => 0,
            'puntuacion_amonestaciones' => 0,
            'penalties_parados'       => 0,
        ];
    }
}
