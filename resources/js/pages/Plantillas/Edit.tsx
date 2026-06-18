import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

type Jugador = {
  id: number; 
  nombre: string
}

interface PlantillaJugadores {
    jugador_id: number;
    dorsal: string;
}

interface Props {
  plantilla: {
    id: number;
    equipo_id: number;
    temporada_id: number;
    campeonato_id?: number;
    jugadores: { id: number }[];
  };
  equipos: { id: number; nombre: string }[];
  temporadas: { id: number; nombre: string }[];
  campeonatos: { id: number; nombre: string }[];
  jugadores: Jugador[];
  jugadoresPlantilla : PlantillaJugadores[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ plantilla, equipos, temporadas, campeonatos, jugadores, jugadoresPlantilla }) => {
    
    const jugadoresFields = jugadores.map(j => ({
        name: "jugador_id",
        label: j.nombre,
        type: "select",
        options: [{ value: j.id, label: j.nombre }]
    }));

    const campeonatosOptions = campeonatos.map(c => ({ value: c.id, label: c.nombre }));
    const fields = [
        { name: "equipo_id", label: "Equipo", type: "select", options: equipos.map(e => ({ label: e.nombre, value: e.id })) },
        { name: "temporada_id", label: "Temporada", type: "select", options: temporadas.map(t => ({ label: t.nombre, value: t.id })) },
        { name: "campeonato_id", label: "Campeonato", type: "select", options: campeonatosOptions },
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
          ]
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Plantillas"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 className="text-2xl font-bold mb-4">Editar Plantilla</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/plantillas/${plantilla.id}`}
                    cancel="plantillas"
                    initialValues={{
                      equipo_id: plantilla.equipo_id,
                      temporada_id: plantilla.temporada_id,
                      campeonato_id: plantilla.campeonato_id || (campeonatosOptions.length > 0 ? campeonatosOptions[0].value : null),
                      jugadores: jugadoresPlantilla || [],
                    }}
                    onSuccess={() => console.log("Plantilla actualizada con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
