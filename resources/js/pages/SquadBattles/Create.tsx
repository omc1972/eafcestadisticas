import React, { useState } from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

interface Temporada {
  id: number;
  nombre: string;
}

interface Props {
  temporadas: Temporada[];
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Dashboard", href: dashboard().url },
  { title: "Squad Battles", href: "/squad-battles" },
  { title: "Nuevo", href: "/squad-battles/create" },
];

const Create: React.FC<Props> = ({ temporadas }) => {
  const [form, setForm] = useState({ nombre: "", temporada_id: "" });
  const [errors, setErrors] = useState<Record<string, string>>({});

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    router.post("/squad-battles", form, {
      onError: (errs) => setErrors(errs),
    });
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Nuevo Squad Battle" />
      <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 max-w-lg">
        <h1 className="text-2xl font-bold mb-4">Nuevo Squad Battle</h1>
        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
          <div>
            <Label htmlFor="nombre">Nombre</Label>
            <Input
              id="nombre"
              value={form.nombre}
              onChange={(e) => setForm({ ...form, nombre: e.target.value })}
              placeholder="Ej: Squad Battles Semana 1"
            />
            {errors.nombre && (
              <p className="text-red-500 text-sm mt-1">{errors.nombre}</p>
            )}
          </div>
          <div>
            <Label htmlFor="temporada_id">Temporada (opcional)</Label>
            <select
              id="temporada_id"
              value={form.temporada_id}
              onChange={(e) => setForm({ ...form, temporada_id: e.target.value })}
              className="w-full border rounded px-3 py-2 bg-background text-foreground"
            >
              <option value="">— Sin temporada —</option>
              {temporadas.map((t) => (
                <option key={t.id} value={t.id}>
                  {t.nombre}
                </option>
              ))}
            </select>
          </div>
          <div className="flex gap-2 mt-2">
            <Button type="submit" className="bg-blue-600 text-white hover:bg-blue-700">
              Crear
            </Button>
            <Button
              type="button"
              variant="outline"
              onClick={() => router.get("/squad-battles")}
            >
              Cancelar
            </Button>
          </div>
        </form>
      </div>
    </AppLayout>
  );
};

export default Create;
