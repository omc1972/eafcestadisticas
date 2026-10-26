# NavigationMenu

Menú de navegación principal con desplegables, para webs y portales.

## Cuándo usarlo

- Cabeceras de sitios con secciones y subsecciones. En apps de trabajo usa `Sidebar`.

## Qué tienes que pasarle

`NavigationMenu` (`viewport`), `NavigationMenuList`, `NavigationMenuItem`, `NavigationMenuTrigger`, `NavigationMenuContent`, `NavigationMenuLink` y `navigationMenuTriggerStyle()`.

## Ejemplo

```tsx
<NavigationMenu><NavigationMenuList>
  <NavigationMenuItem>
    <NavigationMenuTrigger>Personas</NavigationMenuTrigger>
    <NavigationMenuContent><NavigationMenuLink href="/vacaciones">Vacaciones</NavigationMenuLink></NavigationMenuContent>
  </NavigationMenuItem>
</NavigationMenuList></NavigationMenu>
```
