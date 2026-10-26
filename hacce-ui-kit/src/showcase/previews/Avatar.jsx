// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Avatar, AvatarImage, AvatarFallback } = HacceUI
export default function Preview() {
  return (<div className="flex items-center gap-4">
  <Avatar><AvatarFallback>LM</AvatarFallback></Avatar>
  <Avatar className="size-10"><AvatarFallback className="bg-primary text-primary-foreground">JR</AvatarFallback></Avatar>
  <div className="flex -space-x-2">{["AS", "PG", "MT"].map(i => <Avatar key={i} className="ring-background ring-2"><AvatarFallback>{i}</AvatarFallback></Avatar>)}</div>
</div>)
}
