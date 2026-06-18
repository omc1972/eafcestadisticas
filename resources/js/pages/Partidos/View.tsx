import React from "react";
import { Head } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/components/ui/table";

interface Jugador {
  id: number;
  nombre: string;
}

interface Alineacion {
  id: number;
  jugador: Jugador;
  jugador_del_partido: boolean;
  tiros?: number;
  tiros_a_puerta?: number;
  tiros_al_palo?: number;
  pases?: number;
  pases_exitosos?: number;
  entradas?: number;
  entradas_exitosas?: number;
  regates?: number;
  regates_exitosos?: number;
  posesion_ganada?: number;
  posesion_perdida?: number;
  fueras_de_juego?: number;
  faltas_cometidas?: number;
  faltas_recibidas?: number;
  posesion?: number;
  distancia_recorrida?: number;
  rendimiento?: number;
}

interface TipoEvento {
  id: number;
  nombre: string;
}

interface Evento {
  id: number;
  jugador: Jugador;
  tipo_evento: TipoEvento;
  local_id?: number | null;
  visitante_id?: number | null;
  equipo_id?: number | null;
  rival_id?: number | null;
  minuto: number;
}

interface Equipo {
  id: number;
  nombre: string;
  codigo: string;
}

interface Partido {
  id: number;
  equipo: Equipo;
  rival: Equipo;
  visitante?: Equipo | null;
  goles_equipo: number;
  goles_rival: number;
  partido_como_local: number;
  valor_equipo?: number;
  valor_rival?: number;
  duracion: number; // duración del partido
  posesion_equipo?: number;
  posesion_rival?: number;
  delantera_equipo?: number;
  delantera_rival?: number;
  media_equipo?: number;
  media_rival?: number;
  defensa_equipo?: number;
  defensa_rival?: number;
  tiros_equipo?: number;
  tiros_rival?: number;
  tiros_a_puerta_equipo?: number;
  tiros_a_puerta_rival?: number;
  pases_equipo?: number;
  pases_rival?: number;
  porcentaje_pases_equipo?: number;
  porcentaje_pases_rival?: number;
  entradas_equipo?: number;
  entradas_rival?: number;
  entradas_equipo_completadas?: number;
  entradas_rival_completadas?: number;
  faltas_equipo?: number;
  faltas_rival?: number;
  tarjetas_amarillas_equipo?: number;
  tarjetas_amarillas_rival?: number;
  tarjetas_rojas_equipo?: number;
  tarjetas_rojas_rival?: number;
  corners_equipo?: number;
  corners_rival?: number;
  minutos_jugados?: number;
  alineaciones: Alineacion[];
  alineaciones_locales: Alineacion[];
  alineaciones_visitantes: Alineacion[];
  eventos: Evento[];
}

interface Props {
  partido: Partido;
  alineaciones_locales?: Alineacion[];
  alineaciones_visitantes?: Alineacion[];
}

const View: React.FC<Props> = ({ partido, alineaciones_locales: alineacionesLocalesProp, alineaciones_visitantes: alineacionesVisitantesProp }) => {
  // Función para obtener el icono del evento
  const getEventoIcono = (tipoEvento: string) => {
    const tipo = tipoEvento.toLowerCase();
    if (tipo.includes('gol')) return '⚽';
    if (tipo.includes('asistencia')) return '🅰️';
    if (tipo.includes('entra')) return '🔼';
    if (tipo.includes('sale')) return '🔽';
    if (tipo.includes('ta realizada')) return '🟨';
    if (tipo.includes('tr realizada')) return '🟥';
    if (tipo.includes('ta provocada')) return '⚠️';
    if (tipo.includes('penalty provocado')) return '🎯';
    if (tipo.includes('penalty parado')) return '🧤';
    return '📌';
  };

  // Determinar equipos y goles según partido_como_local
  const esLocal = partido.partido_como_local === 1;
  const visitante = partido.visitante ?? partido.rival;
  const equipoLocal = esLocal ? partido.equipo : visitante;
  const equipoVisitante = esLocal ? visitante : partido.equipo;
  const eventos = partido.eventos ?? [];
  const alineaciones_locales = alineacionesLocalesProp ?? (partido.alineaciones_locales ?? []);
  const alineaciones_visitantes = alineacionesVisitantesProp ?? (partido.alineaciones_visitantes ?? []);
  const golesLocal = esLocal ? partido.goles_equipo : partido.goles_rival;
  const golesVisitante = esLocal ? partido.goles_rival : partido.goles_equipo;

  // Obtener goles con sus asistencias
  const golesEvento = eventos.filter(e => (e.tipo_evento?.nombre ?? '').toLowerCase() === 'gol');
  const asistenciasEvento = eventos.filter(e => (e.tipo_evento?.nombre ?? '').toLowerCase() === 'asistencia');
  
  const golesConAsistencias = golesEvento.map(gol => {
    const asistencia = asistenciasEvento.find(a => a.minuto === gol.minuto);
    const esGolEquipo = gol.equipo_id === (partido.equipo?.id ?? null);
    const esGolLocal = esLocal ? esGolEquipo : !esGolEquipo;
    return {
      minuto: gol.minuto,
      jugador: gol.jugador?.nombre ?? 'Desconocido',
      asistente: asistencia ? (asistencia.jugador?.nombre ?? null) : null,
      esLocal: esGolLocal
    };
  });

  const golesLocalArray = golesConAsistencias.filter(g => g.esLocal);
  const golesVisitanteArray = golesConAsistencias.filter(g => !g.esLocal);

  // Contar eventos por jugador
  const contarEventos = (tipo: string, jugadorId: number) =>
    eventos.filter(
      (e) =>
        (e.tipo_evento?.nombre ?? '').toLowerCase() === tipo.toLowerCase() &&
        (e.jugador?.id ?? null) === jugadorId
    ).length;

  const calcularMinutos = (jugadorId: number) => {
    const entradaEvento = eventos.find(
      (e) => (e.jugador?.id ?? null) === jugadorId && (e.tipo_evento?.nombre ?? '').toLowerCase() === "entra"
    );
    const salidaEvento = eventos.find(
      (e) => (e.jugador?.id ?? null) === jugadorId && (e.tipo_evento?.nombre ?? '').toLowerCase() === "sale"
    );

    const minutoEntrada = entradaEvento ? entradaEvento.minuto : 0;
    const minutoSalida = salidaEvento ? salidaEvento.minuto : partido.minutos_jugados ?? 90;

    return minutoSalida - minutoEntrada;
  };

  return (
    <AppLayout breadcrumbs={[{ title: "Partidos", href: "/partidos" }]}>
      <Head title={`${equipoLocal?.nombre ?? 'Local'} vs ${equipoVisitante?.nombre ?? 'Visitante'}`} />
      <div className="flex flex-col gap-4 p-4">
        
        {/* Marcador Principal */}
        <Card>
          <CardContent className="py-6">
            <div className="flex items-center justify-between mb-6">
              <div className="flex-1 text-center">
                <h2 className="text-2xl font-bold">{equipoLocal.nombre}</h2>
                <span className="text-sm text-muted-foreground">🏠 Local</span>
              </div>
              <div className="text-center px-8">
                <div className="text-5xl font-bold flex items-center gap-4">
                  <span className={golesLocal > golesVisitante ? 'text-green-600' : ''}>{golesLocal}</span>
                  <span className="text-2xl text-muted-foreground">-</span>
                  <span className={golesVisitante > golesLocal ? 'text-green-600' : ''}>{golesVisitante}</span>
                </div>
              </div>
              <div className="flex-1 text-center">
                <h2 className="text-2xl font-bold">{equipoVisitante.nombre}</h2>
                <span className="text-sm text-muted-foreground">✈️ Visitante</span>
              </div>
            </div>
            
            {/* Goles */}
            <div className="grid grid-cols-[1fr_auto_1fr] gap-8 border-t pt-4">
              {/* Goles Local */}
              <div className="space-y-1 text-sm">
                {golesLocalArray.map((gol, idx) => (
                  <div key={idx} className="text-right">
                    <span className="font-semibold">{gol.jugador}</span>
                    <span className="font-mono text-muted-foreground ml-2">{gol.minuto}'</span>
                    {gol.asistente && (
                      <div className="text-xs text-muted-foreground">(asist: {gol.asistente})</div>
                    )}
                  </div>
                ))}
              </div>
              
              {/* Separador central */}
              <div className="w-px bg-border"></div>
              
              {/* Goles Visitante */}
              <div className="space-y-1 text-sm">
                {golesVisitanteArray.map((gol, idx) => (
                  <div key={idx} className="text-left">
                    <span className="font-mono text-muted-foreground mr-2">{gol.minuto}'</span>
                    <span className="font-semibold">{gol.jugador}</span>
                    {gol.asistente && (
                      <div className="text-xs text-muted-foreground">(asist: {gol.asistente})</div>
                    )}
                  </div>
                ))}
              </div>
            </div>
          </CardContent>
        </Card>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-4">
          
          {/* Resumen del partido */}
          <div className="lg:col-span-1 space-y-2">
            <Card>
              <CardHeader>
                <div className="flex items-center justify-between text-sm">
                  <span className="font-bold">{equipoLocal.codigo} <span className="text-muted-foreground">({esLocal ? partido.valor_equipo ?? 0 : partido.valor_rival ?? 0})</span></span>
                  <CardTitle className="text-base">Estadísticas</CardTitle>
                  <span className="font-bold">{equipoVisitante.codigo} <span className="text-muted-foreground">({esLocal ? partido.valor_rival ?? 0 : partido.valor_equipo ?? 0})</span></span>
                </div>
              </CardHeader>
              <CardContent className="space-y-3">
                {/* Posesión */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Posesión</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.posesion_equipo ?? 0 : partido.posesion_rival ?? 0}%</span>
                    <span>{esLocal ? partido.posesion_rival ?? 0 : partido.posesion_equipo ?? 0}%</span>
                  </div>
                </div>
                
                {/* Tiros */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Tiros</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.tiros_equipo ?? 0 : partido.tiros_rival ?? 0}</span>
                    <span>{esLocal ? partido.tiros_rival ?? 0 : partido.tiros_equipo ?? 0}</span>
                  </div>
                </div>
                
                {/* Tiros a puerta */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Tiros a puerta</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.tiros_a_puerta_equipo ?? 0 : partido.tiros_a_puerta_rival ?? 0}</span>
                    <span>{esLocal ? partido.tiros_a_puerta_rival ?? 0 : partido.tiros_a_puerta_equipo ?? 0}</span>
                  </div>
                </div>
                
                {/* Pases */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Pases</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.pases_equipo ?? 0 : partido.pases_rival ?? 0}</span>
                    <span>{esLocal ? partido.pases_rival ?? 0 : partido.pases_equipo ?? 0}</span>
                  </div>
                </div>
                
                {/* % Pases */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">% Pases</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.porcentaje_pases_equipo ?? 0 : partido.porcentaje_pases_rival ?? 0}%</span>
                    <span>{esLocal ? partido.porcentaje_pases_rival ?? 0 : partido.porcentaje_pases_equipo ?? 0}%</span>
                  </div>
                </div>
                
                {/* Entradas */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Entradas</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.entradas_equipo_completadas ?? 0 : partido.entradas_rival_completadas ?? 0}</span>
                    <span>{esLocal ? partido.entradas_rival_completadas ?? 0 : partido.entradas_equipo_completadas ?? 0}</span>
                  </div>
                </div>
                
                {/* Faltas */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Faltas</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.faltas_equipo ?? 0 : partido.faltas_rival ?? 0}</span>
                    <span>{esLocal ? partido.faltas_rival ?? 0 : partido.faltas_equipo ?? 0}</span>
                  </div>
                </div>
                
                {/* Tarjetas */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">TA / TR</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.tarjetas_amarillas_equipo ?? 0 : partido.tarjetas_amarillas_rival ?? 0} / {esLocal ? partido.tarjetas_rojas_equipo ?? 0 : partido.tarjetas_rojas_rival ?? 0}</span>
                    <span>{esLocal ? partido.tarjetas_amarillas_rival ?? 0 : partido.tarjetas_amarillas_equipo ?? 0} / {esLocal ? partido.tarjetas_rojas_rival ?? 0 : partido.tarjetas_rojas_equipo ?? 0}</span>
                  </div>
                </div>
                
                {/* Corners */}
                <div className="border-b pb-2">
                  <div className="text-xs text-center text-muted-foreground mb-1">Corners</div>
                  <div className="flex items-center justify-between text-sm font-semibold">
                    <span>{esLocal ? partido.corners_equipo ?? 0 : partido.corners_rival ?? 0}</span>
                    <span>{esLocal ? partido.corners_rival ?? 0 : partido.corners_equipo ?? 0}</span>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Eventos del partido en formato timeline */}
          <div className="lg:col-span-3">
            <Card>
              <CardHeader>
                <CardTitle>Eventos del Partido</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-1">
                  {eventos.filter(e => {
                    const tipo = (e.tipo_evento?.nombre ?? '').toLowerCase();
                    return tipo !== 'gol' && tipo !== 'asistencia';
                  }).length > 0 ? (
                    eventos
                      .filter(e => {
                        const tipo = (e.tipo_evento?.nombre ?? '').toLowerCase();
                        return tipo !== 'gol' && tipo !== 'asistencia';
                      })
                      .map((evento) => {
                      const ladoLocalId = evento.local_id ?? evento.equipo_id;
                      const esEventoEquipo = ladoLocalId === (partido.equipo?.id ?? null);
                      const esEventoLocal = esLocal ? esEventoEquipo : !esEventoEquipo;

                      return (
                        <div key={evento.id} className="grid grid-cols-[1fr_60px_1fr] gap-4 items-center py-2 border-b hover:bg-muted/30">
                          {/* Columna Izquierda - Equipo Local */}
                          {esEventoLocal ? (
                            <div className="flex items-center gap-2 justify-end">
                              <span className="text-sm">{evento.jugador?.nombre ?? 'Desconocido'}</span>
                              <span className="text-lg">{getEventoIcono(evento.tipo_evento.nombre)}</span>
                            </div>
                          ) : <div></div>}
                          
                          {/* Columna Central - Minuto */}
                          <div className="text-center">
                            <span className="font-bold text-sm bg-muted px-2 py-1 rounded">{evento.minuto}'</span>
                          </div>
                          
                          {/* Columna Derecha - Equipo Visitante */}
                          {!esEventoLocal ? (
                            <div className="flex items-center gap-2">
                              <span className="text-lg">{getEventoIcono(evento.tipo_evento.nombre)}</span>
                              <span className="text-sm">{evento.jugador?.nombre ?? 'Desconocido'}</span>
                            </div>
                          ) : <div></div>}
                        </div>
                      );
                    })
                  ) : (
                    <p className="text-center text-muted-foreground py-8">No hay eventos registrados</p>
                  )}
                </div>
              </CardContent>
            </Card>
          </div>
        </div>

        {/* Tabla alineaciones */}
        <Card>
          <CardHeader>
            <CardTitle>Alineaciones y Estadísticas Detalladas</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {/* Alineación Local */}
              <div>
                <h3 className="text-lg font-bold mb-4">🏠 Alineación Local - {equipoLocal.nombre}</h3>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Jugador</TableHead>
                      <TableHead>Min</TableHead>
                      <TableHead>G</TableHead>
                      <TableHead>A</TableHead>
                      <TableHead>T</TableHead>
                      <TableHead>TP</TableHead>
                      <TableHead>P</TableHead>
                      <TableHead>E</TableHead>
                      <TableHead>R</TableHead>
                      <TableHead>Ren</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {alineaciones_locales
                      .map((a) => {
                      const jugadorId = a.jugador?.id ?? null;
                      const minutosJugados = jugadorId ? calcularMinutos(jugadorId) : 0;
                      const goles = jugadorId ? contarEventos("gol", jugadorId) : 0;
                      const asistencias = jugadorId ? contarEventos("asistencia", jugadorId) : 0;

                      return (
                        <TableRow key={a.id} className={a.jugador_del_partido ? "font-bold bg-yellow-50" : ""}>
                          <TableCell>
                            {a.jugador_del_partido ? (
                              <>
                                <span className="text-yellow-500 mr-1">⭐</span>
                                {a.jugador?.nombre ?? 'Desconocido'}
                              </>
                            ) : (
                              a.jugador?.nombre ?? 'Desconocido'
                            )}
                          </TableCell>
                          <TableCell>{minutosJugados}</TableCell>
                          <TableCell>{goles}</TableCell>
                          <TableCell>{asistencias}</TableCell>
                          <TableCell>{a.tiros ?? 0}</TableCell>
                          <TableCell>{a.tiros_a_puerta ?? 0}</TableCell>
                          <TableCell>{a.pases ?? 0}</TableCell>
                          <TableCell>{a.entradas ?? 0}</TableCell>
                          <TableCell>{a.regates ?? 0}</TableCell>
                          <TableCell>{a.rendimiento ?? 0}</TableCell>
                        </TableRow>
                      );
                    })}
                  </TableBody>
                </Table>
              </div>

              {/* Alineación Visitante */}
              <div>
                <h3 className="text-lg font-bold mb-4">✈️ Alineación Visitante - {equipoVisitante.nombre}</h3>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Jugador</TableHead>
                      <TableHead>Min</TableHead>
                      <TableHead>G</TableHead>
                      <TableHead>A</TableHead>
                      <TableHead>T</TableHead>
                      <TableHead>TP</TableHead>
                      <TableHead>P</TableHead>
                      <TableHead>E</TableHead>
                      <TableHead>R</TableHead>
                      <TableHead>Ren</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {alineaciones_visitantes
                      .map((a) => {
                      const jugadorId = a.jugador?.id ?? null;
                      const minutosJugados = jugadorId ? calcularMinutos(jugadorId) : 0;
                      const goles = jugadorId ? contarEventos("gol", jugadorId) : 0;
                      const asistencias = jugadorId ? contarEventos("asistencia", jugadorId) : 0;

                      return (
                        <TableRow key={a.id} className={a.jugador_del_partido ? "font-bold bg-blue-50" : ""}>
                          <TableCell>
                            {a.jugador_del_partido ? (
                              <>
                                <span className="text-yellow-500 mr-1">⭐</span>
                                {a.jugador?.nombre ?? 'Desconocido'}
                              </>
                            ) : (
                              a.jugador?.nombre ?? 'Desconocido'
                            )}
                          </TableCell>
                          <TableCell>{minutosJugados}</TableCell>
                          <TableCell>{goles}</TableCell>
                          <TableCell>{asistencias}</TableCell>
                          <TableCell>{a.tiros ?? 0}</TableCell>
                          <TableCell>{a.tiros_a_puerta ?? 0}</TableCell>
                          <TableCell>{a.pases ?? 0}</TableCell>
                          <TableCell>{a.entradas ?? 0}</TableCell>
                          <TableCell>{a.regates ?? 0}</TableCell>
                          <TableCell>{a.rendimiento ?? 0}</TableCell>
                        </TableRow>
                      );
                    })}
                  </TableBody>
                </Table>
              </div>
            </div>
            </CardContent>
          </Card>
        
      </div>
    </AppLayout>
  );
};

export default View;
