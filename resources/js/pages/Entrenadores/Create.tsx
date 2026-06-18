import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
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

const Create: React.FC<Props> = ({ entrenadores }) => {
  const fields = [
    { name :'nombre', label : "Nombre", type : "text" },
    { name :'pais', label : "País", type : "text" },
    { name :'tactica', label : "Táctica", type : "text" },
  ];
  
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Entrenadores"/>
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Nuevo Entrenador</h1>
        <DynamicForm
          fields={fields}
          method="post"
          action="/entrenadores"
          cancel="/entrenadores"
          initialValues={{ 
            nombre: "", 
            pais: "", 
            tactica: "", 
          }}
          onSuccess={() => console.log("Entrenador creado con éxito")}
        />
      </div>
    </AppLayout>
  );
}

export default Create;
