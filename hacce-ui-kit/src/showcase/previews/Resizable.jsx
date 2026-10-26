// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { ResizablePanelGroup, ResizablePanel, ResizableHandle } = HacceUI
export default function Preview() {
  return (<ResizablePanelGroup direction="horizontal" className="max-w-lg rounded-lg border" style={{ height: 180 }}>
  <ResizablePanel defaultSize={35}><div className="flex h-full items-center justify-center p-4 text-sm">Bandeja</div></ResizablePanel>
  <ResizableHandle withHandle />
  <ResizablePanel defaultSize={65}><div className="flex h-full items-center justify-center p-4 text-sm">Detalle de la solicitud</div></ResizablePanel>
</ResizablePanelGroup>)
}
