import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

type Entrenador = {
    id: number;
    nombre: string;
    pais: string;
    tactica: string;
};

interface Props {
  entrenador: Entrenador;
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ entrenador }) => {

  const fields = [
    { name :'nombre', label : "Nombre", type : "text" },
    { name :'pais', label : "Pais", type : "text" },
    { name :'tactica', label : "Táctica", type : "text" },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Entrenadores"/>
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Editar</h1>
        <DynamicForm
          fields={fields}
          method="put"
          action={`/entrenadores/${entrenador.id}`}
          cancel="/entrenadores"
          initialValues={{
            nombre: entrenador.nombre,
            pais: entrenador.pais,
            tactica: entrenador.tactica,
          }}
          onSuccess={() => console.log("Entrenador actualizado con éxito")}
        />
      </div>
    </AppLayout>
  );
};

export default Edit;
