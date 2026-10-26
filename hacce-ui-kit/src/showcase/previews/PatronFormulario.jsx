// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { FieldSet, FieldLegend, FieldDescription, FieldGroup, Field, FieldLabel, FieldError, FieldSeparator, Input, Textarea, Select, SelectTrigger, SelectValue, SelectContent, SelectItem, RadioGroup, RadioGroupItem, Switch, Button, Calendar, Popover, PopoverTrigger, PopoverContent, Alert, AlertDescription, HacceIcon } = HacceUI
const { Info } = Icons
export default function Preview() {
  return (<div className="mx-auto grid max-w-2xl gap-6">
  <div><h1 className="text-3xl font-semibold tracking-tight">Nueva solicitud</h1><p className="text-muted-foreground text-sm">Tu responsable recibirá un aviso al enviarla.</p></div>
  <Alert><Info /><AlertDescription>Te quedan 12 días de vacaciones en 2026.</AlertDescription></Alert>
  <form className="grid gap-6" onSubmit={e => e.preventDefault()}>
    <FieldSet><FieldLegend>Tipo y fechas</FieldLegend><FieldGroup>
      <Field><FieldLabel htmlFor="pf-tipo">Tipo de ausencia</FieldLabel><Select defaultValue="vacaciones"><SelectTrigger id="pf-tipo" className="w-full"><SelectValue /></SelectTrigger><SelectContent><SelectItem value="vacaciones">Vacaciones</SelectItem><SelectItem value="propios">Asuntos propios</SelectItem><SelectItem value="medico">Cita médica</SelectItem></SelectContent></Select></Field>
      <div className="grid grid-cols-2 gap-4">
        <Field><FieldLabel htmlFor="pf-desde">Desde</FieldLabel><Input id="pf-desde" defaultValue="03/11/2026" /></Field>
        <Field data-invalid="true"><FieldLabel htmlFor="pf-hasta">Hasta</FieldLabel><Input id="pf-hasta" aria-invalid="true" defaultValue="01/11/2026" /><FieldError>La fecha final debe ser posterior a la inicial.</FieldError></Field>
      </div>
    </FieldGroup></FieldSet>
    <FieldSeparator />
    <FieldSet><FieldLegend>Detalles</FieldLegend><FieldGroup>
      <Field><FieldLabel>Jornada</FieldLabel><RadioGroup defaultValue="completa" className="flex gap-6"><div className="flex items-center gap-2"><RadioGroupItem value="completa" id="pf-c" /><FieldLabel htmlFor="pf-c" className="font-normal">Día completo</FieldLabel></div><div className="flex items-center gap-2"><RadioGroupItem value="media" id="pf-m" /><FieldLabel htmlFor="pf-m" className="font-normal">Media jornada</FieldLabel></div></RadioGroup></Field>
      <Field><FieldLabel htmlFor="pf-com">Comentario (opcional)</FieldLabel><Textarea id="pf-com" placeholder="Algo que deba saber tu responsable" /><FieldDescription>Máximo 500 caracteres.</FieldDescription></Field>
      <Field orientation="horizontal"><Switch id="pf-av" defaultChecked /><FieldLabel htmlFor="pf-av">Avisar a mi equipo por correo</FieldLabel></Field>
    </FieldGroup></FieldSet>
    <div className="flex gap-2"><Button type="submit">Enviar solicitud</Button><Button type="button" variant="ghost">Guardar borrador</Button></div>
  </form>
</div>)
}
