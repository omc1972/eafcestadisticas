# Tooltip

Texto breve que aparece al pasar o enfocar un elemento.

## Cuándo usarlo

- Obligatorio en botones con solo icono. Una o dos palabras; nunca información imprescindible.

## Qué tienes que pasarle

`Tooltip` (`open`, `defaultOpen`, `delayDuration`), `TooltipTrigger asChild` y `TooltipContent` (`side`, `sideOffset`).

## Ejemplo

```tsx
<Tooltip>
  <TooltipTrigger asChild><Button size="icon" variant="ghost" aria-label="Ajustes"><HacceIcon name="ajustes" size={18} /></Button></TooltipTrigger>
  <TooltipContent>Ajustes</TooltipContent>
</Tooltip>
```
