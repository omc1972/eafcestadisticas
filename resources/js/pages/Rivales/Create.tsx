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

const Create: React.FC<Props> = ({}) => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "codigo", label: "Código", type: "text" },
        { name: "usuario", label: "Usuario", type: "text" },
        { name: "quimica", label: "Química", type: "number" },
        { name: "valoracion", label: "Valoración", type: "number" },
        { name: "tactica", label: "Táctica", type: "text" },
        { name: "fecha", label: "Fecha", type: "text" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Rivales"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Nuevo Rival</h1>
                <DynamicForm
                    fields={fields}
                    method="post"
                    action="/rivales"
                    cancel="/rivales"
                    initialValues={{
                        nombre: "",
                        codigo: "",
                        usuario: "",
                        quimica: "",
                        valoracion: "",
                        tactica: "",
                        fecha: "",
                    }}
                    onSuccess={() => console.log("Rival creado con éxito")}
                />
            </div>
        </AppLayout>     
    );
};

export default Create;
