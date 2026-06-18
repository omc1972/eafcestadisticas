import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

type Equipacion = {
    id: number;
    nombre: string;
};

interface Props {
  equipacion: Equipacion;
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ equipacion }) => {

    const fields = [
        { name :'nombre', label : "Nombre", type : "text" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Equipaciones"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Editar</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/equipaciones/${equipacion.id}`}
                    cancel="/equipaciones"
                    initialValues={{
                        nombre: equipacion.nombre,
                    }}
                    onSuccess={() => console.log("Equipación actualizado con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
