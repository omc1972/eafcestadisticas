# Sidebar

Barra lateral de navegación para las apps internas.

## Cuándo usarlo

- Estructura de toda app de trabajo: `SidebarProvider` › `Sidebar` + `SidebarInset` con el contenido.
- Logo arriba (`HacceLogo` a 22–28 px), grupos con etiqueta, elemento actual con `isActive`, usuario abajo.
- Iconos `HacceIcon` a 16 px. En móvil se convierte en un panel (Sheet) automáticamente.

## Qué tienes que pasarle

`SidebarProvider` (`defaultOpen`, `open`, `onOpenChange`), `Sidebar` (`side`, `variant`: `sidebar` | `floating` | `inset`, `collapsible`: `offcanvas` | `icon` | `none`), `SidebarHeader`, `SidebarContent`, `SidebarGroup`, `SidebarGroupLabel`, `SidebarMenu`, `SidebarMenuItem`, `SidebarMenuButton` (`isActive`, `tooltip`, `asChild`), `SidebarMenuBadge`, `SidebarMenuSub`, `SidebarFooter`, `SidebarInset`, `SidebarTrigger`, `SidebarRail` y `useSidebar()`.

## Ejemplo

```tsx
<SidebarProvider>
  <Sidebar>
    <SidebarHeader><HacceLogo height={24} /></SidebarHeader>
    <SidebarContent><SidebarGroup><SidebarMenu>
      <SidebarMenuItem><SidebarMenuButton isActive><HacceIcon name="inicio" size={16} /> Inicio</SidebarMenuButton></SidebarMenuItem>
    </SidebarMenu></SidebarGroup></SidebarContent>
  </Sidebar>
  <SidebarInset><SidebarTrigger />…</SidebarInset>
</SidebarProvider>
```
