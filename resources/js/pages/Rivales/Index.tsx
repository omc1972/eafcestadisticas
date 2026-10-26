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

interface Rival {
  id: number;
  nombre: string;
  usuario: string;
  quimica: number;
  valoracion: number;
  codigo: string;
  divisiones: { id: number }[];
}

interface Props {
  rivales: Rival[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
  {
    title: "Rivales",
    href: "/rivales",
  },
];

const Index: React.FC<Props> = ({ rivales }) => {
  const [filter, setFilter] = useState("");

  const filtered = rivales.filter((r) =>
    r.nombre.toLowerCase().includes(filter.toLowerCase())
  );

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Rivales" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Rivales</h1>
        <div className="flex justify-between items-center mb-4">
          <Input
            placeholder="Buscar..."
            value={filter}
            onChange={(e) => setFilter(e.target.value)}
            className="max-w-md"
          />
          <Button onClick={() => router.get("/rivales/create")}>
            +
          </Button>
        </div>

        {filtered.length > 0 ? (
          <Table className="w-full">
            <TableHeader>
              <TableRow>
                <TableHead className="p-2 border">ID</TableHead>
                <TableHead className="p-2 border">Nombre</TableHead>
                <TableHead className="p-2 border">Código</TableHead>
                <TableHead className="p-2 border">Usuario</TableHead>
                <TableHead className="p-2 border">Química</TableHead>
                <TableHead className="p-2 border">Valoración</TableHead>
                <TableHead className="p-2 border">Divisiones</TableHead>
                <TableHead className="p-2 border">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {filtered.map((rival, i) => (
                <TableRow
                  key={rival.id}
                  className={`cursor-pointer ${i % 2 === 0 ? "" : "bg-muted/30"}`}
                  onClick={() => router.get(`/rivales/${rival.id}`)}
                >
                  <TableCell className="p-2 border">{rival.id}</TableCell>
                  <TableCell className="p-2 border">{rival.nombre}</TableCell>
                  <TableCell className="p-2 border">{rival.codigo}</TableCell>
                  <TableCell className="p-2 border">{rival.usuario}</TableCell>
                  <TableCell className="p-2 border">{rival.quimica}</TableCell>
                  <TableCell className="p-2 border">{rival.valoracion}</TableCell>
                  <TableCell className="p-2 border">
                    {rival.divisiones.length}
                  </TableCell>
                  <TableCell className="p-2 border" onClick={(e) => e.stopPropagation()}>
                    <AccionesTabla id={rival.id} basePath="/rivales" />
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        ) : (
          <div className="text-center py-8">
            <p className="text-muted-foreground text-lg">No hay rivales registrados</p>
            <p className="text-muted-foreground text-sm">
              Crea uno nuevo con el botón +
            </p>
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default Index;
  