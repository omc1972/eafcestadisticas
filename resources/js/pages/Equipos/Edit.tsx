import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
    equipo: {
        id: number;
        nombre: string;
        codigo: string;
        pais: string;
        liga: string;
        entrenador_id: number;
        equipacion_id: number;
        estadio_id: number;
        liga_id: number | null;
    };
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

const Edit: React.FC<Props> = ({ equipo, entrenadores, equipaciones, estadios, ligas }) => {
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
            <Head title="Jugadores"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Editar Equipo</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/equipos/${equipo.id}`}
                    cancel="/equipos"
                    initialValues={{
                        nombre: equipo.nombre,
                        codigo: equipo.codigo,
                        pais: equipo.pais,
                        liga: equipo.liga,
                        entrenador_id: equipo.entrenador_id.toString(),
                        equipacion_id: equipo.equipacion_id.toString(),
                        estadio_id: equipo.estadio_id.toString(),
                        liga_id: equipo.liga_id ? equipo.liga_id.toString() : "",
                    }}
                    onSuccess={() => console.log("Equipo actualizado con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
