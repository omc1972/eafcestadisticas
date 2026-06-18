import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

interface Props {
  competicion: { id: number; nombre: string };
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

export default function Edit({ competicion }: Props) {
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;
    const data = new FormData(form);
    router.post(`/competiciones/${competicion.id}`, Object.fromEntries(data as any), { _method: 'PUT' });
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Editar Competición" />
      <div className="max-w-md">
        <h1 className="text-2xl font-bold mb-4">Editar Competición</h1>
        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <label>Nombre</label>
          <Input name="nombre" defaultValue={competicion.nombre} />
          <div className="flex gap-2">
            <Button type="submit">Guardar</Button>
            <Button variant="outline" onClick={() => router.get('/competiciones')}>Cancelar</Button>
          </div>
        </form>
      </div>
    </AppLayout>
  );
}
