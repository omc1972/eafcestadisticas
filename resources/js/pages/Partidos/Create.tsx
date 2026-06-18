import React, { useState } from "react";
import { Head } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { dashboard } from "@/routes";
import { type BreadcrumbItem } from "@/types";
import DynamicForm from "@/components/forms/DynamicForm";
import axios from "axios";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Dashboard", href: dashboard().url },
];

type Plantilla = {
  id: number;
  equipo_id: number;
  temporada_id: number;
  jugadores: { id: number; nombre: string }[];
};

type Props = {
  equipos?: { id: number; nombre: string }[];
  rivales?: { id: number; nombre: string }[];
  temporadas: { id: number; nombre: string }[];
  competiciones: { id: number; nombre: string }[];
  campeonatos?: { id: number; nombre: string }[];
  tiposEventos?: { id: number; nombre: string }[];
};

const Create: React.FC<Props> = ({ temporadas, competiciones }) => {
  const [step, setStep] = useState<1 | 2 | 3>(1);

  const [selectedTemporada, setSelectedTemporada] = useState<string | null>(null);
  const [selectedCompeticion, setSelectedCompeticion] = useState<string | null>(null);
  const [selectedEquipo, setSelectedEquipo] = useState<string | null>(null);
  const [selectedRival, setSelectedRival] = useState<string | null>(null);
  const [visitanteEsRival, setVisitanteEsRival] = useState(false);
  const [localEsRival, setLocalEsRival] = useState(false);

  const [equipos, setEquipos] = useState<{ id: number; nombre: string }[]>([]);
  const [rivales, setRivales] = useState<{ id: number; nombre: string }[]>([]);
  const [campeonatos, setCampeonatos] = useState<{ id: number; nombre: string }[]>([]);
  const [tiposEventos, setTiposEventos] = useState<{ id: number; nombre: string }[]>([]);

  const [isFifa, setIsFifa] = useState(false);
  const [selectedRivalId, setSelectedRivalId] = useState<string | null>(null);

  const [jugadoresEquipo, setJugadoresEquipo] = useState<{ value: number; label: string }[]>([]);
  const [jugadoresRival, setJugadoresRival] = useState<{ value: number; label: string }[]>([]);
  const [initialAlineaciones, setInitialAlineaciones] = useState<any[]>([]);
  const [initialAlineacionesRival, setInitialAlineacionesRival] = useState<any[]>([]);
  const [visitanteEquipoId, setVisitanteEquipoId] = useState<number | null>(null);

  const [esLocal, setEsLocal] = useState(false);
  const [selectedCampeonato, setSelectedCampeonato] = useState<string | null>(null);
  const [selectedDificultad, setSelectedDificultad] = useState<string | null>(null);

  React.useEffect(() => {
    console.log("[Create] step:", step, "isFifa:", isFifa, "selectedDificultad:", selectedDificultad);
  }, [step, isFifa, selectedDificultad]);

  const equiposOptions = equipos.map((e) => ({ value: e.id, label: e.nombre }));
  const rivalesOptions = rivales.map((r) => ({ value: r.id, label: r.nombre }));
  const temporadasOptions = temporadas.map((t) => ({ value: t.id, label: t.nombre }));
  const competicionesOptions = competiciones.map((c) => ({ value: c.id, label: c.nombre }));
  const campeonatosOptions = campeonatos.map((c: { id: number; nombre: string }) => ({ value: c.id, label: c.nombre }));
  const tiposEventosOptions = tiposEventos.map((te) => ({ value: te.id, label: te.nombre }));

  const [pendingDificultad, setPendingDificultad] = useState<string | null>(null);

  const fetchOptions = async () => {
    if (!selectedCompeticion) {
      alert('Selecciona una competición antes de continuar.');
      return;
    }

    try {
      const response = await axios.get('/ajax/partidos/options', {
        params: { competicion_id: selectedCompeticion },
      });

      const fifaFlag = !!response.data.isFifa;

      setEquipos(response.data.equipos || []);
      setRivales(response.data.rivales || []);
      setCampeonatos(response.data.campeonatos || []);
      setTiposEventos(response.data.tiposEventos || []);

      setIsFifa(fifaFlag);

      // If competition is FIFA, both sides pick from equipos, but keep a separate rivales select
      if (fifaFlag) {
        // default none marked as rival; user can mark only one side later
        setLocalEsRival(false);
        setVisitanteEsRival(false);
      }

      // reset selected sides when options change
      setSelectedEquipo(null);
      setSelectedRival(null);
      setSelectedRivalId(null);

      setStep(2);
    } catch (error) {
      console.error('Error cargando opciones', error);
      alert('No se pudieron cargar las opciones. Intenta de nuevo.');
    }
  };

  const loadPlantillas = async () => {
    if (!selectedTemporada || !selectedCompeticion || !selectedEquipo) {
      alert('Selecciona temporada, competición y equipo local antes de continuar.');
      return;
    }

    // En competiciones FIFA necesitamos también el visitante (equipo) seleccionado
    if (isFifa && !selectedRival && !visitanteEsRival) {
      alert('Para competiciones FIFA selecciona también el equipo visitante antes de cargar plantillas.');
      return;
    }

    // visitante puede ser equipo o rival depending on flags; for FIFA visitante es equipo
    // En el flujo NO-FIFA forzamos visitante_id = 0 para que el backend devuelva la plantilla genérica
    try {
      // Usamos solo temporada_id, equipo_id y visitante_id.
      // Convención: visitante_id === 0 -> visitante es rival (backend devolverá jugador genérico).
      // Tomar el visitante únicamente del select; no forzar 0 por defecto.
      let visitanteIdForPlantilla: number | null = null;
      if (!isFifa) {
        visitanteIdForPlantilla = 0; // forzar plantilla genérica
      } else {
        if (selectedRival) {
          visitanteIdForPlantilla = Number(selectedRival);
        }
      }

      const response = await axios.get('/ajax/plantillas', {
        params: {
          temporada_id: Number(selectedTemporada) || null,
          equipo_id: Number(selectedEquipo) || null,
          visitante_id: visitanteIdForPlantilla,
        },
      });

      const { plantillaEquipo, plantillaRival, visitante_equipo_id } = response.data;

      setJugadoresEquipo(
        localEsRival ? [] : (plantillaEquipo || []).map((j: any) => ({ value: j.id, label: j.nombre }))
      );
      setJugadoresRival(
        (plantillaRival || []).map((j: any) => ({ value: j.id, label: j.nombre }))
      );

      // Build initial alineaciones arrays based on plantilla data
      const buildRow = (equipoId: number | null, jugadorId: number) => ({
        equipo_id: equipoId,
        jugador_id: jugadorId,
        tiros: 0,
        tiros_a_puerta: 0,
        tiros_al_palo: 0,
        pases: 0,
        pases_exitosos: 0,
        entradas: 0,
        entradas_exitosas: 0,
        regates: 0,
        regates_exitosos: 0,
        posesion_ganada: 0,
        posesion_perdida: 0,
        fueras_de_juego: 0,
        faltas_cometidas: 0,
        faltas_recibidas: 0,
        posesion: 0,
        distancia_recorrida: 0,
        rendimiento: 0,
        jugador_del_partido: false,
      });

      const equipoIdNum = selectedEquipo ? Number(selectedEquipo) : null;
      // Cuando no es FIFA forzamos equipo visitante a 0 (plantilla genérica). Si backend devuelve
      // visitante_equipo_id lo respetamos.
      let rivalEquipoIdNum: number | null = null;
      if (!isFifa) {
        rivalEquipoIdNum = 0;
      } else {
        rivalEquipoIdNum = visitante_equipo_id ? Number(visitante_equipo_id) : (visitanteEsRival ? null : (selectedRival ? Number(selectedRival) : null));
      }

      const alineacionesInit = (plantillaEquipo || []).map((j: any) => buildRow(equipoIdNum, j.id));
      const alineacionesRivalInit = (plantillaRival || []).map((j: any) => buildRow(rivalEquipoIdNum, j.id));

      setInitialAlineaciones(alineacionesInit);
      setInitialAlineacionesRival(alineacionesRivalInit);
      // visitanteEquipoId será el id del equipo a usar como visitante (si existe)
      setVisitanteEquipoId(visitante_equipo_id ?? visitanteIdForPlantilla);

      setPendingDificultad(selectedDificultad);
      setStep(3);
    } catch (error) {
      console.error('Error cargando plantillas', error);
      alert('No se pudieron cargar las plantillas. Intenta de nuevo.');
    }
  };

  const fieldsStep2: any[] = [
    { name: "temporada_id", label: "Temporada", type: "select", options: temporadasOptions, disabled: true },
    { name: "competicion_id", label: "Competición", type: "select", options: competicionesOptions, disabled: true },
    { name: "campeonato_id", label: "Campeonato", type: "select", options: campeonatosOptions },
    { name: "equipo_id", label: "Equipo (Local)", type: "select", options: equiposOptions, disabled: true },
    {
      name: "local_es_rival",
      label: "Local es rival",
      type: "boolean",
    },
    {
      name: "visitante_es_rival",
      label: "Visitante es rival",
      type: "boolean",
    },
    {
      name: "visitante_id",
      label: "Visitante (Equipo)",
      type: "select",
      options: equiposOptions,
      disabled: !isFifa,
    },
    { name: "rival_id", label: "Rival (compatibilidad)", type: "select", options: rivalesOptions, disabled: !isFifa },
    { name: "partido_como_local", label: "MVP", type: "boolean" },
    // Dificultad justo antes de jornada
    {
      name: "dificultad",
      label: "Dificultad 2",
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
      value: selectedDificultad || "",
      onChange: (value: string) => setSelectedDificultad(value),
    },
    { name: "jornada", label: "Jornada", type: "string" },
    { name: "valor_equipo", label: "Valor Equipo", type: "number" },
    { name: "valor_rival", label: "Valor rival", type: "number" },
    { name: "delantera_equipo", label: "DEL equipo", type: "number" },
    { name: "delantera_rival", label: "DEL rival", type: "number" },
    { name: "media_equipo", label: "MED Equipo", type: "number" },
    { name: "media_rival", label: "MED rival", type: "number" },
    { name: "defensa_equipo", label: "DEF Equipo", type: "number" },
    { name: "defensa_rival", label: "DEF rival", type: "number" },
    { name: "goles_equipo", label: "Goles Equipo", type: "number" },
    { name: "goles_rival", label: "Goles rival", type: "number" },
    { name: "posesion_equipo", label: "Posesión Equipo (%)", type: "number" },
    { name: "posesion_rival", label: "Posesión rival (%)", type: "number" },
    { name: "minutos_jugados", label: "Minutos Jugados", type: "number" },
    { name: "puntuacion", label: "Puntuación", type: "number" },
    { name: "tiros_equipo", label: "Tiros Equipo", type: "number" },
    { name: "tiros_rival", label: "Tiros rival", type: "number" },
    { name: "tiros_a_puerta_equipo", label: "Tiros a Puerta Equipo", type: "number" },
    { name: "tiros_a_puerta_rival", label: "Tiros a Puerta rival", type: "number" },
    { name: "pases_equipo", label: "Pases Equipo", type: "number" },
    { name: "pases_rival", label: "Pases rival", type: "number" },
    { name: "porcentaje_pases_equipo", label: "Porcentaje Pases Equipo (%)", type: "number" },
    { name: "porcentaje_pases_rival", label: "Porcentaje Pases rival (%)", type: "number" },
    { name: "entradas_equipo", label: "Entradas Equipo", type: "number" },
    { name: "entradas_rival", label: "Entradas rival", type: "number" },
    { name: "entradas_equipo_completadas", label: "Entradas Equipo Completadas", type: "number" },
    { name: "entradas_rival_completadas", label: "Entradas rival Completadas", type: "number" },
    { name: "distancia_equipo", label: "Distancia Equipo (km)", type: "number" },
    { name: "distancia_rival", label: "Distancia rival (km)", type: "number" },
    { name: "faltas_equipo", label: "Faltas Equipo", type: "number" },
    { name: "faltas_rival", label: "Faltas rival", type: "number" },
    { name: "penalties_equipo", label: "Penaltis Equipo", type: "number" },
    { name: "penalties_rival", label: "Penaltis rival", type: "number" },
    { name: "tarjetas_amarillas_equipo", label: "Tarjetas Amarillas Equipo", type: "number" },
    { name: "tarjetas_amarillas_rival", label: "Tarjetas Amarillas rival", type: "number" },
    { name: "tarjetas_rojas_equipo", label: "Tarjetas Rojas Equipo", type: "number" },
    { name: "tarjetas_rojas_rival", label: "Tarjetas Rojas rival", type: "number" },
    { name: "corners_equipo", label: "Corners Equipo", type: "number" },
    { name: "corners_rival", label: "Corners rival", type: "number" },
    { name: "interceciones_equipo", label: "Intercepciones Equipo", type: "number" },
    { name: "intercepciones_rival", label: "Intercepciones rival", type: "number" },
    { name: "balones_ganados_equipo", label: "Balones Ganados Equipo", type: "number" },
    { name: "balones_ganados_rival", label: "Balones Ganados rival", type: "number" },
    { name: "balones_perdidos_equipo", label: "Balones Perdidos Equipo", type: "number" },
    { name: "balones_perdidos_rival", label: "Balones Perdidos rival", type: "number" },
    {
      name: "alineaciones",
      label: "Alineaciones Equipo",
      type: "dynamicArray",
      fields: [
        { name: "equipo_id", label: "Equipo ID", type: "number", default: selectedEquipo ? Number(selectedEquipo) : null, hidden: true },
        { name: "jugador_id", label: "Jugador", type: "select", options: [...jugadoresEquipo, ...jugadoresRival] },
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
        { name: "posesion", label: "P(%)", type: "number" },
        { name: "distancia_recorrida", label: "DR(km)", type: "number" },
        { name: "rendimiento", label: "Ren", type: "number" },
        { name: "jugador_del_partido", label: "MVP", type: "boolean" },
      ],
    },
    {
      name: "alineaciones_rival",
      label: "Alineaciones Rival",
      type: "dynamicArray",
      fields: [
        { name: "equipo_id", label: "Equipo ID", type: "number", default: (visitanteEsRival ? (selectedRivalId ? Number(selectedRivalId) : null) : (selectedRival ? Number(selectedRival) : null)), hidden: true },
        { name: "jugador_id", label: "Jugador", type: "select", options: jugadoresRival },
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
        { name: "posesion", label: "P(%)", type: "number" },
        { name: "distancia_recorrida", label: "DR(km)", type: "number" },
        { name: "rendimiento", label: "Ren", type: "number" },
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
        { name: "jugador_id", label: "Jugador", type: "select", options: [...jugadoresEquipo, ...jugadoresRival] },
        { name: "local_id", label: "Local", type: "select", options: [
          { value: selectedEquipo ? Number(selectedEquipo) : null, label: equipos.find(e => e.id.toString() === selectedEquipo)?.nombre || "Selecciona equipo" }
        ]},
        { name: "visitante_id", label: "Visitante", type: "select", options: [
          { value: selectedRivalId ? Number(selectedRivalId) : (visitanteEsRival ? (selectedRival ? Number(selectedRival) : null) : null), label: (selectedRivalId ? (rivales.find(r => r.id === Number(selectedRivalId))?.nombre) : (visitanteEsRival ? (rivales.find(r => r.id.toString() === selectedRival)?.nombre) : (equipos.find(e => e.id.toString() === selectedRival)?.nombre))) || "Selecciona visitante" }
        ]},
      ],
    },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Partidos" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        {step === 1 && (
          <div className="flex flex-col gap-6 max-w-md">
            <h1 className="text-2xl font-bold">Selecciona Temporada y Competición</h1>

            <div className="flex flex-col gap-2">
              <Label>Temporada</Label>
              <Select
                onValueChange={(value) => setSelectedTemporada(String(value))}
                value={selectedTemporada ? String(selectedTemporada) : "0"}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Selecciona una temporada" />
                </SelectTrigger>
                <SelectContent>
                  {temporadasOptions.map((t) => (
                    <SelectItem key={t.value} value={t.value.toString()}>
                      {t.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex flex-col gap-2">
              <Label>Competición</Label>
              <Select
                onValueChange={(value) => setSelectedCompeticion(String(value))}
                value={selectedCompeticion?.toString() || ""}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Selecciona una competición" />
                </SelectTrigger>
                <SelectContent>
                  {competicionesOptions.map((c) => (
                    <SelectItem key={c.value} value={c.value.toString()}>
                      {c.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <Button onClick={fetchOptions}>Siguiente</Button>
          </div>
        )}

        {step === 2 && (
          <div className="flex flex-col gap-6 max-w-md">
            <h1 className="text-2xl font-bold">Selecciona equipos y opciones</h1>

            {isFifa ? (
              // Comportamiento existente para FIFA
              <>
                <div className="flex flex-col gap-2">
                  <Label>Local</Label>
                  <div className="flex items-center space-x-2 pb-2">
                    <Checkbox
                      id="local_es_rival"
                      checked={localEsRival}
                      onCheckedChange={(checked) => {
                        const val = !!checked;
                        setLocalEsRival(val);
                      }}
                    />
                    <Label htmlFor="local_es_rival">Es rival</Label>
                  </div>
                  <Select
                    onValueChange={(value) => setSelectedEquipo(String(value))}
                    value={selectedEquipo?.toString() || ""}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder={isFifa ? "Selecciona un equipo local" : (localEsRival ? "Selecciona un rival (local)" : "Selecciona un equipo local")} />
                    </SelectTrigger>
                    <SelectContent>
                      {(isFifa ? equiposOptions : (localEsRival ? rivalesOptions : equiposOptions))
                        .map((v) => (
                          <SelectItem key={v.value} value={v.value.toString()}>
                            {v.label}
                          </SelectItem>
                        ))}
                    </SelectContent>
                  </Select>
                </div>

                <div className="flex flex-col gap-2">
                  <Label>Visitante</Label>
                  <div className="flex items-center space-x-2 pb-2">
                    <Checkbox
                      id="visitante_es_rival"
                      checked={visitanteEsRival}
                      onCheckedChange={(checked) => {
                        const esRival = !!checked;
                        setVisitanteEsRival(esRival);
                      }}
                    />
                    <Label htmlFor="visitante_es_rival">Es rival</Label>
                  </div>
                  <Select
                    onValueChange={(value) => setSelectedRival(String(value))}
                    value={selectedRival?.toString() || ""}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecciona un equipo visitante" />
                    </SelectTrigger>
                    <SelectContent>
                      {equiposOptions
                        .filter((opt) => opt.value.toString() !== selectedEquipo)
                        .map((v) => (
                          <SelectItem key={v.value} value={v.value.toString()}>
                            {v.label}
                          </SelectItem>
                        ))}
                    </SelectContent>
                  </Select>
                </div>

                <div className="flex flex-col gap-2">
                  <Label>Rival (compatibilidad)</Label>
                  <Select
                    onValueChange={(value) => setSelectedRivalId(String(value))}
                    value={selectedRivalId?.toString() || ""}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecciona un rival (opcional)" />
                    </SelectTrigger>
                    <SelectContent>
                      {rivalesOptions.map((r) => (
                        <SelectItem key={r.value} value={r.value.toString()}>
                          {r.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </>
            ) : (
              // Cuando NO es FIFA: mostrar solo equipo (local), rival (desde rivales), dificultad y partido_como_local
              <>
                <div className="flex flex-col gap-2">
                  <Label>Equipo (Local)</Label>
                  <Select
                    onValueChange={(value) => setSelectedEquipo(String(value))}
                    value={selectedEquipo?.toString() || ""}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecciona un equipo local" />
                    </SelectTrigger>
                    <SelectContent>
                      {equiposOptions.map((v) => (
                        <SelectItem key={v.value} value={v.value.toString()}>
                          {v.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                <div className="flex flex-col gap-2">
                  <Label>Rival (Visitante)</Label>
                  <Select
                    onValueChange={(value) => {
                      setSelectedRival(String(value));
                      // Para este flujo, siempre tratamos al visitante como `rival`
                      setVisitanteEsRival(true);
                    }}
                    value={selectedRival?.toString() || ""}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecciona un rival" />
                    </SelectTrigger>
                    <SelectContent>
                      {rivalesOptions.map((r) => (
                        <SelectItem key={r.value} value={r.value.toString()}>
                          {r.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                

                <div className="flex items-center space-x-2">
                  <Checkbox
                    id="partido_como_local"
                    checked={esLocal}
                    onCheckedChange={(checked) => setEsLocal(!!checked)}
                  />
                  <Label htmlFor="partido_como_local">Partido como local</Label>
                </div>
              </>
            )}

            <div className="flex flex-col gap-2">
              <div className="flex flex-col gap-2">
                <Label>Dificultad 2</Label>
                <Select
                  onValueChange={(value) => setSelectedDificultad(String(value))}
                  value={selectedDificultad || ""}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Selecciona dificultad" />
                  </SelectTrigger>
                  <SelectContent>
                    {["Principiante","Amateur","Semiprofesional","Profesional","Clase Mundial","Legendario","Ultimate"].map((dif) => (
                      <SelectItem key={dif} value={dif}>{dif}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>

              <div className="flex gap-2">
                <Button onClick={() => setStep(1)}>Atrás</Button>
                <Button onClick={loadPlantillas}>Cargar plantilla y continuar</Button>
              </div>
            </div>
          </div>
        )}

        {step === 3 && (
          <div>
            <h1 className="text-2xl font-bold mb-4">Nuevo Partido</h1>
            {console.log('[Create] step3 debug', { visitanteEquipoId, selectedRival, visitanteEsRival, isFifa })}
            <DynamicForm
              fields={fieldsStep2}
              method="post"
              action="/partidos"
              cancel="/partidos"
              initialValues={{
                temporada_id: selectedTemporada,
                competicion_id: selectedCompeticion,
                campeonato_id: selectedCampeonato || (campeonatosOptions.length > 0 ? campeonatosOptions[0].value : null),
                dificultad: selectedDificultad || "",
                equipo_id: selectedEquipo ? Number(selectedEquipo) : null,
                local_es_rival: localEsRival ? 1 : 0,
                visitante_es_rival: visitanteEsRival ? 1 : 0,
                // visitante_id: para NO-FIFA forzamos 0 (visitante como rival genérico). En FIFA usamos la selección.
                visitante_id: (!isFifa) ? 0 : ((isFifa && selectedRival) ? Number(selectedRival) : (visitanteEquipoId ?? (!visitanteEsRival && selectedRival ? Number(selectedRival) : null))),
                // rival_id siempre apunta a la tabla `rivales`: preferimos selectedRivalId (compatibilidad FIFA),
                // si no existe y visitanteEsRival es true usamos selectedRival (id del rival).
                rival_id: selectedRivalId ? Number(selectedRivalId) : (visitanteEsRival ? (selectedRival ? Number(selectedRival) : null) : null),
                partido_como_local: esLocal ? 1 : 0,
                jornada: "",
                goles_equipo: 0,
                goles_rival: 0,
                valor_equipo: 0,
                valor_rival: 0,
                defensa_equipo: 0,
                defensa_rival: 0,
                media_equipo: 0,
                media_rival: 0,
                delantera_equipo: 0,
                delantera_rival: 0,
                posesion_equipo: 0,
                posesion_rival: 0,
                minutos_jugados: 0,
                puntuacion: 0,
                tiros_equipo: 0,
                tiros_rival: 0,
                tiros_a_puerta_equipo: 0,
                tiros_a_puerta_rival: 0,
                pases_equipo: 0,
                pases_rival: 0,
                porcentaje_pases_equipo: 0,
                porcentaje_pases_rival: 0,
                entradas_equipo: 0,
                entradas_rival: 0,
                entradas_equipo_completadas: 0,
                entradas_rival_completadas: 0,
                distancia_equipo: 0,
                distancia_rival: 0,
                faltas_equipo: 0,
                faltas_rival: 0,
                penalties_equipo: 0,
                penalties_rival: 0,
                tarjetas_amarillas_equipo: 0,
                tarjetas_amarillas_rival: 0,
                tarjetas_rojas_equipo: 0,
                tarjetas_rojas_rival: 0,
                corners_equipo: 0,
                corners_rival: 0,
                interceciones_equipo: 0,
                intercepciones_rival: 0,
                balones_ganados_equipo: 0,
                balones_ganados_rival: 0,
                balones_perdidos_equipo: 0,
                balones_perdidos_rival: 0,
                alineaciones: initialAlineaciones,
                alineaciones_rival: initialAlineacionesRival,
                eventos: [],
              }}
              onSuccess={() => console.log("Partido creado con éxito")}
            />
          </div>
        )}
      </div>
    </AppLayout>
  );
};

export default Create;
