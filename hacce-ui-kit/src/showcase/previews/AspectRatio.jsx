// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { AspectRatio, HacceLogo } = HacceUI
export default function Preview() {
  return (<div className="w-72"><AspectRatio ratio={16 / 9} className="bg-hacce-azul-claro flex items-center justify-center rounded-lg">
  <HacceLogo height={36} className="text-hacce-negro" /></AspectRatio></div>)
}
