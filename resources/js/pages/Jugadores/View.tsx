import React from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Separator } from "@/components/ui/separator";
import { Head, router } from "@inertiajs/react";
import AppLayout from "@/layouts/app-layout";
import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import EstiloIcon from "@/components/estilo-icon";


const getAge = (fechaNacimiento: string) => {
  const birthDate = new Date(fechaNacimiento);
  const diff = Date.now() - birthDate.getTime();
  const ageDate = new Date(diff);
  return Math.abs(ageDate.getUTCFullYear() - 1970);
};

export default function View({ jugador, resumen }) {
  const personalData = [
    { label: "Nombre", value: jugador.nombre },
    { label: "Edad", value: getAge(jugador.fecha_nacimiento) },
    { label: "Nacionalidad", value: jugador.nacionalidad },
    { label: "Altura (cm)", value: jugador.altura },
    { label: "Peso (kg)", value: jugador.peso },
    { label: "Posición", value: jugador.posicion },
    { label: "Estilo Química", value: jugador.estilo_quimica },
  ];

  const caracteristicas = [
    {
      cabecera: { label: "Ritmo", value: jugador.ritmo },
      items: [
        { label: "Aceleración", value: jugador.aceleracion },
        { label: "Velocidad", value: jugador.velocidad },
      ],
    },
    {
      cabecera: { label: "Tiro", value: jugador.tiro },
      items: [
        { label: "Posición de ataque", value: jugador.pos_ataque },
        { label: "Finalización", value: jugador.finalizacion },
        { label: "Potencia de tiro", value: jugador.potencia_de_tiro },
        { label: "Tiro lejano", value: jugador.tiro_lejano },
        { label: "Voleas", value: jugador.voleas },
        { label: "Penalties", value: jugador.penalties },
      ],
    },
    {
      cabecera: { label: "Pase", value: jugador.pase },
      items: [
        { label: "Visión", value: jugador.vision },
        { label: "Centros", value: jugador.centros },
        { label: "Precisión de falta", value: jugador.precision_falta },
        { label: "Pase corto", value: jugador.pase_corto },
        { label: "Pase largo", value: jugador.pase_largo },
        { label: "Efecto", value: jugador.efecto },
      ],
    },
    {
      cabecera: { label: "Regate", value: jugador.regate },
      items: [
        { label: "Agilidad", value: jugador.agilidad },
        { label: "Equilibrio", value: jugador.equilibrio },
        { label: "Anticipación", value: jugador.anticipacion },
        { label: "Control de balón", value: jugador.control_de_balon },
        { label: "Regates", value: jugador.regates },
        { label: "Compostura", value: jugador.compostura },
      ],
    },
    {
      cabecera: { label: "Defensa", value: jugador.defensa },
      items: [
        { label: "Intercepciones", value: jugador.intercepciones },
        { label: "Precisión cabezazo", value: jugador.precision_cabezazo },
        { label: "Capacidad defensiva", value: jugador.capacidad_defensiva },
        { label: "Robos", value: jugador.robos },
        { label: "Entradas", value: jugador.entradas },
      ],
    },
    {
      cabecera: { label: "Físico", value: jugador.fisico },
      items: [
        { label: "Salto", value: jugador.salto },
        { label: "Resistencia", value: jugador.resistencia },
        { label: "Fuerza", value: jugador.fuerza },
        { label: "Agresividad", value: jugador.agresividad },
      ],
    },
    {
      cabecera: { label: "Media", value: jugador.media },
      items: [],
    },
  ];

  const statGroups = [
    {
      title: "Ofensivas",
      stats: [
        { key: "goles", label: "Goles" },
        { key: "tiros_vs_puerta", label: "Tiros / A puerta" },
        { key: "tiros_al_palo", label: "Tiros al palo" },
        { key: "penalties_marcados", label: "Penalties marcados" },
        { key: "asistencias", label: "Asistencias" },
        { key: "pases", label: "Pases" },
        { key: "regates", label: "Regates" },
        { key: "faltas_recibidas", label: "Faltas recibidas" },
        { key: "fueras_de_juego", label: "Fueras de juego" },
      ],
    },
    {
      title: "Defensivas",
      stats: [
        { key: "entradas", label: "Entradas" },
        { key: "posesion_ganada", label: "Posesión ganada" },
        { key: "posesion_perdida", label: "Posesión perdida" },
        { key: "faltas_cometidas", label: "Faltas cometidas" },
        { key: "penalties_realizados", label: "Penalties realizados" },
        { key: "ta_r", label: "Tarjetas amarillas" },
        { key: "tr_r", label: "Tarjetas rojas" },
        { key: "distancia_recorrida", label: "Distancia recorrida (km)" },
      ],
    },
    {
      title: "Otros",
      stats: [
        { key: "partidos", label: "Partidos jugados" },
        { key: "minutos", label: "Minutos jugados" },
        { key: "rendimiento", label: "Rendimiento" },
        { key: "jugador_del_partido", label: "Jugador del partido" },
      ],
    },
  ];

  const breadcrumbs = [
    { label: "Inicio", href: "/" },
    { label: "Jugadores", href: "/jugadores" },
    { label: jugador.nombre, href: "#" },
  ];

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Jugador - ${jugador.nombre}`} />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div className="flex justify-between items-center mb-4">
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4 }}
          >
            <h1 className="text-3xl font-bold">{jugador.nombre}</h1>
          </motion.div>
          <Button
            onClick={() => router.get("/jugadores")}
            variant="outline"
            className="flex items-center gap-2"
          >
            <span>←</span>
            <span>Volver al listado</span>
          </Button>
        </div>

        {/* Primera fila: 3 cards - Datos personales + 2 de estadísticas */}
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
          {/* Card 1: Datos Personales */}
          <Card className="shadow-md rounded-2xl">
            <CardHeader>
              <CardTitle className="text-lg font-semibold">Datos Personales</CardTitle>
            </CardHeader>
            <CardContent>
              <ul className="space-y-2">
                {personalData.map((item, i) => (
                  <li key={i} className="flex justify-between text-sm">
                    <span className="text-muted-foreground">{item.label}</span>
                    <span className="font-bold">{item.value}</span>
                  </li>
                ))}
              </ul>
            </CardContent>
          </Card>

          {/* Card 2: Estadísticas Ofensivas */}
          <Card className="shadow-md rounded-2xl">
            <CardHeader>
              <CardTitle className="text-lg font-semibold">Estadísticas Ofensivas</CardTitle>
            </CardHeader>
            <CardContent>
              <ul className="space-y-2">
                {statGroups[0].stats.map((stat) => (
                  <li key={stat.key} className="flex justify-between text-sm">
                    <span className="text-muted-foreground">{stat.label}</span>
                    <span className="font-bold">
                      {stat.key === "tiros_vs_puerta"
                        ? `${resumen.tiros_a_puerta} / ${resumen.tiros}`
                        : stat.key === "regates"
                        ? `${resumen.regates_exitosos} / ${resumen.regates}`
                        : stat.key === "pases"
                        ? `${resumen.pases_exitosos} / ${resumen.pases}`
                        : stat.key === "penalties_marcados"
                        ? `${resumen.penalties_marcados} / ${resumen.penalties_marcados+resumen.penalties_fallados}`
                        : resumen[stat.key]}
                    </span>
                  </li>
                ))}
              </ul>
            </CardContent>
          </Card>

          {/* Card 3: Estadísticas Defensivas + Otros */}
          <Card className="shadow-md rounded-2xl">
            <CardHeader>
              <CardTitle className="text-lg font-semibold">Estadísticas Defensivas</CardTitle>
            </CardHeader>
            <CardContent>
              <ul className="space-y-2">
                {statGroups[1].stats.map((stat) => (
                  <li key={stat.key} className="flex justify-between text-sm">
                    <span className="text-muted-foreground">{stat.label}</span>
                    <span className="font-bold">
                      {stat.key === "entradas"
                        ? `${resumen.entradas_exitosas} / ${resumen.entradas}`
                        : stat.key === "distancia_recorrida"
                        ? resumen[stat.key].toFixed(1)
                        : resumen[stat.key]}
                    </span>
                  </li>
                ))}
                <Separator className="my-3" />
                {statGroups[2].stats.map((stat) => (
                  <li key={stat.key} className="flex justify-between text-sm">
                    <span className="text-muted-foreground">{stat.label}</span>
                    <span className="font-bold">
                      {stat.key === "rendimiento"
                        ? (resumen[stat.key]/(resumen.partidos?resumen.partidos:1)).toFixed(1)
                        : resumen[stat.key]}
                    </span>
                  </li>
                ))}
              </ul>
            </CardContent>
          </Card>
        </div>

        {/* Segunda fila: Características del jugador en 3 columnas */}
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
          {/* Características con barras de progreso */}
          {caracteristicas.map((bloque, i) => (
            <Card key={i} className="shadow-md rounded-2xl">
              <CardHeader>
                <CardTitle className="text-lg font-semibold">{bloque.cabecera.label}</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="mb-2 flex justify-between font-bold">
                  <span>{bloque.cabecera.value}</span>
                  <span>{bloque.cabecera.value}/100</span>
                </div>
                <ul className="space-y-2">
                  {bloque.items.map((item, j) => (
                    <li key={j} className="flex items-center text-sm gap-2">
                      <span className="text-muted-foreground w-40 flex-shrink-0">{item.label}</span>
                      <div className="flex-1 bg-gray-200 h-2 rounded-full relative">
                        <div
                          className="bg-green-500 h-2 rounded-full absolute top-0 left-0"
                          style={{ width: `${item.value}%` }}
                        ></div>
                      </div>
                      <span className="font-bold w-10 text-right flex-shrink-0">{item.value}</span>
                    </li>
                  ))}
                </ul>
              </CardContent>
            </Card>
          ))}
          
          {/* Card de Estilos */}
          <Card className="lg:col-span-3 shadow-md rounded-2xl" style={{backgroundColor:'#323232',color:"#fff"}}>
            <CardHeader>
              <CardTitle className="text-lg font-semibold">Estilos</CardTitle>
            </CardHeader>
            <CardContent>                 
              <div className="flex gap-2 flex-wrap">
                {jugador.estilos.map((estilo) => (
                  <EstiloIcon key={estilo.id} estilo={estilo} />
                ))}
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
