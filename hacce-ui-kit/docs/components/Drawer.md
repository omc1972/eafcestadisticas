# Drawer

Cajón que sube desde abajo, pensado para móvil.

## Cuándo usarlo

- Acciones y resúmenes en pantallas pequeñas. En escritorio usa `Dialog` o `Sheet`.

## Qué tienes que pasarle

`Drawer` (`direction`: `bottom` | `top` | `left` | `right`), `DrawerTrigger`, `DrawerContent`, `DrawerHeader`, `DrawerTitle`, `DrawerDescription`, `DrawerFooter` y `DrawerClose`.

## Ejemplo

```tsx
<Drawer>
  <DrawerTrigger asChild><Button variant="outline">Ver resumen</Button></DrawerTrigger>
  <DrawerContent><DrawerHeader><DrawerTitle>Resumen de la semana</DrawerTitle></DrawerHeader></DrawerContent>
</Drawer>
```
