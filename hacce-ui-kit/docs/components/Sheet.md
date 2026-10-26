# Sheet

Panel lateral que se desliza sobre la página.

## Cuándo usarlo

- Filtros, detalle de un elemento de la lista o formularios largos sin perder el contexto.
- En móvil, para acciones desde abajo, mejor `Drawer`.

## Qué tienes que pasarle

`Sheet`, `SheetTrigger`, `SheetContent` (`side`: `right` | `left` | `top` | `bottom`), `SheetHeader`, `SheetTitle`, `SheetDescription`, `SheetFooter` y `SheetClose`.

## Ejemplo

```tsx
<Sheet>
  <SheetTrigger asChild><Button variant="outline">Filtros</Button></SheetTrigger>
  <SheetContent>
    <SheetHeader><SheetTitle>Filtrar solicitudes</SheetTitle></SheetHeader>
  </SheetContent>
</Sheet>
```
