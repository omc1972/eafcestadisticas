// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Input, Label } = HacceUI
export default function Preview() {
  return (<div className="grid max-w-sm gap-4">
  <div className="grid gap-2"><Label htmlFor="email">Correo electrónico</Label><Input id="email" type="email" placeholder="nombre@hacce.com" /></div>
  <div className="grid gap-2"><Label htmlFor="dni">DNI</Label><Input id="dni" aria-invalid="true" defaultValue="1234" /></div>
  <Input disabled placeholder="Campo desactivado" />
</div>)
}
