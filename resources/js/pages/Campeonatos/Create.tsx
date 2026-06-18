import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

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

const Create: React.FC = () => {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Campeonatos"/>
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Nuevo Campeonato</h1>
        <DynamicForm
          fields={fields}
          method="post"
          action="/campeonatos"
          cancel="/campeonatos"
          initialValues={{
            nombre: "",
            tipo: "",
          }}
          onSuccess={() => router.visit("/campeonatos")}
        />
      </div>
    </AppLayout>
  );
};

export default Create;
