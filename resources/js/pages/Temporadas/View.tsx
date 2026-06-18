import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableCell,
  TableBody,
} from "@/components/ui/table";
import ControlesPaginacion from "@/components/controles-paginacion";

interface EquipoStats {
  equipo: string;
  partidos_jugados: number;
  ganados: number;
  empatados: number;
  perdidos: number;
  goles_favor: number;
  goles_contra: number;
  diferencia_goles: number;
  puntos: number;
}

interface JugadorStats {
  jugador: string;
  equipo: string;
  goles?: number;
  asistencias?: number;
  tiros?: number;
  pases?: number;
  regates?: number;
  regates_exitosos?: number;
  entradas?: number;
  entradas_exitosas?: number;
  distancia_recorrida?: number;
  jugador_del_partido?: number;
  posesion_ganada?: number;
  posesion_perdida?: number;
  rendimiento?: number;
  partidos_jugados?: number;
  tarjetas_amarillas?: number;
  tarjetas_rojas?: number;
}

interface Partido {
  id: number;
  equipo: { nombre: string };
  rival: { nombre: string };
  goles_equipo: number;
  goles_rival: number;
}

interface Props {
  temporada: {
    id: number;
    nombre: string;
    partidos: Partido[];
  };
  estadisticas: EquipoStats[];
  goleadores: JugadorStats[];
  asistentes: JugadorStats[];
  tiros: JugadorStats[];
  pases: JugadorStats[];
  regates: JugadorStats[];
  entradas: JugadorStats[];
  pases_rendimiento: JugadorStats[];
  regates_rendimiento: JugadorStats[];
  entradas_rendimiento: JugadorStats[];
  distancia_recorrida: JugadorStats[];
  jugador_del_partido: JugadorStats[];
  posesion_ganada: JugadorStats[];
  posesion_perdida: JugadorStats[];
  tarjetas_amarillas: JugadorStats[];
  tarjetas_rojas: JugadorStats[];
  rendimiento: JugadorStats[];
  ligasRankings: {
    [key: string]: {
      liga_nombre: string;
      goles: Array<{ equipo: string; goles_favor: number }>;
      pases: Array<{ equipo: string; pases: number }>;
      penalties_favor: Array<{ equipo: string; penalties: number }>;
      penalties_contra: Array<{ equipo: string; penalties_en_contra: number }>;
      goles_encajados: Array<{ equipo: string; goles_encajados: number }>;
      balones_recuperados: Array<{ equipo: string; balones_recuperados: number }>;
      balones_perdidos: Array<{ equipo: string; balones_perdidos: number }>;
    };
  };
}

const Index: React.FC<Props> = ({
  temporada,
  estadisticas,
  goleadores,
  asistentes,
  tiros,
  pases,
  regates,
  entradas,
  pases_rendimiento,
  regates_rendimiento,
  entradas_rendimiento,
  tarjetas_rojas,
  tarjetas_amarillas,
  distancia_recorrida,
  jugador_del_partido,
  posesion_ganada,
  posesion_perdida,
  rendimiento,
  ligasRankings
}) => {

  // Paginación para partidos
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 20;
  const totalPages = Math.ceil(temporada.partidos.length / itemsPerPage);
  const paginated = temporada.partidos.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );
const getDifficultyClass = (valoracion: number) => {
if (valoracion >= 95) return "bg-red-600 text-center font-bold w-auto";
if (valoracion >= 90) return "bg-red-500 text-center font-bold w-auto";
if (valoracion >= 85) return "bg-orange-500 text-center font-bold w-auto";
if (valoracion >= 80) return "bg-orange-400 text-center font-bold w-auto";
if (valoracion >= 75) return "bg-yellow-400 text-center font-bold w-auto";
if (valoracion >= 70) return "bg-yellow-300 text-center font-bold w-auto";
if (valoracion >= 65) return "bg-green-400 text-center font-bold w-auto";
if (valoracion >= 60) return "bg-green-300 text-center font-bold w-auto";
return "bg-green-200";
};
  return (
    <AppLayout breadcrumbs={[{ title: "Temporadas", href: "/temporadas" }]}>
      <Head title={`Temporada ${temporada.id}`} />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-3xl font-bold">{temporada.nombre}</h1>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

          {/* Clasificación de equipos */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Clasificación de equipos</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Pos</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>PJ</TableHead>
                    <TableHead>G</TableHead>
                    <TableHead>E</TableHead>
                    <TableHead>P</TableHead>
                    <TableHead>GF</TableHead>
                    <TableHead>GC</TableHead>
                    <TableHead>DG</TableHead>
                    <TableHead>Pts</TableHead>
                    <TableHead>VAL</TableHead>
                    <TableHead>PUN</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {estadisticas.map((e, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="p-2 border"><img src={`/storage/images/${e.escudo}`} alt="escudo"  className="w-6 h-6 object-contain mx-auto"/></TableCell>
                      <TableCell>{e.partidos_jugados}</TableCell>
                      <TableCell>{e.ganados}</TableCell>
                      <TableCell>{e.empatados}</TableCell>
                      <TableCell>{e.perdidos}</TableCell>
                      <TableCell>{e.goles_favor}</TableCell>
                      <TableCell>{e.goles_contra}</TableCell>
                      <TableCell>{e.diferencia_goles}</TableCell>
                      <TableCell className="font-bold">{e.puntos}</TableCell>
                      <TableCell>{(e.valoracion/e.partidos_jugados).toFixed(1)}</TableCell>
                      <TableCell className="font-bold">{e.puntuacion}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Clasificación de equipos */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Estadísticas de equipos</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Pos</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>T</TableHead>
                    <TableHead>TP</TableHead>
                    <TableHead>P</TableHead>
                    <TableHead>PC</TableHead>
                    <TableHead>%P</TableHead>
                    <TableHead>E</TableHead>
                    <TableHead>EE</TableHead>
                    <TableHead>%E</TableHead>
                    <TableHead>POS</TableHead>
                    <TableHead>KM</TableHead>
                    <TableHead>TA</TableHead>
                    <TableHead>TR</TableHead>
                    <TableHead>PN</TableHead>
                    <TableHead>TR</TableHead>
                    <TableHead>TPR</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {estadisticas.map((e, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="p-2 border"><img src={`/storage/images/${e.escudo}`} alt="escudo"  className="w-6 h-6 object-contain mx-auto"/></TableCell>
                      <TableCell>{e.tiros}</TableCell>
                      <TableCell>{e.tiros_a_puerta}</TableCell>
                      <TableCell>{e.pases}</TableCell>
                      <TableCell>{e.pases_correctos}</TableCell>
                      <TableCell>{100*(e.pases_correctos/e.pases).toFixed(1)}</TableCell>
                      <TableCell>{e.entradas}</TableCell>
                      <TableCell>{e.entradas_correctas}</TableCell>
                      <TableCell>{100*(e.entradas_correctas/e.entradas).toFixed(1)}</TableCell>
                      <TableCell>{(e.posesion/e.partidos_jugados).toFixed(1)}</TableCell>
                      <TableCell>{e.distancia_recorrida.toFixed(1)}</TableCell>
                      <TableCell>{e.tarjetas_amarillas}</TableCell>
                      <TableCell>{e.tarjetas_rojas}</TableCell>
                      <TableCell>{e.penalties}</TableCell>
                      <TableCell>{e.tiros_r}</TableCell>
                      <TableCell>{e.tiros_a_puerta_r}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Card de Partidos */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Partidos</CardTitle>
            </CardHeader>
            <CardContent>
              {paginated.length > 0 ? (
                <>
                  <Table className="w-full">
                    <TableHeader>
                      <TableRow className="bg-gray-200">
                        <TableHead className="p-2 border text-center">Jornada</TableHead>
                        <TableHead className="p-2 border text-right">Local</TableHead>
                        <TableHead className="p-2 border text-center">Goles</TableHead>
                        <TableHead className="p-2 border text-center">Goles</TableHead>
                        <TableHead className="p-2 border text-left">Visitante</TableHead>
                        <TableHead className="p-2 border text-center w-auto">Dificultad</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {paginated.map((row, i) => (
                        <TableRow
                          key={row.id}
                          className=""
                          onClick={() => router.get(`/partidos/${row.id}`)}
                        >
                          {row.partido_como_local === 1 ? (
                            <>
                              <TableCell className="p-2 text-center">{row.jornada}</TableCell>
                              <TableCell className="p-2 text-right"><img src={`/storage/images/${row.equipo.escudo}`} alt={row.equipo.codigo}  className="w-6 h-6 object-contain mx-auto"/></TableCell>
                              <TableCell className="p-2 text-center">{row.goles_equipo}</TableCell>
                              <TableCell className="p-2 text-center">{row.goles_rival}</TableCell>
                              <TableCell className="p-2 text-left"><img src={`/storage/images/ultimate.png`} alt={row.rival.codigo}  className="w-6 h-6 object-contain mx-auto"/></TableCell>
                              <TableCell className={getDifficultyClass(row.rival.valoracion)}>{row.rival.valoracion}</TableCell>
                            </> 
                          ) : (
                            <>
                              <TableCell className="p-2 text-center">{row.jornada}</TableCell>
                              <TableCell className="p-2 text-right "><img src={`/storage/images/ultimate.png`} alt={row.rival.codigo}   className="w-6 h-6 object-contain mx-auto"/></TableCell>
                              <TableCell className="p-2 text-center">{row.goles_rival}</TableCell>
                              <TableCell className="p-2 text-center">{row.goles_equipo}</TableCell>
                              <TableCell className="p-2 text-left"><img src={`/storage/images/${row.equipo.escudo}`} alt={row.equipo.codigo}   className="w-6 h-6 object-contain mx-auto"/></TableCell>  
                              <TableCell className={getDifficultyClass(row.rival.valoracion)}>{row.rival.valoracion}</TableCell>
                            </> 
                          )}
                         
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>

                  {totalPages > 1 && (
                    <ControlesPaginacion
                      currentPage={currentPage}
                      totalPages={totalPages}
                      onPageChange={(page) => setCurrentPage(page)}
                    />
                  )}
                </>
              ) : (
                <p>No hay partidos para esta temporada.</p>
              )}
            </CardContent>
          </Card>

          {/* MVP */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>MVP</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>MVP</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {jugador_del_partido.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.jugador_del_partido}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Mejores jugadores */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Mejores jugadores</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Rendimiento</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {rendimiento.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{(j.rendimiento! / j.partidos_jugados!).toFixed(1)}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Máximos goleadores */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Máximos goleadores</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Goles</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {goleadores.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.goles}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Máximos asistentes */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Máximos asistentes</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Asistencias</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {asistentes.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.asistencias}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Distancia recorrida */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Distancia Recorrida</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Distancia</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {distancia_recorrida.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{(j.distancia_recorrida!).toFixed(1)} Km</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Disparos */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Disparos</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Disparos</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {tiros.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.tiros}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Balones recuperados */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Balones recuperados</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Recuperaciones</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {posesion_ganada.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.posesion_ganada}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Balones perdidos */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Balones perdidos</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Perdidas</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {posesion_perdida.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.posesion_perdida}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Regates */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Regates</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Regates</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {regates.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.regates} ({(100*(j.regates_exitosos!/j.regates!)).toFixed(1)} %)</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Efectividad Regates */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Efectividad Regates</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Regates</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {regates_rendimiento.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{(100*(j.regates_exitosos!/j.regates!)).toFixed(1)} % ({j.regates}) </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Pases */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Pases</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Pases</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {pases.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.pases} ({(100*(j.pases_exitosos!/j.pases!)).toFixed(1)} %)</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Efectividad Pases */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Efectividad Pases</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Pases</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {pases_rendimiento.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{(100*(j.pases_exitosos!/j.pases!)).toFixed(1)} % ({j.pases}) </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Entradas */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Entradas</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Entradas</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {entradas.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.entradas} ({(100*(j.entradas_exitosas!/j.entradas!)).toFixed(1)} %)</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Efectividad Entradas */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Efectividad Entradas</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Entradas</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {entradas_rendimiento.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{(100*(j.entradas_exitosas!/j.entradas!)).toFixed(1)} % ({j.entradas}) </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Amarillas */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Tarjetas Amarillas</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>TA</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {tarjetas_amarillas.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.tarjetas_amarillas}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Rojas */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Tarjetas Rojas</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableCell>Pos</TableCell>
                    <TableHead>Jugador</TableHead>
                    <TableHead>Equipo</TableHead>
                    <TableHead>Rojas</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {tarjetas_rojas.map((j, i) => (
                    <TableRow key={i}>
                      <TableCell>{i+1}</TableCell>
                      <TableCell className="font-medium">{j.jugador}</TableCell>
                      <TableCell>{j.equipo}</TableCell>
                      <TableCell>{j.tarjetas_rojas} </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </CardContent>
          </Card>

          {/* Rankings por liga */}
          <Card className="shadow-lg">
            <CardHeader>
              <CardTitle>Rankings por liga</CardTitle>
            </CardHeader>
            <CardContent>
              {ligasRankings && Object.values(ligasRankings).map((liga: any, idx: number) => (
                <Card className="shadow-lg" key={idx}>
                  <CardHeader>
                    <CardTitle>Top equipos {liga.liga_nombre}</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <h3 className="font-bold">Goles a favor</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Goles</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.goles.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.goles_favor}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                      <div>
                        <h3 className="font-bold">Pases</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Pases</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.pases.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.pases}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                      <div>
                        <h3 className="font-bold">Penalties a favor</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Penalties</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.penalties_favor.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.penalties}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                      <div>
                        <h3 className="font-bold">Penalties en contra</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Penalties</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.penalties_contra.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.penalties_en_contra}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                      <div>
                        <h3 className="font-bold">Goles encajados</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Goles</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.goles_encajados.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.goles_encajados}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                      <div>
                        <h3 className="font-bold">Balones recuperados</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Recuperados</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.balones_recuperados.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.balones_recuperados}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                      <div>
                        <h3 className="font-bold">Balones perdidos</h3>
                        <Table>
                          <TableHeader>
                            <TableRow>
                              <TableHead>Equipo</TableHead>
                              <TableHead>Perdidos</TableHead>
                            </TableRow>
                          </TableHeader>
                          <TableBody>
                            {liga.balones_perdidos.map((e: any, i: number) => (
                              <TableRow key={i}>
                                <TableCell>{e.equipo}</TableCell>
                                <TableCell>{e.balones_perdidos}</TableCell>
                              </TableRow>
                            ))}
                          </TableBody>
                        </Table>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </CardContent>
          </Card>

        </div>
      </div>
    </AppLayout>
  );
};

export default Index;
