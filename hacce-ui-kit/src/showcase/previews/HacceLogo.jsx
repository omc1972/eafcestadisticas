// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { HacceLogo } = HacceUI
export default function Preview() {
  return (<div className="flex flex-wrap items-end gap-10">
  <HacceLogo height={40} />
  <HacceLogo variant="vertical" height={80} />
</div>)
}
