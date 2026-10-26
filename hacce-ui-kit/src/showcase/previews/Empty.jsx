// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription, EmptyContent, Button, HacceIcon } = HacceUI
export default function Preview() {
  return (<Empty className="border">
  <EmptyHeader><EmptyMedia variant="icon"><HacceIcon name="carpeta" size={24} /></EmptyMedia>
    <EmptyTitle>Aún no hay documentos</EmptyTitle><EmptyDescription>Cuando subas un justificante o un contrato aparecerá aquí.</EmptyDescription></EmptyHeader>
  <EmptyContent><Button><HacceIcon name="subir" size={16} /> Subir documento</Button></EmptyContent>
</Empty>)
}
