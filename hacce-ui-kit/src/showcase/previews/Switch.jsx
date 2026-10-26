// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Switch, Label } = HacceUI
export default function Preview() {
  return (<div className="grid gap-3">
  <div className="flex items-center gap-2"><Switch id="s1" defaultChecked /><Label htmlFor="s1">Notificaciones activadas</Label></div>
  <div className="flex items-center gap-2"><Switch id="s2" /><Label htmlFor="s2">Modo compacto</Label></div>
</div>)
}
