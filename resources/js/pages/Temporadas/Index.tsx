import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { ListOrdered } from "lucide-react";
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

interface Temporada {
    id: number;
    nombre: string;
    activo: boolean;
}

interface Props {
    temporadas: Temporada[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ temporadas }) => {
    const [globalFilter, setGlobalFilter] = useState("");
    const [currentPage, setCurrentPage] = useState(1);
    const rowsPerPage = 10;
    const filtered = temporadas.filter(
        (t) =>
           t.nombre.toLowerCase().includes(globalFilter.toLowerCase())
    );

    const paginated = filtered.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );

    const totalPages = Math.ceil(filtered.length / rowsPerPage);
    const pages = Array.from({ length: totalPages }, (_, i) => i + 1);
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Temporadas"/>
              <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Temporadas</h1>
                <div className="flex justify-between items-center mb-4">        
                    <Input
                        placeholder="Buscar..."
                        value={globalFilter}
                        onChange={(e) => setGlobalFilter(e.target.value)}
                        className="max-w-md"
                    />
                    <Button onClick={() => router.get("/temporadas/create")} className="bg-blue-600 text-white hover:bg-blue-700">+</Button>
                </div>
                {paginated.length > 0 ? (
                  <>
                    <Table className="w-full">
                      <TableHeader>
                        <TableRow className="bg-gray-200">
                          <TableHead className="p-2 border">ID</TableHead>
                          <TableHead className="p-2 border">Nombre</TableHead>
                          <TableHead className="p-2 border">Activa</TableHead>
                          <TableHead className="p-2 border">Gestión Ligas</TableHead>
                          <TableHead className="p-2 border">Eliminatorias</TableHead>
                          <TableHead className="p-2 border">Acciones</TableHead>
                        </TableRow>
                      </TableHeader>
                      <TableBody>
                        {paginated.map((row, i) => (
                          <TableRow
                            key={row.id}
                            className={`cursor-pointer ${
                              i % 2 === 0 ? "bg-gray-800" : "bg-gray-750"
                            }`}
                          >
                            <TableCell className="p-2 border" onClick={() => router.get(`/temporadas/${row.id}`)}>{row.id}</TableCell>
                            <TableCell className="p-2 border" onClick={() => router.get(`/temporadas/${row.id}`)}>{row.nombre}</TableCell>
                            <TableCell className="p-2 border" onClick={() => router.get(`/temporadas/${row.id}`)}>{row.activo ? "Sí" : "No"}</TableCell>
                            <TableCell className="p-2 border" onClick={(e) => e.stopPropagation()}>
                              <Button
                                variant="outline"
                                size="sm"
                                onClick={(e) => {
                                  e.stopPropagation();
                                  router.get(`/temporadas/${row.id}/gestion-ligas`);
                                }}
                                className="gap-2"
                              >
                                <ListOrdered className="h-4 w-4" />
                                Ligas
                              </Button>
                            </TableCell>
                            <TableCell className="p-2 border" onClick={(e) => e.stopPropagation()}>
                              <Button
                                variant="outline"
                                size="sm"
                                onClick={(e) => {
                                  e.stopPropagation();
                                  router.get(`/campeonatos/${row.id}/eliminatorias`);
                                }}
                                className="gap-2"
                              >
                                🏆
                                Copa
                              </Button>
                            </TableCell>
                            <TableCell>
                              <AccionesTabla 
                                id={row.id} 
                                  basePath="/temporadas" 
                              />
                            </TableCell>
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
                          <i className="pi pi-inbox text-4xl text-gray-400 mb-2"></i>
                          <p className="text-gray-500 text-lg">
                            No se encontraron Temporadas
                          </p>
                          <p className="text-gray-400 text-sm">
                            No hay Temporadas registradas en el sistema
                          </p>
                        </div>
                      )}
            </div>
        </AppLayout>
    );
};

export default Index;
