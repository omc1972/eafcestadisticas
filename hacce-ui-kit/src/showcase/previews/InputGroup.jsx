// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText, InputGroupButton } = HacceUI
const { Search, Copy } = Icons
export default function Preview() {
  return (<div className="grid max-w-sm gap-4">
  <InputGroup>
    <InputGroupAddon><Search /></InputGroupAddon>
    <InputGroupInput placeholder="Buscar empleado" />
    <InputGroupAddon align="inline-end"><InputGroupText>12 resultados</InputGroupText></InputGroupAddon>
  </InputGroup>
  <InputGroup>
    <InputGroupAddon><InputGroupText>https://</InputGroupText></InputGroupAddon>
    <InputGroupInput defaultValue="portal.hacce.com" />
    <InputGroupAddon align="inline-end"><InputGroupButton size="icon-xs" aria-label="Copiar"><Copy /></InputGroupButton></InputGroupAddon>
  </InputGroup>
</div>)
}
