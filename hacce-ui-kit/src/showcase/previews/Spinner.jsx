// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Spinner, Button } = HacceUI
export default function Preview() {
  return (<div className="flex items-center gap-6">
  <Spinner /><Spinner className="size-6 text-primary" />
  <Button disabled><Spinner /> Enviando</Button>
</div>)
}
