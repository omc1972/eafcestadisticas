import React from "react";
import { Link, Head } from "@inertiajs/react";
import DynamicForm from "@/components/forms/DynamicForm";
import { dashboard } from "@/routes";
import AppLayout from "@/layouts/app-layout";

type Estadio = {
    id: number;
    nombre: string;
};

interface Props {
    estadio: Estadio;
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ estadio }) => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estadios"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="p-6">
                    <h1 className="text-2xl font-bold mb-4">Editar Estadio</h1>
                    <DynamicForm
                        fields={fields}
                        method="put"
                        action={`/estadios/${estadio.id}`}
                         cancel="/estadios"
                        initialValues={{
                            nombre: estadio.nombre,
                        }}
                        onSuccess={() => console.log("Estadio actualizado con éxito")}
                    />
                </div>
            </div>
        </AppLayout>
    );
};

export default Edit;
