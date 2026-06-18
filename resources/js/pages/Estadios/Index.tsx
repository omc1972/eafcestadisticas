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

type Estadio = {
    id: number;
    nombre: string;
};

interface Props {
    estadios: Estadio[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ estadios }) => {
    const [globalFilter, setGlobalFilter] = useState("");
    const [currentPage, setCurrentPage] = useState(1);
    const rowsPerPage = 10;
    const filtered = estadios.filter(
      (e) =>
           e.nombre.toLowerCase().includes(globalFilter.toLowerCase())
    );
    
    const paginated = filtered.slice(
      (currentPage - 1) * rowsPerPage,
      currentPage * rowsPerPage
    );
    
    const totalPages = Math.ceil(filtered.length / rowsPerPage);
    const pages = Array.from({ length: totalPages }, (_, i) => i + 1);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estadios"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Entrenadores</h1>
                <div className="flex justify-between items-center mb-4">        
                  <Input
                    placeholder="Buscar..."
                    value={globalFilter}
                    onChange={(e) => setGlobalFilter(e.target.value)}
                    className="max-w-md"
                  />
                  <Button onClick={() => router.get("/estadios/create")} className="bg-blue-600 text-white hover:bg-blue-700">+</Button>
                </div>

            {paginated.length > 0 ? (
                <>
                    <Table className="w-full">
                        <TableHeader>
                            <TableRow className="bg-gray-100">
                            <TableHead className="p-2 font-semibold text-left">ID</TableHead>
                            <TableHead className="p-2 font-semibold text-left">Nombre</TableHead>
                            <TableHead className="p-2 font-semibold text-left">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {paginated.map((row, i) => (
                            <TableRow
                                key={row.id}
                                className={`cursor-pointer ${
                                i % 2 === 0 ? "bg-gray-800" : "bg-gray-750"
                                } hover:bg-gray-100`}
                                onClick={() => router.get(`/estadios/${row.id}/edit`)}
                            >
                                <TableCell className="p-2 border">{row.id}</TableCell>
                                <TableCell className="p-2 border">{row.nombre}</TableCell>
                                <TableCell><AccionesTabla 
                                    id={row.id} 
                                    basePath="/estadios" 
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
                    <p className="text-gray-500 text-lg">No se encontraron estadios</p>
                    <p className="text-gray-400 text-sm">
                        No hay estadios registrados en el sistema
                    </p>
                    </div>
                )}
            </div>
        </AppLayout>
    );
};

export default Index;
