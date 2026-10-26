// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Tooltip, TooltipTrigger, TooltipContent, Button, HacceIcon } = HacceUI
export default function Preview() {
  return (<div className="flex items-end gap-4 pt-10">
  <Tooltip defaultOpen><TooltipTrigger asChild><Button variant="outline" size="icon" aria-label="Ajustes"><HacceIcon name="ajustes" size={18} /></Button></TooltipTrigger><TooltipContent>Ajustes</TooltipContent></Tooltip>
</div>)
}
