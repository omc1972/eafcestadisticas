// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Checkbox, Label } = HacceUI
export default function Preview() {
  return (<div className="grid gap-3">
  <div className="flex items-center gap-2"><Checkbox id="c1" defaultChecked /><Label htmlFor="c1">Vacaciones</Label></div>
  <div className="flex items-center gap-2"><Checkbox id="c2" /><Label htmlFor="c2">Asuntos propios</Label></div>
  <div className="flex items-center gap-2"><Checkbox id="c3" disabled /><Label htmlFor="c3">Baja médica (no disponible)</Label></div>
</div>)
}
