import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
  campeonato: {
    id: number;
    nombre: string;
    fecha_inicio: string;
    fecha_fin: string;
    tipo: string;
  };
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const fields = [
  { name: "nombre", label: "Nombre", type: "text" },
  {
    name: "tipo",
    label: "Tipo",
    type: "select",
    options: [
      { value: "liga", label: "Liga" },
      { value: "mixto", label: "Mixto" },
      { value: "eliminatorias", label: "Eliminatorias" },
    ],
  },
];

const Edit: React.FC<Props> = ({ campeonato }) => {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Campeonatos"/>
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Editar Campeonato</h1>
        <DynamicForm
          fields={fields}
          method="put"
          action={`/campeonatos/${campeonato.id}`}
          cancel="/campeonatos"
          initialValues={{
            nombre: campeonato.nombre,
            tipo: campeonato.tipo,
          }}
          onSuccess={() => router.visit("/campeonatos")}
        />
      </div>
    </AppLayout>
  );
};

export default Edit;
