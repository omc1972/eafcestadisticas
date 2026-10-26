// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { Badge } = HacceUI
const { CircleCheck, TriangleAlert } = Icons
export default function Preview() {
  return (<div className="flex flex-wrap items-center gap-2">
  <Badge>Nuevo</Badge><Badge variant="secondary">Borrador</Badge><Badge variant="outline">Pendiente</Badge><Badge variant="destructive">Rechazada</Badge>
  <Badge variant="outline" className="text-success"><CircleCheck /> Aprobada</Badge>
  <Badge variant="outline" className="text-warning"><TriangleAlert /> Caduca pronto</Badge>
</div>)
}
