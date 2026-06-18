import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import {
  Table,
  TableHeader,
  TableRow,
  TableHead,
  TableBody,
  TableCell,
} from "@/components/ui/table";
import { Input } from "@/components/ui/input";
import AccionesTabla from "@/components/acciones-tabla";
import ControlesPaginacion from "@/components/controles-paginacion";

interface Rival {
  id: number;
  nombre: string;
  usuario: string;
  quimica: string;
  valoracion: string;
  codigo: string;
}

interface Props {
  rivales: Rival[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ rivales }) => {
  const [globalFilter, setGlobalFilter] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const rowsPerPage = 10;

  const filtered = rivales.filter(
    (r) =>
      r.nombre.toLowerCase().includes(globalFilter.toLowerCase()) ||
      r.codigo.toLowerCase().includes(globalFilter.toLowerCase()) ||
      r.usuario.toLowerCase().includes(globalFilter.toLowerCase())
  );

  const totalPages = Math.ceil(filtered.length / rowsPerPage);
  const paginated = filtered.slice(
    (currentPage - 1) * rowsPerPage,
    currentPage * rowsPerPage
  );

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Rivales" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Rivales</h1>
        <div className="flex justify-between items-center mb-4">
          <Input
            placeholder="Buscar..."
            value={globalFilter}
            onChange={(e) => setGlobalFilter(e.target.value)}
            className="max-w-md"
          />
          <Button
            onClick={() => router.get("/rivales/create")}
            className="bg-blue-600 text-white hover:bg-blue-700"
          >
            +
          </Button>
        </div>

        {paginated.length > 0 ? (
          <>
            <Table className="w-full">
              <TableHeader>
                <TableRow className="bg-gray-200">
                  <TableHead className="p-2 border">ID</TableHead>
                  <TableHead className="p-2 border">Nombre</TableHead>
                  <TableHead className="p-2 border">Código</TableHead>
                  <TableHead className="p-2 border">Usuario</TableHead>
                  <TableHead className="p-2 border">Química</TableHead>
                  <TableHead className="p-2 border">Valoración</TableHead>
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
                    onClick={() => router.get(`/equipos/${row.id}/edit`)}
                  >
                    <TableCell className="p-2 border">{row.id}</TableCell>
                    <TableCell className="p-2 border">{row.nombre}</TableCell>
                    <TableCell className="p-2 border">{row.codigo}</TableCell>
                    <TableCell className="p-2 border">{row.usuario}</TableCell>
                    <TableCell className="p-2 border">{row.quimica}</TableCell>
                    <TableCell className="p-2 border">
                      {row.valoracion}
                    </TableCell>
                    <TableCell>
                      <AccionesTabla 
                        id={row.id} 
                        basePath="/rivales" 
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
            <p className="text-gray-500 text-lg">No se encontraron equipos</p>
            <p className="text-gray-400 text-sm">
              No hay equipos registrados en el sistema
            </p>
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default Index;
  