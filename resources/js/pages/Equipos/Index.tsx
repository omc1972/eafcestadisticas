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

interface Equipo {
    id: number;
    nombre: string;
    codigo:string;
    escudo:string;
}

interface Props {
    equipos: Equipo[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ equipos }) => {

    const [globalFilter, setGlobalFilter] = useState("");
    const [currentPage, setCurrentPage] = useState(1);
    const rowsPerPage = 10;
    const filtered = equipos.filter(
        (e) =>
            e.nombre.toLowerCase().includes(globalFilter.toLowerCase()) ||
            e.codigo.toLowerCase().includes(globalFilter.toLowerCase()) ||
            e.usuario.toLowerCase().includes(globalFilter.toLowerCase())
        );
    
    const paginated = filtered.slice(
        (currentPage - 1) * rowsPerPage,
        currentPage * rowsPerPage
    );
    
    const totalPages = Math.ceil(filtered.length / rowsPerPage);
    const pages = Array.from({ length: totalPages }, (_, i) => i + 1);

return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Equipos"/>
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Equipos</h1>
        <div className="flex justify-between items-center mb-4">        
          <Input
            placeholder="Buscar..."
            value={globalFilter}
            onChange={(e) => setGlobalFilter(e.target.value)}
            className="max-w-md"
          />
          <Button onClick={() => router.get("/equipos/create")} className="bg-blue-600 text-white hover:bg-blue-700">+</Button>
        </div>
        {paginated.length > 0 ? (
          <>
            <Table className="w-full">
            <TableHeader>
              <TableRow className="bg-gray-200">
                <TableHead className="p-2 border"></TableHead>
                <TableHead className="p-2 border">Nombre</TableHead>
                <TableHead className="p-2 border">Código</TableHead>
                <TableHead className="p-2 border">Estadio</TableHead>
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
                  onClick={() => router.get(`/equipos/${row.id}`)}
                >
                  <TableCell className="p-2 border"><img src={`/storage/images/${row.escudo}`} alt="escudo"  className="w-6 h-6 object-contain mx-auto"/></TableCell>
                  <TableCell className="p-2 border">{row.nombre}</TableCell>
                  <TableCell className="p-2 border">{row.codigo}</TableCell>
                  <TableCell className="p-2 border">{row.estadio.nombre}</TableCell>
                  <TableCell><AccionesTabla 
                              id={row.id} 
                              basePath="/equipos" 
                            /></TableCell>
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
                No se encontraron equipos
              </p>
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
