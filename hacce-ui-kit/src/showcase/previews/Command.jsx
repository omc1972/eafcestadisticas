// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Command, CommandInput, CommandList, CommandEmpty, CommandGroup, CommandItem, CommandSeparator, CommandShortcut, HacceIcon } = HacceUI
export default function Preview() {
  return (<Command className="max-w-md rounded-lg border shadow-md">
  <CommandInput placeholder="Busca una acción o una persona" />
  <CommandList><CommandEmpty>No hay resultados.</CommandEmpty>
    <CommandGroup heading="Acciones"><CommandItem><HacceIcon name="calendario" size={16} /> Pedir vacaciones</CommandItem><CommandItem><HacceIcon name="subir" size={16} /> Subir justificante</CommandItem><CommandItem><HacceIcon name="ajustes" size={16} /> Ajustes <CommandShortcut>⌘,</CommandShortcut></CommandItem></CommandGroup>
    <CommandSeparator />
    <CommandGroup heading="Personas"><CommandItem>Lucía Martín</CommandItem><CommandItem>Jorge Ruiz</CommandItem></CommandGroup>
  </CommandList>
</Command>)
}
