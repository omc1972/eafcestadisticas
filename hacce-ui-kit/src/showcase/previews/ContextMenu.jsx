// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { ContextMenu, ContextMenuTrigger, ContextMenuContent, ContextMenuItem, ContextMenuSeparator, ContextMenuShortcut } = HacceUI
export default function Preview() {
  return (<ContextMenu>
  <ContextMenuTrigger className="flex h-32 w-full max-w-md items-center justify-center rounded-lg border border-dashed text-sm text-muted-foreground">Haz clic derecho sobre el documento</ContextMenuTrigger>
  <ContextMenuContent className="w-52"><ContextMenuItem>Abrir <ContextMenuShortcut>⌘O</ContextMenuShortcut></ContextMenuItem><ContextMenuItem>Descargar</ContextMenuItem><ContextMenuSeparator /><ContextMenuItem variant="destructive">Mover a la papelera</ContextMenuItem></ContextMenuContent>
</ContextMenu>)
}
