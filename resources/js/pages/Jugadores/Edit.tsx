import React, { useState } from "react";
import { Head, router , Link} from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

type Jugador = {
    id: number;
    nombre: string;
    fecha_nacimiento: string;
    nacionalidad: string;
    altura: number;
    peso: number;
    posicion: string;
    ritmo?: number;
    aceleracion?: number;
    velocidad?: number;
    tiro?: number;
    pos_ataque?: number;
    finalizacion?: number;
    potencia_de_tiro?: number;
    tiro_lejano?: number;
    voleas?: number;
    penalties?: number;
    pase?: number;
    vision?: number;
    centros?: number;
    precision_falta?: number;
    pase_corto?: number;
    pase_largo?: number;
    efecto?: number;
    regate?: number;
    agilidad?: number;
    equilibrio?: number;
    anticipacion?: number;
    control_de_balon?: number;
    regates?: number;
    compostura?: number;
    defensa?: number;
    intercepciones?: number;
    precision_cabezazo?: number;
    capacidad_defensiva?: number;
    robos?: number;
    entradas?: number;
    fisico?: number;
    salto?: number;
    resistencia?: number;
    fuerza?: number;
    agresividad?: number;
    media?: number;
    estilo_quimica?: string;
};

interface Estilo {
  id: number;
  nombre: string;
}

interface EstiloJugador {
  estilo_id: number;
}

interface Props {
  jugador: Jugador;
  estilos: Estilo[];
  estiloJugadores: EstiloJugador[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({ jugador, estilos, estiloJugadores }) => {

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

    const formatDate = (dateString: string | null | undefined) => {
      if (!dateString) return "";
      // Si ya está en formato YYYY-MM-DD, devuélvelo igual
      if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) return dateString;
      const d = new Date(dateString);
      if (isNaN(d.getTime())) return "";
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const day = String(d.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    };

    return (
         <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Jugadores"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold mb-4">Editar Jugador</h1>
                <DynamicForm
                    fields={fields}
                    method="put"
                    action={`/jugadores/${jugador.id}`}
                    cancel="/jugadores"
                    initialValues={{
                        id: jugador.id ?? 0,
                        nombre: jugador.nombre ?? "",
                        fecha_nacimiento: formatDate(jugador.fecha_nacimiento),
                        nacionalidad: jugador.nacionalidad ?? "",
                        altura: jugador.altura ?? 0,
                        peso: jugador.peso ?? 0,
                        posicion: jugador.posicion ?? "",
                        ritmo: jugador.ritmo ?? 0,
                        aceleracion: jugador.aceleracion ?? 0,
                        velocidad: jugador.velocidad ?? 0,
                        tiro: jugador.tiro ?? 0,
                        pos_ataque: jugador.pos_ataque ?? 0,
                        finalizacion: jugador.finalizacion ?? 0,
                        potencia_de_tiro: jugador.potencia_de_tiro ?? 0,
                        tiro_lejano: jugador.tiro_lejano ?? 0,
                        voleas: jugador.voleas ?? 0,
                        penalties: jugador.penalties ?? 0,
                        pase: jugador.pase ?? 0,
                        vision: jugador.vision ?? 0,
                        centros: jugador.centros ?? 0,
                        precision_falta: jugador.precision_falta ?? 0,
                        pase_corto: jugador.pase_corto ?? 0,
                        pase_largo: jugador.pase_largo ?? 0,
                        efecto: jugador.efecto ?? 0,
                        regate: jugador.regate ?? 0,
                        agilidad: jugador.agilidad ?? 0,
                        equilibrio: jugador.equilibrio ?? 0,
                        anticipacion: jugador.anticipacion ?? 0,
                        control_de_balon: jugador.control_de_balon ?? 0,
                        regates: jugador.regates ?? 0,
                        compostura: jugador.compostura ?? 0,
                        defensa: jugador.defensa ?? 0,
                        intercepciones: jugador.intercepciones ?? 0,
                        precision_cabezazo: jugador.precision_cabezazo ?? 0,
                        capacidad_defensiva: jugador.capacidad_defensiva ?? 0,
                        robos: jugador.robos ?? 0,
                        entradas: jugador.entradas ?? 0,
                        fisico: jugador.fisico ?? 0,
                        salto: jugador.salto ?? 0,
                        resistencia: jugador.resistencia ?? 0,
                        fuerza: jugador.fuerza ?? 0,
                        agresividad: jugador.agresividad ?? 0,
                        media: jugador.media ?? 0,
                        estilo_quimica: jugador.estilo_quimica ?? "Básico",
                        estilos: estiloJugadores || [],
                    }}
                    onSuccess={() => console.log("Jugador actualizado con éxito")}
                />
            </div>
        </AppLayout>
    );
};

export default Edit;
