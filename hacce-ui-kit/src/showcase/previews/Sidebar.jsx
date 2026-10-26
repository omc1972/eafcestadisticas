// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { SidebarProvider, Sidebar, SidebarHeader, SidebarContent, SidebarGroup, SidebarGroupLabel, SidebarGroupContent, SidebarMenu, SidebarMenuItem, SidebarMenuButton, SidebarMenuBadge, SidebarFooter, SidebarInset, SidebarTrigger, HacceLogo, HacceIcon, Separator } = HacceUI
const items = [["inicio", "Inicio", true], ["calendario", "Vacaciones"], ["documento", "Nóminas"], ["carpeta", "Documentos"], ["ajustes", "Ajustes"]]
export default function Preview() {
  return (<SidebarProvider className="min-h-[380px] rounded-lg border overflow-hidden" style={{ "--sidebar-width": "14rem" }}>
  <Sidebar collapsible="none" className="h-auto border-r">
    <SidebarHeader className="px-4 py-3"><HacceLogo height={22} /></SidebarHeader>
    <SidebarContent><SidebarGroup><SidebarGroupLabel>Portal</SidebarGroupLabel><SidebarGroupContent><SidebarMenu>
      {items.map(([i, t, a]) => <SidebarMenuItem key={t}><SidebarMenuButton isActive={!!a}><HacceIcon name={i} size={16} /><span>{t}</span></SidebarMenuButton>{t === "Vacaciones" && <SidebarMenuBadge>2</SidebarMenuBadge>}</SidebarMenuItem>)}
    </SidebarMenu></SidebarGroupContent></SidebarGroup></SidebarContent>
    <SidebarFooter className="text-muted-foreground px-4 text-xs">Lucía Martín</SidebarFooter>
  </Sidebar>
  <SidebarInset className="p-6"><h1 className="text-2xl font-semibold tracking-tight">Inicio</h1><p className="text-muted-foreground text-sm">Tienes 2 solicitudes pendientes.</p></SidebarInset>
</SidebarProvider>)
}
