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

interface Props {
  estilos: Array<{ id: number; nombre: string }>;
}

const Create: React.FC<Props> = ({ estilos }) => {
    const [posicion, setPosicion] = useState("");

    const estilosOptions = estilos.map(e => ({
        value : e.id,
        label : e.nombre
    }));

    // Función para obtener los labels dinámicos según posición
    const getAttributeLabel = (fieldName: string, isGoalkeeper: boolean) => {
        if (isGoalkeeper) {
            const goalkeeperLabels: Record<string, string> = {
                ritmo: "Velocidad",
                aceleracion: "Aceleración",
                velocidad: "Velocidad",
                tiro: "Parada",
                pos_ataque: "Paradas",
                pase: "Saque",
                vision: "Pases",
                regate: "Reflejos",
                agilidad: "Reflejos",
                defensa: "Posición",
                intercepciones: "Colocación",
                fisico: "Salto",
                salto: "Estirada",
            };
            return goalkeeperLabels[fieldName] || fieldName;
        }
        return fieldName.replace(/_/g, " ").charAt(0).toUpperCase() + fieldName.slice(1).replace(/_/g, " ");
    };

    const isGoalkeeper = posicion === "POR";
    const goalkeeperFields = ["ritmo", "aceleracion", "velocidad", "tiro", "pos_ataque", "pase", "vision", "regate", "agilidad", "defensa", "intercepciones", "fisico", "salto"];

    const allFields = [
        { name: "nombre", label: "Nombre", type: "text" },
        { name: "fecha_nacimiento", label: "Fecha de nacimiento", type: "date" },
        { name: "nacionalidad", label: "Nacionalidad", type: "text" },
        { name: "altura", label: "Altura (cm)", type: "number" },
        { name: "peso", label: "Peso (kg)", type: "number" },
        { name: "posicion", label: "Posición", type: "text" },

        { name: "ritmo", label: getAttributeLabel("ritmo", isGoalkeeper), type: "number" },
        { name: "aceleracion", label: getAttributeLabel("aceleracion", isGoalkeeper), type: "number" },
        { name: "velocidad", label: getAttributeLabel("velocidad", isGoalkeeper), type: "number" },
        { name: "tiro", label: getAttributeLabel("tiro", isGoalkeeper), type: "number" },
        { name: "pos_ataque", label: getAttributeLabel("pos_ataque", isGoalkeeper), type: "number" },
        { name: "finalizacion", label: getAttributeLabel("finalizacion", isGoalkeeper), type: "number" },
        { name: "potencia_de_tiro", label: getAttributeLabel("potencia_de_tiro", isGoalkeeper), type: "number" },
        { name: "tiro_lejano", label: getAttributeLabel("tiro_lejano", isGoalkeeper), type: "number" },
        { name: "voleas", label: getAttributeLabel("voleas", isGoalkeeper), type: "number" },
        { name: "penalties", label: getAttributeLabel("penalties", isGoalkeeper), type: "number" },

        { name: "pase", label: getAttributeLabel("pase", isGoalkeeper), type: "number" },
        { name: "vision", label: getAttributeLabel("vision", isGoalkeeper), type: "number" },
        { name: "centros", label: getAttributeLabel("centros", isGoalkeeper), type: "number" },
        { name: "precision_falta", label: getAttributeLabel("precision_falta", isGoalkeeper), type: "number" },
        { name: "pase_corto", label: getAttributeLabel("pase_corto", isGoalkeeper), type: "number" },
        { name: "pase_largo", label: getAttributeLabel("pase_largo", isGoalkeeper), type: "number" },
        { name: "efecto", label: getAttributeLabel("efecto", isGoalkeeper), type: "number" },

        { name: "regate", label: getAttributeLabel("regate", isGoalkeeper), type: "number" },
        { name: "agilidad", label: getAttributeLabel("agilidad", isGoalkeeper), type: "number" },
        { name: "equilibrio", label: getAttributeLabel("equilibrio", isGoalkeeper), type: "number" },
        { name: "anticipacion", label: getAttributeLabel("anticipacion", isGoalkeeper), type: "number" },
        { name: "control_de_balon", label: getAttributeLabel("control_de_balon", isGoalkeeper), type: "number" },
        { name: "regates", label: getAttributeLabel("regates", isGoalkeeper), type: "number" },
        { name: "compostura", label: getAttributeLabel("compostura", isGoalkeeper), type: "number" },

        { name: "defensa", label: getAttributeLabel("defensa", isGoalkeeper), type: "number" },
        { name: "intercepciones", label: getAttributeLabel("intercepciones", isGoalkeeper), type: "number" },
        { name: "precision_cabezazo", label: getAttributeLabel("precision_cabezazo", isGoalkeeper), type: "number" },
        { name: "capacidad_defensiva", label: getAttributeLabel("capacidad_defensiva", isGoalkeeper), type: "number" },
        { name: "robos", label: getAttributeLabel("robos", isGoalkeeper), type: "number" },
        { name: "entradas", label: getAttributeLabel("entradas", isGoalkeeper), type: "number" },

        { name: "fisico", label: getAttributeLabel("fisico", isGoalkeeper), type: "number" },
        { name: "salto", label: getAttributeLabel("salto", isGoalkeeper), type: "number" },
        { name: "resistencia", label: getAttributeLabel("resistencia", isGoalkeeper), type: "number" },
        { name: "fuerza", label: getAttributeLabel("fuerza", isGoalkeeper), type: "number" },
        { name: "agresividad", label: getAttributeLabel("agresividad", isGoalkeeper), type: "number" },

        { name: "media", label: "Media", type: "number" },
        { name: "estilo_quimica", label: "Estilo Química", type: "text" },
        {
          name: "estilos",
          label: "Estilos",
          type: "multiCheckbox",
          options: estilos.map(e => ({ value: e.id, label: e.nombre })),
        },
    ];

    const fields = isGoalkeeper 
      ? allFields.filter(f => !f.name.match(/^(finalizacion|potencia_de_tiro|tiro_lejano|voleas|penalties|centros|precision_falta|pase_corto|pase_largo|efecto|equilibrio|anticipacion|control_de_balon|regates|compostura|precision_cabezazo|capacidad_defensiva|robos|entradas|resistencia|fuerza|agresividad)$/))
      : allFields;

    const handleFieldChange = (fieldName: string, value: any) => {
      if (fieldName === "posicion") {
        setPosicion(value);
      }
    };

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
                    onFieldChange={handleFieldChange}
                />
            </div>
        </AppLayout>
    );
};

export default Create;
