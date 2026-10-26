// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { RadioGroup, RadioGroupItem, Label } = HacceUI
export default function Preview() {
  return (<RadioGroup defaultValue="hibrido">
  <div className="flex items-center gap-2"><RadioGroupItem value="presencial" id="r1" /><Label htmlFor="r1">Presencial</Label></div>
  <div className="flex items-center gap-2"><RadioGroupItem value="hibrido" id="r2" /><Label htmlFor="r2">Híbrido</Label></div>
  <div className="flex items-center gap-2"><RadioGroupItem value="remoto" id="r3" /><Label htmlFor="r3">Teletrabajo</Label></div>
</RadioGroup>)
}
