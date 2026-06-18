import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
    temporada: {
        id: number;
        nombre: string;
        activo: boolean;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ temporada }) => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "activo", label: "Activo", type: "checkbox" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Jugadores"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Editar Temporada</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/temporadas/${temporada.id}`}
                    cancel="/temporadas"
                    initialValues={{
                        nombre: temporada.nombre,
                        activo: temporada.activo,
                    }}
                    onSuccess={() => console.log("Temporada actualizada con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
