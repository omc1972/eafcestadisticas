// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Sheet, SheetTrigger, SheetContent, SheetHeader, SheetTitle, SheetDescription, SheetFooter, SheetClose, Button, Label, Input } = HacceUI
export default function Preview() {
  return (<Sheet defaultOpen>
  <SheetTrigger asChild><Button variant="outline">Filtros</Button></SheetTrigger>
  <SheetContent>
    <SheetHeader><SheetTitle>Filtrar solicitudes</SheetTitle><SheetDescription>Acota la lista por persona o fecha.</SheetDescription></SheetHeader>
    <div className="grid gap-4 px-4"><div className="grid gap-2"><Label htmlFor="sh-p">Persona</Label><Input id="sh-p" placeholder="Nombre o apellido" /></div></div>
    <SheetFooter><Button>Aplicar filtros</Button><SheetClose asChild><Button variant="outline">Cerrar</Button></SheetClose></SheetFooter>
  </SheetContent>
</Sheet>)
}
