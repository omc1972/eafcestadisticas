// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { Alert, AlertTitle, AlertDescription } = HacceUI
const { Info, CircleAlert } = Icons
export default function Preview() {
  return (<div className="grid max-w-xl gap-3">
  <Alert><Info /><AlertTitle>Nuevo calendario laboral</AlertTitle><AlertDescription>Ya puedes consultar los festivos de 2027.</AlertDescription></Alert>
  <Alert variant="destructive"><CircleAlert /><AlertTitle>No se ha podido enviar</AlertTitle><AlertDescription>Revisa tu conexión e inténtalo de nuevo.</AlertDescription></Alert>
</div>)
}
