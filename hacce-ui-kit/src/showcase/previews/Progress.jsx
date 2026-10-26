// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Progress } = HacceUI
export default function Preview() {
  return (<div className="grid max-w-sm gap-2">
  <div className="flex justify-between text-sm"><span>Formación obligatoria</span><span className="text-muted-foreground tabular-nums">60 %</span></div>
  <Progress value={60} aria-label="Formación obligatoria" />
</div>)
}
