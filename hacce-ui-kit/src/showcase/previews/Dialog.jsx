// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose, Button, Input, Label } = HacceUI
export default function Preview() {
  return (<Dialog defaultOpen>
  <DialogTrigger asChild><Button variant="outline">Editar perfil</Button></DialogTrigger>
  <DialogContent className="sm:max-w-md">
    <DialogHeader><DialogTitle>Editar perfil</DialogTitle><DialogDescription>Cambia tus datos de contacto. Guarda cuando termines.</DialogDescription></DialogHeader>
    <div className="grid gap-4"><div className="grid gap-2"><Label htmlFor="d-tel">Teléfono</Label><Input id="d-tel" defaultValue="600 123 456" /></div></div>
    <DialogFooter><DialogClose asChild><Button variant="outline">Cancelar</Button></DialogClose><Button>Guardar cambios</Button></DialogFooter>
  </DialogContent>
</Dialog>)
}
