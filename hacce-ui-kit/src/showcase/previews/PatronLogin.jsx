// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { HacceLogo, Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter, Field, FieldLabel, FieldGroup, Input, Button, Checkbox, Label, Separator } = HacceUI
export default function Preview() {
  return (<div className="grid min-h-[520px] grid-cols-2 overflow-hidden rounded-lg border">
  <div className="bg-hacce-azul-claro text-hacce-negro relative flex flex-col justify-between p-10">
    <HacceLogo height={28} />
    <div><p className="font-display text-6xl leading-none">Trabajar<br />mejor</p><p className="mt-4 max-w-xs text-sm">Tu portal para vacaciones, fichajes, nóminas y documentos.</p></div>
  </div>
  <div className="flex items-center justify-center p-10">
    <Card className="w-full max-w-sm shadow-none border-0">
      <CardHeader className="px-0"><CardTitle className="text-2xl">Accede al portal</CardTitle><CardDescription>Usa tu correo de Hacce.</CardDescription></CardHeader>
      <CardContent className="px-0"><FieldGroup className="gap-4">
        <Field><FieldLabel htmlFor="l-mail">Correo electrónico</FieldLabel><Input id="l-mail" type="email" placeholder="nombre@hacce.com" /></Field>
        <Field><div className="flex items-center justify-between"><FieldLabel htmlFor="l-pass">Contraseña</FieldLabel><a href="#" className="text-primary text-sm underline-offset-4 hover:underline">¿La has olvidado?</a></div><Input id="l-pass" type="password" /></Field>
        <div className="flex items-center gap-2"><Checkbox id="l-rec" /><Label htmlFor="l-rec">Recordarme en este equipo</Label></div>
      </FieldGroup></CardContent>
      <CardFooter className="grid gap-3 px-0"><Button className="w-full">Entrar</Button><Separator /><Button variant="outline" className="w-full">Entrar con Microsoft</Button></CardFooter>
    </Card>
  </div>
</div>)
}
