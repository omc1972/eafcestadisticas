// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Card, CardHeader, CardTitle, CardDescription, CardAction, CardContent, CardFooter, Button, Badge } = HacceUI
export default function Preview() {
  return (<div className="grid gap-4 md:grid-cols-2">
  <Card>
    <CardHeader><CardTitle>Vacaciones 2026</CardTitle><CardDescription>Te quedan días por disfrutar este año.</CardDescription><CardAction><Badge variant="outline">Anual</Badge></CardAction></CardHeader>
    <CardContent><p className="text-3xl font-light tabular-nums">12 <span className="text-muted-foreground text-base">de 23 días</span></p></CardContent>
    <CardFooter className="gap-2"><Button size="sm">Nueva solicitud</Button><Button size="sm" variant="ghost">Ver historial</Button></CardFooter>
  </Card>
  <Card>
    <CardHeader><CardTitle>Próximo festivo</CardTitle><CardDescription>Calendario laboral de Madrid</CardDescription></CardHeader>
    <CardContent><p className="text-xl font-semibold">01/11/2026</p><p className="text-muted-foreground text-sm">Todos los Santos</p></CardContent>
  </Card>
</div>)
}
