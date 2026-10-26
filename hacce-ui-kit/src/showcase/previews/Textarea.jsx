// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Textarea, Label } = HacceUI
export default function Preview() {
  return (<div className="grid max-w-md gap-2">
  <Label htmlFor="motivo">Motivo de la solicitud</Label>
  <Textarea id="motivo" placeholder="Cuéntanos brevemente el motivo" />
  <p className="text-muted-foreground text-sm">Máximo 500 caracteres.</p>
</div>)
}
