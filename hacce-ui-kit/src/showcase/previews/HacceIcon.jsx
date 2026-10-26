// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { HacceIcon, hacceIconNames } = HacceUI
export default function Preview() {
  return (<div className="grid grid-cols-12 gap-4 text-primary">
  {hacceIconNames.map(n => <div key={n} className="flex flex-col items-center gap-1"><HacceIcon name={n} size={24} /><span className="text-[10px] text-muted-foreground">{n}</span></div>)}
</div>)
}
