// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Slider, Label } = HacceUI
export default function Preview() {
  return (<div className="grid max-w-sm gap-6">
  <div className="grid gap-3"><Label>Jornada semanal (horas)</Label><Slider defaultValue={[37]} max={40} step={1} /></div>
  <div className="grid gap-3"><Label>Rango salarial</Label><Slider defaultValue={[25, 60]} max={100} step={5} /></div>
</div>)
}
