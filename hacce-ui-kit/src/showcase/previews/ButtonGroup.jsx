// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { ButtonGroup, ButtonGroupSeparator, Button } = HacceUI
const { ChevronDown, ChevronLeft, ChevronRight } = Icons
export default function Preview() {
  return (<div className="flex flex-wrap items-center gap-6">
  <ButtonGroup aria-label="Paginación">
    <Button variant="outline" size="icon" aria-label="Anterior"><ChevronLeft /></Button>
    <Button variant="outline" size="icon" aria-label="Siguiente"><ChevronRight /></Button>
  </ButtonGroup>
  <ButtonGroup>
    <Button variant="outline">Hoy</Button>
    <Button variant="outline">Semana</Button>
    <Button variant="outline">Mes</Button>
  </ButtonGroup>
  <ButtonGroup>
    <Button>Enviar</Button>
    <ButtonGroupSeparator />
    <Button size="icon" aria-label="Más opciones de envío"><ChevronDown /></Button>
  </ButtonGroup>
</div>)
}
