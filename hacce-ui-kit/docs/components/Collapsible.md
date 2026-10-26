# Collapsible

Mostrar u ocultar un bloque de contenido.

## Cuándo usarlo

- “Ver más” en listas largas o detalles secundarios de una tarjeta.

## Qué tienes que pasarle

`Collapsible` (`open`, `defaultOpen`, `onOpenChange`), `CollapsibleTrigger asChild` y `CollapsibleContent`.

## Ejemplo

```tsx
<Collapsible>
  <CollapsibleTrigger asChild><Button variant="ghost">Ver más</Button></CollapsibleTrigger>
  <CollapsibleContent>…</CollapsibleContent>
</Collapsible>
```
