// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Recharts from "recharts"

const { ChartContainer, ChartTooltip, ChartTooltipContent, ChartLegend, ChartLegendContent } = HacceUI
const { BarChart, Bar, XAxis, CartesianGrid } = Recharts
const data = [{ mes: "Jun", presencial: 186, remoto: 80 }, { mes: "Jul", presencial: 205, remoto: 120 }, { mes: "Ago", presencial: 120, remoto: 190 }, { mes: "Sep", presencial: 173, remoto: 130 }, { mes: "Oct", presencial: 209, remoto: 140 }]
const config = { presencial: { label: "Presencial", color: "var(--chart-1)" }, remoto: { label: "Teletrabajo", color: "var(--chart-2)" } }
export default function Preview() {
  return (<ChartContainer config={config} className="h-[240px] w-full">
  <BarChart data={data} accessibilityLayer><CartesianGrid vertical={false} /><XAxis dataKey="mes" tickLine={false} axisLine={false} tickMargin={8} />
    <ChartTooltip content={<ChartTooltipContent />} /><ChartLegend content={<ChartLegendContent />} />
    <Bar dataKey="presencial" fill="var(--color-presencial)" radius={3} /><Bar dataKey="remoto" fill="var(--color-remoto)" radius={3} /></BarChart>
</ChartContainer>)
}
