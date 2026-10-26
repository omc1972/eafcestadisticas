// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Toaster, toast, Button } = HacceUI
function Demo() {
  React.useEffect(() => { toast("Solicitud enviada", { description: "Tu responsable la revisará en 48 horas." }) }, [])
  return <div className="flex flex-wrap gap-2">
    <Button variant="outline" onClick={() => toast.success("Cambios guardados")}>Guardar</Button>
    <Button variant="outline" onClick={() => toast.error("No se ha podido enviar. Inténtalo de nuevo.")}>Simular error</Button>
    <Toaster position="bottom-right" />
  </div>
}
export default function Preview() {
  return (<Demo />)
}
