import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import {
  Table,
  TableCaption,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from "@/components/ui/table";
import { Input } from "@/components/ui/input";
import AccionesTabla from "@/components/acciones-tabla";
import ControlesPaginacion from "@/components/controles-paginacion";

interface Partido {
  id: number;
  partido_como_local: number;
  local_es_rival: boolean;
  visitante_es_rival: boolean;
  visitante_id?: number | null;
  equipo: { id: number; nombre: string };
  rival?: { id: number; nombre: string } | null;
  local_rival?: { id: number; nombre: string } | null;
  visitante_rival?: { id: number; nombre: string } | null;
  visitante_equipo?: { id: number; nombre: string } | null;
  visitante: { id: number; nombre: string } | null;
  goles_equipo: number;
  goles_rival: number;
  valor_equipo?: number | null;
  valor_rival?: number | null;
}

interface Props {
  partidos: Partido[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ partidos }) => {

  const [globalFilter, setGlobalFilter] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const rowsPerPage = 10;

  const getNombreRival = (row: Partido) =>
    row.visitante_rival?.nombre ?? row.visitante_equipo?.nombre ?? row.rival?.nombre ?? 'Visitante';

  const filtered = partidos.filter(
    (p) =>
      p.equipo.nombre.toLowerCase().includes(globalFilter.toLowerCase()) ||
      getNombreRival(p).toLowerCase().includes(globalFilter.toLowerCase())
  );

  const paginated = filtered.slice(
    (currentPage - 1) * rowsPerPage,
    currentPage * rowsPerPage
  );

  const totalPages = Math.ceil(filtered.length / rowsPerPage);
  const pages = Array.from({ length: totalPages }, (_, i) => i + 1);

  const renderLado = (nombre: string, esRival: boolean, rivalNombre?: string | null) => (
    <div className="flex flex-col leading-tight">
      <span>{nombre}</span>
      {esRival && rivalNombre ? (
        <span className="text-xs text-muted-foreground">{rivalNombre}</span>
      ) : null}
    </div>
  );

  // Dificultad = valor del equipo - valor del rival (el mismo dato que se ve en el detalle del partido).
  // Positivo => favorable (equipo más fuerte), negativo => más difícil (rival más fuerte).
  const MAX_DIFERENCIA = 20;
  const renderDificultad = (valorEquipo?: number | null, valorRival?: number | null) => {
    if (valorEquipo == null || valorRival == null) {
      return <span className="text-xs text-muted-foreground">-</span>;
    }
    const diff = valorEquipo - valorRival;
    const esFavorable = diff >= 0;
    const anchoRelleno = (Math.min(Math.abs(diff), MAX_DIFERENCIA) / MAX_DIFERENCIA) * 50;

    return (
      <div className="flex items-center gap-2 w-36">
        <div className="relative flex-1 h-1.5 rounded-full bg-muted">
          <div className="absolute inset-y-0 left-1/2 w-px bg-border" />
          <div
            className={`absolute inset-y-0 ${esFavorable ? 'left-1/2 rounded-r-full bg-success' : 'right-1/2 rounded-l-full bg-destructive'}`}
            style={{ width: `${anchoRelleno}%` }}
          />
        </div>
        <span className={`text-xs font-mono w-10 text-right ${esFavorable ? 'text-success' : 'text-destructive'}`}>
          {diff > 0 ? `+${diff}` : diff}
        </span>
      </div>
    );
  };


  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Partidos"/>
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Partidos</h1>
        <div className="flex justify-between items-center mb-4">        
          <Input
            placeholder="Buscar..."
            value={globalFilter}
            onChange={(e) => setGlobalFilter(e.target.value)}
            className="max-w-md"
          />
          <Button onClick={() => router.get("/partidos/create")}>+</Button>
        </div>
    
        {paginated.length > 0 ? (
          <>
          <Table className="w-full">
            <TableHeader>
              <TableRow>
                <TableHead className="p-2 border">Local</TableHead>
                <TableHead className="p-2 border">Goles Local</TableHead>
                <TableHead className="p-2 border">Goles Visitante</TableHead>
                <TableHead className="p-2 border">Visitante</TableHead>
                <TableHead className="p-2 border">Dificultad</TableHead>
                <TableHead className="p-2 border">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {paginated.map((row, i) => (
        
                <TableRow
                  key={row.id}
                  className={`cursor-pointer ${
                    i % 2 === 0 ? "" : "bg-muted/30"
                  }`}
                  onClick={() => router.get(`/partidos/${row.id}/edit`)}
                >
                {row.partido_como_local === 1 ? ( 
                  <>
                    <TableCell className="p-2 border">
                      {renderLado(row.equipo.nombre, row.local_es_rival, row.rival?.nombre)}
                    </TableCell>
                    <TableCell className="p-2 border">{row.goles_equipo}</TableCell>
                    <TableCell className="p-2 border">{row.goles_rival}</TableCell>
                    <TableCell className="p-2 border">
                      {renderLado(getNombreRival(row), row.visitante_es_rival, row.rival?.nombre)}
                    </TableCell>
                    <TableCell className="p-2 border">
                      {renderDificultad(row.valor_equipo, row.valor_rival)}
                    </TableCell>
                    <TableCell className="p-2 border">
                      <AccionesTabla
                        id={row.id}
                        basePath="/partidos"
                      />
                    </TableCell>
                  </>

                ) : (
                  <>
                    <TableCell className="p-2 border">
                      {renderLado(getNombreRival(row), row.visitante_es_rival, row.rival?.nombre)}
                    </TableCell>
                    <TableCell className="p-2 border">{row.goles_rival}</TableCell>
                    <TableCell className="p-2 border">{row.goles_equipo}</TableCell>
                    <TableCell className="p-2 border">
                      {renderLado(row.equipo.nombre, row.local_es_rival, row.rival?.nombre)}
                    </TableCell>
                    <TableCell className="p-2 border">
                      {renderDificultad(row.valor_equipo, row.valor_rival)}
                    </TableCell>
                    <TableCell className="p-2 border">
                      <AccionesTabla
                        id={row.id}
                        basePath="/partidos"
                      />
                    </TableCell>
                  </>
                 )}
                
              </TableRow>
            ))}
            </TableBody>
          </Table>
          {/* Paginación */}
          {totalPages > 1 && (
            <ControlesPaginacion
              currentPage={currentPage}
              totalPages={totalPages}
              onPageChange={(page) => setCurrentPage(page)}
            />
          )}  
          </>          
            ) : (
              <div className="text-center py-8">
                <i className="pi pi-inbox text-4xl text-muted-foreground mb-2"></i>
                <p className="text-muted-foreground text-lg">
                  No se encontraron plantillas
                </p>
                <p className="text-muted-foreground text-sm">
                  No hay plantillas registradas en el sistema
                </p>
              </div>
              )}
            </div>
          </AppLayout>
    );
};

export default Index;
