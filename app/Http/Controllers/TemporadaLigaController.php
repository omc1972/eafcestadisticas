<?php

namespace App\Http\Controllers;

use App\Models\Temporada;
use App\Models\Plantilla;
use App\Models\Equipo;
use App\Models\Competicion;
use App\Models\Partido;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemporadaLigaController extends Controller
{
    /**
     * Mostrar la página de gestión de orden de ligas para una temporada
     */
    public function index(Temporada $temporada)
    {
        $competicionLigaId = Competicion::query()
            ->whereRaw('LOWER(nombre) = ?', ['liga'])
            ->value('id');

        // Obtener todos los equipos con liga, agrupados por liga
        $equiposConLiga = Equipo::with('liga')
            ->whereNotNull('liga_id')
            ->orderBy('liga_id')
            ->get()
            ->groupBy(function ($equipo) {
                return $equipo->liga_id;
            })
            ->map(function ($equiposLiga) use ($temporada, $competicionLigaId) {
                $primerEquipo = $equiposLiga->first();
                $ligaRelacion = $primerEquipo->getRelation('liga');

                $equiposOrdenados = $equiposLiga
                    ->map(function ($equipo) use ($temporada) {
                        // Buscar si existe plantilla para obtener el orden
                        $plantilla = Plantilla::with('jugadores:id,media')
                            ->where('temporada_id', $temporada->id)
                            ->where('equipo_id', $equipo->id)
                            ->first();

                        $mediaPlantilla = null;
                        if ($plantilla && $plantilla->jugadores->count() > 0) {
                            $mediaPlantilla = round((float) $plantilla->jugadores->avg('media'), 1);
                        }

                        return [
                            'plantilla_id' => $plantilla ? $plantilla->id : null,
                            'orden_liga' => $plantilla ? $plantilla->orden_liga : null,
                            'campeonato_id' => $plantilla ? $plantilla->campeonato_id : null,
                            'equipo' => [
                                'id' => $equipo->id,
                                'nombre' => $equipo->nombre,
                                'codigo' => $equipo->codigo,
                                'escudo' => $equipo->escudo,
                                'media' => $mediaPlantilla,
                            ],
                        ];
                    })
                    ->sortBy(function ($item) {
                        return [$item['orden_liga'] ?? PHP_INT_MAX, $item['equipo']['nombre']];
                    })
                    ->values();

                $campeonatoLigaId = $equiposOrdenados
                    ->pluck('campeonato_id')
                    ->filter()
                    ->first();
                
                $equipoIds = $equiposOrdenados->pluck('equipo.id')->values()->all();
                $campeonatosLigaIds = Plantilla::query()
                    ->where('temporada_id', $temporada->id)
                    ->whereIn('equipo_id', $equipoIds)
                    ->pluck('campeonato_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $partidosQuery = Partido::query()
                    ->select(['id', 'equipo_id', 'visitante_id', 'jornada', 'campeonato_id', 'goles_equipo', 'goles_rival'])
                    ->where('temporada_id', $temporada->id);

                if (!empty($campeonatosLigaIds)) {
                    $partidosQuery->whereIn('campeonato_id', $campeonatosLigaIds);
                } else {
                    // Sin campeonato de referencia no podemos validar que el partido corresponda a esta liga.
                    $partidosQuery->whereRaw('1 = 0');
                }

                $partidosExistentes = $partidosQuery->get();

                $partidosExistentesPorClave = [];
                foreach ($partidosExistentes as $partidoExistente) {
                    $numeroJornada = $this->extraerNumeroJornada($partidoExistente->jornada);
                    if (is_null($numeroJornada)) {
                        continue;
                    }

                    if (
                        !in_array((int) $partidoExistente->equipo_id, $equipoIds, true)
                        || !in_array((int) $partidoExistente->visitante_id, $equipoIds, true)
                    ) {
                        continue;
                    }

                    $clave = $numeroJornada . '-' . $partidoExistente->equipo_id . '-' . $partidoExistente->visitante_id;
                    if (!isset($partidosExistentesPorClave[$clave])) {
                        $resultado = null;
                        if (!is_null($partidoExistente->goles_equipo) && !is_null($partidoExistente->goles_rival)) {
                            $resultado = $partidoExistente->goles_equipo . ' - ' . $partidoExistente->goles_rival;
                        }

                        $partidosExistentesPorClave[$clave] = [
                            'id' => (int) $partidoExistente->id,
                            'resultado' => $resultado,
                        ];
                    }
                }

                $jornadas = $this->generarJornadasLiga($equiposOrdenados->pluck('equipo')->values()->all());
                $jornadasConEstado = array_map(function (array $jornada) use ($partidosExistentesPorClave) {
                    $numero = (int) $jornada['numero'];
                    $partidos = array_map(function (array $partido) use ($partidosExistentesPorClave, $numero) {
                        $clave = $numero . '-' . $partido['local']['id'] . '-' . $partido['visitante']['id'];
                        $partidoExistente = $partidosExistentesPorClave[$clave] ?? null;

                        return [
                            'local' => $partido['local'],
                            'visitante' => $partido['visitante'],
                            'creado' => !is_null($partidoExistente),
                            'partido_id' => $partidoExistente['id'] ?? null,
                            'resultado' => $partidoExistente['resultado'] ?? null,
                        ];
                    }, $jornada['partidos']);

                    return [
                        'numero' => $numero,
                        'partidos' => $partidos,
                    ];
                }, $jornadas);

                return [
                    'liga' => [
                        'id' => $ligaRelacion->id,
                        'nombre' => $ligaRelacion->nombre,
                        'pais' => $ligaRelacion->pais,
                    ],
                    'campeonato_id' => $campeonatoLigaId,
                    'equipos' => $equiposOrdenados,
                    'jornadas' => $jornadasConEstado,
                ];
            })
            ->values();

        return Inertia::render('Temporadas/GestionLigas', [
            'temporada' => [
                'id' => $temporada->id,
                'nombre' => $temporada->nombre,
            ],
            'ligas' => $equiposConLiga,
            'competicionLigaId' => $competicionLigaId,
        ]);
    }

    /**
     * Genera jornadas de liga a doble vuelta (todos contra todos).
     *
     * @param array<int, array{id:int,nombre:string,codigo:string,escudo:?string}> $equipos
     * @return array<int, array{numero:int,partidos:array<int, array{local:array,visitante:array}>}>
     */
    private function generarJornadasLiga(array $equipos): array
    {
        if (count($equipos) < 2) {
            return [];
        }

        $emparejamientosIda = $this->generarEmparejamientosLiga($equipos);
        $jornadasIda = $this->orientarJornadas($emparejamientosIda);

        $calendarioDirecto = array_merge(
            $jornadasIda,
            $this->invertirJornadas($jornadasIda, count($jornadasIda), false)
        );

        $calendarioReverso = array_merge(
            $jornadasIda,
            $this->invertirJornadas($jornadasIda, count($jornadasIda), true)
        );

        return $this->calcularPenalizacionRachas($calendarioReverso) < $this->calcularPenalizacionRachas($calendarioDirecto)
            ? array_values($calendarioReverso)
            : array_values($calendarioDirecto);
    }

    /**
     * @param array<int, array{id:int,nombre:string,codigo:string,escudo:?string}> $equipos
     * @return array<int, array{numero:int,partidos:array<int, array{a:array,b:array}>}>
     */
    private function generarEmparejamientosLiga(array $equipos): array
    {
        $rotacion = array_values($equipos);
        $esImpar = count($rotacion) % 2 !== 0;

        if ($esImpar) {
            $rotacion[] = null;
        }

        $numEquipos = count($rotacion);
        $numJornadasIda = $numEquipos - 1;
        $partidosPorJornada = (int) ($numEquipos / 2);
        $jornadas = [];

        for ($jornada = 0; $jornada < $numJornadasIda; $jornada++) {
            $partidos = [];

            for ($i = 0; $i < $partidosPorJornada; $i++) {
                $equipoA = $rotacion[$i];
                $equipoB = $rotacion[$numEquipos - 1 - $i];

                if ($equipoA === null || $equipoB === null) {
                    continue;
                }

                $partidos[] = [
                    'a' => $equipoA,
                    'b' => $equipoB,
                ];
            }

            $jornadas[] = [
                'numero' => $jornada + 1,
                'partidos' => $partidos,
            ];

            $fijo = array_shift($rotacion);
            $ultimo = array_pop($rotacion);
            array_unshift($rotacion, $fijo);
            array_splice($rotacion, 1, 0, [$ultimo]);
        }

        return $jornadas;
    }

    /**
     * @param array<int, array{numero:int,partidos:array<int, array{a:array,b:array}>}> $emparejamientos
     * @return array<int, array{numero:int,partidos:array<int, array{local:array,visitante:array}>}>
     */
    private function orientarJornadas(array $emparejamientos): array
    {
        $estados = [];
        $jornadas = [];

        foreach ($emparejamientos as $indiceJornada => $jornada) {
            $partidosOrientados = [];

            foreach ($jornada['partidos'] as $indicePartido => $partido) {
                $equipoA = $partido['a'];
                $equipoB = $partido['b'];

                $penalizacionAB = $this->calcularPenalizacionOrientacion($equipoA['id'], true, $equipoB['id'], false, $estados);
                $penalizacionBA = $this->calcularPenalizacionOrientacion($equipoB['id'], true, $equipoA['id'], false, $estados);

                $invertir = $penalizacionBA < $penalizacionAB
                    || ($penalizacionBA === $penalizacionAB && (($indiceJornada + $indicePartido) % 2 === 1));

                $local = $invertir ? $equipoB : $equipoA;
                $visitante = $invertir ? $equipoA : $equipoB;

                $this->registrarCondicionEquipo($estados, $local['id'], true);
                $this->registrarCondicionEquipo($estados, $visitante['id'], false);

                $partidosOrientados[] = [
                    'local' => $local,
                    'visitante' => $visitante,
                ];
            }

            $jornadas[] = [
                'numero' => $indiceJornada + 1,
                'partidos' => $partidosOrientados,
            ];
        }

        return $jornadas;
    }

    /**
     * @param array<int, array{numero:int,partidos:array<int, array{local:array,visitante:array}>}> $jornadasIda
     * @return array<int, array{numero:int,partidos:array<int, array{local:array,visitante:array}>}>
     */
    private function invertirJornadas(array $jornadasIda, int $desfase, bool $reverseOrder): array
    {
        $fuente = $reverseOrder ? array_values(array_reverse($jornadasIda)) : $jornadasIda;
        $resultado = [];

        foreach ($fuente as $indice => $jornada) {
            $resultado[] = [
                'numero' => $desfase + $indice + 1,
                'partidos' => array_map(function (array $partido) {
                    return [
                        'local' => $partido['visitante'],
                        'visitante' => $partido['local'],
                    ];
                }, $jornada['partidos']),
            ];
        }

        return $resultado;
    }

    /**
     * @param array<int, array{ultima:?string,racha:int,locales:int,visitantes:int}> $estados
     */
    private function calcularPenalizacionOrientacion(int $equipoLocalId, bool $localPrimero, int $equipoVisitanteId, bool $localSegundo, array $estados): int
    {
        return $this->penalizacionCondicionEquipo($equipoLocalId, $localPrimero, $estados)
            + $this->penalizacionCondicionEquipo($equipoVisitanteId, $localSegundo, $estados);
    }

    /**
     * @param array<int, array{ultima:?string,racha:int,locales:int,visitantes:int}> $estados
     */
    private function penalizacionCondicionEquipo(int $equipoId, bool $esLocal, array $estados): int
    {
        $estado = $estados[$equipoId] ?? [
            'ultima' => null,
            'racha' => 0,
            'locales' => 0,
            'visitantes' => 0,
        ];

        $condicion = $esLocal ? 'L' : 'V';
        $nuevaRacha = $estado['ultima'] === $condicion ? $estado['racha'] + 1 : 1;
        $locales = $estado['locales'] + ($esLocal ? 1 : 0);
        $visitantes = $estado['visitantes'] + ($esLocal ? 0 : 1);

        $penalizacion = abs($locales - $visitantes) * 3;

        if ($nuevaRacha > 2) {
            $penalizacion += 1000 + (($nuevaRacha - 2) * 500);
        } elseif ($nuevaRacha === 2) {
            $penalizacion += 15;
        }

        return $penalizacion;
    }

    /**
     * @param array<int, array{ultima:?string,racha:int,locales:int,visitantes:int}> $estados
     */
    private function registrarCondicionEquipo(array &$estados, int $equipoId, bool $esLocal): void
    {
        $estado = $estados[$equipoId] ?? [
            'ultima' => null,
            'racha' => 0,
            'locales' => 0,
            'visitantes' => 0,
        ];

        $condicion = $esLocal ? 'L' : 'V';
        $estado['racha'] = $estado['ultima'] === $condicion ? $estado['racha'] + 1 : 1;
        $estado['ultima'] = $condicion;

        if ($esLocal) {
            $estado['locales']++;
        } else {
            $estado['visitantes']++;
        }

        $estados[$equipoId] = $estado;
    }

    /**
     * @param array<int, array{numero:int,partidos:array<int, array{local:array,visitante:array}>}> $jornadas
     */
    private function calcularPenalizacionRachas(array $jornadas): int
    {
        $estados = [];
        $penalizacion = 0;

        foreach ($jornadas as $jornada) {
            foreach ($jornada['partidos'] as $partido) {
                $penalizacion += $this->penalizacionCondicionEquipo($partido['local']['id'], true, $estados);
                $this->registrarCondicionEquipo($estados, $partido['local']['id'], true);

                $penalizacion += $this->penalizacionCondicionEquipo($partido['visitante']['id'], false, $estados);
                $this->registrarCondicionEquipo($estados, $partido['visitante']['id'], false);
            }
        }

        return $penalizacion;
    }

    private function extraerNumeroJornada(?string $jornada): ?int
    {
        if (is_null($jornada) || trim($jornada) === '') {
            return null;
        }

        if (preg_match('/(\d+)/', $jornada, $matches) !== 1) {
            return null;
        }

        return (int) $matches[1];
    }

    /**
     * Sortear aleatoriamente los equipos de una liga
     */
    public function sortear(Request $request, Temporada $temporada)
    {
        $request->validate([
            'liga_id' => 'required|exists:ligas,id',
        ]);

        // Obtener todos los equipos de esa liga
        $equipos = Equipo::where('liga_id', $request->liga_id)->get()->shuffle();

        $orden = 1;
        foreach ($equipos as $equipo) {
            // Crear o actualizar plantilla con el orden
            Plantilla::updateOrCreate(
                [
                    'temporada_id' => $temporada->id,
                    'equipo_id' => $equipo->id,
                ],
                [
                    'liga_id' => $request->liga_id,
                    'orden_liga' => $orden,
                ]
            );
            $orden++;
        }

        return redirect()->back();
    }

    /**
     * Actualizar el orden manual de los equipos en una liga
     */
    public function updateOrden(Request $request, Temporada $temporada)
    {
        $request->validate([
            'liga_id' => 'required|exists:ligas,id',
            'orden' => 'required|array',
            'orden.*.equipo_id' => 'required|exists:equipos,id',
            'orden.*.orden' => 'required|integer|min:1',
        ]);

        foreach ($request->orden as $item) {
            Plantilla::updateOrCreate(
                [
                    'temporada_id' => $temporada->id,
                    'equipo_id' => $item['equipo_id'],
                ],
                [
                    'liga_id' => $request->liga_id,
                    'orden_liga' => $item['orden'],
                ]
            );
        }

        return back()->with('success', 'Orden actualizado correctamente');
    }

    /**
     * Auto-generar plantillas para todos los equipos con liga
     */
    public function autoGenerarPlantillas(Temporada $temporada)
    {
        $equiposConLiga = Equipo::whereNotNull('liga_id')->get();
        
        $creadas = 0;
        foreach ($equiposConLiga as $equipo) {
            // Verificar si ya existe una plantilla
            $existe = Plantilla::where('temporada_id', $temporada->id)
                ->where('equipo_id', $equipo->id)
                ->exists();
            
            if (!$existe) {
                Plantilla::create([
                    'equipo_id' => $equipo->id,
                    'temporada_id' => $temporada->id,
                    'liga_id' => $equipo->liga_id,
                ]);
                $creadas++;
            }
        }

        return back()->with('success', "Se crearon {$creadas} plantillas automáticamente");
    }
}
