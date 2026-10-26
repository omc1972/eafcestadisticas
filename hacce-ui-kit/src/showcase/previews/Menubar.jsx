// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { Menubar, MenubarMenu, MenubarTrigger, MenubarContent, MenubarItem, MenubarSeparator, MenubarShortcut, MenubarCheckboxItem } = HacceUI
export default function Preview() {
  return (<Menubar defaultValue="archivo">
  <MenubarMenu value="archivo"><MenubarTrigger>Archivo</MenubarTrigger><MenubarContent>
    <MenubarItem>Nuevo informe <MenubarShortcut>⌘N</MenubarShortcut></MenubarItem><MenubarItem>Abrir…</MenubarItem><MenubarSeparator /><MenubarItem>Exportar a PDF</MenubarItem></MenubarContent></MenubarMenu>
  <MenubarMenu><MenubarTrigger>Editar</MenubarTrigger><MenubarContent><MenubarItem>Deshacer <MenubarShortcut>⌘Z</MenubarShortcut></MenubarItem></MenubarContent></MenubarMenu>
  <MenubarMenu><MenubarTrigger>Ver</MenubarTrigger><MenubarContent><MenubarCheckboxItem checked>Mostrar totales</MenubarCheckboxItem></MenubarContent></MenubarMenu>
</Menubar>)
}
