import React, { useState, useEffect } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import EstiloIcon from "@/components/estilo-icon";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import AccionesTabla from "@/components/acciones-tabla";
import ControlesPaginacion from "@/components/controles-paginacion";
import { getStatsConfig } from "@/lib/estadisticas";

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
  const rowsPerPage = 12;

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
        <span className="text-xs font-medium w-8 text-gray-400">{label}</span>
        <div className="flex-1 bg-gray-700 rounded-full h-2 overflow-hidden">
          <div
            className="h-full bg-gradient-to-r from-green-500 to-blue-500 transition-all"
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
        <div className="flex justify-between items-center mb-4">
          <Input
            placeholder="Buscar..."
            value={globalFilter}
            onChange={(e) => {
              setGlobalFilter(e.target.value);
              setCurrentPage(1);
            }}
            className="max-w-md"
          />
          <Button
            onClick={() => router.get("/jugadores/create")}
            className="bg-blue-600 text-white hover:bg-blue-700"
          >
            +
          </Button>
        </div>

        {paginated.length > 0 ? (
          <>
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
                        <div className="flex gap-2 text-sm text-gray-400 mt-1">
                          <span>{jugador.posicion}</span>
                          <span>•</span>
                          <span>{calcularEdad(jugador.fecha_nacimiento)} años</span>
                          <span>•</span>
                          <span>{jugador.nacionalidad}</span>
                        </div>
                      </div>
                      <div className="flex flex-col items-end gap-2">
                        <div className="text-2xl font-bold text-blue-500">
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
                      <div className="pt-2 mt-2 border-t border-gray-700">
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
            <i className="pi pi-inbox text-4xl text-gray-400 mb-2"></i>
            <p className="text-gray-500 text-lg">No se encontraron jugadores</p>
            <p className="text-gray-400 text-sm">
              No hay jugadores registrados en el sistema
            </p>
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default Index;
