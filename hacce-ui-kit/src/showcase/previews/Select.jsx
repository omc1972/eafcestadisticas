// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectLabel, SelectItem } = HacceUI
export default function Preview() {
  return (<div className="flex flex-wrap gap-4">
  <Select defaultValue="madrid">
    <SelectTrigger className="w-56" aria-label="Oficina"><SelectValue placeholder="Elige una oficina" /></SelectTrigger>
    <SelectContent><SelectGroup><SelectLabel>Oficinas</SelectLabel>
      <SelectItem value="madrid">Madrid</SelectItem><SelectItem value="barcelona">Barcelona</SelectItem><SelectItem value="valencia">Valencia</SelectItem>
    </SelectGroup></SelectContent>
  </Select>
  <Select><SelectTrigger size="sm" className="w-40" aria-label="Estado"><SelectValue placeholder="Estado" /></SelectTrigger>
    <SelectContent><SelectItem value="p">Pendiente</SelectItem><SelectItem value="a">Aprobada</SelectItem></SelectContent></Select>
</div>)
}
