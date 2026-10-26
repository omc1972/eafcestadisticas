// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Tabs, TabsList, TabsTrigger, TabsContent, Card, CardContent } = HacceUI
export default function Preview() {
  return (<Tabs defaultValue="pendientes" className="max-w-lg">
  <TabsList><TabsTrigger value="pendientes">Pendientes</TabsTrigger><TabsTrigger value="aprobadas">Aprobadas</TabsTrigger><TabsTrigger value="historial">Historial</TabsTrigger></TabsList>
  <TabsContent value="pendientes"><Card><CardContent className="text-sm">Tienes 2 solicitudes pendientes de revisar.</CardContent></Card></TabsContent>
  <TabsContent value="aprobadas"><Card><CardContent className="text-sm">5 solicitudes aprobadas este mes.</CardContent></Card></TabsContent>
  <TabsContent value="historial"><Card><CardContent className="text-sm">Sin cambios recientes.</CardContent></Card></TabsContent>
</Tabs>)
}
