// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Accordion, AccordionItem, AccordionTrigger, AccordionContent } = HacceUI
export default function Preview() {
  return (<Accordion type="single" collapsible defaultValue="a" className="max-w-lg">
  <AccordionItem value="a"><AccordionTrigger>¿Cuántos días de vacaciones tengo?</AccordionTrigger><AccordionContent>23 días laborables al año, que puedes repartir como quieras.</AccordionContent></AccordionItem>
  <AccordionItem value="b"><AccordionTrigger>¿Puedo teletrabajar?</AccordionTrigger><AccordionContent>Sí, hasta tres días por semana si tu puesto lo permite.</AccordionContent></AccordionItem>
  <AccordionItem value="c"><AccordionTrigger>¿Dónde están mis nóminas?</AccordionTrigger><AccordionContent>En Documentos, ordenadas por mes.</AccordionContent></AccordionItem>
</Accordion>)
}
