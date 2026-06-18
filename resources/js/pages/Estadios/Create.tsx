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

const Create: React.FC = () => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estadios"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Nuevo Estadio</h1>
                <DynamicForm
                    fields={fields}
                    method="post"
                    action="/estadios"
                    cancel="/estadios"
                    initialValues={{
                        nombre: "",
                    }}
                    onSuccess={() => console.log("Estadio creado con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Create;
