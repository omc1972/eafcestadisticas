# Popover

Contenido flotante anclado a un botón: ajustes rápidos o selectores.

## Cuándo usarlo

- Selector de fecha (con `Calendar`), filtros pequeños, edición en línea de 1–3 campos.

## Qué tienes que pasarle

`Popover`, `PopoverTrigger asChild`, `PopoverContent` (`align`, `side`, `sideOffset`) y `PopoverAnchor`.

## Ejemplo

```tsx
<Popover>
  <PopoverTrigger asChild><Button variant="outline">Elegir fecha</Button></PopoverTrigger>
  <PopoverContent className="w-auto p-0"><Calendar mode="single" /></PopoverContent>
</Popover>
```
