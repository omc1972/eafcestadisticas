// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Drawer, DrawerTrigger, DrawerContent, DrawerHeader, DrawerTitle, DrawerDescription, DrawerFooter, DrawerClose, Button } = HacceUI
export default function Preview() {
  return (<Drawer defaultOpen>
  <DrawerTrigger asChild><Button variant="outline">Ver resumen</Button></DrawerTrigger>
  <DrawerContent>
    <div className="mx-auto w-full max-w-sm">
      <DrawerHeader><DrawerTitle>Resumen de la semana</DrawerTitle><DrawerDescription>37,5 horas registradas de 40.</DrawerDescription></DrawerHeader>
      <DrawerFooter><Button>Confirmar horas</Button><DrawerClose asChild><Button variant="outline">Cerrar</Button></DrawerClose></DrawerFooter>
    </div>
  </DrawerContent>
</Drawer>)
}
