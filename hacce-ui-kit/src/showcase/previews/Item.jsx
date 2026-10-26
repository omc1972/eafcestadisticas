// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Item, ItemMedia, ItemContent, ItemTitle, ItemDescription, ItemActions, ItemGroup, ItemSeparator, Button, HacceIcon } = HacceUI
export default function Preview() {
  return (<ItemGroup className="max-w-lg rounded-lg border">
  <Item><ItemMedia variant="icon"><HacceIcon name="documento" size={18} /></ItemMedia><ItemContent><ItemTitle>Nómina de octubre</ItemTitle><ItemDescription>PDF · 182 KB · 31/10/2026</ItemDescription></ItemContent><ItemActions><Button size="sm" variant="outline">Descargar</Button></ItemActions></Item>
  <ItemSeparator />
  <Item><ItemMedia variant="icon"><HacceIcon name="calendario" size={18} /></ItemMedia><ItemContent><ItemTitle>Calendario laboral 2027</ItemTitle><ItemDescription>Publicado el 15/10/2026</ItemDescription></ItemContent><ItemActions><Button size="sm" variant="ghost">Abrir</Button></ItemActions></Item>
</ItemGroup>)
}
