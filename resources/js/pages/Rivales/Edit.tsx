import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
    rival: {
        id: number;
        nombre: string;
        codigo: string;
        usuario?: string;
        quimica?: number;
        valoracion?: number;
        tactica?: string;
        fecha?: string;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ rival }) => {
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
                <h1 className="text-2xl font-bold mb-4">Editar Rival</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/rivales/${rival.id}`}
                    cancel="/rivales"
                    initialValues={{
                        nombre: rival.nombre,
                        codigo: rival.codigo,
                        usuario: rival.usuario || "",
                        quimica: rival.quimica || "",
                        valoracion: rival.valoracion || "",
                        tactica: rival.tactica || "",
                        fecha: rival.fecha || "",
                    }}
                    onSuccess={() => console.log("Rival actualizado con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
