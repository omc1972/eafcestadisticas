// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Calendar } = HacceUI
function Demo() {
  const [d, setD] = React.useState(new Date(2026, 9, 19))
  return <Calendar mode="single" selected={d} onSelect={setD} defaultMonth={new Date(2026, 9, 1)} className="rounded-lg border" />
}
export default function Preview() {
  return (<Demo />)
}
