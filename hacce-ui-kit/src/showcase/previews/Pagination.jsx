// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Pagination, PaginationContent, PaginationItem, PaginationLink, PaginationPrevious, PaginationNext, PaginationEllipsis } = HacceUI
export default function Preview() {
  return (<Pagination><PaginationContent>
  <PaginationItem><PaginationPrevious href="#" /></PaginationItem>
  <PaginationItem><PaginationLink href="#">1</PaginationLink></PaginationItem>
  <PaginationItem><PaginationLink href="#" isActive>2</PaginationLink></PaginationItem>
  <PaginationItem><PaginationLink href="#">3</PaginationLink></PaginationItem>
  <PaginationItem><PaginationEllipsis /></PaginationItem>
  <PaginationItem><PaginationNext href="#" /></PaginationItem>
</PaginationContent></Pagination>)
}
