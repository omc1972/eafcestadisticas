// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Field, FieldLabel, FieldDescription, FieldError, FieldGroup, FieldSet, FieldLegend, FieldSeparator, Input, Switch, Checkbox } = HacceUI
export default function Preview() {
  return (<FieldSet className="max-w-md">
  <FieldLegend>Datos de la solicitud</FieldLegend>
  <FieldGroup>
    <Field><FieldLabel htmlFor="f-nombre">Nombre completo</FieldLabel><Input id="f-nombre" defaultValue="Lucía Martín" /></Field>
    <Field data-invalid="true"><FieldLabel htmlFor="f-tel">Teléfono</FieldLabel><Input id="f-tel" aria-invalid="true" defaultValue="600 12" /><FieldError>Escribe un teléfono de 9 cifras.</FieldError></Field>
    <FieldSeparator />
    <Field orientation="horizontal"><Switch id="f-aviso" defaultChecked /><FieldLabel htmlFor="f-aviso">Avisarme por correo</FieldLabel></Field>
    <Field orientation="horizontal"><Checkbox id="f-resp" /><div><FieldLabel htmlFor="f-resp">Notificar a mi responsable</FieldLabel><FieldDescription>Recibirá una copia de la solicitud.</FieldDescription></div></Field>
  </FieldGroup>
</FieldSet>)
}
