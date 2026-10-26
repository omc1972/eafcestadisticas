// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Popover, PopoverTrigger, PopoverContent, Button, Label, Input } = HacceUI
export default function Preview() {
  return (<Popover defaultOpen>
  <PopoverTrigger asChild><Button variant="outline">Ajustar jornada</Button></PopoverTrigger>
  <PopoverContent align="start" className="w-72">
    <div className="grid gap-3"><p className="leading-none font-medium">Jornada</p><p className="text-muted-foreground text-sm">Horario de entrada y salida.</p>
      <div className="grid grid-cols-2 gap-2"><div className="grid gap-1"><Label htmlFor="po-1">Entrada</Label><Input id="po-1" defaultValue="08:30" /></div><div className="grid gap-1"><Label htmlFor="po-2">Salida</Label><Input id="po-2" defaultValue="17:00" /></div></div></div>
  </PopoverContent>
</Popover>)
}
