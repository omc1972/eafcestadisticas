// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { ToggleGroup, ToggleGroupItem } = HacceUI
const { AlignLeft, AlignCenter, AlignRight } = Icons
export default function Preview() {
  return (<div className="flex flex-wrap items-center gap-6">
  <ToggleGroup type="single" defaultValue="izq" aria-label="Alineación">
    <ToggleGroupItem value="izq" aria-label="Izquierda"><AlignLeft /></ToggleGroupItem>
    <ToggleGroupItem value="centro" aria-label="Centro"><AlignCenter /></ToggleGroupItem>
    <ToggleGroupItem value="der" aria-label="Derecha"><AlignRight /></ToggleGroupItem>
  </ToggleGroup>
  <ToggleGroup type="single" variant="outline" defaultValue="mes">
    <ToggleGroupItem value="semana" className="px-3">Semana</ToggleGroupItem>
    <ToggleGroupItem value="mes" className="px-3">Mes</ToggleGroupItem>
    <ToggleGroupItem value="anio" className="px-3">Año</ToggleGroupItem>
  </ToggleGroup>
</div>)
}
