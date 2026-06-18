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

const Create = () => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "activo", label: "Activo", type: "checkbox" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estadios"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Nueva Temporada</h1>
                <DynamicForm
                    fields={fields}
                    method="post"
                    action="/temporadas"
                    cancel="/temporadas"
                    initialValues={{
                        nombre: "",
                        activo: true,
                    }}
                    onSuccess={() => console.log("Temporada creada con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Create;
