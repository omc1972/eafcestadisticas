// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"
import * as Icons from "lucide-react"

const { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuLabel, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuShortcut, DropdownMenuCheckboxItem, Button } = HacceUI
const { User, Settings, LogOut } = Icons
export default function Preview() {
  return (<DropdownMenu defaultOpen modal={false}>
  <DropdownMenuTrigger asChild><Button variant="outline">Mi cuenta</Button></DropdownMenuTrigger>
  <DropdownMenuContent align="start" className="w-56">
    <DropdownMenuLabel>Lucía Martín</DropdownMenuLabel><DropdownMenuSeparator />
    <DropdownMenuItem><User /> Perfil <DropdownMenuShortcut>⇧⌘P</DropdownMenuShortcut></DropdownMenuItem>
    <DropdownMenuItem><Settings /> Ajustes</DropdownMenuItem>
    <DropdownMenuCheckboxItem checked>Avisos por correo</DropdownMenuCheckboxItem>
    <DropdownMenuSeparator /><DropdownMenuItem variant="destructive"><LogOut /> Cerrar sesión</DropdownMenuItem>
  </DropdownMenuContent>
</DropdownMenu>)
}
