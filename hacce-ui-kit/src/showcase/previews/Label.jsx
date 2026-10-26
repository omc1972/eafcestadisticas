// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Label, Checkbox } = HacceUI
export default function Preview() {
  return (<div className="flex items-center gap-2"><Checkbox id="cond" /><Label htmlFor="cond">Acepto las condiciones de teletrabajo</Label></div>)
}
