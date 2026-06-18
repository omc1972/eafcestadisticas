import React from "react";
import { Head } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";

interface Props {
  partido: any;
  equipos: { id: number; nombre: string }[];
  rivales: { id: number; nombre: string }[];
  temporadas: { id: number; nombre: string }[];
  competiciones: { id: number; nombre: string }[];
  campeonatos: { id: number; nombre: string }[];
  jugadores: { id: number; nombre: string }[];
  tiposEventos: { id: number; nombre: string }[];
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: "Dashboard",
    href: dashboard().url,
  },
];

const Edit: React.FC<Props> = ({
  partido,
  equipos,
  rivales,
  temporadas,
  competiciones,
  campeonatos,
  jugadores,
  tiposEventos,
}) => {
  const equiposOptions = equipos.map((e) => ({ value: e.id, label: e.nombre }));
  const rivalesOptions = rivales.map((r) => ({ value: r.id, label: r.nombre }));
  const visitantesOptions = [
    ...rivalesOptions.map((r) => ({ value: r.value, label: `Rival: ${r.label}` })),
    ...equiposOptions.map((e) => ({ value: e.value, label: `Equipo: ${e.label}` })),
  ];
  const temporadasOptions = temporadas.map((t) => ({ value: t.id, label: t.nombre }));
  const competicionesOptions = competiciones.map((c) => ({ value: c.id, label: c.nombre }));
  const campeonatosOptions = campeonatos.map((c) => ({ value: c.id, label: c.nombre }));
  const jugadoresOptions = jugadores.map((j) => ({ value: j.id, label: j.nombre }));
  const tiposEventosOptions = tiposEventos.map((te) => ({ value: te.id, label: te.nombre }));

  const fields: any[] = [
    { name: "temporada_id", label: "Temporada", type: "select", options: temporadasOptions, disabled: true },
    { name: "competicion_id", label: "Competicion", type: "select", options: competicionesOptions, disabled: true },
    { name: "campeonato_id", label: "Campeonato", type: "select", options: campeonatosOptions },
    { name: "equipo_id", label: "Equipo (Local)", type: "select", options: equiposOptions, disabled: true },
    { name: "local_es_rival", label: "Local es rival", type: "boolean" },
    { name: "visitante_es_rival", label: "Visitante es rival", type: "boolean" },
    { name: "visitante_id", label: "Visitante", type: "select", options: visitantesOptions },
    { name: "rival_id", label: "Rival (compatibilidad)", type: "select", options: rivalesOptions, disabled: true },
    { name: "partido_como_local", label: "Partido como local", type: "boolean" },
    {
      name: "dificultad",
      label: "Dificultad",
      type: "select",
      options: [
        { value: "Principiante", label: "Principiante" },
        { value: "Amateur", label: "Amateur" },
        { value: "Semiprofesional", label: "Semiprofesional" },
        { value: "Profesional", label: "Profesional" },
        { value: "Clase Mundial", label: "Clase Mundial" },
        { value: "Legendario", label: "Legendario" },
        { value: "Ultimate", label: "Ultimate" },
      ],
    },
    { name: "jornada", label: "Jornada", type: "text" },
    { name: "valor_equipo", label: "Valor Equipo", type: "number" },
    { name: "valor_rival", label: "Valor Rival", type: "number" },
    { name: "delantera_equipo", label: "DEL Equipo", type: "number" },
    { name: "delantera_rival", label: "DEL Rival", type: "number" },
    { name: "media_equipo", label: "MED Equipo", type: "number" },
    { name: "media_rival", label: "MED Rival", type: "number" },
    { name: "defensa_equipo", label: "DEF Equipo", type: "number" },
    { name: "defensa_rival", label: "DEF Rival", type: "number" },
    { name: "goles_equipo", label: "Goles Equipo", type: "number" },
    { name: "goles_rival", label: "Goles Rival", type: "number" },
    { name: "posesion_equipo", label: "Posesion Equipo", type: "number" },
    { name: "posesion_rival", label: "Posesion Rival", type: "number" },
    { name: "minutos_jugados", label: "Minutos Jugados", type: "number" },
    { name: "puntuacion", label: "Puntuacion", type: "number" },
    { name: "tiros_equipo", label: "Tiros Equipo", type: "number" },
    { name: "tiros_rival", label: "Tiros Rival", type: "number" },
    { name: "tiros_a_puerta_equipo", label: "Tiros a Puerta Equipo", type: "number" },
    { name: "tiros_a_puerta_rival", label: "Tiros a Puerta Rival", type: "number" },
    { name: "pases_equipo", label: "Pases Equipo", type: "number" },
    { name: "pases_rival", label: "Pases Rival", type: "number" },
    { name: "porcentaje_pases_equipo", label: "Porcentaje Pases Equipo", type: "number" },
    { name: "porcentaje_pases_rival", label: "Porcentaje Pases Rival", type: "number" },
    { name: "entradas_equipo", label: "Entradas Equipo", type: "number" },
    { name: "entradas_rival", label: "Entradas Rival", type: "number" },
    { name: "entradas_equipo_completadas", label: "Entradas Equipo Completadas", type: "number" },
    { name: "entradas_rival_completadas", label: "Entradas Rival Completadas", type: "number" },
    { name: "distancia_equipo", label: "Distancia Equipo", type: "number" },
    { name: "distancia_rival", label: "Distancia Rival", type: "number" },
    { name: "faltas_equipo", label: "Faltas Equipo", type: "number" },
    { name: "faltas_rival", label: "Faltas Rival", type: "number" },
    { name: "penalties_equipo", label: "Penalties Equipo", type: "number" },
    { name: "penalties_rival", label: "Penalties Rival", type: "number" },
    { name: "tarjetas_amarillas_equipo", label: "Tarjetas Amarillas Equipo", type: "number" },
    { name: "tarjetas_amarillas_rival", label: "Tarjetas Amarillas Rival", type: "number" },
    { name: "tarjetas_rojas_equipo", label: "Tarjetas Rojas Equipo", type: "number" },
    { name: "tarjetas_rojas_rival", label: "Tarjetas Rojas Rival", type: "number" },
    { name: "corners_equipo", label: "Corners Equipo", type: "number" },
    { name: "corners_rival", label: "Corners Rival", type: "number" },
    { name: "interceciones_equipo", label: "Intercepciones Equipo", type: "number" },
    { name: "intercepciones_rival", label: "Intercepciones Rival", type: "number" },
    { name: "balones_ganados_equipo", label: "Balones Ganados Equipo", type: "number" },
    { name: "balones_ganados_rival", label: "Balones Ganados Rival", type: "number" },
    { name: "balones_perdidos_equipo", label: "Balones Perdidos Equipo", type: "number" },
    { name: "balones_perdidos_rival", label: "Balones Perdidos Rival", type: "number" },
    {
      name: "alineaciones",
      label: "Alineaciones",
      type: "dynamicArray",
      fields: [
        { name: "jugador_id", label: "Jugador", type: "select", options: jugadoresOptions },
        { name: "tiros", label: "T", type: "number" },
        { name: "tiros_a_puerta", label: "TP", type: "number" },
        { name: "tiros_al_palo", label: "Palo", type: "number" },
        { name: "pases", label: "P", type: "number" },
        { name: "pases_exitosos", label: "PE", type: "number" },
        { name: "entradas", label: "E", type: "number" },
        { name: "entradas_exitosas", label: "EEx", type: "number" },
        { name: "regates", label: "Rg", type: "number" },
        { name: "regates_exitosos", label: "RgEx", type: "number" },
        { name: "posesion_ganada", label: "PG", type: "number" },
        { name: "posesion_perdida", label: "PP", type: "number" },
        { name: "fueras_de_juego", label: "FJ", type: "number" },
        { name: "faltas_cometidas", label: "FC", type: "number" },
        { name: "faltas_recibidas", label: "FR", type: "number" },
        { name: "posesion", label: "Posesion", type: "number" },
        { name: "distancia_recorrida", label: "DR", type: "number" },
        { name: "rendimiento", label: "Rend", type: "number" },
        { name: "jugador_del_partido", label: "MVP", type: "boolean" },
      ],
    },
    {
      name: "eventos",
      label: "Eventos",
      type: "dynamicArray",
      fields: [
        { name: "minuto", label: "Min", type: "number" },
        { name: "tipo_evento_id", label: "Tipo", type: "select", options: tiposEventosOptions },
        { name: "jugador_id", label: "Jugador", type: "select", options: jugadoresOptions },
        { name: "local_id", label: "Local", type: "number" },
        { name: "visitante_id", label: "Visitante", type: "number" },
      ],
    },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Editar Partido" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1 className="text-2xl font-bold mb-4">Editar Partido</h1>

        <DynamicForm
          fields={fields}
          method="put"
          action={`/partidos/${partido.id}`}
          cancel={"/partidos"}
          initialValues={{
            dificultad: partido.dificultad || "",
            jornada: partido.jornada || "",
            equipo_id: partido.equipo_id || 0,
            visitante_id: partido.visitante_id ?? partido.rival_id ?? 0,
            // Normalizar visitante_es_rival a partir de visitante_id cuando proceda:
            // si visitante_id === 0 entonces es rival (genérico), si existe visitante_id>0 entonces no es rival
            visitante_es_rival: (partido.visitante_id === 0 || partido.visitante_es_rival) ? 1 : 0,
            rival_id: partido.rival_id || 0,
            temporada_id: partido.temporada_id || 0,
            competicion_id: partido.competicion_id || 0,
            campeonato_id: partido.campeonato_id || (campeonatosOptions.length > 0 ? campeonatosOptions[0].value : null),
            partido_como_local: partido.partido_como_local ?? 0,
            minutos_jugados: partido.minutos_jugados || 0,
            puntuacion: partido.puntuacion || 0,
            posesion_equipo: partido.posesion_equipo || 0,
            posesion_rival: partido.posesion_rival || 0,
            tiros_equipo: partido.tiros_equipo || 0,
            tiros_rival: partido.tiros_rival || 0,
            tiros_a_puerta_equipo: partido.tiros_a_puerta_equipo || 0,
            tiros_a_puerta_rival: partido.tiros_a_puerta_rival || 0,
            pases_equipo: partido.pases_equipo || 0,
            pases_rival: partido.pases_rival || 0,
            porcentaje_pases_equipo: partido.porcentaje_pases_equipo || 0,
            porcentaje_pases_rival: partido.porcentaje_pases_rival || 0,
            goles_equipo: partido.goles_equipo || 0,
            goles_rival: partido.goles_rival || 0,
            valor_equipo: partido.valor_equipo || 0,
            valor_rival: partido.valor_rival || 0,
            delantera_equipo: partido.delantera_equipo || 0,
            delantera_rival: partido.delantera_rival || 0,
            media_equipo: partido.media_equipo || 0,
            media_rival: partido.media_rival || 0,
            defensa_equipo: partido.defensa_equipo || 0,
            defensa_rival: partido.defensa_rival || 0,
            entradas_equipo: partido.entradas_equipo || 0,
            entradas_rival: partido.entradas_rival || 0,
            entradas_equipo_completadas: partido.entradas_equipo_completadas || 0,
            entradas_rival_completadas: partido.entradas_rival_completadas || 0,
            distancia_equipo: partido.distancia_equipo || 0,
            distancia_rival: partido.distancia_rival || 0,
            faltas_equipo: partido.faltas_equipo || 0,
            faltas_rival: partido.faltas_rival || 0,
            penalties_equipo: partido.penalties_equipo || 0,
            penalties_rival: partido.penalties_rival || 0,
            tarjetas_amarillas_equipo: partido.tarjetas_amarillas_equipo || 0,
            tarjetas_amarillas_rival: partido.tarjetas_amarillas_rival || 0,
            tarjetas_rojas_equipo: partido.tarjetas_rojas_equipo || 0,
            tarjetas_rojas_rival: partido.tarjetas_rojas_rival || 0,
            corners_equipo: partido.corners_equipo || 0,
            corners_rival: partido.corners_rival || 0,
            interceciones_equipo: partido.interceciones_equipo || 0,
            intercepciones_rival: partido.intercepciones_rival || 0,
            balones_ganados_equipo: partido.balones_ganados_equipo || 0,
            balones_ganados_rival: partido.balones_ganados_rival || 0,
            balones_perdidos_equipo: partido.balones_perdidos_equipo || 0,
            balones_perdidos_rival: partido.balones_perdidos_rival || 0,
            alineaciones: partido.alineaciones
              ? partido.alineaciones.map((a: any) => ({
                  jugador_id: a.jugador_id,
                  tiros: a.tiros,
                  tiros_a_puerta: a.tiros_a_puerta,
                  tiros_al_palo: a.tiros_al_palo,
                  pases: a.pases,
                  pases_exitosos: a.pases_exitosos,
                  entradas: a.entradas,
                  entradas_exitosas: a.entradas_exitosas,
                  regates: a.regates,
                  regates_exitosos: a.regates_exitosos,
                  posesion_ganada: a.posesion_ganada,
                  posesion_perdida: a.posesion_perdida,
                  fueras_de_juego: a.fueras_de_juego,
                  faltas_cometidas: a.faltas_cometidas,
                  faltas_recibidas: a.faltas_recibidas,
                  posesion: a.posesion,
                  distancia_recorrida: a.distancia_recorrida,
                  rendimiento: a.rendimiento,
                  jugador_del_partido: a.jugador_del_partido,
                }))
              : [],
            eventos: partido.eventos
              ? partido.eventos.map((e: any) => ({
                  minuto: e.minuto,
                  local_id: e.local_id ?? e.equipo_id,
                  visitante_id: e.visitante_id ?? e.rival_id,
                  tipo_evento_id: e.tipo_evento_id,
                  jugador_id: e.jugador_id,
                }))
              : [],
          }}
          onSuccess={() => console.log("Partido actualizado con exito")}
        />
      </div>
    </AppLayout>
  );
};

export default Edit;
