// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { ScrollArea, Separator } = HacceUI
const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
export default function Preview() {
  return (<ScrollArea className="h-48 w-56 rounded-md border"><div className="p-4"><p className="mb-3 text-sm font-semibold">Nóminas 2026</p>
  {meses.map(m => <React.Fragment key={m}><div className="text-sm">{m}</div><Separator className="my-2" /></React.Fragment>)}</div></ScrollArea>)
}
