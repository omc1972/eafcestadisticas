// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { Button, HacceIcon } = HacceUI
const { Loader2, Plus, Trash2 } = Icons
export default function Preview() {
  return (<div className="flex flex-col gap-4">
  <div className="flex flex-wrap items-center gap-2">
    <Button>Guardar</Button>
    <Button variant="secondary">Borrador</Button>
    <Button variant="outline">Exportar</Button>
    <Button variant="ghost">Cancelar</Button>
    <Button variant="link">Ver detalle</Button>
    <Button variant="destructive"><Trash2 /> Eliminar solicitud</Button>
  </div>
  <div className="flex flex-wrap items-center gap-2">
    <Button size="sm">Pequeño</Button>
    <Button size="lg"><HacceIcon name="descargar" size={16} /> Descargar</Button>
    <Button size="icon" variant="outline" aria-label="Añadir"><Plus /></Button>
    <Button disabled><Loader2 className="animate-spin" /> Enviando</Button>
    <Button disabled variant="outline">No disponible</Button>
  </div>
</div>)
}
