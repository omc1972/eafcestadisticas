// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Recharts from "recharts"

const { Card, CardHeader, CardTitle, CardDescription, CardContent, CardAction, ChartContainer, ChartTooltip, ChartTooltipContent, ChartLegend, ChartLegendContent, Tabs, TabsList, TabsTrigger, Progress, Item, ItemContent, ItemTitle, ItemDescription, ItemActions, ItemGroup, ItemSeparator, ItemMedia, Badge, Button, HacceIcon, Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } = HacceUI
const { AreaChart, Area, XAxis, CartesianGrid } = Recharts
const data = [{ s: "S36", p: 82, r: 18 }, { s: "S37", p: 76, r: 24 }, { s: "S38", p: 71, r: 29 }, { s: "S39", p: 68, r: 32 }, { s: "S40", p: 64, r: 36 }, { s: "S41", p: 66, r: 34 }, { s: "S42", p: 61, r: 39 }]
const config = { p: { label: "Presencial", color: "var(--chart-1)" }, r: { label: "Teletrabajo", color: "var(--chart-2)" } }
function Kpi({ t, v, b }) { return <Card className="gap-1 py-5"><CardHeader className="px-5"><CardDescription>{t}</CardDescription><CardTitle className="text-3xl font-light tabular-nums">{v}</CardTitle><CardAction><Badge variant="outline">{b}</Badge></CardAction></CardHeader></Card> }
export default function Preview() {
  return (<div className="grid gap-6">
  <div className="flex items-end justify-between"><div><h1 className="text-3xl font-semibold tracking-tight">Panel de Personas</h1><p className="text-muted-foreground text-sm">Datos de la oficina de Madrid · semana 42</p></div>
    <Tabs defaultValue="mes"><TabsList><TabsTrigger value="semana">Semana</TabsTrigger><TabsTrigger value="mes">Mes</TabsTrigger><TabsTrigger value="anio">Año</TabsTrigger></TabsList></Tabs></div>
  <div className="grid grid-cols-4 gap-4"><Kpi t="Plantilla" v="1.248" b="+12" /><Kpi t="Ausencias hoy" v="37" b="3 %" /><Kpi t="Teletrabajo" v="39 %" b="+5 pt" /><Kpi t="Rotación anual" v="6,2 %" b="−0,4 pt" /></div>
  <div className="grid grid-cols-3 gap-4">
    <Card className="col-span-2"><CardHeader><CardTitle>Modalidad de trabajo</CardTitle><CardDescription>Porcentaje de jornadas por semana</CardDescription></CardHeader>
      <CardContent><ChartContainer config={config} className="h-[220px] w-full"><AreaChart data={data} accessibilityLayer><CartesianGrid vertical={false} /><XAxis dataKey="s" tickLine={false} axisLine={false} tickMargin={8} />
        <ChartTooltip content={<ChartTooltipContent indicator="line" />} /><ChartLegend content={<ChartLegendContent />} />
        <Area dataKey="p" type="monotone" fill="var(--color-p)" fillOpacity={0.15} stroke="var(--color-p)" stackId="a" /><Area dataKey="r" type="monotone" fill="var(--color-r)" fillOpacity={0.2} stroke="var(--color-r)" stackId="a" /></AreaChart></ChartContainer></CardContent></Card>
    <Card><CardHeader><CardTitle>Formación obligatoria</CardTitle><CardDescription>Completada por la plantilla</CardDescription></CardHeader>
      <CardContent className="grid gap-4">{[["Prevención de riesgos", 92], ["Protección de datos", 74], ["Igualdad", 58]].map(([t, v]) => <div key={t} className="grid gap-2"><div className="flex justify-between text-sm"><span>{t}</span><span className="text-muted-foreground tabular-nums">{v} %</span></div><Progress value={v} aria-label={t} /></div>)}</CardContent></Card>
  </div>
  <div className="grid grid-cols-2 gap-4">
    <Card className="py-0"><ItemGroup>{[["documento", "Calendario laboral 2027", "Publicado el 15/10/2026"], ["comentario", "Encuesta de clima", "Cierra el 30/10/2026"]].map(([i, t, d], k) => <React.Fragment key={t}>{k > 0 && <ItemSeparator />}<Item><ItemMedia variant="icon"><HacceIcon name={i} size={18} /></ItemMedia><ItemContent><ItemTitle>{t}</ItemTitle><ItemDescription>{d}</ItemDescription></ItemContent><ItemActions><Button size="sm" variant="outline">Abrir</Button></ItemActions></Item></React.Fragment>)}</ItemGroup></Card>
    <Card className="py-0"><Empty className="py-6"><EmptyHeader><EmptyMedia variant="icon"><HacceIcon name="correcto" size={22} /></EmptyMedia><EmptyTitle>No hay incidencias</EmptyTitle><EmptyDescription>Todos los fichajes de ayer están completos.</EmptyDescription></EmptyHeader></Empty></Card>
  </div>
</div>)
}
