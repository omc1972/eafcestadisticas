import React from "react";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: dashboard().url,
    },
];

const Create: React.FC<Props> = () => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "pais", label: "País", type: "text" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Nueva Liga"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Nueva Liga</h1>
                <DynamicForm
                    fields={fields}
                    method="post"
                    action="/ligas"
                    cancel="/ligas"
                    initialValues={{
                        nombre: "",
                        pais: "",
                    }}
                    onSuccess={() => console.log("Liga creada con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Create;
