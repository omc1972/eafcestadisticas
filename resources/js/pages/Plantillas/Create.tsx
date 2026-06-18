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

type Props = {
  equipos: { id: number; nombre: string }[];
  temporadas: { id: number; nombre: string }[];
  campeonatos: { id: number; nombre: string }[];
  jugadores: { id: number; nombre: string }[];
};

const Create: React.FC<Props> = ({ equipos, temporadas, campeonatos, jugadores }) => {

    const equiposOptions = equipos.map(e => ({
        value : e.id,
        label : e.nombre
    }));


    const temporadasOptions = temporadas.map(t => ({
      value : t.id,
      label : t.nombre
    }));
    const campeonatosOptions = campeonatos.map(c => ({ value: c.id, label: c.nombre }));

     const fields = [
        { 
            name :'equipo_id', 
            label : "Equipo", 
            type : "select",
            options : equiposOptions
        },
        { 
            name :'temporada_id', 
            label : "Temporada",
            type : "select",
            options : temporadasOptions
        },
        {
            name: "campeonato_id",
            label: "Campeonato",
            type: "select",
            options: campeonatosOptions
        },
        {
          name: "jugadores",
          label: "Jugadores",
          type: "dynamicArray",
          fields: [
            {
              name: "jugador_id",
              label: "Jugador",
              type: "select",
              options: jugadores.map(j => ({ value: j.id, label: j.nombre }))
            },
            { name: "dorsal", label: "Dorsal", type: "text" },
            { name: "es_titular", label: "Titular", type: "boolean" },
          ]
        }
    ];

    return (
      <AppLayout breadcrumbs={breadcrumbs}>
        <Head title="Plantillas"/>
        <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
          <h1 className="text-2xl font-bold mb-4">Nueva Plantilla</h1>
          <DynamicForm
            fields={fields}
            method="post"
            action="/plantillas"
            cancel="/plantillas"
            initialValues={{
              jugador_id: 0,
              equipo_id: 0,
              campeonato_id: campeonatosOptions.length > 0 ? campeonatosOptions[0].value : null,
            }}
            onSuccess={() => console.log("Plantilla creada con éxito")}
          />
      </div>
    </AppLayout>
  );
};

export default Create;
