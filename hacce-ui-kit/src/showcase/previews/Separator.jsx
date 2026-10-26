// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Separator } = HacceUI
export default function Preview() {
  return (<div className="max-w-sm">
  <p className="text-sm font-semibold">Portal del empleado</p><p className="text-muted-foreground text-sm">Todo lo que necesitas en un sitio.</p>
  <Separator className="my-4" />
  <div className="flex h-5 items-center gap-4 text-sm"><span>Inicio</span><Separator orientation="vertical" /><span>Vacaciones</span><Separator orientation="vertical" /><span>Nóminas</span></div>
</div>)
}
