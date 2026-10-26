// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Kbd, KbdGroup } = HacceUI
export default function Preview() {
  return (<div className="flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
  <span className="flex items-center gap-2">Buscar <KbdGroup><Kbd>⌘</Kbd><Kbd>K</Kbd></KbdGroup></span>
  <span className="flex items-center gap-2">Guardar <KbdGroup><Kbd>Ctrl</Kbd><span>+</span><Kbd>S</Kbd></KbdGroup></span>
</div>)
}
