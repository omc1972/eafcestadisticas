// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { ThemeToggle, HacceTheme, HacceLogo } = HacceUI
function Demo() {
  return <HacceTheme storageKey={false} applyToDocument={false} defaultAppearance="light">
    <div className="flex items-center justify-between rounded-lg border px-4 py-3">
      <HacceLogo height={24} />
      <div className="flex items-center gap-2"><ThemeToggle /><ThemeToggle variant="icon" /></div>
    </div>
  </HacceTheme>
}
export default function Preview() {
  return (<Demo />)
}
