# Slider

Selector de un valor o un rango dentro de una escala.

## Cuándo usarlo

- Valores aproximados (horas, porcentajes). Si el número exacto importa, añade un `Input` al lado o muestra el valor.

## Qué tienes que pasarle

`value` / `defaultValue` (array: un valor o dos para rango), `onValueChange`, `min`, `max`, `step`, `orientation`.

## Ejemplo

```tsx
<Slider defaultValue={[37]} max={40} step={1} />
```
