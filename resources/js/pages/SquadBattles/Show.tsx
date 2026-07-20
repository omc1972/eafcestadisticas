import React, { useState, useRef } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from "@/components/ui/table";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Trophy, Users, TrendingUp } from "lucide-react";

interface Equipo { id: number; nombre: string; codigo?: string; escudo?: string }
interface DivisionEquipo { id: number; orden: number; equipo: Equipo }
interface Division { id: number; nombre: string; orden: number; rivales: DivisionEquipo[] }
interface SquadBattle { id: number; nombre: string; temporada: { nombre: string } | null; divisiones: Division[] }
interface Jugador { id: number; nombre: string; media: number; es_titular: boolean }
interface PlantillaInfo { id: number; equipo: Equipo; media_plantilla: number | null; media_alineacion: number | null; jugadores: Jugador[] }

interface Estadistica {
  equipo: Equipo;
  partidos_jugados: number;
  victorias: number;
  empates: number;
  derrotas: number;
  goles_favor: number;
  goles_contra: number;
  diferencia_goles: number;
  puntos: number;
  tarjetas_amarillas: number;
  tarjetas_rojas: number;
  posesion_promedio: number;
  tiros_al_palo: number;
  tiros_realizados: number;
  tiros_recibidos: number;
  pases: number;
  distancia_recorrida: number;
  balones_ganados_equipo: number;
  interceciones_equipo: number;
  penalties_equipo: number;
  penalties_rival: number;
}

interface DivisionData {
  estadisticas: Estadistica[];
  estadisticas_equipos: Record<string, Estadistica[]>;
  partidos: any[];
}

interface Props {
  squadBattle: SquadBattle;
  equipos: Equipo[];
  plantillasMap: Record<number, PlantillaInfo>;
  divisionesData: Record<number, DivisionData>;
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Dashboard", href: dashboard().url },
  { title: "Squad Battles", href: "/squad-battles" },
];

function shuffle<T>(arr: T[]): T[] {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

const EquipoTagSelector: React.FC<{
  disponibles: Equipo[];
  selected: Equipo[];
  onAdd: (eq: Equipo) => void;
  onRemove: (id: number) => void;
}> = ({ disponibles, selected, onAdd, onRemove }) => {
  const [query, setQuery] = useState("");
  const [focused, setFocused] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  const filtered = disponibles.filter(eq =>
    eq.nombre.toLowerCase().includes(query.toLowerCase())
  );
  const showDropdown = focused && filtered.length > 0;

  return (
    <div
      ref={containerRef}
      className="flex flex-col gap-2"
      onMouseDown={e => {
        if ((e.target as HTMLElement).tagName !== "INPUT") e.preventDefault();
      }}
    >
      {selected.length > 0 && (
        <div className="flex flex-wrap gap-1">
          {selected.map(eq => (
            <span key={eq.id} className="flex items-center gap-1 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
              {eq.nombre}
              <button type="button" onClick={() => onRemove(eq.id)} className="hover:text-red-200">×</button>
            </span>
          ))}
        </div>
      )}
      <div className="relative">
        <Input
          value={query}
          onChange={e => setQuery(e.target.value)}
          onFocus={() => setFocused(true)}
          onBlur={() => setFocused(false)}
          placeholder="Buscar equipo..."
          className="text-sm"
        />
        {showDropdown && (
          <ul className="absolute z-20 w-full mt-1 border rounded bg-background shadow-lg max-h-48 overflow-y-auto">
            {filtered.map(eq => (
              <li key={eq.id} onClick={() => { onAdd(eq); setQuery(""); }}
                className="px-3 py-2 text-sm cursor-pointer hover:bg-accent">
                {eq.nombre}
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
};

const TopEquiposTable: React.FC<{ data: Estadistica[]; field: keyof Estadistica; label: string; colorClass: string }> = ({ data, field, label, colorClass }) => (
  <Table className="text-xs">
    <TableHeader><TableRow><TableHead className="w-8">#</TableHead><TableHead>Equipo</TableHead><TableHead className="text-center">{label}</TableHead></TableRow></TableHeader>
    <TableBody>
      {data?.map((stat: Estadistica, idx: number) => (
        <TableRow key={stat.equipo.id}>
          <TableCell className="font-bold">{idx + 1}</TableCell>
          <TableCell>{stat.equipo.nombre}</TableCell>
          <TableCell className={`text-center font-bold ${colorClass}`}>{String(stat[field] ?? 0)}</TableCell>
        </TableRow>
      ))}
    </TableBody>
  </Table>
);

const ClasificacionTab: React.FC<{ estadisticas: Estadistica[] }> = ({ estadisticas }) => (
  <Card>
    <CardHeader><CardTitle>Tabla de Clasificación</CardTitle></CardHeader>
    <CardContent>
      <Table className="text-xs">
        <TableHeader>
          <TableRow>
            <TableHead className="w-10">#</TableHead>
            <TableHead>Equipo</TableHead>
            <TableHead className="text-center">PJ</TableHead>
            <TableHead className="text-center">V</TableHead>
            <TableHead className="text-center">E</TableHead>
            <TableHead className="text-center">D</TableHead>
            <TableHead className="text-center">GF</TableHead>
            <TableHead className="text-center">GC</TableHead>
            <TableHead className="text-center">DG</TableHead>
            <TableHead className="text-center font-bold">PTS</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {estadisticas.map((stat, index) => (
            <TableRow key={stat.equipo.id} className={index === 0 ? 'bg-green-500/10' : ''}>
              <TableCell className="font-bold">
                {index === 0 && <Trophy className="h-4 w-4 inline text-yellow-500 mr-1" />}
                {index + 1}
              </TableCell>
              <TableCell className="font-medium">{stat.equipo.nombre}</TableCell>
              <TableCell className="text-center">{stat.partidos_jugados}</TableCell>
              <TableCell className="text-center text-green-600">{stat.victorias}</TableCell>
              <TableCell className="text-center text-yellow-600">{stat.empates}</TableCell>
              <TableCell className="text-center text-red-600">{stat.derrotas}</TableCell>
              <TableCell className="text-center">{stat.goles_favor}</TableCell>
              <TableCell className="text-center">{stat.goles_contra}</TableCell>
              <TableCell className={`text-center font-medium ${stat.diferencia_goles > 0 ? 'text-green-600' : stat.diferencia_goles < 0 ? 'text-red-600' : ''}`}>
                {stat.diferencia_goles > 0 ? '+' : ''}{stat.diferencia_goles}
              </TableCell>
              <TableCell className="text-center font-bold text-lg">{stat.puntos}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </CardContent>
  </Card>
);

const EstadisticasEquiposTab: React.FC<{ stats: Record<string, Estadistica[]> }> = ({ stats }) => (
  <div className="space-y-8">
    <div>
      <h3 className="text-xl font-semibold mb-4 text-green-700">⚡ ATAQUE</h3>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <Card><CardHeader><CardTitle className="text-sm flex items-center gap-2"><TrendingUp className="h-4 w-4 text-green-600" />Top 5 Goles</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.goles_favor || []} field="goles_favor" label="GF" colorClass="text-green-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">🎯 Tiros</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.tiros_realizados || []} field="tiros_realizados" label="TR" colorClass="text-blue-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">🪵 Tiros al Palo</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.tiros_al_palo || []} field="tiros_al_palo" label="Palo" colorClass="text-amber-700" /></CardContent></Card>
      </div>
    </div>

    <div>
      <h3 className="text-xl font-semibold mb-4 text-blue-700">🛡️ DEFENSA</h3>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <Card><CardHeader><CardTitle className="text-sm">🟩 Balones Ganados</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.balones_ganados_equipo || []} field="balones_ganados_equipo" label="BG" colorClass="text-green-700" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm text-red-600">Menos Goles en Contra</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.goles_contra || []} field="goles_contra" label="GC" colorClass="text-red-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">🛑 Intercepciones</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.interceciones_equipo || []} field="interceciones_equipo" label="INT" colorClass="text-blue-700" /></CardContent></Card>
      </div>
    </div>

    <div>
      <h3 className="text-xl font-semibold mb-4 text-purple-700">💪 FÍSICO</h3>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <Card><CardHeader><CardTitle className="text-sm">⏱️ Distancia</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.distancia_recorrida || []} field="distancia_recorrida" label="Kms" colorClass="text-green-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">📊 Pases</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.pases || []} field="pases" label="Pases" colorClass="text-purple-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">⭐ Posesión</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.posesion || []} field="posesion_promedio" label="%" colorClass="text-blue-600" /></CardContent></Card>
      </div>
    </div>

    <div>
      <h3 className="text-xl font-semibold mb-4 text-yellow-700">🟨 TARJETAS</h3>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Card><CardHeader><CardTitle className="text-sm">⚠️ Amarillas</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.tarjetas_amarillas || []} field="tarjetas_amarillas" label="TA" colorClass="text-yellow-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">🟥 Rojas</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.tarjetas_rojas || []} field="tarjetas_rojas" label="TR" colorClass="text-red-600" /></CardContent></Card>
      </div>
    </div>

    <div>
      <h3 className="text-xl font-semibold mb-4 text-green-700">⚽ PENALTIES</h3>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <Card><CardHeader><CardTitle className="text-sm">✅ A Favor</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.penalties_equipo || []} field="penalties_equipo" label="Pen" colorClass="text-green-600" /></CardContent></Card>
        <Card><CardHeader><CardTitle className="text-sm">🚫 En Contra</CardTitle></CardHeader><CardContent><TopEquiposTable data={stats.penalties_rival || []} field="penalties_rival" label="Pen" colorClass="text-red-600" /></CardContent></Card>
      </div>
    </div>
  </div>
);

const OrdenJuegoTab: React.FC<{
  division: Division;
  squadBattleId: number;
  equipos: Equipo[];
  plantillasMap: Record<number, PlantillaInfo>;
}> = ({ division, squadBattleId, equipos, plantillasMap }) => {
  const [pendientes, setPendientes] = useState<Equipo[]>([]);
  const total = division.rivales.length;
  const sorted = [...division.rivales].sort((a, b) => a.orden - b.orden);

  const equiposEnDiv = new Set(sorted.map(dr => dr.equipo.id));
  const pendientesIds = new Set(pendientes.map(e => e.id));
  const disponibles = equipos.filter(e => !equiposEnDiv.has(e.id) && !pendientesIds.has(e.id));

  const confirmarAdd = () => {
    if (!pendientes.length) return;
    router.post(
      `/squad-battles/${squadBattleId}/divisiones/${division.id}/equipos`,
      { equipo_ids: pendientes.map(e => String(e.id)) },
      { onSuccess: () => setPendientes([]) }
    );
  };

  const removeEquipo = (drId: number) =>
    router.delete(`/squad-battles/${squadBattleId}/divisiones/${division.id}/equipos/${drId}`);

  const aleatorizar = () => {
    const orden = shuffle(sorted).map((dr, i) => ({ id: dr.id, orden: i + 1 }));
    router.patch(`/squad-battles/${squadBattleId}/divisiones/${division.id}/orden`, { orden });
  };

  return (
    <div className="flex flex-col gap-3">
      <div className="flex justify-between items-center mb-2">
        <p className="text-sm font-semibold text-muted-foreground">{total}/18 equipos</p>
        {total > 1 && <Button size="sm" variant="outline" onClick={aleatorizar}>🔀 Aleatorizar</Button>}
      </div>

      <Table className="text-sm">
        <TableHeader>
          <TableRow>
            <TableHead className="w-10 text-center">#</TableHead>
            <TableHead>Equipo</TableHead>
            <TableHead className="w-8"></TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {sorted.map(dr => {
            const info = plantillasMap[dr.equipo.id];
            return (
              <TableRow key={dr.id}>
                <TableCell className="text-center font-bold">{dr.orden}</TableCell>
                <TableCell>
                  <span className={info ? "" : "text-orange-400"}>{dr.equipo.nombre}</span>
                  {info && <span className="ml-1 text-xs text-gray-500">({info.media_alineacion ?? "—"}/{info.media_plantilla ?? "—"})</span>}
                </TableCell>
                <TableCell className="text-center">
                  <button onClick={() => removeEquipo(dr.id)} className="text-red-400 hover:text-red-600">×</button>
                </TableCell>
              </TableRow>
            );
          })}
        </TableBody>
      </Table>

      {total < 18 && (
        <div className="flex flex-col gap-2 border-t pt-3 mt-2">
          <EquipoTagSelector
            disponibles={disponibles}
            selected={pendientes}
            onAdd={eq => total + pendientes.length < 18 && setPendientes(p => [...p, eq])}
            onRemove={id => setPendientes(p => p.filter(e => e.id !== id))}
          />
          {pendientes.length > 0 && (
            <Button size="sm" onClick={confirmarAdd} className="bg-blue-600 hover:bg-blue-700 text-white self-start">
              Añadir {pendientes.length}
            </Button>
          )}
        </div>
      )}
      {total >= 18 && <p className="text-xs text-gray-500 text-center">División completa (18/18)</p>}
    </div>
  );
};

interface JugadorEstadistica {
  jugador: { id: number; nombre: string; posicion: string };
  equipo: string;
  goles: number;
  asistencias: number;
  tiros: number;
  tiros_a_puerta: number;
  tiros_al_palo: number;
  pases: number;
  pases_exitosos: number;
  regates_exitosos: number;
  posesion_ganada: number;
  posesion_perdida: number;
  distancia_recorrida: number;
  tarjetas_amarillas: number;
  tarjetas_rojas: number;
  partidos_jugados: number;
  minutos_jugados: number;
  rendimiento: number;
  rendimiento_promedio?: number;
  penalties_provocados: number;
  faltas_cometidas: number;
  faltas_recibidas: number;
  paradas: number;
  fueras_de_juego: number;
  jugador_del_partido: number;
  puntuacion_amonestaciones: number;
  penalties_parados: number;
}

interface JugadoresData {
  topGoleadores: JugadorEstadistica[];
  topAsistentes: JugadorEstadistica[];
  topTirosAlPalo: JugadorEstadistica[];
  topPasadores: JugadorEstadistica[];
  topAmarillas: JugadorEstadistica[];
  topMinutos: JugadorEstadistica[];
  topRendimiento: JugadorEstadistica[];
  topKilometros: JugadorEstadistica[];
  topRecuperaciones: JugadorEstadistica[];
  topPerdidas: JugadorEstadistica[];
  topRegates: JugadorEstadistica[];
  topPenaltiesProvocados: JugadorEstadistica[];
  topFaltasCometidas: JugadorEstadistica[];
  topFaltasRecibidas: JugadorEstadistica[];
  topParadas: JugadorEstadistica[];
  topFuerasJuego: JugadorEstadistica[];
  topMVP: JugadorEstadistica[];
  topAmonestados: JugadorEstadistica[];
  topRojas: JugadorEstadistica[];
  topPenaltiesParados: JugadorEstadistica[];
}

const TopJugadoresTable: React.FC<{ data: JugadorEstadistica[]; field: keyof JugadorEstadistica; label: string; colorClass: string }> = ({ data, field, label, colorClass }) => (
  <Table className="text-xs">
    <TableHeader><TableRow><TableHead className="w-8">#</TableHead><TableHead>Jugador</TableHead><TableHead className="text-center">{label}</TableHead></TableRow></TableHeader>
    <TableBody>
      {data.map((jugador, index) => (
        <TableRow key={jugador.jugador.id}>
          <TableCell className="font-bold">{index + 1}</TableCell>
          <TableCell>
            <div className="text-sm">{jugador.jugador.nombre}</div>
            <div className="text-xs text-muted-foreground">{jugador.equipo}</div>
          </TableCell>
          <TableCell className={`text-center font-bold ${colorClass}`}>{String(jugador[field] ?? 0)}</TableCell>
        </TableRow>
      ))}
    </TableBody>
  </Table>
);

const EstadisticasJugadoresTab: React.FC<{ jugadoresData: JugadoresData | null; loading: boolean }> = ({ jugadoresData, loading }) => {
  if (loading) return <div className="text-center py-8 text-muted-foreground">Cargando estadísticas de jugadores...</div>;
  if (!jugadoresData) return <div className="text-center py-8 text-muted-foreground">Haz clic en esta pestaña para cargar las estadísticas.</div>;

  return (
    <div className="space-y-8">
      <div>
        <h3 className="text-xl font-semibold mb-4 text-green-600">⚽ OFENSIVA</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card><CardHeader><CardTitle className="text-sm">🔥 Top 10 Goleadores</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topGoleadores} field="goles" label="Goles" colorClass="text-green-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">👁️ Asistentes</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topAsistentes} field="asistencias" label="ASI" colorClass="text-blue-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">🎯 Tiros al Palo</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topTirosAlPalo} field="tiros_al_palo" label="Palo" colorClass="text-amber-700" /></CardContent></Card>
        </div>
      </div>

      <div>
        <h3 className="text-xl font-semibold mb-4 text-purple-600">🎮 JUEGO</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card><CardHeader><CardTitle className="text-sm">📊 Pasadores</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPasadores} field="pases_exitosos" label="Pases" colorClass="text-purple-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">💨 Regates</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRegates} field="regates_exitosos" label="Regates" colorClass="text-cyan-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">⏱️ Más Minutos</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topMinutos} field="minutos_jugados" label="Min" colorClass="text-indigo-600" /></CardContent></Card>
        </div>
      </div>

      <div>
        <h3 className="text-xl font-semibold mb-4 text-blue-600">🛡️ DEFENSA</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card><CardHeader><CardTitle className="text-sm">🟩 Recuperaciones</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRecuperaciones} field="posesion_ganada" label="Rec" colorClass="text-green-700" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">🟥 Pérdidas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPerdidas} field="posesion_perdida" label="Pérdidas" colorClass="text-red-700" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">🟨 Fueras de Juego</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topFuerasJuego} field="fueras_de_juego" label="FJ" colorClass="text-yellow-600" /></CardContent></Card>
        </div>
      </div>

      <div>
        <h3 className="text-xl font-semibold mb-4 text-yellow-700">🟨 DISCIPLINA</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card><CardHeader><CardTitle className="text-sm">⚠️ Amarillas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topAmarillas} field="tarjetas_amarillas" label="Amarillas" colorClass="text-yellow-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">🟥 Rojas</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRojas} field="tarjetas_rojas" label="Rojas" colorClass="text-red-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">📝 Amonestaciones</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topAmonestados} field="puntuacion_amonestaciones" label="Am." colorClass="text-orange-600" /></CardContent></Card>
        </div>
      </div>

      <div>
        <h3 className="text-xl font-semibold mb-4 text-purple-700">💪 FÍSICO</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card><CardHeader><CardTitle className="text-sm">⏰ Distancia</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topKilometros} field="distancia_recorrida" label="Km" colorClass="text-green-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">⭐ Rendimiento</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topRendimiento} field="rendimiento_promedio" label="Rend" colorClass="text-blue-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">🏆 MVP</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topMVP} field="jugador_del_partido" label="MVP" colorClass="text-amber-600" /></CardContent></Card>
        </div>
      </div>

      <div>
        <h3 className="text-xl font-semibold mb-4 text-green-600">⚽ PENALTIES</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card><CardHeader><CardTitle className="text-sm">⚡ Provocados</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPenaltiesProvocados} field="penalties_provocados" label="Prov" colorClass="text-green-600" /></CardContent></Card>
          <Card><CardHeader><CardTitle className="text-sm">🧤 Porteros Parados</CardTitle></CardHeader><CardContent><TopJugadoresTable data={jugadoresData.topPenaltiesParados} field="penalties_parados" label="Parados" colorClass="text-blue-600" /></CardContent></Card>
        </div>
      </div>
    </div>
  );
};

const DivisionContent: React.FC<{
  division: Division;
  squadBattleId: number;
  equipos: Equipo[];
  plantillasMap: Record<number, PlantillaInfo>;
  divisionData: DivisionData;
}> = ({ division, squadBattleId, equipos, plantillasMap, divisionData }) => {
  const [jugadoresData, setJugadoresData] = useState<JugadoresData | null>(null);
  const [loadingJugadores, setLoadingJugadores] = useState(false);

  const deleteDiv = () => {
    if (confirm(`¿Eliminar la división "${division.nombre}"?`))
      router.delete(`/squad-battles/${squadBattleId}/divisiones/${division.id}`);
  };

  const handleTabChange = (value: string) => {
    if (value === 'jugadores' && !jugadoresData && !loadingJugadores) {
      setLoadingJugadores(true);
      fetch(`/squad-battles/${squadBattleId}/divisiones/${division.id}/jugadores`)
        .then((r) => r.json())
        .then((data) => { setJugadoresData(data); setLoadingJugadores(false); })
        .catch(() => setLoadingJugadores(false));
    }
  };

  const totalEquipos = division.rivales.length;
  const totalPartidos = divisionData.estadisticas.reduce((s, e) => s + e.partidos_jugados, 0);
  const totalGoles = divisionData.estadisticas.reduce((s, e) => s + e.goles_favor, 0);

  return (
    <div className="flex flex-col gap-4">
      {/* Resumen */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
        <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground">Equipos</CardTitle></CardHeader><CardContent><div className="flex items-center gap-2"><Users className="h-4 w-4 text-blue-500" /><span className="text-xl font-bold">{totalEquipos}</span></div></CardContent></Card>
        <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground">Partidos</CardTitle></CardHeader><CardContent><div className="text-xl font-bold">{totalPartidos}</div></CardContent></Card>
        <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground flex items-center gap-1"><TrendingUp className="h-3 w-3 text-green-500" />Goles</CardTitle></CardHeader><CardContent><div className="text-xl font-bold text-green-600">{totalGoles}</div></CardContent></Card>
        <Card><CardHeader className="pb-2"><CardTitle className="text-xs font-medium text-muted-foreground">Goles/Partido</CardTitle></CardHeader><CardContent><div className="text-xl font-bold">{(totalGoles / Math.max(totalPartidos, 1)).toFixed(2)}</div></CardContent></Card>
      </div>

      {/* Tabs */}
      <Tabs defaultValue="orden" onValueChange={handleTabChange}>
        <TabsList>
          <TabsTrigger value="orden">Orden de Juego</TabsTrigger>
          <TabsTrigger value="clasificacion">Clasificación</TabsTrigger>
          <TabsTrigger value="equipos">Estadísticas Equipos</TabsTrigger>
          <TabsTrigger value="jugadores">Estadísticas Jugadores</TabsTrigger>
        </TabsList>

        <TabsContent value="orden" className="mt-4">
          <OrdenJuegoTab division={division} squadBattleId={squadBattleId} equipos={equipos} plantillasMap={plantillasMap} />
        </TabsContent>

        <TabsContent value="clasificacion" className="mt-4">
          <ClasificacionTab estadisticas={divisionData.estadisticas} />
        </TabsContent>

        <TabsContent value="equipos" className="mt-4">
          <EstadisticasEquiposTab stats={divisionData.estadisticas_equipos} />
        </TabsContent>

        <TabsContent value="jugadores" className="mt-4">
          <EstadisticasJugadoresTab jugadoresData={jugadoresData} loading={loadingJugadores} />
        </TabsContent>
      </Tabs>

      {/* Botón eliminar división */}
      <div className="flex justify-end">
        <Button size="sm" variant="destructive" onClick={deleteDiv}>Eliminar División</Button>
      </div>
    </div>
  );
};

const Show: React.FC<Props> = ({ squadBattle, equipos, plantillasMap, divisionesData }) => {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Squad Battle: ${squadBattle.nombre}`} />
      <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div className="flex justify-between items-start">
          <div>
            <h1 className="text-2xl font-bold flex items-center gap-2">
              <Trophy className="h-6 w-6 text-yellow-500" />
              {squadBattle.nombre}
            </h1>
            {squadBattle.temporada && (
              <p className="text-gray-400 text-sm mt-1">Temporada: {squadBattle.temporada.nombre}</p>
            )}
          </div>
          <div className="flex gap-2">
            <Button variant="outline" size="sm" onClick={() => router.get(`/squad-battles/${squadBattle.id}/edit`)}>Editar</Button>
            <Button variant="outline" size="sm" onClick={() => router.get("/squad-battles")}>← Volver</Button>
          </div>
        </div>

        {squadBattle.divisiones.length === 0 ? (
          <p className="text-gray-500 text-center py-12">No hay divisiones. Añade una para empezar.</p>
        ) : (
          <Tabs defaultValue={String(squadBattle.divisiones[0]?.id)} className="w-full">
            <TabsList className="grid w-full" style={{ gridTemplateColumns: `repeat(${Math.min(squadBattle.divisiones.length, 6)}, minmax(0, 1fr))` }}>
              {squadBattle.divisiones.map(div => (
                <TabsTrigger key={div.id} value={String(div.id)} className="text-xs md:text-sm">
                  {div.nombre}
                </TabsTrigger>
              ))}
            </TabsList>

            {squadBattle.divisiones.map(div => (
              <TabsContent key={div.id} value={String(div.id)} className="mt-4">
                <DivisionContent
                  division={div}
                  squadBattleId={squadBattle.id}
                  equipos={equipos}
                  plantillasMap={plantillasMap}
                  divisionData={divisionesData[div.id] || { estadisticas: [], estadisticas_equipos: {}, partidos: [] }}
                />
              </TabsContent>
            ))}
          </Tabs>
        )}

        {/* Botón añadir división */}
        <div className="flex justify-start">
          <Button
            variant="outline"
            size="sm"
            onClick={() => {
              const nombre = prompt("Nombre de la división:");
              if (nombre) {
                router.post(`/squad-battles/${squadBattle.id}/divisiones`, {
                  nombre,
                  orden: squadBattle.divisiones.length + 1,
                });
              }
            }}
          >
            + Añadir División
          </Button>
        </div>
      </div>
    </AppLayout>
  );
};

export default Show;
