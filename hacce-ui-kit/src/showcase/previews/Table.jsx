// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Table, TableHeader, TableBody, TableFooter, TableRow, TableHead, TableCell, TableCaption, Badge } = HacceUI
const rows = [["Lucía Martín", "Vacaciones", "03/11/2026", "5", "Aprobada"], ["Jorge Ruiz", "Asuntos propios", "07/11/2026", "1", "Pendiente"], ["Ana Sanz", "Teletrabajo", "10/11/2026", "3", "Aprobada"]]
export default function Preview() {
  return (<Table>
  <TableCaption>Solicitudes de noviembre</TableCaption>
  <TableHeader><TableRow><TableHead>Persona</TableHead><TableHead>Tipo</TableHead><TableHead>Desde</TableHead><TableHead className="text-right">Días</TableHead><TableHead>Estado</TableHead></TableRow></TableHeader>
  <TableBody>{rows.map(r => <TableRow key={r[0]}><TableCell className="font-medium">{r[0]}</TableCell><TableCell>{r[1]}</TableCell><TableCell>{r[2]}</TableCell><TableCell className="text-right tabular-nums">{r[3]}</TableCell>
    <TableCell><Badge variant={r[4] === "Aprobada" ? "default" : "outline"}>{r[4]}</Badge></TableCell></TableRow>)}</TableBody>
  <TableFooter><TableRow><TableCell colSpan={3}>Total</TableCell><TableCell className="text-right tabular-nums">9</TableCell><TableCell /></TableRow></TableFooter>
</Table>)
}
