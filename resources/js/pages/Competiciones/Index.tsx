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

interface Competicion {
  id: number;
  nombre: string;
}

interface Props {
  competiciones: Competicion[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Index: React.FC<Props> = ({ competiciones }) => {
  const [globalFilter, setGlobalFilter] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const rowsPerPage = 10;
  const filtered = competiciones.filter((c) => c.nombre.toLowerCase().includes(globalFilter.toLowerCase()));
  const paginated = filtered.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage);
  const totalPages = Math.ceil(filtered.length / rowsPerPage);

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Competiciones" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Competiciones</h1>
        <div className="flex justify-between items-center mb-4">
          <Input
            placeholder="Buscar..."
            value={globalFilter}
            onChange={(e) => setGlobalFilter(e.target.value)}
            className="max-w-md"
          />
          <Button onClick={() => router.get("/competiciones/create")}>+</Button>
        </div>
        {paginated.length > 0 ? (
          <>
            <Table className="w-full">
              <TableHeader>
                <TableRow>
                  <TableHead className="p-2 border">Nombre</TableHead>
                  <TableHead className="p-2 border">Acciones</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {paginated.map((row, i) => (
                  <TableRow
                    key={row.id}
                    className={`cursor-pointer ${i % 2 === 0 ? "" : "bg-muted/30"}`}
                    onClick={() => router.get(`/competiciones/${row.id}`)}
                  >
                    <TableCell className="p-2 border">{row.nombre}</TableCell>
                    <TableCell className="p-2 border">
                      <AccionesTabla id={row.id} basePath="/competiciones" />
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
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
            <p className="text-muted-foreground text-lg">No se encontraron competiciones</p>
            <p className="text-muted-foreground text-sm">No hay competiciones registradas en el sistema</p>
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default Index;
