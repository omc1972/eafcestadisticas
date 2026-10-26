import React, { useState, useRef } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from "@/components/ui/table";

interface Equipo { id: number; nombre: string }

interface DivisionEquipo { id: number; orden: number; equipo: Equipo }

interface Division {
  id: number;
  nombre: string;
  orden: number;
  rivales: DivisionEquipo[];
}

interface RivalsBattle {
  id: number;
  nombre: string;
  temporada: { nombre: string } | null;
  divisiones: Division[];
}

interface PlantillaInfo {
  id: number;
  media_plantilla: number | null;
  media_alineacion: number | null;
}

interface Standing {
  pj: number; pg: number; pe: number; pp: number;
  gf: number; gc: number; dif: number; pts: number;
}

interface Props {
  rivalsBattle: RivalsBattle;
  equipos: Equipo[];
  plantillasMap: Record<number, PlantillaInfo>;
  standingsMap: Record<number, Standing>;
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Dashboard", href: dashboard().url },
  { title: "Rivals", href: "/rivals-battles" },
];

function shuffle<T>(arr: T[]): T[] {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

// ── Tag selector ───────────────────────────────────
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
            <span key={eq.id} className="flex items-center gap-1 bg-primary text-primary-foreground text-xs px-2 py-1 rounded-full">
              {eq.nombre}
              <button type="button" onClick={() => onRemove(eq.id)} className="hover:text-destructive">×</button>
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

// ── Standings table ────────────────────────────────
const ClasificacionTable: React.FC<{
  rivales: DivisionEquipo[];
  standingsMap: Record<number, Standing>;
  plantillasMap: Record<number, PlantillaInfo>;
}> = ({ rivales, standingsMap, plantillasMap }) => {
  const rows = [...rivales]
    .map(dr => ({ equipo: dr.equipo, s: standingsMap[dr.equipo.id] ?? { pj:0,pg:0,pe:0,pp:0,gf:0,gc:0,dif:0,pts:0 } }))
    .sort((a, b) => b.s.pts - a.s.pts || b.s.dif - a.s.dif || b.s.gf - a.s.gf);

  return (
    <Table className="text-xs w-full">
      <TableHeader>
        <TableRow>
          <TableHead className="p-1">#</TableHead>
          <TableHead className="p-1">Equipo</TableHead>
          <TableHead className="p-1 text-center">PJ</TableHead>
          <TableHead className="p-1 text-center">PG</TableHead>
          <TableHead className="p-1 text-center">PE</TableHead>
          <TableHead className="p-1 text-center">PP</TableHead>
          <TableHead className="p-1 text-center">GF</TableHead>
          <TableHead className="p-1 text-center">GC</TableHead>
          <TableHead className="p-1 text-center">DIF</TableHead>
          <TableHead className="p-1 text-center font-bold">PTS</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        {rows.map(({ equipo, s }, i) => {
          const info = plantillasMap[equipo.id];
          return (
            <TableRow key={equipo.id}>
              <TableCell className="p-1 text-center text-muted-foreground">{i + 1}</TableCell>
              <TableCell className="p-1">
                <span className={info ? "" : "text-warning"}>{equipo.nombre}</span>
                {info && (
                  <span className="ml-1 text-muted-foreground">
                    ({info.media_alineacion ?? "—"}/{info.media_plantilla ?? "—"})
                  </span>
                )}
              </TableCell>
              <TableCell className="p-1 text-center">{s.pj}</TableCell>
              <TableCell className="p-1 text-center text-success">{s.pg}</TableCell>
              <TableCell className="p-1 text-center text-muted-foreground">{s.pe}</TableCell>
              <TableCell className="p-1 text-center text-destructive">{s.pp}</TableCell>
              <TableCell className="p-1 text-center">{s.gf}</TableCell>
              <TableCell className="p-1 text-center">{s.gc}</TableCell>
              <TableCell className={`p-1 text-center ${s.dif > 0 ? "text-success" : s.dif < 0 ? "text-destructive" : ""}`}>
                {s.dif > 0 ? `+${s.dif}` : s.dif}
              </TableCell>
              <TableCell className="p-1 text-center font-bold">{s.pts}</TableCell>
            </TableRow>
          );
        })}
      </TableBody>
    </Table>
  );
};

// ── Division card ──────────────────────────────────
const DivisionCard: React.FC<{
  division: Division;
  rivalsBattleId: number;
  equipos: Equipo[];
  plantillasMap: Record<number, PlantillaInfo>;
  standingsMap: Record<number, Standing>;
}> = ({ division, rivalsBattleId, equipos, plantillasMap, standingsMap }) => {
  const [pendientes, setPendientes] = useState<Equipo[]>([]);
  const total = division.rivales.length;
  const sorted = [...division.rivales].sort((a, b) => a.orden - b.orden);

  const equiposEnDiv = new Set(sorted.map(dr => dr.equipo.id));
  const pendientesIds = new Set(pendientes.map(e => e.id));
  const disponibles = equipos.filter(e => !equiposEnDiv.has(e.id) && !pendientesIds.has(e.id));

  const confirmarAdd = () => {
    if (!pendientes.length) return;
    router.post(
      `/rivals-battles/${rivalsBattleId}/divisiones/${division.id}/equipos`,
      { equipo_ids: pendientes.map(e => String(e.id)) },
      { onSuccess: () => setPendientes([]) }
    );
  };

  const removeEquipo = (drId: number) =>
    router.delete(`/rivals-battles/${rivalsBattleId}/divisiones/${division.id}/equipos/${drId}`);

  const aleatorizar = () => {
    const orden = shuffle(sorted).map((dr, i) => ({ id: dr.id, orden: i + 1 }));
    router.patch(`/rivals-battles/${rivalsBattleId}/divisiones/${division.id}/orden`, { orden });
  };

  const deleteDiv = () => {
    if (confirm(`¿Eliminar la división "${division.nombre}"?`))
      router.delete(`/rivals-battles/${rivalsBattleId}/divisiones/${division.id}`);
  };

  return (
    <div className="border rounded-lg p-4 flex flex-col gap-3">
      {/* Header */}
      <div className="flex justify-between items-center">
        <h2 className="text-lg font-bold">
          <span className="text-muted-foreground text-sm mr-2">#{division.orden}</span>
          {division.nombre}
          <span className="ml-3 text-sm font-normal text-muted-foreground">{total}/18</span>
        </h2>
        <div className="flex gap-2">
          {total > 1 && (
            <Button size="sm" variant="outline" onClick={aleatorizar}>🔀 Aleatorizar</Button>
          )}
          <Button size="sm" variant="destructive" onClick={deleteDiv}>Eliminar</Button>
        </div>
      </div>

      {/* 50/50 split */}
      <div className="grid grid-cols-2 gap-4 min-h-0">

        {/* Izquierda: orden de juego */}
        <div className="flex flex-col gap-2">
          <p className="text-xs text-muted-foreground font-semibold uppercase tracking-wide">Orden de juego</p>
          {total > 0 ? (
            <Table className="text-sm w-full">
              <TableHeader>
                <TableRow>
                  <TableHead className="p-1 w-10 text-center">#</TableHead>
                  <TableHead className="p-1">Equipo</TableHead>
                  <TableHead className="p-1 w-8"></TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {sorted.map(dr => {
                  const info = plantillasMap[dr.equipo.id];
                  return (
                    <TableRow key={dr.id}>
                      <TableCell className="p-1 text-center font-mono font-bold">{dr.orden}</TableCell>
                      <TableCell className="p-1">
                        <span className={info ? "" : "text-warning"}>{dr.equipo.nombre}</span>
                        {info && (
                          <span className="ml-1 text-xs text-muted-foreground">
                            ({info.media_alineacion ?? "—"}/{info.media_plantilla ?? "—"})
                          </span>
                        )}
                      </TableCell>
                      <TableCell className="p-1 text-center">
                        <button onClick={() => removeEquipo(dr.id)} className="text-destructive hover:text-destructive/80">×</button>
                      </TableCell>
                    </TableRow>
                  );
                })}
              </TableBody>
            </Table>
          ) : (
            <p className="text-muted-foreground text-sm text-center py-3">Sin equipos todavía</p>
          )}

          {total < 18 && (
            <div className="flex flex-col gap-2 border-t pt-2 mt-1">
              <EquipoTagSelector
                disponibles={disponibles}
                selected={pendientes}
                onAdd={eq => total + pendientes.length < 18 && setPendientes(p => [...p, eq])}
                onRemove={id => setPendientes(p => p.filter(e => e.id !== id))}
              />
              {pendientes.length > 0 && (
                <Button size="sm" onClick={confirmarAdd} className="self-start">
                  Añadir {pendientes.length} equipo{pendientes.length > 1 ? "s" : ""}
                </Button>
              )}
            </div>
          )}
          {total >= 18 && <p className="text-xs text-muted-foreground text-center">División completa (18/18)</p>}
        </div>

        {/* Derecha: clasificación */}
        <div className="flex flex-col gap-2">
          <p className="text-xs text-muted-foreground font-semibold uppercase tracking-wide">Clasificación</p>
          {total > 0 ? (
            <ClasificacionTable
              rivales={division.rivales}
              standingsMap={standingsMap}
              plantillasMap={plantillasMap}
            />
          ) : (
            <p className="text-muted-foreground text-sm text-center py-3">Sin datos</p>
          )}
        </div>

      </div>
    </div>
  );
};

// ── Add Division form ──────────────────────────────
const AddDivisionForm: React.FC<{ rivalsBattleId: number; nextOrden: number }> = ({ rivalsBattleId, nextOrden }) => {
  const [nombre, setNombre] = useState("");
  const [open, setOpen] = useState(false);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    router.post(
      `/rivals-battles/${rivalsBattleId}/divisiones`,
      { nombre, orden: nextOrden },
      { onSuccess: () => { setNombre(""); setOpen(false); } }
    );
  };

  if (!open)
    return <Button onClick={() => setOpen(true)} variant="outline" className="self-start">+ Añadir División</Button>;

  return (
    <form onSubmit={submit} className="flex gap-2 items-end border rounded p-3 self-start">
      <Input value={nombre} onChange={e => setNombre(e.target.value)}
        placeholder="Ej: División 1" className="w-40" required autoFocus />
      <Button type="submit">Crear</Button>
      <Button type="button" variant="outline" onClick={() => setOpen(false)}>Cancelar</Button>
    </form>
  );
};

// ── Main page ──────────────────────────────────────
const Show: React.FC<Props> = ({ rivalsBattle, equipos, plantillasMap, standingsMap }) => {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Rivals: ${rivalsBattle.nombre}`} />
      <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
        <div className="flex justify-between items-start">
          <div>
            <h1 className="text-2xl font-bold">{rivalsBattle.nombre}</h1>
            {rivalsBattle.temporada && (
              <p className="text-muted-foreground text-sm mt-1">Temporada: {rivalsBattle.temporada.nombre}</p>
            )}
          </div>
          <div className="flex gap-2">
            <Button variant="outline" size="sm" onClick={() => router.get(`/rivals-battles/${rivalsBattle.id}/edit`)}>Editar</Button>
            <Button variant="outline" size="sm" onClick={() => router.get("/rivals-battles")}>← Volver</Button>
          </div>
        </div>

        {rivalsBattle.divisiones.length === 0 ? (
          <p className="text-muted-foreground text-center py-12">No hay divisiones. Añade una para empezar.</p>
        ) : (
          <div className="flex flex-col gap-4">
            {rivalsBattle.divisiones.map(div => (
              <DivisionCard
                key={div.id}
                division={div}
                rivalsBattleId={rivalsBattle.id}
                equipos={equipos}
                plantillasMap={plantillasMap}
                standingsMap={standingsMap}
              />
            ))}
          </div>
        )}

        <AddDivisionForm rivalsBattleId={rivalsBattle.id} nextOrden={rivalsBattle.divisiones.length + 1} />
      </div>
    </AppLayout>
  );
};

export default Show;
