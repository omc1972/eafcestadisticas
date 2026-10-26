// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Skeleton } = HacceUI
export default function Preview() {
  return (<div className="flex items-center gap-4">
  <Skeleton className="size-12 rounded-full" />
  <div className="grid gap-2"><Skeleton className="h-4 w-64" /><Skeleton className="h-4 w-48" /></div>
</div>)
}
