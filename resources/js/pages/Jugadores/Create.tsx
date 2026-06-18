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

const Create: React.FC<Props> = ({ estilos }) => {

    const estilosOptions = estilos.map(e => ({
        value : e.id,
        label : e.nombre
    }));

    const fields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "fecha_nacimiento", label: "Fecha de nacimiento", type: "date" },
        { name: "nacionalidad", label: "Nacionalidad", type: "text" },
        { name: "altura", label: "Altura (cm)", type: "number" },
        { name: "peso", label: "Peso (kg)", type: "number" },
        { name: "posicion", label: "Posición", type: "text" },

        // atributos de stats
        { name: "ritmo", label: "Ritmo", type: "number" },
        { name: "aceleracion", label: "Aceleración", type: "number" },
        { name: "velocidad", label: "Velocidad", type: "number" },
        { name: "tiro", label: "Tiro", type: "number" },
        { name: "pos_ataque", label: "Posición de ataque", type: "number" },
        { name: "finalizacion", label: "Finalización", type: "number" },
        { name: "potencia_de_tiro", label: "Potencia de tiro", type: "number" },
        { name: "tiro_lejano", label: "Tiro lejano", type: "number" },
        { name: "voleas", label: "Voleas", type: "number" },
        { name: "penalties", label: "Penaltis", type: "number" },

        { name: "pase", label: "Pase", type: "number" },
        { name: "vision", label: "Visión", type: "number" },
        { name: "centros", label: "Centros", type: "number" },
        { name: "precision_falta", label: "Precisión de falta", type: "number" },
        { name: "pase_corto", label: "Pase corto", type: "number" },
        { name: "pase_largo", label: "Pase largo", type: "number" },
        { name: "efecto", label: "Efecto", type: "number" },

        { name: "regate", label: "Regate", type: "number" },
        { name: "agilidad", label: "Agilidad", type: "number" },
        { name: "equilibrio", label: "Equilibrio", type: "number" },
        { name: "anticipacion", label: "Anticipación", type: "number" },
        { name: "control_de_balon", label: "Control de balón", type: "number" },
        { name: "regates", label: "Regates", type: "number" },
        { name: "compostura", label: "Compostura", type: "number" },

        { name: "defensa", label: "Defensa", type: "number" },
        { name: "intercepciones", label: "Intercepciones", type: "number" },
        { name: "precision_cabezazo", label: "Precisión cabezazo", type: "number" },
        { name: "capacidad_defensiva", label: "Capacidad defensiva", type: "number" },
        { name: "robos", label: "Robos", type: "number" },
        { name: "entradas", label: "Entradas", type: "number" },

        { name: "fisico", label: "Físico", type: "number" },
        { name: "salto", label: "Salto", type: "number" },
        { name: "resistencia", label: "Resistencia", type: "number" },
        { name: "fuerza", label: "Fuerza", type: "number" },
        { name: "agresividad", label: "Agresividad", type: "number" },

        { name: "media", label: "Media", type: "number" },
        { name: "estilo_quimica", label: "Estilo Química", type: "text" },
        {
          name: "estilos",
          label: "Estilos",
          type: "multiCheckbox",
          options: estilos.map(e => ({ value: e.id, label: e.nombre })),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estadios"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Nuevo Jugador</h1>
                <DynamicForm
                    fields={fields}
                    method="post"
                    action="/jugadores"
                    cancel="/jugadores"
                    initialValues={{
                        nombre: "",
                        fecha_nacimiento: "",
                        nacionalidad: "",
                        altura: "",
                        peso: "",
                        posicion: "",
                        ritmo: 0,
                        aceleracion: 0,
                        velocidad: 0,
                        tiro: 0,
                        pos_ataque: 0,
                        finalizacion: 0,
                        potencia_de_tiro: 0,
                        tiro_lejano: 0,
                        voleas: 0,
                        penalties: 0,
                        pase: 0,
                        vision: 0,
                        centros: 0,
                        precision_falta: 0,
                        pase_corto: 0,
                        pase_largo: 0,
                        efecto: 0,
                        regate: 0,
                        agilidad: 0,
                        equilibrio: 0,
                        anticipacion: 0,
                        control_de_balon: 0,
                        regates: 0,
                        compostura: 0,
                        defensa: 0,
                        intercepciones: 0,
                        precision_cabezazo: 0,
                        capacidad_defensiva: 0,
                        robos: 0,
                        entradas: 0,
                        fisico: 0,
                        salto: 0,
                        resistencia: 0,
                        fuerza: 0,
                        agresividad: 0,
                        media: 0,
                        estilo_quimica: 'Basico',
                    }}
                    onSuccess={() => console.log("Jugador creado con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Create;
