import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

export default function Create() {
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;
    const data = new FormData(form);
    router.post('/competiciones', Object.fromEntries(data as any));
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Nueva Competición" />
      <div className="max-w-md">
        <h1 className="text-2xl font-bold mb-4">Nueva Competición</h1>
        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <label>Nombre</label>
          <Input name="nombre" />
          <div className="flex gap-2">
            <Button type="submit">Crear</Button>
            <Button variant="outline" onClick={() => router.get('/competiciones')}>Cancelar</Button>
          </div>
        </form>
      </div>
    </AppLayout>
  );
}
