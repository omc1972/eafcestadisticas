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

interface SquadBattle {
  id: number;
  nombre: string;
  temporada: { nombre: string } | null;
  divisiones: { id: number }[];
}

interface Props {
  squadBattles: SquadBattle[];
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Dashboard", href: dashboard().url },
  { title: "Squad Battles", href: "/squad-battles" },
];

const Index: React.FC<Props> = ({ squadBattles }) => {
  const [filter, setFilter] = useState("");

  const filtered = squadBattles.filter((sb) =>
    sb.nombre.toLowerCase().includes(filter.toLowerCase())
  );

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Squad Battles" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Squad Battles</h1>
        <div className="flex justify-between items-center mb-4">
          <Input
            placeholder="Buscar..."
            value={filter}
            onChange={(e) => setFilter(e.target.value)}
            className="max-w-md"
          />
          <Button onClick={() => router.get("/squad-battles/create")}>
            +
          </Button>
        </div>

        {filtered.length > 0 ? (
          <Table className="w-full">
            <TableHeader>
              <TableRow>
                <TableHead className="p-2 border">ID</TableHead>
                <TableHead className="p-2 border">Nombre</TableHead>
                <TableHead className="p-2 border">Temporada</TableHead>
                <TableHead className="p-2 border">Divisiones</TableHead>
                <TableHead className="p-2 border">Acciones</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {filtered.map((sb, i) => (
                <TableRow
                  key={sb.id}
                  className={`cursor-pointer ${i % 2 === 0 ? "" : "bg-muted/30"}`}
                  onClick={() => router.get(`/squad-battles/${sb.id}`)}
                >
                  <TableCell className="p-2 border">{sb.id}</TableCell>
                  <TableCell className="p-2 border">{sb.nombre}</TableCell>
                  <TableCell className="p-2 border">
                    {sb.temporada?.nombre ?? "—"}
                  </TableCell>
                  <TableCell className="p-2 border">
                    {sb.divisiones.length}
                  </TableCell>
                  <TableCell className="p-2 border" onClick={(e) => e.stopPropagation()}>
                    <AccionesTabla id={sb.id} basePath="/squad-battles" />
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        ) : (
          <div className="text-center py-8">
            <p className="text-muted-foreground text-lg">No hay Squad Battles registrados</p>
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
