import React, { useMemo, useState } from "react";
import { Head } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { type BreadcrumbItem } from "@/types";
import { getStatsConfig, formatStatValue, getGroupOrder } from "@/lib/estadisticas";

interface Equipo {
  id: number;
  nombre: string;
  codigo: string;
  pais?: string | null;
  liga?: string | null;
  escudo?: string | null;
  estadio?: { nombre: string } | null;
  equipacion?: { nombre: string } | null;
}

interface PartidosStats {
  jugados: number;
  ganados: number;
  empatados: number;
  perdidos: number;
  goles_favor: number;
  goles_contra: number;
  diferencia: number;
}

interface JugadorResumen {
  id: number;
  nombre: string;
  posicion: string;
  dorsal: number | null;
  partidos: number;
  minutos_jugados: number;
  goles: number;
  asistencias: number;
  tarjetas_amarillas: number;
  tarjetas_rojas: number;
  tiros: number;
  tiros_a_puerta: number;
  tiros_al_palo: number;
  pases: number;
  pases_exitosos: number;
  entradas: number;
  entradas_exitosas: number;
  regates: number;
  regates_exitosos: number;
  posesion_ganada: number;
  posesion_perdida: number;
  faltas_cometidas: number;
  faltas_recibidas: number;
  fueras_de_juego: number;
  distancia_recorrida: number;
  posesion: number;
  rendimiento: number;
  mvps: number;
}

interface Props {
  equipo: Equipo;
  partidosStats: PartidosStats;
  partidosStatsPorCompeticion: PartidosStats[];
  jugadores: JugadorResumen[];
}

const View: React.FC<Props> = ({ equipo, partidosStats, partidosStatsPorCompeticion, jugadores }) => {
  const breadcrumbs: BreadcrumbItem[] = [
    { title: "Equipos", href: "/equipos" },
    { title: equipo.nombre, href: `/equipos/${equipo.id}` },
  ];

  type SortKey =
    | "nombre"
    | "posicion"
    | "dorsal"
    | "partidos"
    | "minutos_jugados"
    | "goles"
    | "asistencias"
    | "tarjetas_amarillas"
    | "tarjetas_rojas"
    | "tiros"
    | "tiros_a_puerta"
    | "pases"
    | "pases_exitosos"
    | "entradas"
    | "entradas_exitosas"
    | "regates"
    | "regates_exitosos"
    | "rendimiento"
    | "mvps";

  const [sortKey, setSortKey] = useState<SortKey>("nombre");
  const [sortDir, setSortDir] = useState<"asc" | "desc">("asc");

  const toggleSort = (key: SortKey) => {
    if (key === sortKey) {
      setSortDir(sortDir === "asc" ? "desc" : "asc");
      return;
    }
    setSortKey(key);
    setSortDir("asc");
  };

  const sortedJugadores = useMemo(() => {
    const data = [...jugadores];
    data.sort((a, b) => {
      const aValue = a[sortKey];
      const bValue = b[sortKey];

      let aNum = Number(aValue);
      let bNum = Number(bValue);

      // Si ambos son números válidos (no NaN), compara numéricamente
      if (!isNaN(aNum) && !isNaN(bNum)) {
        return sortDir === "asc" ? aNum - bNum : bNum - aNum;
      }

      // Si no, compara como strings
      const aStr = String(aValue ?? "").toLowerCase();
      const bStr = String(bValue ?? "").toLowerCase();

      if (aStr < bStr) return sortDir === "asc" ? -1 : 1;
      if (aStr > bStr) return sortDir === "asc" ? 1 : -1;
      return 0;
    });
    return data;
  }, [jugadores, sortKey, sortDir]);

  const sortLabel = (label: string, key: SortKey) => {
    if (sortKey !== key) return label;
    return `${label} ${sortDir === "asc" ? "↑" : "↓"}`;
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Equipo ${equipo.nombre}`} />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div className="flex items-center gap-4">
          {equipo.escudo ? (
            <img
              src={`/storage/images/${equipo.escudo}`}
              alt={`Escudo ${equipo.nombre}`}
              className="w-14 h-14 object-contain"
            />
          ) : null}
          <div>
            <h1 className="text-2xl font-bold">{equipo.nombre}</h1>
            <p className="text-sm text-muted-foreground">
              {equipo.codigo}
              {equipo.pais ? ` · ${equipo.pais}` : ""}
              {equipo.liga ? ` · ${equipo.liga}` : ""}
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <Card className="md:col-span-1">
            <CardHeader>
              <CardTitle>Estadística General</CardTitle>
            </CardHeader>
            <CardContent className="space-y-2 text-sm">
              <p><strong>Jugados:</strong> {partidosStats.jugados ?? 0}</p>
              <p><strong>Ganados:</strong> {partidosStats.ganados ?? 0}</p>
              <p><strong>Empatados:</strong> {partidosStats.empatados ?? 0}</p>
              <p><strong>Perdidos:</strong> {partidosStats.perdidos ?? 0}</p>
              <p><strong>Goles a favor:</strong> {partidosStats.goles_favor ?? 0}</p>
              <p><strong>Goles en contra:</strong> {partidosStats.goles_contra ?? 0}</p>
              <p><strong>Diferencia:</strong> {partidosStats.diferencia ?? 0}</p>
              <hr className="my-2" />
              <p><strong>Estadio:</strong> {equipo.estadio?.nombre ?? "-"}</p>
              <p><strong>Equipación:</strong> {equipo.equipacion?.nombre ?? "-"}</p>
            </CardContent>
          </Card>

          <Card className="md:col-span-2">
            <CardHeader>
              <CardTitle>Estadística por Competición</CardTitle>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Competición</TableHead>
                    <TableHead>PJ</TableHead>
                    <TableHead>G</TableHead>
                    <TableHead>E</TableHead>
                    <TableHead>P</TableHead>
                    <TableHead>GF</TableHead>
                    <TableHead>GC</TableHead>
                    <TableHead>Dif</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {partidosStatsPorCompeticion && partidosStatsPorCompeticion.length > 0 ? (
                    partidosStatsPorCompeticion.map((stat: any, idx) => (
                      <TableRow key={idx}>
                        <TableCell>{stat.competicion || "-"}</TableCell>
                        <TableCell>{stat.jugados ?? 0}</TableCell>
                        <TableCell>{stat.ganados ?? 0}</TableCell>
                        <TableCell>{stat.empatados ?? 0}</TableCell>
                        <TableCell>{stat.perdidos ?? 0}</TableCell>
                        <TableCell>{stat.goles_favor ?? 0}</TableCell>
                        <TableCell>{stat.goles_contra ?? 0}</TableCell>
                        <TableCell>{stat.diferencia ?? 0}</TableCell>
                      </TableRow>
                    ))
                  ) : (
                    <TableRow>
                      <TableCell colSpan={8} className="text-center text-muted-foreground">
                        No hay competiciones registradas.
                      </TableCell>
                    </TableRow>
                  )}
                </TableBody>
              </Table>
            </CardContent>
          </Card>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Plantilla y Estadísticas</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-6">
              {jugadores.length > 0 ? (
                jugadores.map((j) => {
                  const statsConfig = getStatsConfig(j.posicion);
                  const groupOrder = getGroupOrder();
                  
                  return (
                    <div key={j.id} className="border rounded-lg p-4 space-y-3">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                          <div className="font-bold text-lg min-w-8 h-8 flex items-center justify-center bg-primary text-primary-foreground rounded">
                            {j.dorsal ?? "-"}
                          </div>
                          <div>
                            <h3 className="font-bold text-base">{j.nombre}</h3>
                            <p className="text-sm text-muted-foreground">{j.posicion}</p>
                          </div>
                        </div>
                        <div className="text-right">
                          <p className="text-xs text-muted-foreground">PJ / Min</p>
                          <p className="text-sm font-semibold">{j.partidos ?? 0} / {j.minutos_jugados ?? 0}</p>
                        </div>
                      </div>
                      
                      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 pt-2 border-t">
                        {groupOrder.map((groupKey) => {
                          const group = statsConfig[groupKey];
                          return (
                            <div key={groupKey} className="space-y-1">
                              <p className="text-xs font-semibold text-muted-foreground uppercase">{group.title}</p>
                              {group.stats.map((stat) => (
                                <div key={stat.field} className="text-xs">
                                  <span className="text-muted-foreground">{stat.label}:</span>
                                  <span className="ml-1 font-semibold">
                                    {formatStatValue((j as any)[stat.field], stat.format)}
                                  </span>
                                </div>
                              ))}
                            </div>
                          );
                        })}
                      </div>
                    </div>
                  );
                })
              ) : (
                <div className="text-center py-8 text-muted-foreground">
                  No hay jugadores en la plantilla.
                </div>
              )}
            </div>
            </CardContent>
          </Card>
      </div>
    </AppLayout>
  );
};

export default View;
