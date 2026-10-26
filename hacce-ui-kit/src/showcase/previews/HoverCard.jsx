// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { HoverCard, HoverCardTrigger, HoverCardContent, Avatar, AvatarFallback, Button } = HacceUI
export default function Preview() {
  return (<HoverCard defaultOpen>
  <HoverCardTrigger asChild><Button variant="link">@lmartin</Button></HoverCardTrigger>
  <HoverCardContent align="start" className="w-72"><div className="flex gap-3"><Avatar><AvatarFallback>LM</AvatarFallback></Avatar>
    <div className="grid gap-1"><p className="text-sm font-semibold">Lucía Martín</p><p className="text-sm">Responsable de Personas · Madrid</p><p className="text-muted-foreground text-xs">En Hacce desde 2019</p></div></div></HoverCardContent>
</HoverCard>)
}
