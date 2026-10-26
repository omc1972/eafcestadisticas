# HoverCard

Vista previa al pasar sobre un enlace (persona, documento).

## Cuándo usarlo

- Solo información complementaria; todo debe ser accesible también sin hover.

## Qué tienes que pasarle

`HoverCard` (`openDelay`, `closeDelay`), `HoverCardTrigger asChild` y `HoverCardContent`.

## Ejemplo

```tsx
<HoverCard>
  <HoverCardTrigger asChild><Button variant="link">@lmartin</Button></HoverCardTrigger>
  <HoverCardContent>Lucía Martín · Personas</HoverCardContent>
</HoverCard>
```
