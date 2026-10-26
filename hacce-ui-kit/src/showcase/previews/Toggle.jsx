// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { Toggle } = HacceUI
const { Bold, Italic, Underline } = Icons
export default function Preview() {
  return (<div className="flex items-center gap-2">
  <Toggle aria-label="Negrita" defaultPressed><Bold /></Toggle>
  <Toggle aria-label="Cursiva"><Italic /></Toggle>
  <Toggle variant="outline" aria-label="Subrayado"><Underline /></Toggle>
  <Toggle variant="outline">Solo pendientes</Toggle>
</div>)
}
