// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Breadcrumb, BreadcrumbList, BreadcrumbItem, BreadcrumbLink, BreadcrumbPage, BreadcrumbSeparator, BreadcrumbEllipsis } = HacceUI
export default function Preview() {
  return (<Breadcrumb><BreadcrumbList>
  <BreadcrumbItem><BreadcrumbLink href="#">Inicio</BreadcrumbLink></BreadcrumbItem><BreadcrumbSeparator />
  <BreadcrumbItem><BreadcrumbEllipsis /></BreadcrumbItem><BreadcrumbSeparator />
  <BreadcrumbItem><BreadcrumbLink href="#">Solicitudes</BreadcrumbLink></BreadcrumbItem><BreadcrumbSeparator />
  <BreadcrumbItem><BreadcrumbPage>Vacaciones de noviembre</BreadcrumbPage></BreadcrumbItem>
</BreadcrumbList></Breadcrumb>)
}
