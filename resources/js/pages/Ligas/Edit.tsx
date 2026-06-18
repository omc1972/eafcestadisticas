import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
    liga: {
        id: number;
        nombre: string;
        pais: string | null;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: dashboard().url,
    },
];

const Edit: React.FC<Props> = ({ liga }) => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "pais", label: "País", type: "text" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Editar Liga"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Editar Liga</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/ligas/${liga.id}`}
                    cancel="/ligas"
                    initialValues={{
                        nombre: liga.nombre,
                        pais: liga.pais || "",
                    }}
                    onSuccess={() => console.log("Liga actualizada con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
