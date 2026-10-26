// @ts-nocheck
import * as React from "react"
import * as HacceUI from "@/hacce-ui"

const { NavigationMenu, NavigationMenuList, NavigationMenuItem, NavigationMenuTrigger, NavigationMenuContent, NavigationMenuLink, navigationMenuTriggerStyle } = HacceUI
const links = [["Vacaciones", "Pide días y consulta tu saldo."], ["Fichajes", "Registra tu jornada diaria."], ["Nóminas", "Descarga tus nóminas y certificados."]]
export default function Preview() {
  return (<NavigationMenu defaultValue="personas" viewport={false}><NavigationMenuList>
  <NavigationMenuItem value="personas"><NavigationMenuTrigger>Personas</NavigationMenuTrigger>
    <NavigationMenuContent><ul className="grid w-80 gap-1">{links.map(([t, d]) => <li key={t}><NavigationMenuLink href="#"><div className="text-sm font-medium">{t}</div><p className="text-muted-foreground text-sm">{d}</p></NavigationMenuLink></li>)}</ul></NavigationMenuContent></NavigationMenuItem>
  <NavigationMenuItem><NavigationMenuLink href="#" className={navigationMenuTriggerStyle()}>Documentos</NavigationMenuLink></NavigationMenuItem>
  <NavigationMenuItem><NavigationMenuLink href="#" className={navigationMenuTriggerStyle()}>Ayuda</NavigationMenuLink></NavigationMenuItem>
</NavigationMenuList></NavigationMenu>)
}
