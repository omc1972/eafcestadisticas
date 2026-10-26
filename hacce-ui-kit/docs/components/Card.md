# Card

Contenedor con borde para agrupar un bloque de información y sus acciones.

## Cuándo usarlo

- Resúmenes de panel, fichas de una persona o un trámite, bloques de ajustes.
- Borde, no sombra fuerte: las tarjetas Hacce usan `border` y `shadow-sm`.
- Cifras grandes en `font-light` (Inter Light).

## Qué tienes que pasarle

`Card`, `CardHeader`, `CardTitle`, `CardDescription`, `CardAction` (arriba a la derecha), `CardContent` y `CardFooter`.

## Ejemplo

```tsx
<Card>
  <CardHeader>
    <CardTitle>Vacaciones 2026</CardTitle>
    <CardDescription>Te quedan días por disfrutar.</CardDescription>
  </CardHeader>
  <CardContent>12 de 23 días</CardContent>
  <CardFooter><Button size="sm">Nueva solicitud</Button></CardFooter>
</Card>
```
