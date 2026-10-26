// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Carousel, CarouselContent, CarouselItem, CarouselPrevious, CarouselNext, Card, CardContent } = HacceUI
const avisos = ["Nuevo calendario 2027", "Formación en seguridad", "Encuesta de clima", "Cambio de oficina"]
export default function Preview() {
  return (<div className="px-12"><Carousel className="max-w-sm"><CarouselContent>
  {avisos.map(a => <CarouselItem key={a}><Card><CardContent className="flex h-24 items-center justify-center text-center font-semibold">{a}</CardContent></Card></CarouselItem>)}
</CarouselContent><CarouselPrevious /><CarouselNext /></Carousel></div>)
}
