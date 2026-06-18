import React, { useState, useEffect } from "react";
import { Head, Link } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Trophy, TrendingUp, Users, Target, Award, Clock, Star } from 'lucide-react';

// Helpers copied from Temporadas/LigaEstadisticas to render equipos/jugadores UI
const TopEquiposTable = ({ data, field, label, colorClass }: { data: any[]; field: string; label: string; colorClass: string }) => (
  <Table>
    <TableHeader>
      <TableRow>
        <TableHead className="w-8">#</TableHead>
        <TableHead>Equipo</TableHead>
        <TableHead className="text-center">{label}</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      {data?.map((stat: any, idx: number) => (
        <TableRow key={stat.equipo?.id ?? idx}>
          <TableCell className="font-bold">{idx + 1}</TableCell>
          <TableCell>
            <div className="flex items-center gap-2">
              {stat.equipo?.escudo && <img src={`/storage/images/${stat.equipo.escudo}`} alt={stat.equipo.nombre} className="h-5 w-5 object-contain" onError={e => { (e.currentTarget as HTMLImageElement).src = '/storage/images/nologo.png'; }} />}
              <span>{stat.equipo?.nombre}</span>
            </div>
          </TableCell>
          <TableCell className={`text-center font-bold ${colorClass}`}>{typeof stat[field] === 'number' ? stat[field] : (stat[field] ?? '-')}</TableCell>
        </TableRow>
      ))}
    </TableBody>
  </Table>
);

const TopJugadoresTable = ({ data, field, label, colorClass, format }: { data: any[]; field: string; label: string; colorClass: string; format?: (v: any) => string }) => (
  <Table>
    <TableHeader>
      <TableRow>
        <TableHead className="w-8">#</TableHead>
        <TableHead>Jugador</TableHead>
        <TableHead className="text-center">{label}</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      {data?.map((jugador: any, index: number) => (
        <TableRow key={jugador.jugador?.id ?? index}>
          <TableCell className="font-bold">{index + 1}</TableCell>
          <TableCell>
            <div className="text-sm">{jugador.jugador?.nombre}</div>
            <div className="text-xs text-muted-foreground">{jugador.equipo}</div>
          </TableCell>
          <TableCell className={`text-center font-bold ${colorClass}`}>{format ? format(jugador[field]) : String(jugador[field] ?? 0)}</TableCell>
        </TableRow>
      ))}
    </TableBody>
  </Table>
);

interface ResumenTemporada {
  temporada_id: number;
  temporada_nombre: string;
  matches?: any[];
  por_equipos?: any[];
  sumatorio?: {
    partidos_jugados: number;
    ganados: number;
    empatados: number;
    perdidos: number;
    goles_equipo: number;
    goles_visitante: number;
    diferencia: number;
  };
}

interface Competicion {
  id: number;
  nombre: string;
}

interface Props {
  competicion: Competicion;
  resumenPorTemporada?: ResumenTemporada[];
}

const View: React.FC<Props> = ({ competicion, resumenPorTemporada = [] }) => {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: "Dashboard", href: dashboard().url },
    { title: "Competiciones", href: "/competiciones" },
    { title: competicion?.nombre ?? "Competición", href: `/competiciones/${competicion?.id}` },
  ];

  const [page, setPage] = useState(1);
  const params = new URLSearchParams(window.location.search);
  const temporadaSeleccionadaGlobal = params.get('temporada_id') ? Number(params.get('temporada_id')) : null;
  useEffect(() => { setPage(1); }, [temporadaSeleccionadaGlobal]);

  const [equiposStatsMap, setEquiposStatsMap] = useState<Record<number, any> | null>(null);
  const [jugadoresStatsMap, setJugadoresStatsMap] = useState<Record<number, any> | null>(null);
  const [loadingEquipos, setLoadingEquipos] = useState(false);
  const [loadingJugadores, setLoadingJugadores] = useState(false);

  const handleTabChange = (value: string, temporadaId?: number) => {
    if (value === 'equipos' && !equiposStatsMap && !loadingEquipos) {
      setLoadingEquipos(true);
      fetch(`/competiciones/${competicion.id}/estadisticas?temporada_id=${temporadaId}&scope=equipos`)
        .then(r => r.json())
        .then(data => { setEquiposStatsMap(data || {}); setLoadingEquipos(false); })
        .catch(() => { setEquiposStatsMap({ error: 'No disponible' }); setLoadingEquipos(false); });
    }
    if (value === 'jugadores' && !jugadoresStatsMap && !loadingJugadores) {
      setLoadingJugadores(true);
      fetch(`/competiciones/${competicion.id}/estadisticas?temporada_id=${temporadaId}&scope=jugadores`)
        .then(r => r.json())
        .then(data => { setJugadoresStatsMap(data || {}); setLoadingJugadores(false); })
        .catch(() => { setJugadoresStatsMap({ error: 'No disponible' }); setLoadingJugadores(false); });
    }
  };

  const temporadaSeleccionada = temporadaSeleccionadaGlobal;

  const renderClasificacionYPartidos = (t: any) => {
    const partidosTodos = t.matches ?? [];
    const statsMap: Record<string, any> = {};
    const findInPorEquipos = (id: any) => (t.por_equipos ?? []).find((x: any) => (x.equipo && x.equipo.id === id) || x.equipo_id === id || x.id === id || x.equipo_id === id);

    partidosTodos.forEach((m: any) => {
      if (m.equipo_id == null) return;
      const id = m.equipo_id;
      if (!statsMap[id]) {
        const pe = findInPorEquipos(id);
        statsMap[id] = {
          id,
          nombre_mostrar: m.equipo?.nombre ?? m.equipo_nombre ?? pe?.equipo?.nombre ?? pe?.nombre ?? String(id),
          escudo: m.equipo?.escudo ?? pe?.equipo?.escudo ?? pe?.escudo ?? null,
          played: 0,
          wins: 0,
          draws: 0,
          losses: 0,
          gf: 0,
          ga: 0,
        };
      }
      const s = statsMap[id];
      const gEquipo = Number(m.goles_equipo ?? 0);
      const gRival = Number(m.goles_rival ?? 0);
      s.played += 1;
      s.gf += gEquipo;
      s.ga += gRival;
      if (gEquipo > gRival) s.wins += 1;
      else if (gEquipo === gRival) s.draws += 1;
      else s.losses += 1;
    });

    const equipos = Object.values(statsMap).map((s: any) => ({
      ...s,
      puntos: (s.wins ?? 0) * 3 + (s.draws ?? 0) * 1,
      gf: s.gf ?? 0,
      ga: s.ga ?? 0,
      played: s.played ?? 0,
    })).sort((a: any, b: any) => (b.puntos - a.puntos) || ((b.gf - b.ga) - (a.gf - a.ga)) || (b.gf - a.gf));

    const partidos = (t.matches ?? []).filter((p: any) => p.equipo_id != null && p.equipo_id !== undefined);
    const perPage = 10;
    const totalPages = Math.max(1, Math.ceil(partidos.length / perPage));
    const slice = partidos.slice((page - 1) * perPage, page * perPage);

    return (
      <Tabs defaultValue="clasificacion" onValueChange={(v) => handleTabChange(v, t.temporada_id)}>
        <TabsList className="mb-4">
          <TabsTrigger value="clasificacion">Clasificación</TabsTrigger>
          <TabsTrigger value="equipos">Estadísticas Equipos</TabsTrigger>
          <TabsTrigger value="jugadores">Estadísticas Jugadores</TabsTrigger>
        </TabsList>

        <TabsContent value="clasificacion">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div className="lg:col-span-2">
              <Card>
                <CardHeader><CardTitle>Clasificación ({t.temporada_nombre})</CardTitle></CardHeader>
                <CardContent>
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead className="w-8">#</TableHead>
                        <TableHead>Equipo</TableHead>
                        <TableHead className="text-center">PJ</TableHead>
                        <TableHead className="text-center">V</TableHead>
                        <TableHead className="text-center">E</TableHead>
                        <TableHead className="text-center">D</TableHead>
                        <TableHead className="text-center">GF</TableHead>
                        <TableHead className="text-center">GA</TableHead>
                        <TableHead className="text-center">DG</TableHead>
                        <TableHead className="text-center font-bold">PTS</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {equipos.map((eq, idx) => (
                        <TableRow key={eq.id ?? idx} className={idx === 0 ? 'bg-green-500/10' : ''}>
                          <TableCell className="font-bold">{idx + 1}</TableCell>
                          <TableCell>
                            <div className="flex items-center gap-2">
                              {eq.escudo && <img src={`/storage/images/${eq.escudo}`} alt={eq.nombre_mostrar} className="h-6 w-6 object-contain" onError={e => { (e.currentTarget as HTMLImageElement).src = '/storage/images/nologo.png'; }} />}
                              <span className="font-medium">{eq.nombre_mostrar}</span>
                            </div>
                          </TableCell>
                          <TableCell className="text-center">{eq.played}</TableCell>
                          <TableCell className="text-center text-green-600">{eq.wins}</TableCell>
                          <TableCell className="text-center text-yellow-600">{eq.draws}</TableCell>
                          <TableCell className="text-center text-red-600">{eq.losses}</TableCell>
                          <TableCell className="text-center">{eq.gf}</TableCell>
                          <TableCell className="text-center">{eq.ga}</TableCell>
                          <TableCell className={`text-center font-medium ${((eq.gf - eq.ga) > 0) ? 'text-green-600' : (eq.gf - eq.ga) < 0 ? 'text-red-600' : ''}`}>
                            {(eq.gf - eq.ga) > 0 ? '+' : ''}{eq.gf - eq.ga}
                          </TableCell>
                          <TableCell className="text-center font-bold">{eq.puntos}</TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </CardContent>
              </Card>
            </div>
            <div className="lg:col-span-1">
              <Card>
                <CardHeader><CardTitle>Partidos ({t.temporada_nombre})</CardTitle></CardHeader>
                <CardContent>
                  <div className="flex flex-col gap-2">
                    {slice.map((p: any) => {
                      const esLocal = (p.partido_como_local ?? p.es_local ?? 1) === 1;
                      const equipoNombre = p.equipo?.nombre ?? p.equipo_nombre ?? p.home_name ?? (esLocal ? p.equipo_id : p.visitante_id);
                      const rivalNombre = p.rival?.nombre ?? p.visitante_nombre ?? p.away_name ?? (esLocal ? p.visitante_id : p.equipo_id);
                      const gLocal = esLocal ? (p.goles_equipo ?? p.goles_local ?? p.goles_home) : (p.goles_rival ?? p.goles_away);
                      const gVis = esLocal ? (p.goles_rival ?? p.goles_away) : (p.goles_equipo ?? p.goles_home ?? p.goles_local);
                      const diff = (p.valor_equipo ?? 0) - (p.valor_rival ?? 0);
                      const isV = (gLocal ?? 0) > (gVis ?? 0);
                      const isE = (gLocal ?? 0) === (gVis ?? 0);
                      const badgeColor = diff <= -8 ? 'bg-orange-600' : diff <= -3 ? 'bg-orange-400' : diff <= 3 ? 'bg-yellow-400' : diff <= 8 ? 'bg-green-400' : 'bg-emerald-500';
                      const badgeIcon = diff <= -8 ? '😰' : diff <= -3 ? '😟' : diff <= 3 ? '😐' : diff <= 8 ? '🙂' : '🏆';
                      return (
                        <div key={p.id} onClick={() => window.location.href = `/partidos/${p.id}`} className="flex items-center gap-1.5 p-1.5 border rounded hover:bg-muted/50 transition-colors text-sm cursor-pointer">
                          <span className="text-[12px]">{esLocal ? '🏠' : '✈️'}</span>
                          <div className="flex-1 flex items-center gap-2 min-w-0">
                            {esLocal ? (
                              <><span className="font-bold text-blue-700 truncate">{equipoNombre}</span><span className="text-muted-foreground text-[12px]">vs</span><span className="text-gray-400 truncate">{rivalNombre}</span></>
                            ) : (
                              <><span className="text-gray-400 truncate">{rivalNombre}</span><span className="text-muted-foreground text-[12px]">vs</span><span className="font-bold text-blue-700 truncate">{equipoNombre}</span></>
                            )}
                          </div>
                          <span className={`px-1 py-0.5 rounded text-[10px] font-medium text-white ${badgeColor}`}>{badgeIcon}</span>
                          <div className={`font-bold px-2 py-0.5 rounded whitespace-nowrap text-[12px] ${isV ? 'bg-green-500/20 text-green-600' : isE ? 'bg-yellow-500/20 text-yellow-600' : 'bg-red-500/20 text-red-600'}`}>
                            {gLocal ?? '-'}-{gVis ?? '-'}
                          </div>
                        </div>
                      );
                    })}
                  </div>
                  <div className="flex justify-between items-center mt-3">
                    <button className="btn btn-light" onClick={() => setPage(Math.max(1, page - 1))} disabled={page === 1}>Anterior</button>
                    <div>Page {page} / {totalPages}</div>
                    <button className="btn btn-light" onClick={() => setPage(Math.min(totalPages, page + 1))} disabled={page === totalPages}>Siguiente</button>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </TabsContent>

        <TabsContent value="equipos">
          {loadingEquipos && (
            <div className="py-8 text-center text-muted-foreground">Cargando estadísticas de equipos...</div>
          )}
          {!loadingEquipos && !equiposStatsMap && (
            <div className="py-8 text-center text-sm text-muted-foreground">
              Haz clic en esta pestaña para cargar las estadísticas de equipos.
              <div className="mt-3"><button className="btn btn-primary" onClick={() => handleTabChange('equipos', t.temporada_id)}>Cargar estadísticas</button></div>
            </div>
          )}
          {!loadingEquipos && equiposStatsMap && equiposStatsMap.estadisticas_equipos && (
            <div className="space-y-8">
              <div>
                <h3 className="text-xl font-semibold mb-4 text-green-700">⚡ ATAQUE</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <Card><CardHeader><CardTitle className="flex items-center gap-2"><Target className="h-5 w-5 text-green-600" />Top 5 Goles a Favor</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.goles_favor} field="goles_favor" label="GF" colorClass="text-green-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle className="flex items-center gap-2">Top 5 Tiros a Puerta Realizados</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.tiros_a_puerta_realizados} field="tiros_a_puerta_realizados" label="TPR" colorClass="text-blue-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle className="flex items-center gap-2">Top 5 Tiros Totales Realizados</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.tiros_realizados} field="tiros_realizados" label="TR" colorClass="text-orange-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle className="flex items-center gap-2">Menos Tiros Totales</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.tiros_realizados_min} field="tiros_realizados" label="TR" colorClass="text-cyan-700" /></CardContent></Card>
                </div>
              </div>

              <div>
                <h3 className="text-xl font-semibold mb-4 text-blue-700">🛡️ DEFENSA</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <Card><CardHeader><CardTitle>Top 5 Balones Ganados</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.balones_ganados_equipo} field="balones_ganados_equipo" label="BG" colorClass="text-green-700" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Menos Goles en Contra</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.goles_contra} field="goles_contra" label="GC" colorClass="text-red-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 5 Intercepciones</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.interceciones_equipo} field="interceciones_equipo" label="INT" colorClass="text-blue-700" /></CardContent></Card>
                </div>
              </div>

              <div>
                <h3 className="text-xl font-semibold mb-4 text-orange-700">💪 FÍSICO</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <Card><CardHeader><CardTitle>Top 5 Distancia Recorrida</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.distancia_recorrida} field="distancia_recorrida" label="Kms" colorClass="text-green-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 5 Pases</CardTitle></CardHeader><CardContent><TopEquiposTable data={equiposStatsMap.estadisticas_equipos.pases} field="pases" label="Pases" colorClass="text-purple-600" /></CardContent></Card>
                </div>
              </div>
            </div>
          )}
        </TabsContent>

        <TabsContent value="jugadores">
          {loadingJugadores && (
            <div className="py-8 text-center text-muted-foreground">Cargando estadísticas de jugadores...</div>
          )}
          {!loadingJugadores && !jugadoresStatsMap && (
            <div className="py-8 text-center text-sm text-muted-foreground">
              Haz clic en esta pestaña para cargar las estadísticas de jugadores.
              <div className="mt-3"><button className="btn btn-primary" onClick={() => handleTabChange('jugadores', t.temporada_id)}>Cargar estadísticas</button></div>
            </div>
          )}
          {!loadingJugadores && jugadoresStatsMap && (
            <div className="space-y-8">
              <div>
                <h3 className="text-xl font-semibold mb-4 text-green-600">⚽ OFENSIVA</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <Card><CardHeader><CardTitle>Top 10 Goleadores</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topGoleadores ?? []} field="goles" label="G" colorClass="text-green-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 10 Asistentes</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topAsistentes ?? []} field="asistencias" label="A" colorClass="text-blue-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 10 Tiros al Palo</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topTirosAlPalo ?? []} field="tiros_al_palo" label="Palo" colorClass="text-amber-700" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 10 Penalties Provocados</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topPenaltiesProvocados ?? []} field="penalties_provocados" label="PP" colorClass="text-blue-600" /></CardContent></Card>
                </div>
              </div>

              <div>
                <h3 className="text-xl font-semibold mb-4 text-purple-600">🎯 PASES Y POSESIÓN</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <Card><CardHeader><CardTitle>Top 10 Pasadores</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topPasadores ?? []} field="pases_exitosos" label="P" colorClass="text-purple-600" /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 10 Regates</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topRegates ?? []} field="regates_exitosos" label="R" colorClass="text-purple-600" /></CardContent></Card>
                </div>
              </div>

              <div>
                <h3 className="text-xl font-semibold mb-4 text-yellow-600">🏆 RENDIMIENTO</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <Card><CardHeader><CardTitle>Top 10 Rendimiento</CardTitle></CardHeader><CardContent><TopJugadoresTable data={(jugadoresStatsMap.topRendimiento ?? []).map((j: any) => ({...j, rendimiento_promedio: j.rendimiento_promedio}))} field="rendimiento_promedio" label="Prom" colorClass="text-yellow-600" format={(v: any) => v ? Number(v).toFixed(1) : '0'} /></CardContent></Card>
                  <Card><CardHeader><CardTitle>Top 10 Minutos</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresStatsMap.topMinutos ?? []} field="minutos_jugados" label="M" colorClass="text-orange-600" /></CardContent></Card>
                </div>
              </div>
            </div>
          )}
        </TabsContent>
      </Tabs>
    );
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={competicion?.nombre ?? "Competición"} />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div className="flex justify-between items-center mb-2">
          <div>
            <h1 className="text-2xl font-bold flex items-center gap-2">{competicion?.nombre}</h1>
          </div>
        </div>

        {resumenPorTemporada.length === 0 ? (
          <p className="text-gray-500">No hay temporadas registradas.</p>
        ) : temporadaSeleccionada ? (
          (() => {
            const t = resumenPorTemporada.find(r => r.temporada_id === temporadaSeleccionada);
            if (!t) return <p className="text-gray-500">Temporada no encontrada.</p>;
            return renderClasificacionYPartidos(t);
          })()
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {resumenPorTemporada.map((t) => (
              <Card key={t.temporada_id} className="h-full">
                <CardHeader className="pb-2">
                  <CardTitle className="text-xs font-medium text-muted-foreground">{t.temporada_nombre}</CardTitle>
                </CardHeader>
                <CardContent className="flex flex-col gap-3 h-56">
                  {t.sumatorio ? (
                    <div className="flex-1 overflow-auto">
                      <Table>
                        <TableHeader>
                          <TableRow>
                            <TableHead>PJ</TableHead>
                            <TableHead className="text-center">G</TableHead>
                            <TableHead className="text-center">E</TableHead>
                            <TableHead className="text-center">P</TableHead>
                            <TableHead className="text-center">GF</TableHead>
                            <TableHead className="text-center">GA</TableHead>
                            <TableHead className="text-center">DG</TableHead>
                          </TableRow>
                        </TableHeader>
                        <TableBody>
                          <TableRow className="border-t">
                            <TableCell className="font-bold">{t.sumatorio.partidos_jugados}</TableCell>
                            <TableCell className="text-center">{t.sumatorio.ganados}</TableCell>
                            <TableCell className="text-center">{t.sumatorio.empatados}</TableCell>
                            <TableCell className="text-center">{t.sumatorio.perdidos}</TableCell>
                            <TableCell className="text-center">{t.sumatorio.goles_equipo}</TableCell>
                            <TableCell className="text-center">{t.sumatorio.goles_visitante}</TableCell>
                            <TableCell className="text-center">{t.sumatorio.diferencia}</TableCell>
                          </TableRow>
                        </TableBody>
                      </Table>
                    </div>
                  ) : (
                    <div className="text-sm text-muted-foreground mb-3">Sin datos de partidos.</div>
                  )}
                  {competicion?.id ? (
                    <Link href={`/competiciones/${competicion.id}?temporada_id=${t.temporada_id}`} className="btn btn-light w-full">Ver</Link>
                  ) : (
                    <button className="btn w-full" disabled>Ver</button>
                  )}
                </CardContent>
              </Card>
            ))}
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default View;
