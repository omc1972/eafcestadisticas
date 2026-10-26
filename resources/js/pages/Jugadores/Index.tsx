import React, { useState, useEffect } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import EstiloIcon from "@/components/estilo-icon";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/components/ui/table";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import AccionesTabla from "@/components/acciones-tabla";
import ControlesPaginacion from "@/components/controles-paginacion";
import { getStatsConfig } from "@/lib/estadisticas";
import { List, LayoutGrid } from "lucide-react";

type Vista = "cards" | "lista";
const VISTA_STORAGE_KEY = "jugadores-vista";
const ROWS_PER_PAGE_STORAGE_KEY = "jugadores-rows-per-page";
const ROWS_PER_PAGE_OPTIONS = [12, 24, 48] as const;

type Estilo = {
  id: number;
  nombre: string;
  categoria?: string;
  nivel: "Oro" | "Plata";
};

type Jugador = {
  id: number;
  nombre: string;
  nacionalidad: string;
  fecha_nacimiento: string;
  posicion: string;
  media: number;
  ritmo?: number;
  tiro?: number;
  pase?: number;
  regate?: number;
  defensa?: number;
  fisico?: number;
  estilos?: Estilo[];
};

interface Props {
  jugadores: Jugador[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ jugadores }) => {
  const [globalFilter, setGlobalFilter] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [vista, setVista] = useState<Vista>("cards");
  const [rowsPerPage, setRowsPerPage] = useState<number>(12);

  // Recupera la vista y el tamaño de página guardados (localStorage no existe en el render de SSR, por eso se lee en un efecto)
  useEffect(() => {
    const guardada = localStorage.getItem(VISTA_STORAGE_KEY);
    if (guardada === "cards" || guardada === "lista") {
      setVista(guardada);
    }

    const rowsGuardadas = Number(localStorage.getItem(ROWS_PER_PAGE_STORAGE_KEY));
    if (ROWS_PER_PAGE_OPTIONS.includes(rowsGuardadas as (typeof ROWS_PER_PAGE_OPTIONS)[number])) {
      setRowsPerPage(rowsGuardadas);
    }
  }, []);

  const cambiarVista = (v: Vista) => {
    setVista(v);
    localStorage.setItem(VISTA_STORAGE_KEY, v);
  };

  const cambiarRowsPerPage = (v: number) => {
    setRowsPerPage(v);
    setCurrentPage(1);
    localStorage.setItem(ROWS_PER_PAGE_STORAGE_KEY, String(v));
  };

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const page = params.get("page");
    if (page) {
      const pageNum = parseInt(page, 10);
      if (pageNum > 0) {
        setCurrentPage(pageNum);
      }
    }
  }, []);

  // Filtrado por búsqueda
  const filtered = jugadores.filter(
    (j) =>
      j.nombre.toLowerCase().includes(globalFilter.toLowerCase()) ||
      j.posicion.toLowerCase().includes(globalFilter.toLowerCase()) ||
      j.nacionalidad.toLowerCase().includes(globalFilter.toLowerCase())
  );

  // Paginar resultados
  const totalPages = Math.ceil(filtered.length / rowsPerPage);
  const paginated = filtered.slice(
    (currentPage - 1) * rowsPerPage,
    currentPage * rowsPerPage
  );

  // Si la página actual excede el total de páginas, volver a la página 1
  useEffect(() => {
    if (currentPage > totalPages && totalPages > 0) {
      setCurrentPage(1);
    }
  }, [currentPage, totalPages]);

  // Calcular edad
  const calcularEdad = (fecha: string) => {
    const nacimiento = new Date(fecha);
    const hoy = new Date();
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }
    return edad;
  };

  // Componente para barra de atributo
  const AttributeBar = ({ label, value }: { label: string; value?: number }) => {
    const val = value ?? 0;
    return (
      <div className="flex items-center gap-2">
        <span className="text-xs font-medium w-8 text-muted-foreground">{label}</span>
        <div className="flex-1 bg-muted rounded-full h-2 overflow-hidden">
          <div
            className="h-full bg-primary transition-all"
            style={{ width: `${val}%` }}
          />
        </div>
        <span className="text-xs font-bold w-8 text-right">{val}</span>
      </div>
    );
  };

  // Función para obtener labels dinámicos basados en posición
  const getAttributeLabels = (posicion: string | null) => {
    if (posicion === "POR") {
      return [
        { label: "VEL", key: "ritmo" },      // Velocidad
        { label: "PAR", key: "tiro" },       // Parada
        { label: "SAQ", key: "pase" },       // Saque
        { label: "REF", key: "regate" },     // Reflejos
        { label: "POS", key: "defensa" },    // Posición
        { label: "SAL", key: "fisico" },     // Salto
      ];
    }
    return [
      { label: "RIT", key: "ritmo" },
      { label: "TIR", key: "tiro" },
      { label: "PAS", key: "pase" },
      { label: "REG", key: "regate" },
      { label: "DEF", key: "defensa" },
      { label: "FIS", key: "fisico" },
    ];
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Jugadores" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Jugadores</h1>
        <div className="flex justify-between items-center mb-4 gap-2">
          <Input
            placeholder="Buscar..."
            value={globalFilter}
            onChange={(e) => {
              setGlobalFilter(e.target.value);
              setCurrentPage(1);
            }}
            className="max-w-md"
          />
          <div className="flex items-center gap-2">
            <div className="flex items-center gap-1.5 text-sm text-muted-foreground whitespace-nowrap">
              <span className="hidden sm:inline">Mostrar</span>
              <Select
                value={String(rowsPerPage)}
                onValueChange={(v) => cambiarRowsPerPage(Number(v))}
              >
                <SelectTrigger className="h-8 w-[64px]" aria-label="Jugadores por página">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  {ROWS_PER_PAGE_OPTIONS.map((n) => (
                    <SelectItem key={n} value={String(n)}>{n}</SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex items-center rounded-md border p-0.5">
              <Button
                variant={vista === "cards" ? "secondary" : "ghost"}
                size="sm"
                aria-label="Ver en tarjetas"
                onClick={() => cambiarVista("cards")}
              >
                <LayoutGrid className="h-4 w-4" />
              </Button>
              <Button
                variant={vista === "lista" ? "secondary" : "ghost"}
                size="sm"
                aria-label="Ver en lista"
                onClick={() => cambiarVista("lista")}
              >
                <List className="h-4 w-4" />
              </Button>
            </div>
            <Button onClick={() => router.get("/jugadores/create")}>
              +
            </Button>
          </div>
        </div>

        {paginated.length > 0 ? (
          <>
            {vista === "lista" ? (
            <Table className="w-full">
              <TableHeader>
                <TableRow>
                  <TableHead className="p-2 border">Nombre</TableHead>
                  <TableHead className="p-2 border">Posición</TableHead>
                  <TableHead className="p-2 border">Nacionalidad</TableHead>
                  <TableHead className="p-2 border text-center">Edad</TableHead>
                  <TableHead className="p-2 border text-center">MED</TableHead>
                  <TableHead className="p-2 border text-center">RIT</TableHead>
                  <TableHead className="p-2 border text-center">TIR</TableHead>
                  <TableHead className="p-2 border text-center">PAS</TableHead>
                  <TableHead className="p-2 border text-center">REG</TableHead>
                  <TableHead className="p-2 border text-center">DEF</TableHead>
                  <TableHead className="p-2 border text-center">FIS</TableHead>
                  <TableHead className="p-2 border">Estilos</TableHead>
                  <TableHead className="p-2 border">Acciones</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {paginated.map((jugador, i) => (
                  <TableRow
                    key={jugador.id}
                    className={`cursor-pointer ${i % 2 === 0 ? "" : "bg-muted/30"}`}
                    onClick={() => router.get(`/jugadores/${jugador.id}/edit?page=${currentPage}`)}
                  >
                    <TableCell className="p-2 border font-medium">{jugador.nombre}</TableCell>
                    <TableCell className="p-2 border">{jugador.posicion}</TableCell>
                    <TableCell className="p-2 border">{jugador.nacionalidad}</TableCell>
                    <TableCell className="p-2 border text-center">{calcularEdad(jugador.fecha_nacimiento)}</TableCell>
                    <TableCell className="p-2 border text-center font-bold text-primary">{jugador.media}</TableCell>
                    <TableCell className="p-2 border text-center">{jugador.ritmo ?? 0}</TableCell>
                    <TableCell className="p-2 border text-center">{jugador.tiro ?? 0}</TableCell>
                    <TableCell className="p-2 border text-center">{jugador.pase ?? 0}</TableCell>
                    <TableCell className="p-2 border text-center">{jugador.regate ?? 0}</TableCell>
                    <TableCell className="p-2 border text-center">{jugador.defensa ?? 0}</TableCell>
                    <TableCell className="p-2 border text-center">{jugador.fisico ?? 0}</TableCell>
                    <TableCell className="p-2 border">
                      {jugador.estilos && jugador.estilos.length > 0 ? (
                        <div className="flex flex-wrap gap-1">
                          {jugador.estilos.map((estilo) => (
                            <EstiloIcon key={estilo.id} estilo={estilo} />
                          ))}
                        </div>
                      ) : (
                        <span className="text-muted-foreground">-</span>
                      )}
                    </TableCell>
                    <TableCell className="p-2 border" onClick={(e) => e.stopPropagation()}>
                      <AccionesTabla
                        id={jugador.id}
                        basePath="/jugadores"
                        queryString={`?page=${currentPage}`}
                      />
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
            ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              {paginated.map((jugador) => (
                <Card
                  key={jugador.id}
                  className="hover:shadow-lg transition-shadow cursor-pointer"
                  onClick={() => router.get(`/jugadores/${jugador.id}/edit?page=${currentPage}`)}
                >
                  <CardHeader className="pb-3">
                    <div className="flex justify-between items-start">
                      <div className="flex-1">
                        <h3 className="text-lg font-bold">{jugador.nombre}</h3>
                        <div className="flex gap-2 text-sm text-muted-foreground mt-1">
                          <span>{jugador.posicion}</span>
                          <span>•</span>
                          <span>{calcularEdad(jugador.fecha_nacimiento)} años</span>
                          <span>•</span>
                          <span>{jugador.nacionalidad}</span>
                        </div>
                      </div>
                      <div className="flex flex-col items-end gap-2">
                        <div className="text-2xl font-bold text-primary">
                          {jugador.media}
                        </div>
                        <div onClick={(e) => e.stopPropagation()}>
                          <AccionesTabla
                            id={jugador.id}
                            basePath="/jugadores"
                            queryString={`?page=${currentPage}`}
                          />
                        </div>
                      </div>
                    </div>
                  </CardHeader>
                  <CardContent className="space-y-2">
                    {(() => {
                      const labels = getAttributeLabels(jugador.posicion);
                      return labels.map((attr) => (
                        <AttributeBar
                          key={attr.key}
                          label={attr.label}
                          value={jugador[attr.key as keyof typeof jugador] as number}
                        />
                      ));
                    })()}
                    
                    {jugador.estilos && jugador.estilos.length > 0 && (
                      <div className="pt-2 mt-2 border-t border-border">
                        <div className="flex flex-wrap gap-1 justify-center">
                          {jugador.estilos.map((estilo) => (
                            <EstiloIcon key={estilo.id} estilo={estilo} />
                          ))}
                        </div>
                      </div>
                    )}
                  </CardContent>
                </Card>
              ))}
            </div>
            )}
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
            <p className="text-muted-foreground text-lg">No se encontraron jugadores</p>
            <p className="text-muted-foreground text-sm">
              No hay jugadores registrados en el sistema
            </p>
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default Index;
