// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { Collapsible, CollapsibleTrigger, CollapsibleContent, Button } = HacceUI
const { ChevronsUpDown } = Icons
export default function Preview() {
  return (<Collapsible defaultOpen className="grid max-w-sm gap-2">
  <div className="flex items-center justify-between"><p className="text-sm font-semibold">3 documentos pendientes de firma</p>
    <CollapsibleTrigger asChild><Button variant="ghost" size="icon" aria-label="Mostrar u ocultar"><ChevronsUpDown /></Button></CollapsibleTrigger></div>
  <div className="rounded-md border px-4 py-2 text-sm">Contrato de teletrabajo</div>
  <CollapsibleContent className="grid gap-2"><div className="rounded-md border px-4 py-2 text-sm">Política de privacidad</div><div className="rounded-md border px-4 py-2 text-sm">Anexo salarial</div></CollapsibleContent>
</Collapsible>)
}
