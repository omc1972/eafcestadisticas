import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
    entrenadores: { id: number; nombre: string }[];
    equipaciones: { id: number; nombre: string }[];
    estadios: { id: number; nombre: string }[];
    ligas: { id: number; nombre: string }[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Create: React.FC<Props> = ({ entrenadores, equipaciones, estadios, ligas }) => {
    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "codigo", label: "Código", type: "text" },
        { name: "pais", label: "País", type: "text" },
        { name: "liga", label: "Liga (texto)", type: "text" },
        {
            name: "entrenador_id",
            label: "Entrenador",
            type: "select",
            options: entrenadores.map(e => ({ label: e.nombre, value: e.id })),
        },
        {
            name: "equipacion_id",
            label: "Equipación",
            type: "select",
            options: equipaciones.map(e => ({ label: e.nombre, value: e.id })),
        },
        {
            name: "estadio_id",
            label: "Estadio",
            type: "select",
            options: estadios.map(e => ({ label: e.nombre, value: e.id })),
        },
        {
            name: "liga_id",
            label: "Liga",
            type: "select",
            options: ligas.map(l => ({ label: l.nombre, value: l.id })),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estadios"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Nuevo Equipo</h1>
                <DynamicForm
                    fields={fields}
                    method="post"
                    action="/equipos"
                    cancel="/equipos"
                    initialValues={{
                        nombre: "",
                        codigo: "",
                        pais: "",
                        liga: "",
                        entrenador_id: "",
                        equipacion_id: "",
                        estadio_id: "",
                        liga_id: "",
                       
                    }}
                    onSuccess={() => console.log("Equipo creado con éxito")}
                />
            </div>
        </AppLayout>     
    );
};

export default Create;
